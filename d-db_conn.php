<?php

$host="localhost";
$port=3306;
$socket="";
$user="root";
$password="";
$dbname="InvMangSys";

$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Connection Failed! ' . mysqli_connect_error());