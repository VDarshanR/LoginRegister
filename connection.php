<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $databasename = "darshan";
    $conn = mysqli_connect($servername, $username, $password, $databasename);
    if(!$conn)
    {
        die("Didn't Connected to database");
    }
?>