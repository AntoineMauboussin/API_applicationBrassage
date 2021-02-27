<?php
class typeM{
  
    private $conn;
  
    public $numTypeMesure;
    public $nomTypeMesure;
    public $Unite;

    public function __construct($db){
        $this->conn = $db;
    }
    
	function readOne(){
		$this->numTypeMesure=htmlspecialchars(strip_tags($this->numTypeMesure));
		
		$query = "Select * from TypeMesure Where numTypeMesure='$this->numTypeMesure'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
	}
    function readAll(){
        
        $query = "Select * from TypeMesure";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    function create(){
        
        $this->nomTypeMesure=htmlspecialchars(strip_tags($this->nomTypeMesure));
        $this->Unite=htmlspecialchars(strip_tags($this->Unite));

        
        $query = "Insert into TypeMesure (nomTypeMesure, Unite) values ('$this->nomTypeMesure', '$this->Unite')";
        
        $stmt = $this->conn->prepare($query);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
        
    }
}
?>