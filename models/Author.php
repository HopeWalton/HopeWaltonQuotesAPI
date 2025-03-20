<?php

    class Author {

        private $conn;
        private $table = 'authors';

        public $id;
        public $author;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        // Get authors
        public function read() {
            $query = 'SELECT
                id,
                author
            FROM 
                ' . $this->table;

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Execute Statement
            $stmt->execute();

            return $stmt;
        }

        // Get Single Author
        public function read_single() {
            $query = 'SELECT
                id,
                author
            FROM 
                ' . $this->table . '
            WHERE 
                id=? 
            LIMIT 1';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);

            // Execute
            $stmt->execute();

            // Fetch result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row){
                // Set Properties
                $this->id = $row['id'];
                $this->author = $row['author'];
            } else {
                // If no author found, set properties to null
                $this->id = null;
                $this->author = null;
            }

        }

        // Create author
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author) RETURNING id';
        
            $stmt = $this->conn->prepare($query);
        
            $this->author = htmlspecialchars(strip_tags($this->author));
            $stmt->bindParam(':author', $this->author, PDO::PARAM_STR);
        
            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result && isset($result['id'])) { // Ensure ID exists
                    return $result['id'];
                }
            }
        
            return false;
        }
        

        public function update(){
            $query = 'UPDATE ' . $this->table . '
            SET 
                author = :author
            WHERE 
                id = :id';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Clean the data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

           //Execute query
           if($stmt->execute()){
            return true;
        }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function delete() {
            $query = 'DELETE FROM ' . $this->table . '
                WHERE 
                    id = :id';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Clean Data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            } else {
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }

    }