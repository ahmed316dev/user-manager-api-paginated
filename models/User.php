<?php
class User {
    private $conn;
    private $table= "users";


    public $_id;
    public $name;
    public $email;
    public $phone;
    public $address;

    public $limit;
    public $startIndex;

    public $searchText;
    public $nameBoolean;
    public $emailBoolean;
    
    
    
    public function __construct ($db){
        $this->conn = $db;
        
        $this->limit= 5;
        $this->startIndex= 0;
        
        $this->nameBoolean= true;
        $this->emailBoolean= false;
    }


    public function read(){
        if(!$this->searchText){
            $query = 'SELECT * FROM ' . $this->table . ' LIMIT ' .$this->startIndex .' , '. $this->limit;
            $countQuery = 'SELECT * FROM ' . $this->table;
        }
        if($this->searchText){
            if($this->nameBoolean && !$this->emailBoolean){

                $query = 'SELECT * FROM ' . $this->table . ' WHERE name LIKE "%'.$this->searchText.'%" LIMIT ' .$this->startIndex .' , '. $this->limit ;
                $countQuery = 'SELECT * FROM ' . $this->table . ' WHERE name LIKE "%'.$this->searchText.'%"';
            }

            if(!$this->nameBoolean && $this->emailBoolean){

                $query = 'SELECT * FROM ' . $this->table . ' WHERE email LIKE "%'.$this->searchText.'%" LIMIT ' .$this->startIndex .' , '. $this->limit ;
                $countQuery = 'SELECT * FROM ' . $this->table . ' WHERE email LIKE "%'.$this->searchText.'%"';
            }

            if($this->nameBoolean && $this->emailBoolean){

                $query = 'SELECT * FROM ' . $this->table . ' WHERE email LIKE "%'.$this->searchText.'%" OR name LIKE "%'.$this->searchText.'%" LIMIT ' .$this->startIndex .' , '. $this->limit  ;
                $countQuery = 'SELECT * FROM ' . $this->table . ' WHERE email LIKE "%'.$this->searchText.'%" OR name LIKE "%'.$this->searchText.'%"' ;
            }
            
        }
        
        $stmt = $this->conn->prepare($query);
        $count = $this->conn->prepare($countQuery);
        
        $stmt->execute();
        $count->execute();
        return array(
            "users" => $stmt,
            "count" => $count->rowCount()
        );
    }

    public function read_single(){
        $query = 'SELECT * FROM ' . $this->table . ' WHERE _id=:id';

        $stmt = $this->conn->prepare($query);

        $this->_id = htmlSpecialChars(strip_tags($this->_id));
        $stmt->bindParam(':id', $this->_id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
        $this->address = $row['address'];

    }
    
    public function create(){
        $query= 'INSERT INTO '
        .$this->table. ' 
        SET
            name= :name,
            email= :email,
            phone= :phone,
            address= :address';

        $stmt = $this->conn->prepare($query);


        $this->name = htmlSpecialChars(strip_tags($this->name));
        $this->email = htmlSpecialChars(strip_tags($this->email));
        $this->phone = htmlSpecialChars(strip_tags($this->phone));
        $this->address = htmlSpecialChars(strip_tags($this->address));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        
        if($stmt->execute()){
            return true;
        } 

        printf("Error: %s. \n", $stmt->error);

        return false;

    }

    public function update(){
        $query= 'UPDATE '
        .$this->table. ' 
        SET
            name= :name,
            email= :email,
            phone= :phone,
            address= :address 
        WHERE _id= :id';

        $stmt = $this->conn->prepare($query);

        $this->name = htmlSpecialChars(strip_tags($this->name));
        $this->email = htmlSpecialChars(strip_tags($this->email));
        $this->phone = htmlSpecialChars(strip_tags($this->phone));
        $this->address = htmlSpecialChars(strip_tags($this->address));
        $this->_id = htmlSpecialChars(strip_tags($this->_id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':id', $this->_id);
        
        if($stmt->execute()){
            return true;
        } 

        printf("Error: %s. \n", $stmt->error);

        return false;

    }
    
    
    public function delete() {
        $query = 'DELETE FROM '.$this->table.' WHERE _id= :id';
        
        $stmt = $this->conn->prepare($query);
        $this->_id = htmlSpecialChars(strip_tags($this->_id));
        $stmt->bindParam(':id', $this->_id);



       
        if($stmt->execute()){
            return true;
        } 

        printf("Error: %s. \n", $stmt->error);

        return false;
    }

    public function isEmailUnique(){
        $query = 'SELECT email FROM '.$this->table.' WHERE email=:email';

        $stmt = $this->conn->prepare($query);

        $this->email = htmlSpecialChars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        
        $stmt->execute();
        $email = $stmt->fetch(PDO::FETCH_ASSOC);

        $result = $email === false? true: false;
        return $result;
        
    }

    public function isPhoneUnique(){
        $query = 'SELECT phone FROM '.$this->table.' WHERE phone=:phone';

        $stmt = $this->conn->prepare($query);

        $this->phone = htmlSpecialChars(strip_tags($this->phone));
        $stmt->bindParam(':phone', $this->phone);
        
        $stmt->execute();
        $phone = $stmt->fetch(PDO::FETCH_ASSOC);

        $result = $phone === false? true: false;
        return $result;
        
    }

}

