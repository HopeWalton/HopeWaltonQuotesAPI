<?php

class Quote {

    private $conn;
    private $table = "quotes";

    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author;
    public $category;

    function __construct($db)
    {
        $this->conn = $db;
    }


    // Get all quotes or filter by author_id, category_id, or both
    public function read($author_id = null, $category_id = null) {
        $query = 'SELECT 
                q.id, 
                q.quote, 
                a.author, 
                c.category 
            FROM ' . $this->table . ' q 
            INNER JOIN authors a ON q.author_id = a.id 
            INNER JOIN categories c ON q.category_id = c.id';
        
        // Filtering conditions
        $conditions = [];
        $params = [];

        if (!is_null($author_id)) {
            $conditions[] = 'q.author_id = :author_id';
            $params[':author_id'] = $author_id;
        }

        if (!is_null($category_id)) {
            $conditions[] = 'q.category_id = :category_id';
            $params[':category_id'] = $category_id;
        }

        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        //Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }

        //Execute 
        $stmt->execute();
        return $stmt;
    }

        // Get a single quote by id
        public function read_single() {
            $query = 'SELECT 
                q.id, 
                q.quote,
                a.author,
                c.category 
            FROM ' . $this->table . ' q 
            INNER JOIN authors a ON q.author_id = a.id 
            INNER JOIN categories c ON q.category_id = c.id 
            WHERE 
                q.id = ? 
            LIMIT 1';

            //Prepare Statement
            $stmt = $this->conn->prepare($query);

            //Bind Data
            $stmt->bindParam(1, $this->id, PDO::PARAM_INT);

            // Execute
            $stmt->execute();

            // Fetch result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row) {
                //Set properties
                $this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->author = $row['author'];
                $this->category = $row['category'];
            } else {
                $this->quote = null;
                $this->author = null;
                $this->category = null;
            }
        }

        // Create a new quote
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) 
                      VALUES (:quote, :author_id, :category_id) RETURNING id';
        
            // Prepare Statement
            $stmt = $this->conn->prepare($query);
        
            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
        
            // Bind data
            $stmt->bindParam(':quote', $this->quote, PDO::PARAM_STR);
            $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        
            // Execute query
            if ($stmt->execute()) {
                // Get the last inserted ID in PostgreSQL
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row['id'];
            }
        
            return false;
        }
        

        public function update() {
            $query = 'UPDATE ' . $this->table . ' 
            SET quote = :quote, author_id = :author_id, category_id = :category_id 
                  WHERE id = :id';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Clean Data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind Data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':quote', $this->quote, PDO::PARAM_STR);
            $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_STR);

            // Execute
            if($stmt->execute()) {
                return true;
            } else {
                //Print error if something goes wrong
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }

        public function delete() {
            try {
                $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    
                return $stmt->execute();
            } catch (PDOException $e) {
                die(json_encode(["message" => "Delete failed", "error" => $e->getMessage()]));
            }
        }


}