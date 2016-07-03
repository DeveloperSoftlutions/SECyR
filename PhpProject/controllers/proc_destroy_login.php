<?php
	session_start();
	//session_destroy();
	unset ( $_SESSION['sessU'] );
	unset ( $_SESSION['userId'] );
	unset ( $_SESSION['userName'] );
	unset ( $_SESSION['perfil'] );
	header('Location: ../views/index.php');
?>