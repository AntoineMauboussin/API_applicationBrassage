<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once './config/database.php';
include_once './objects/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $database = new database();
    $db = $database->getConnection();
    
    $user = new user($db);
    $data = json_decode(file_get_contents("php://input"));
	
	
    
    if(isset($_GET["numUtilisateur"])){
		
		$user->id = $_GET["numUtilisateur"];
		$stmt = $user->readOne();
		$num = $stmt->rowCount();
		
		if($num==1){
		$users_arr=array();
			
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
            
				$user_item=array(
					"numUtilisateur" => $numUtilisateur,
					"nom" => $nom,
					"prenom" => $prenom,
					"email" => $email,
				    "mdp" => $mdp,
				    "idFireBase" => $idFireBase,
				    "estAdmin" => $estAdmin
				);
            
				array_push($users_arr, $user_item);
			}
        
			http_response_code(200);
        
			echo json_encode($users_arr);
		
		}else{
			http_response_code(400);

			echo json_encode(array("message" => "Database is empty"));
		}
		
	}else if(isset($data->email)&&isset($data->mdp)){
		$user->email = $data->email;
		$user->mdp = $data->mdp;
		$stmt = $user->connect();
		$num = $stmt->rowCount();
		
				
		if($num==1){
		$users_arr=array();
			
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
            
				$user_item=array(
					"message" => "il existe bien un compte",
					"numUtilisateur" => $numUtilisateur,
					"nom" => $nom,
					"prenom" => $prenom,
					"email" => $email,
				    "idFireBase" => $idFireBase,
				    "estAdmin" => $estAdmin
					
				);
            
				array_push($users_arr, $user_item);
			}
        
			http_response_code(200);
			
			echo json_encode($users_arr);
		
		}else{
			http_response_code(400);

			echo json_encode(array("message" => "Database is empty"));
		}
		
	}else{
		$stmt = $user->readAll();
		$num = $stmt->rowCount();
		if($num>0){
			$users_arr=array();
        
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
            
				$user_item=array(
					"numUtilisateur" => $numUtilisateur,
					"nom" => $nom,
					"prenom" => $prenom,
					"email" => $email,
				    "mdp" => $mdp,
				    "idFireBase" => $idFireBase,
				    "estAdmin" => $estAdmin
				);
            
				array_push($users_arr, $user_item);
			}
        
			http_response_code(200);
        
			echo json_encode($users_arr);
		}
		else
		{
			http_response_code(400);

			echo json_encode(array("message" => "Database is empty"));
		}
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new user($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    if(
        !empty($data->nom) &&
        !empty($data->prenom) &&
        !empty($data->email) &&
		isset($data->estAdmin)&&
        !empty($data->mdp)
        ){
            
            $user->nom = $data->nom;
            $user->prenom = $data->prenom;
            $user->email = $data->email;
            $user->mdp = $data->mdp;
			$user->estAdmin = $data->estAdmin;
            
            if($user->create()){
                
                http_response_code(201);
                
                echo json_encode(array("message" => "Client was created."));
            }
            
            else{
                
                http_response_code(503);
                
                echo json_encode(array("message" => "Unable to create Client."));
            }
    }
    else if (!empty($data->idFireBase)
        && isset($data->estAdmin)) {
        
            $user->idFireBase = $data->idFireBase;
            $user->estAdmin = $data->estAdmin;
            
        if($user->createFB()){
            
            http_response_code(201);
            
            echo json_encode(array("message" => "Client was created."));
        }
        
        else{
            
            http_response_code(503);
            
            echo json_encode(array("message" => "Unable to create Client."));
        }
    }
    else{
        
        http_response_code(400);
        
        echo json_encode(array("message" => "Unable to create Client. Data is incomplete."));
    }
}
?>
