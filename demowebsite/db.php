<?php
/*
Configurasi setting database disini
*/


$host = "mydomain.com";
$mySQLserver = "localhost";
$mySQLuser = "root";
$mySQLpassword = "yourpassword_here";
$mySQLdefaultdb = "yourdatabase_here";
$host = "yourowndomain.com";

$link = mysqli_connect($mySQLserver, $mySQLuser, $mySQLpassword,$mySQLdefaultdb) or die ("Could not connect to MySQL");

//================EOF=========================
?>