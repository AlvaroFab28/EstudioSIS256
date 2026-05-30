<?php 
$con = new mysqli("localhost", "root", "", "bd_recetas");

if ($con->connect_error) {
    die("error al conectarse: " . $con->connect_error);
}
?>
