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
                    $url = server."/v1/events";
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
                        <h1>Evènements</h1>
                        <hr class="style2 line_big">
			            <button type="button" data-toggle="modal" data-target="#create" class="btn btn-primary">Créer un évènement</button>
                        <hr class="style 2 line_small">';
                        if(isset($set_msg))
                        echo $set_msg;
                        echo '
                        <table class="table">
                            <thead>
                                <tr><th>Nom</th><th>Début</th><th>Fin</th><th>description</th><th></th><th></th>
                            </thead><tbody>';//Modal!
                        foreach ($response['results'] as $key => $value) {
                            echo '<tr><td>'.$value['nom'].'</td>
                            <td>'.$value['dateDebut'].'</td>
                            <td>'.$value['dateFin'].'</td>
                            <td>'.$value['description'].'</td>
                            <td><button type="button" class="btn btn-primary open-editDialog" data-toggle="modal" data-target="#edit" data-id='.$value['id'].' data-nom="'.$value['nom'].'" data-description="'.$value['description'].'" data-dateDebut="'.$value['dateDebut'].'" data-dateFin="'.$value['dateFin'].'">
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
                <h5 class="modal-title" id="delete">Supprimer un évènement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Vous êtes sur le point de supprimer l'évenement nommé "<span id="nom"></span>". Êtes-vous sûr?</p>
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
                <h5 class="modal-title" id="delete">Créer un évènement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method=post action=add.php>
                <div class="modal-body">
                    <table><tbody><tr><td><label for="dateFin">Nom: </label></td><td><input type="text" name="nom"></td></tr>
                    <tr><td><label for="dateDebut">Début de l'évènement: </label></td><td><input type="date" name="dateDebut"> à <input type="time" name="heureDebut"></td></tr>
                    <tr><td><label for="dateFin">Fin de l'évènement: </label></td><td><input type="date" name="dateFin"> à <input type="time" name="heureFin"></td></tr>
                    <tr><td><label for="description">Description: </label></td><td><input type="text" name="description"></td></tr></tbody></table>
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
                <h5 class="modal-title" id="delete">Modifier un évènement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method=post action=edit.php>
                <div class="modal-body">
                    <table><tbody><tr><td><label for="dateFin">Nom: </label></td><td><input type="text" id="nom" name="nom" value=""></td></tr>
                    <tr><td><label for="dateDebut">Début de l'évènement: </label></td><td><input type="date" id="dateDebut" name="dateDebut" value=""> à <input type="time" id="heureDebut" name="heureDebut" value=""></td></tr>
                    <tr><td><label for="dateFin">Fin de l'évènement: </label></td><td><input type="date" id="dateFin" name="dateFin" value=""> à <input type="time" id="heureFin" name="heureFin" value=""></td></tr>
                    <tr><td><label for="description">Description: </label></td><td><input type="text" id="description" name="description" value=""></td></tr></tbody></table>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="eventID" id="eventID" value=""/> 
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
        var eventID = $(this).data('id');
        var nomEvent = $(this).data('nom');
        var descriptionEvent = $(this).data('description');
        var datetimeDebutEvent = new Date($(this).data('datedebut'));
        var datetimeFinEvent = new Date($(this).data('datefin'));

        var debutHeure = datetimeDebutEvent.getHours()<10 ? "0"+datetimeDebutEvent.getHours():datetimeDebutEvent.getHours()
        var debutMinutes = datetimeDebutEvent.getMinutes()<10 ? "0"+datetimeDebutEvent.getMinutes():datetimeDebutEvent.getMinutes()
        var finHeure = datetimeFinEvent.getHours()<10 ? "0"+datetimeFinEvent.getHours():datetimeFinEvent.getHours()
        var finMinutes = datetimeFinEvent.getMinutes()<10 ? "0"+datetimeFinEvent.getMinutes():datetimeFinEvent.getMinutes()

        var debutMonth = datetimeDebutEvent.getMonth()<10 ? "0"+datetimeDebutEvent.getMonth():datetimeDebutEvent.getMonth()
        var debutDate = datetimeDebutEvent.getDate()<10 ? "0"+datetimeDebutEvent.getDate():datetimeDebutEvent.getDate()
        var finMonth = datetimeFinEvent.getMonth()<10 ? "0"+datetimeFinEvent.getMonth():datetimeFinEvent.getMonth()
        var finDate = datetimeFinEvent.getDate()<10 ? "0"+datetimeFinEvent.getDate():datetimeFinEvent.getDate()
        $(".modal-footer #eventID").val( eventID );
        $(".modal-body #nom").val( nomEvent );
        $(".modal-body #description").val( descriptionEvent );
        $(".modal-body #dateDebut").val( datetimeDebutEvent.getFullYear()+"-"+debutMonth+"-"+debutDate );
        $(".modal-body #dateFin").val( datetimeFinEvent.getFullYear()+"-"+finMonth+"-"+finDate );
        $(".modal-body #heureDebut").val( debutHeure+":"+debutMinutes );
        $(".modal-body #heureFin").val( finHeure+":"+finMinutes );
        });
    </script>
    </body>
</html>