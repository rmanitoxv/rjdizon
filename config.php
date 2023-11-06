<?php      
    $host = "dpg-cl4852quuipc738s4isg-a";  
    $user = "rjdizon_user";  
    $password = 'u6YBF7tGHo2RJiCu79nUU9yLAYqaQsQ3';  
    $db_name = "rjdizon";  
    $port = "5432";
    
    $con = pg_connect("host='$host' user='$user' password='$password' dbname='$db_name' port='$port'");  
    if(!$con){
        echo "Connection Failed";
    }
?>  