<?php
#import dbconn
include "../dbconn.php";

##### INSTRUCTIONS - CHANGE THE USERNAME AND PASSWORD ##########
##### ONCE CREDENTIALS ARE SET RUN php createadmin.php ON THE CLI ####
##### @TODO - Next step is to make this a run once script ####
$username = "sadmin";
$pasword = 'admin1234';



#########################################
$values['userid'] = $username;
$values['password'] = password_hash($password,PASSWORD_DEFAULT);

if($conn){
                $insert = "INSERT INTO users (user_id,password) VALUES(:userid,:password)";
                $add = $conn->prepare($insert);
                if($add->execute($values)==TRUE){
                        echo $values['userid'] . " added successfully";
                }
}
?>
