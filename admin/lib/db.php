<?php
// slider.php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbName = "phpproject";
    public $conn;

    // Constructor to establish a PDO connection
    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbName}", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error mode
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    // Close the database connection
    public function close() {
        $this->conn = null;
    }

    // Select records from a table
    public function select($table, $columns = "*", $criteria = "", $orderBy = "") {
        $query = "SELECT $columns FROM $table";
        
        if ($criteria) {
            $query .= " WHERE $criteria";
        }
        
        if ($orderBy) {
            $query .= " ORDER BY $orderBy";
        }
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Insert a record into the database
    public function insert($table, $data = []) {
        $fields = implode(",", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        // Bind values
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Update records in a table
    public function update($table, $data = [], $criteria = "") {
        $setClause = "";
        foreach ($data as $field => $value) {
            $setClause .= "$field = :$field, ";
        }
        $setClause = rtrim($setClause, ", ");

        $query = "UPDATE $table SET $setClause WHERE $criteria";
        $stmt = $this->conn->prepare($query);

        // Bind values
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Delete records from the database
    public function delete($table, $criteria) {
        $query = "DELETE FROM $table WHERE $criteria";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    // Count records in a table
    public function count($table = "", $criteria = "") {
        $query = "SELECT COUNT(*) FROM $table";
        if ($criteria) {
            $query .= " WHERE $criteria";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
// Create an instance of the Database class
$db = new Database();
// Call the select method
$result = $db->select('tbl_slideshow', '*', "enable='1'", 'ssorder ASC');

$num = count($result);

?>