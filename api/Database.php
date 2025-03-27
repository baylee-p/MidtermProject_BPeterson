<?php
class Database {
    private $host = "dpg-cviapnfnoe9s739sm7bg-a";
    private $db_name = "quotesdb_km3l";
    private $username = "quotesdb_km3l_user";
    private $password = "ZE9jj9RNQKKYoVoNaCJbcMP42xerVtGt";
    private $port = "5432";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};",
                $this->username,
                $this->password
            );
            // $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
