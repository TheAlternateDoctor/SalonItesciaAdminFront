<?php
    session_start();
    $curl = curl_init();

    include_once("../const.php");
    $url = server."/v1/events/".$_POST['eventID'];
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'DELETE',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$_SESSION['token']
    ),
    ));

    $response = JSON_decode(curl_exec($curl),true);
    curl_close($curl);
        if($response['success']){
            $set_msg = '<div class="alert alert-success col-sm" role="alert">
                          L\'évènement est bien supprimé!
                        </div>
                        <hr class="style2 line_small">';
        }
        else{
            $set_msg = '<div class="alert alert-warning col-sm" role="alert">
                          L\'évènement n\'a pas pu être supprimé.
                        </div>
                        <hr class="style2 line_small">';
        }
    include_once("index.php");
?>
