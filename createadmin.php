<?php
#import dbconn
include "dbconn.php";

$username = "admin";
$pasword = "admin";
#########################################
$values['userid'] = $username;
$values['password'] = md5($password);

if($conn){
                $insert = "INSERT INTO users (user_id,password) VALUES(:userid,:password)";
                $add = $conn->prepare($insert);
                if($add->execute($values)==TRUE){
                        echo $values['userid'] . " added successfully";
                }

?>
