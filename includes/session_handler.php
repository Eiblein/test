<?php
class DbSessionHandler implements SessionHandlerInterface {
    private $db;
    private $ttl;

    public function __construct($file, $ttl = 1800) {
        $this->ttl = $ttl;
        $this->db = new PDO('sqlite:' . $file);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->exec("CREATE TABLE IF NOT EXISTS sessions (
            id TEXT PRIMARY KEY,
            data TEXT,
            timestamp INTEGER
        )");
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
