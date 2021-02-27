<?php

class point{
    
    private $conn;
    
    public function __construct($db){
        $this->conn = $db;
    }
    
    function readFromCurveT($numCurve){
        
        $query = "Select * from PointTheorique where numCourbeTheorique = '$numCurve'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
}

?>