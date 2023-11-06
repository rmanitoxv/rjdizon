<?php 
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: staff.php");
        }

    function filterData(&$str){ 
        $str = preg_replace("/\t/", "\\t", $str); 
        $str = preg_replace("/\r?\n/", "\\n", $str); 
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
    } 
    $fileName = "salesrevenue_" . date('Y-m-d') . ".xls"; 
     
    $fields = array('ID', 'DATE', 'INCOME', 'EXPENSE', 'REVENUE');

    $excelData = implode("\t", array_values($fields)) . "\n"; 

    $query = pg_query($con, "SELECT * FROM revenue WHERE r_isactive=1");

    if(pg_num_rows($query) > 0){ 
        while($row = pg_fetch_assoc($query)){ 
            $lineData = array($row['rid'], $row['rdate'], $row['rincome'], $row['rexpense'], ($row['rincome'] - $row['rexpense'])); 
            array_walk($lineData, 'filterData'); 
            $excelData .= implode("\t", array_values($lineData)) . "\n"; 
        } 
    }else{ 
        $excelData .= 'No records found...'. "\n"; 
    } 

    header ("HTTP/1.1 301 Moved Permanently");
header ("Content-Type: application/vnd.ms-excel"); 
    header ("HTTP/1.1 301 Moved Permanently");
header ("Content-Disposition: attachment; filename=\"$fileName\""); 
    
    echo $excelData; 
    
    exit;

    }
    else {
        header ("HTTP/1.1 301 Moved Permanently");
header ("Location: login.php");
        exit();
    }
?>

