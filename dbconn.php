<?php
/** start the session */
session_start();

/** connect to db */
$servername = "localhost";
$username = "admin_portal";
$password = "3ntmGr53cur1te";
$db = "entmgr";
try {
        $conn = new PDO("mysql:host=$servername;dbname=$db",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
}
?>
