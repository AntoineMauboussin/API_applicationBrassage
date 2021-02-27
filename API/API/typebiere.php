<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once './config/database.php';
include_once './objects/typebiereO.php';

http_response_code(400);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $database = new Database();
    $db = $database->getConnection();
    
    $typeB = new typeB($db);
	$data = json_decode(file_get_contents("php://input"));

    
	if(isset($_GET["num"])){
		
		$typeB->NumTypeBiere = $_GET["num"];
		$stmt = $typeB->readOne();
		$num = $stmt->rowCount();
		
		if($num==1){
			$users_arr=array();
        
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
            
				$user_item=array(
					"NumTypeBiere" => $NumTypeBiere,//
					"nomTypeBiere" => $nomTypeBiere,//
                
				);
            
				array_push($users_arr, $user_item);
			}
        
			http_response_code(200);
        
			echo json_encode($users_arr);
		}
		else
		{
			http_response_code(400);

			echo json_encode(array("message" => "Unable to get Type"));
		}
		
	}else if(isset($data->NumTypeBiere)){
		$typeB->NumTypeBiere = $data->NumTypeBiere;
		$stmt = $typeB->readOne();
		$num = $stmt->rowCount();
		
		if($num==1){
			$users_arr=array();
        
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
            
				$user_item=array(
					"NumTypeBiere" => $NumTypeBiere,//
					"nomTypeBiere" => $nomTypeBiere,//
                
				);
            
				array_push($users_arr, $user_item);
			}
        
			http_response_code(200);
        
			echo json_encode($users_arr);
		}
		else
		{
			http_response_code(400);

			echo json_encode(array("message" => "Unable to get Type"));
		}
		
		
	}else{
		$stmt = $typeB->readAll();
		$num = $stmt->rowCount();
    
		if($num>0){
			$users_arr=array();
        
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
            
				$user_item=array(
					"NumTypeBiere" => $NumTypeBiere,//
					"nomTypeBiere" => $nomTypeBiere,//
                
				);
            
				array_push($users_arr, $user_item);
			}
        
			http_response_code(200);
        
			echo json_encode($users_arr);
		}
		else
		{
			http_response_code(400);

			echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
		}
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $database = new Database();
    $db = $database->getConnection();
    
    $typeB = new typeB($db);//
    
    $data = json_decode(file_get_contents("php://input"));
    
    if(
        !empty($data->nomTypeBiere)//
        ){
            
            $typeB->nomTypeBiere = $data->nomTypeBiere;//
            
            
            
            if($typeB->create()){//
                
                http_response_code(201);
                
                echo json_encode(array("message" => "Type was created."));
            }
            
            else{
                
                http_response_code(503);
                
                echo json_encode(array("message" => "Unable to create Type."));
            }
    }
    
    else{
        
        http_response_code(400);
        
        echo json_encode(array("message" => "Unable to create Type. Data is incomplete."));
    }
}
?>