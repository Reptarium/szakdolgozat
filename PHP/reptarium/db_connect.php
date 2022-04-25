<?php

$mysqli = new mysqli("localhost","root",'',"reptarium_tablak");

if (!$mysqli){
    die("Nem lehet csatlakozni a DB-hez!!!");
}
?>