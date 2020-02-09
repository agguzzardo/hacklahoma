<?php

$servername = 'localhost';
$dbun = 'root';
$dbpass = '';
$dbname = 'handiplan';

$conn = mysqli_connect($servername, $dbun, $dbpass, $dbname);

if(!$conn){
	die("Connection failed: ".mysqli_connect_error());
}