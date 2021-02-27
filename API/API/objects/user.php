<?php
class user{
  
    private $conn;
  
    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $mdp;
	public $estAdmin;
	public $idFireBase;
	
    public function __construct($db){
        $this->conn = $db;
    }
    
	
	function readOne(){
		$this->id=htmlspecialchars(strip_tags($this->id));
		
		$query = "Select * from Utilisateur Where numUtilisateur='$this->id'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
	}
	
	
	function connect(){
		$this->email=htmlspecialchars(strip_tags($this->email));
		$this->mdp=htmlspecialchars(strip_tags($this->mdp));
		$query = "Select * from Utilisateur Where email='$this->email' and mdp='$this->mdp'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
	}
	
    function readAll(){
        
        $query = "Select * from Utilisateur";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    function create(){
        
        $this->nom=htmlspecialchars(strip_tags($this->nom));
        $this->prenom=htmlspecialchars(strip_tags($this->prenom));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->mdp=htmlspecialchars(strip_tags($this->mdp));
        $this->estAdmin=htmlspecialchars(strip_tags($this->estAdmin));
        $query = "Insert into Utilisateur (nom, prenom, email, mdp,estAdmin) values ('$this->nom', '$this->prenom', '$this->email', '$this->mdp', '$this->estAdmin')";
        
        $stmt = $this->conn->prepare($query);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
        
    }
    
    function createFB(){
        
        $this->nom=htmlspecialchars(strip_tags($this->idFireBase));
        $this->estAdmin=htmlspecialchars(strip_tags($this->estAdmin));
        $query = "Insert into Utilisateur (idFireBase,estAdmin) values ('$this->idFireBase', '$this->estAdmin')";
        
        $stmt = $this->conn->prepare($query);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
        
    }
}
?>
