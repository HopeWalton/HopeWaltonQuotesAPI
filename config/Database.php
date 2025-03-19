<?php
class Database {
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            // Get DATABASE_URL from environment variables
            $database_url = getenv("DATABASE_URL");

            if (!$database_url) {
                throw new Exception("DATABASE_URL is not set.");
            }

            // Parse the DATABASE_URL
            $db = parse_url($database_url);
            $host = $db["host"];
            $port = $db["port"] ?? '5432'; // Default to 5432 if not set
            $user = $db["user"];
            $pass = $db["pass"];
            $dbname = ltrim($db["path"], '/');

            // Create PDO connection string
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

            // Connect to PostgreSQL
            $this->conn = new PDO($dsn, $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (Exception $e) {
            die("Connection Error: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>
