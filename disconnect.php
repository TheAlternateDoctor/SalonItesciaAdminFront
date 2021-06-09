<?php
	session_start();
    session_unset();
    session_destroy();
    session_write_close();
    session_regenerate_id(true);
	$set_msg='<div class="alert alert-primary" role="alert">
  			Vous avez été déconnecté!
		  </div>';
	include("index.php");
?>
