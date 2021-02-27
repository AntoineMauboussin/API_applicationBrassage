<?php

class curve{
    
    public $nomModele;
    public $description;
    public $numTypeBiere;
    public $margeErreur;
    public $numUtilisateur;
    public $valeurs = array();
    public $temps = array();
    
    private $conn;
    
    public function __construct($db){
        $this->conn = $db;
    }
    
    function readPublic(){
        
        $query = "Select * from Modele natural join CourbeTheorique where numUtilisateur is null";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    function readModele($numUser){
        
        $query = "Select * from Modele natural join CourbeTheorique where numUtilisateur = '$numUser'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    function readCurve($numCT)
    {
        $query = "Select * from PointTheorique where numCourbeTheorique = '$numCT'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    function create(){
        
        $this->nomModele=htmlspecialchars(strip_tags($this->nomModele));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->numTypeBiere=htmlspecialchars(strip_tags($this->numTypeBiere));
        $this->margeErreur=htmlspecialchars(strip_tags($this->margeErreur));
        $this->numUtilisateur=htmlspecialchars(strip_tags($this->numUtilisateur));
        $query = "Insert into Modele (nomModele, description, numUtilisateur, numTypeBiere) values ('$this->nomModele', '$this->description', '$this->numUtilisateur', '$this->numTypeBiere')";
        
        $stmt = $this->conn->prepare($query);
        
        if($stmt->execute()){
            
            $id = $this->conn->lastInsertId();
            $query = "Insert into CourbeTheorique (MargeErreur, numModele) values ('$this->margeErreur', '$id')";
            
            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()){
                $id = $this->conn->lastInsertId();;
                $i = 0;
                while($i<count($this->valeurs))
                {
                    $j=$this->valeurs[$i];
                    $k=$this->temps[$i];
                    $query = "Insert into PointTheorique (valeurs, temps, numCourbeTheorique) values ('$j', '$k', $id)";
                    
                    $stmt = $this->conn->prepare($query);
                    
                    if(!$stmt->execute())
                    {
                        return false;
                    }
                    
                    $i++;
                }
                return true;
                    
            }
            
            return false;
        }
        
        return false;
        
    }
}

?>