<?php
    session_start();
    $curl = curl_init();

    include_once("../const.php");
    $url = server."/v1/formations/".$_GET["id"]."/stands";
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
      echo '<table class="table"><thead><tr>
        <th>Stand id</th><th>Lien Google meet</th><th>Ecran</th></tr></thead><tbody>';
      foreach ($response['results'] as $key => $value) {
          echo '<tr><td>'.$value['id'].'</td><td>'.$value['meet'].'</td><td>
          <a target="_blank" class="btn btn-primary" href="http://'.server.'/v1/stands/'.$value['id']."/ecran".'" role="button"><i class="fas fa-image"></i></a></td>';
      }
?>