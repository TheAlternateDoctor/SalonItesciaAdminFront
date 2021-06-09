<?php
    session_start();
    $curl = curl_init();

    include_once("../const.php");
    $url = server."/v1/formations/".$_POST['formationID'];
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS =>'{
          "nom":"'.$_POST['nom'].'",
          "representant":"'.$_POST['representant'].'",
          "forms":"'.$_POST['forms'].'",
          "idCampus":"'.$_POST['campus'].'"
      }',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.$_SESSION['token'],
          'Content-Type: application/json'
        ),
      ));
      
      $response = JSON_decode(curl_exec($curl),true);
      curl_close($curl);
        if($response["success"]){
            $set_msg = '<div class="alert alert-success col-sm" role="alert">
                          La formation a bien été ajoutée!
                        </div>
                        <hr class="style2 line_small">';
        }
        else{
            $set_msg = '<div class="alert alert-warning col-sm" role="alert">
                          La formation n\'a pas pu être ajoutée.
                        </div>
                        <hr class="style2 line_small">';
        }
    include_once("index.php");
?>
