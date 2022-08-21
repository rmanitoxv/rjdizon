<?php 
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("Location: staff.php");
        }

    function filterData(&$str){ 
        $str = preg_replace("/\t/", "\\t", $str); 
        $str = preg_replace("/\r?\n/", "\\n", $str); 
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
    } 
    $fileName = "inventory_" . date('Y-m-d') . ".xls"; 
     
    $fields = array('ID', 'ITEM NAME', 'ITEM QUANTITY', 'ITEM UNIT', 'ITEM THRESHOLD');

    $excelData = implode("\t", array_values($fields)) . "\n"; 

    $query = pg_query($con, "SELECT * FROM inventory where item_isactive=1");

    if(pg_num_rows($query) > 0){ 
        while($row = pg_fetch_assoc($query)){ 
            $lineData = array($row['itemid'], $row['itemname'], $row['itemquantity'], $row['itemunit'], $row['itemthreshold']); 
            array_walk($lineData, 'filterData'); 
            $excelData .= implode("\t", array_values($lineData)) . "\n"; 
        } 
    }else{ 
        $excelData .= 'No records found...'. "\n"; 
    } 

    header("Content-Type: application/vnd.ms-excel"); 
    header("Content-Disposition: attachment; filename=\"$fileName\""); 
    
    echo $excelData; 
    
    exit;

    }
    else {
        header("Location: login.php");
        exit();
    }
?>

