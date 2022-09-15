<?php
/*

    $$\   $$\          $$\                 $$\             $$$$$$$$\ $$\      $$\ 
$$ | $$  |         $$ |                $$ |            \__$$  __|$$ | $\  $$ |
$$ |$$  /$$\   $$\ $$ |  $$\ $$\   $$\ $$$$$$$\           $$ |   $$ |$$$\ $$ |
$$$$$  / $$ |  $$ |$$ | $$  |$$ |  $$ |$$  __$$\          $$ |   $$ $$ $$\$$ |
$$  $$<  $$ |  $$ |$$$$$$  / $$ |  $$ |$$ |  $$ |         $$ |   $$$$  _$$$$ |
$$ |\$$\ $$ |  $$ |$$  _$$<  $$ |  $$ |$$ |  $$ |         $$ |   $$$  / \$$$ |
$$ | \$$\\$$$$$$  |$$ | \$$\ \$$$$$$  |$$ |  $$ |         $$ |   $$  /   \$$ |
\__|  \__|\______/ \__|  \__| \______/ \__|  \__|         \__|   \__/     \__|


kukuhtw@gmail.com
whatsapp : 62.8129893706
https://www.linkedin.com/in/kukuhtw/
https://www.instagram.com/kukuhtw/
https://twitter.com/kukuhtw/
https://www.facebook.com/kukuhtw
https://www.facebook.com/profile.php?id=100083608342093

*/
$mySQLserver = "localhost";
$mySQLuser = "root";
$mySQLpassword = "yourpassword_here";
$mySQLdefaultdb = "yourdatabase_here";
$host = "yourowndomain.com";

$link = mysqli_connect($mySQLserver, $mySQLuser, $mySQLpassword,$mySQLdefaultdb) or die ("Could not connect to MySQL");

?>