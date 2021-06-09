<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doc's Login</title>
        <link href="./css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="./css/style.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<?php

		if($_SESSION['is_logged']==true){
		include_once("main.php");
		}
	else{
    		echo'<div id="wrapper" class="wrapper center">
		        <h1>Doc\'s Login</h1>
		        <form method=post action=connect.php>
        		<hr class="style2 line_big">';
	   	if(isset($set_msg))
                	echo $set_msg;
		echo '<p>Login:</p>
        <input name="login"/>
        <hr class="style2 line_small">
            <p>Password:</p>
        <input type="password" name="password"/>
        <hr class="style2 line_small">
            <input class="btn btn-primary" type="submit" value="Connect"/>
        </form>
    </div>
</body>

</html>';
}?>

