<?php
	$con = new PDO("mysql:host=localhost; dbname=handyman", "root", "");
	$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
?>