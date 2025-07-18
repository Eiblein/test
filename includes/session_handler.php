<?php
class DbSessionHandler implements SessionHandlerInterface {
    private $db;
    private $ttl;

    public function __construct($config, $ttl = 1800) {
        $this->ttl = $ttl;
        // $config ist ein Array mit host, dbname, user, pass
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        $this->db = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $this->db->exec("CREATE TABLE IF NOT EXISTS sessions (
            id VARCHAR(128) PRIMARY KEY,
            data TEXT,
            timestamp INT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function open($savePath, $sessionName) {
        return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        $stmt = $this->db->prepare("SELECT data FROM sessions WHERE id = :id AND timestamp >= :time");
        $stmt->execute([':id' => $id, ':time' => time() - $this->ttl]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['data'] : '';
    }

    public function write($id, $data) {
        $stmt = $this->db->prepare("REPLACE INTO sessions(id, data, timestamp) VALUES(:id, :data, :time)");
        return $stmt->execute([':id' => $id, ':data' => $data, ':time' => time()]);
    }

    public function destroy($id) {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function gc($maxlifetime) {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE timestamp < :time");
        return $stmt->execute([':time' => time() - $this->ttl]);
    }
}
