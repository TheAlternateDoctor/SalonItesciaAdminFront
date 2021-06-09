<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
	<meta charset="UTF-8">
        <title>Salon ITESCIA - Page Admin</title>
        <link href="./css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="./css/style.css" type="text/css" rel="stylesheet"/>
    </head>
    <body>
        <?php
            if(isset($_SESSION)){
                if($_SESSION['is_logged']==true){
                    echo '
                    <div id="wrapper" class="wrapper center">
                        <h1>Index</h1>
                        <hr class="style2 line_big">';
                        echo'<a href="./events" class="btn btn-primary">Evènements</a>
                        <hr class="style 2 line_small">
                        <a href="./formations" class="btn btn-primary">Formations</a>
                        <hr class="style 2 line_small">
                        <a href="./stands" class="btn btn-primary">Stands</a>
                        <hr class="style 2 line_small">
                        <a href="./flyers" class="btn btn-primary">Flyers</a>
                        <hr class="style12 line_small">
			            <a href="disconnect.php" class="btn btn-danger">Déconnection</a>
                    </div>';
                }
                else{
                $set_msg='<div class="alert alert-danger" role="alert">
                              Cette page est inaccessible car vous n\'êtes pas connecté.
                            </div>';
                include_once("index.php");  
                }
            }
            else{
                $set_msg='<div class="alert alert-danger" role="alert">
                              Cette page est inaccessible car vous n\'êtes pas connecté.
                            </div>';
                include_once("index.php");    
            }
        ?>
    </body>
</html>
