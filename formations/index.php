<?php
    session_start();
?><!DOCTYPE html>
<html>
    <head>
	<meta charset="UTF-8">
        <title>Salon ITESCIA - Page Admin</title>
        <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link href="../css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="../css/style.css" type="text/css" rel="stylesheet"/>
    </head>
    <body>
        <script type="text/javascript" src="../js/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <?php
            if(isset($_SESSION)){
                if($_SESSION['is_logged']==true){

                    include_once("../const.php");
                    $url = server."/v1/formations";
                    $curl = curl_init();

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
                    $response = json_decode(curl_exec($curl),true);

                    $url = server."/v1/campus";

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
                    $campuses = json_decode(curl_exec($curl),true);
                    $campusSelect = '<select name=campus id=campus>';
                    foreach ($campuses['results'] as $id => $value) {

                        $campusSelect .= '<option value=\''.$value['id'].'\'>'.$value['nom'].'</option>';
                    }
                    $campusSelect .= '</select>';$url = server."/v1/campus";

                    $url = server."/v1/stands/";
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
                    $stands = json_decode(curl_exec($curl),true);
                    $standSelect = '<select name=stands id=stands>';
                    foreach ($stands['results'] as $id => $value) {

                        $standSelect .= '<option>'.$value['id'].'</option>';
                    }
                    $standSelect .= '</select>';
                    curl_close($curl);
                    echo '
                    <div id="wrapper" class="wrapper center">
                        <h1>Formations</h1>
                        <hr class="style2 line_big">
			            <button type="button" data-toggle="modal" data-target="#create" class="btn btn-primary">Créer une formation </button>
                        <hr class="style 2 line_small">';
                        if(isset($set_msg))
                        echo $set_msg;
                        echo '
                        <table class="table">
                            <thead>
                                <tr><th>Nom</th><th>Représentant</th><th>Campus</th><th>Lieu du campus</th><th>Lien Google Forms</th><th>Stands associés</th><th></th><th></th>
                            </thead><tbody>';//Modal!
                        foreach ($response['results'] as $key => $value) {
                            echo '<tr><td>'.$value['nom'].'</td>
                            <td>'.$value['representant'].'</td>
                            <td>'.$value['nomCampus'].'</td>
                            <td>'.$value['lieuCampus'].'</td>
                            <td>'.$value['forms'].'</td>
                            <td><button type="button" class="btn btn-primary open-standsDialog" data-toggle="modal" data-target="#stands" data-id='.$value['id'].'>
                                <i class="fas fa-search"></i></button></td>
                            <td><button type="button" class="btn btn-primary open-editDialog" data-toggle="modal" data-target="#edit" data-id='.$value['id'].' data-nom="'.$value['nom'].'" data-representant="'.$value['representant'].'" data-forms="'.$value['forms'].'" data-idCampus="'.$value['idCampus'].'">
                                <i class="fas fa-edit"></i></button></td>
                            <td><button type="button" class="btn btn-danger open-deleteDialog" data-toggle="modal" data-target="#delete" data-nom="'.$value['nom'].'" data-id='.$value['id'].'>
                                <i class="fas fa-trash red"></i></button></td>';
                        }
                        echo '
			            <tbody></table><a href="../main.php" class="btn btn-danger">Retour</a>
                        </div>';
                }
                else{
                $set_msg='<div class="alert alert-danger" role="alert">
                              Cette page est inaccessible car vous n\'êtes pas connecté. NOT_LOGGED_IN
                            </div>';
                include_once("../index.php");  
                }
            }
            else{
                $set_msg='<div class="alert alert-danger" role="alert">
                              Cette page est inaccessible car vous n\'êtes pas connecté. SESSION_ERROR
                            </div>';
                include_once("../index.php");    
            }
        ?>
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete">Supprimer une formation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Vous êtes sur le point de supprimer la formation nommée "<span id="nom"></span>". Êtes-vous sûr?</p>
            </div>
            <div class="modal-footer">
                <form method=post action=delete.php>
                <input type="hidden" name="eventID" id="eventID" value=""/>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                <input type="submit" class="btn btn-danger" value="Oui">
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete">Créer une formation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method=post action=add.php>
                <div class="modal-body">
                    <table><tbody><tr><td><label for="dateFin">Nom: </label></td><td><input type="text" id="nom" name="nom" value=""></td></tr>
                    <tr><td><label for="representant">Représentant: </label></td><td><input type="text" id="representant" name="representant" value=""></td></tr>
                    <tr><td><label for="forms">Lien Google Forms: </label></td><td><input type="text" id="forms" name="forms" value=""></td></tr>
                    <tr><td><label for="campus">Campus: </label></td><td><?php echo $campusSelect; ?></td></tr></tbody></table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <input type="submit" class="btn btn-primary" value="Créer">
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete">Modifier une formation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method=post action=edit.php>
                <div class="modal-body">
                    <table><tbody><tr><td><label for="dateFin">Nom: </label></td><td><input type="text" id="nom" name="nom" value=""></td></tr>
                    <tr><td><label for="representant">Représentant: </label></td><td><input type="text" id="representant" name="representant" value=""></td></tr>
                    <tr><td><label for="forms">Lien Google Forms: </label></td><td><input type="text" id="forms" name="forms" value=""></td></tr>
                    <tr><td><label for="campus">Campus: </label></td><td><?php echo $campusSelect; ?></td></tr></tbody></table>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="formationID" id="formationID" value=""/> 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <input type="submit" class="btn btn-primary" value="Modifier">
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="stands" tabindex="-1" role="dialog" aria-labelledby="stands" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete">Stands associés</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    <button type="button" id="standAdd" class="btn btn-primary open-addStandDialog" data-toggle="modal" data-target="#addStand">+</button>
                    <p id="standsTable"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addStand" tabindex="-1" role="dialog" aria-labelledby="addStand" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete">Ajouter un stand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method=post action=addStand.php>
                <div class="modal-body">
                <?php echo $standSelect;?>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="formationID" id="formationIDStand" value=""/> 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <input type="submit" class="btn btn-primary" value="Modifier">
                </div>
            </form>
            </div>
        </div>
    </div>

    <script>
        $(document).on("click", ".open-deleteDialog", function () {
        var eventID = $(this).data('id');
        var nomEvent = $(this).data('nom');
        $(".modal-footer #eventID").val( eventID );
        $(".modal-body #nom").html( nomEvent );
        });
        $(document).on("click", ".open-editDialog", function () {
            var formationID = $(this).data('id');
            var nom = $(this).data('nom');
            var representant = $(this).data('representant');
            var forms = $(this).data('forms');
            var idCampus = $(this).data('idCampus');
            $(".modal-footer #formationID").val( formationID );
            $(".modal-body #nom").val( nom );
            $(".modal-body #representant").val( representant );
            $(".modal-body #forms").val( forms );
            $(".modal-body #campus option[value='"+idCampus+"']").prop('selected',true);
        });
        $(document).on("click", ".open-standsDialog", function () {
            var formationID = $(this).data('id');
            $.ajax({
                url : './stands.php?id='+formationID, // La ressource ciblée
                success: function(code_html,statut){
                    $(".modal-body #standsTable").html(code_html)
                    $(".modal-body #standAdd").attr("data-id", formationID)
                }
            });
        });
        $(document).on("click", ".open-addStandDialog", function () {
            var formationID = $(this).data('id');
            $(".modal-footer #formationIDStand").val( formationID );
        });
    </script>
    </body>
</html>