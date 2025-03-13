<?php

    class Category {

        private $conn;
        private $table = 'categories';

        public $id;
        public $category;

        public function __construct($db)
        {
            $this->conn = $db;
        }
        
        // Get Categories
        public function read() {
            $query = 'SELECT 
                id,
                category
            FROM ' . $this->table;

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Get Single Category
        public function read_single() {
            $query = 'SELECT 
                        id, category 
                    FROM ' . $this->table . ' 
                    WHERE id=? LIMIT 1';

            //Prepare Statement
            $stmt = $this->conn->prepare($query);

            //Bind id
            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            // Fetch result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row){
                // Set Properties
                $this->id = $row['id'];
                $this->category = $row['category'];
            } else {
                // If no category found, set properties to null
                $this->id = null;
                $this->category = null;
            }
        }

        // Create Category
        public function create() {
            $query = 'INSERT INTO ' . $this->table . '
            (category) VALUES (:category)';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Clean the data
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind data
            $stmt->bindParam(':category', $this->category, PDO::PARAM_STR);

            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function update(){
            $query = 'UPDATE ' . $this->table . '
                        SET 
                            category = :category 
                        WHERE 
                            id = :id';

            // Prepare
            $stmt = $this->conn->prepare($query);

            //Clean the data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);

            //Execute query
           if($stmt->execute()){
            return true;
        }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;

        }

        // Delete a category
        public function delete() {
            $query = 'DELETE FROM ' . $this->table . '
                WHERE 
                    id = :id';

            // Prepare stamement
            $stmt = $this->conn->prepare($query);

            // Clean the data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind the data
            $stmt->bindParam(':id', $this->id);

            //Execute
            if($stmt->execute()){
                return true;
            } else {
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }
    }