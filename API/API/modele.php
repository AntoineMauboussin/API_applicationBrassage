<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once './config/database.php';
include_once './objects/curve.php';
include_once './objects/point.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $database = new database();
    $db = $database->getConnection();
    
    $curve = new curve($db);
    
    if(isset($_GET["numUtilisateur"]))
    {
        $stmt = $curve->readModele($_GET["numUtilisateur"]);
        $num = $stmt->rowCount();
        
        if($num>0){
            $modeles_arr=array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $modeles_item=array(
                    "numModele" => $numModele,
                    "nomModele" => $nomModele,
                    "description" => $description,
                    "numTypeBiere" => $NumTypeBiere,
                    "margeErreur" => $MargeErreur
                );
                
                $point = new point($db);
                $stmt2 = $point->readFromCurveT($numCourbeTheorique);
                
                $modeles_item['valeurs']=array();
                $modeles_item['temps']=array();
                $i = 0;
                while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    
                    $modeles_item['valeurs'][$i] = $valeurs;
                    $modeles_item['temps'][$i] = $temps;
                    $i++;
                }
                
                array_push($modeles_arr, $modeles_item);
            }
            
            http_response_code(200);
            
            echo json_encode($modeles_arr);
        }
        else
        {
            http_response_code(400);
            
            echo json_encode(array("message" => "No curve with this numero"));
        }
    }
    else
    {
        
        $stmt = $curve->readPublic();
        $num = $stmt->rowCount();
        
        if($num>0){
            $modeles_arr=array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                
                $modeles_item=array(
                    "numModele" => $numModele,
                    "nomModele" => $nomModele,
                    "description" => $description,
                    "numTypeBiere" => $NumTypeBiere,
                    "margeErreur" => $MargeErreur
                );
                
                $point = new point($db);
                $stmt = $point->readFromCurveT($numCourbeTheorique);
                
                $modeles_item['valeurs']=array();
                $modeles_item['temps']=array();
                $i = 0;
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    
                    $modeles_item['valeurs'][$i] = $valeurs;
                    $modeles_item['temps'][$i] = $temps;
                    $i++;
                }
                
                array_push($modeles_arr, $modeles_item);
            }
            
            http_response_code(200);
            
            echo json_encode($modeles_arr);
        }
        else
        {
            http_response_code(400);
            
            echo json_encode(array("message" => "No public curve"));
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $database = new Database();
    $db = $database->getConnection();
    
    $curve = new curve($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    if(
        !empty($data->nomModele) &&
        !empty($data->description) &&
        !empty($data->numTypeBiere) &&
        !empty($data->margeErreur)&&
        !empty($data->valeurs)&&
        !empty($data->temps)&&
        !empty($data->numUtilisateur)
        ){
            $curve->nomModele = $data->nomModele;
            $curve->description = $data->description;
            $curve->numTypeBiere = $data->numTypeBiere;
            $curve->margeErreur = $data->margeErreur;
            $curve->valeurs = $data->valeurs;
            $curve->temps = $data->temps;
            $curve->numUtilisateur = $data->numUtilisateur;
            
            if($curve->create()){
                
                http_response_code(201);
                
                echo json_encode(array("message" => "Modele was created."));
            }
            
            else{
                
                http_response_code(503);
                
                echo json_encode(array("message" => "Unable to create Modele."));
            }
    }
    else{
            
            http_response_code(400);
            
            echo json_encode(array("message" => "Unable to create Modele. Data is incomplete."));
    }
}

?>