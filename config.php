<?php      
    $host = "ec2-34-203-182-65.compute-1.amazonaws.com";  
    $user = "pbnqxugyrtaozx";  
    $password = 'a86cc51cd66101c6f92aeb34309f4aaffae6d1b339bdc5f65df31921e8796adc';  
    $db_name = "dvkbcurm2ngns";  
    $port = "5432";
    
    $con = pg_connect("host='$host' user='$user' password='$password' dbname='$db_name' port='$port'");  
    if(!$con){
        echo "Connection Failed";
    }
?>  