<?php
    session_start();
    $curl = curl_init();

    include_once("../const.php");
    $url = server."/v1/events/".$_POST['eventID'];
    $time = explode(":",$_POST['heureDebut']);
    $date = explode("-",$_POST['dateDebut']);
    $dateDebut = date("c", mktime($time[0],$time[1],null,$date[1],$date[2],$date[0]));
    $time = explode(":",$_POST['heureFin']);
    $date = explode("-",$_POST['dateFin']);
    $dateFin = date("c",mktime($time[0],$time[1],null,$date[1],$date[2],$date[0]));
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
          "dateDebut":"'.$dateDebut.'",
          "dateFin":"'.$dateFin.'",
          "description":"'.$_POST['description'].'"
      }',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.$_SESSION['token'],
          'Content-Type: application/json'
        ),
      ));
      
      $response = JSON_decode(curl_exec($curl),true);
      var_dump($response);
      curl_close($curl);
        if($response["success"]){
            $set_msg = '<div class="alert alert-success col-sm" role="alert">
                          L\'évènement a bien été modifié!
                        </div>
                        <hr class="style2 line_small">';
        }
        else{
            $set_msg = '<div class="alert alert-warning col-sm" role="alert">
                          L\'évènement n\'a pas pu être modifié.
                        </div>
                        <hr class="style2 line_small">';
        }
    include_once("index.php");
?>
