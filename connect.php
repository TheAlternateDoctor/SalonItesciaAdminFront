<?php
    session_start();
    $login = $_POST['login'];

    $curl = curl_init();
    include("./const.php");
    $set_msg = '<div class="alert alert-warning col-sm" role="alert">
                Mot de passe ou login incorrect.
                </div>
                <hr class="style2 line_small">';
    $_SESSION['is_logged']=false;
    $where = "index.php";
        $url = server."/oauth/token";
        $verbose = fopen('./temp', 'w+');
        curl_setopt_array($curl, array(
            CURLOPT_VERBOSE => true,
            CURLOPT_STDERR => $verbose,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/x-www-form-urlencoded',
            ),
            CURLOPT_POSTFIELDS =>'grant_type=password&username='.$login.'&password='.$_POST['password'].'&client_id=application&client_secret=secret'
        ));
        $response = json_decode(curl_exec($curl),true);
        if(!isset($response['error'])){
		    $_SESSION['token']=$response["access_token"];
                    $_SESSION['is_logged']=true;
                    $set_msg = '<div class="alert alert-success col-sm" role="alert">
                                  Vous voilà connecté!
                                </div>
                                <hr class="style2 line_small">';
                    $where = "main.php";
		}
        include_once($where);
        curl_close($curl);
?>
