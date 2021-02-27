<?php
class typeB{
  
    private $conn;
  
    public $NumTypeBiere;//
    public $nomTypeBiere;//
    

    public function __construct($db){
        $this->conn = $db;
    }
    function readOne(){
		$this->NumTypeBiere=htmlspecialchars(strip_tags($this->NumTypeBiere));
		
		$query = "Select * from TypeBiere Where NumTypeBiere='$this->NumTypeBiere'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
	}
    function readAll(){
        
        $query = "Select * from TypeBiere";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    function create(){
        
        $this->nomTypeBiere=htmlspecialchars(strip_tags($this->nomTypeBiere));//
       

        
        $query = "Insert into TypeBiere (nomTypeBiere) values ('$this->nomTypeBiere')";//
        
        $stmt = $this->conn->prepare($query);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
        
    }
}
?>