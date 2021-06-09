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
                    $url = server."/v1/stands";
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

                    curl_close($curl);
                    echo '
                    <div id="wrapper" class="wrapper center">
                        <h1>Formations</h1>
                        <hr class="style2 line_big">
			            <button type="button" data-toggle="modal" data-target="#create" class="btn btn-primary">Créer une formation </button>
                        <hr class="style 2 line_small">';
                        if(isset($set_msg))
                        echo $set_msg;
                        echo '<table class="table"><thead><tr>
                          <th>Stand id</th><th>Lien Google meet</th><th>Ecran</th><th>Flyers</th><th>Formations associées</th><th></th><th></th></tr></thead><tbody>';
                        foreach ($response['results'] as $key => $value) {
                            echo '<tr><td>'.$value['id'].'</td>
                            <td>'.$value['meet'].'</td>
                            <td><a target="_blank" class="btn btn-primary" href="http://'.server.'/v1/stands/'.$value['id']."/ecran".'" role="button"><i class="fas fa-image"></i></a></td>
                            <td><button type="button" class="btn btn-primary open-standsDialog" data-toggle="modal" data-target="#formations" data-id='.$value['id'].'>
                                <i class="fas fa-search"></i></button></td>
                            <td><button type="button" class="btn btn-primary open-formationsDialog" data-toggle="modal" data-target="#formations" data-id='.$value['id'].'>
                                <i class="fas fa-search"></i></button></td>
                            <td><button type="button" class="btn btn-primary open-editDialog" data-toggle="modal" data-target="#edit" data-id='.$value['id'].' data-nom="'.$value['meet'].'">
                                <i class="fas fa-edit"></i></button></td>
                            <td><button type="button" class="btn btn-danger open-deleteDialog" data-toggle="modal" data-target="#delete" data-id='.$value['id'].'>
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

    <div class="modal fade" id="formations" tabindex="-1" role="dialog" aria-labelledby="formations" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete">Formations associés</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                <p id="formationsTable"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addStand" tabindex="-1" role="dialog" aria-labelledby="addStand" aria-hidden="true">
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
        $(document).on("click", ".open-formationsDialog", function () {
            var standID = $(this).data('id');
            $.ajax({
                url : './formations.php?id='+standID, // La ressource ciblée
                success: function(code_html,statut){
                    $(".modal-body #formationsTable").html(code_html)
                }
            });
        });
    </script>
    </body>
</html>