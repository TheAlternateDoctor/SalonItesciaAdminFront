<?php
    session_start();
    $curl = curl_init();

    include_once("../const.php");
    $url = server."/v1/stands/".$_GET["id"]."/formations";
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.$_SESSION['token']
        ),
      ));
      
      $response = JSON_decode(curl_exec($curl),true);
      curl_close($curl);
      echo '<table class="table">
          <thead>
              <tr><th>Nom</th><th>Représentant</th><th>Campus</th><th>Lieu du campus</th><th>Lien Google Forms</th>
          </thead><tbody>';
      foreach($response['results'] as $key => $value) {
          echo '<tr><td>'.$value['nom'].'</td>
          <td>'.$value['representant'].'</td>
          <td>'.$value['nomCampus'].'</td>
          <td>'.$value['lieuCampus'].'</td>
          <td>'.$value['forms'].'</td>';
      }
?>