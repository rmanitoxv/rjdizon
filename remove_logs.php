<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=staff.php">';
        }
        if(!isset($_GET['ids'])){
            echo '<meta http-equiv="refresh" content="0;url=logs.php">';
        }
        $all_id = $_GET['ids'];
        $ids = implode(', ', $all_id);
        $id = pg_query($con, "SELECT * FROM logs WHERE logsid IN ($ids) ");
        $list = array();
        while ($row = pg_fetch_object($id)){
            $list[] = $row->logscustomer;
        }
        $list = implode(', ', $list);
        if(isset($_POST['submit'])){
            $date= (date("F d, Y"));
            $query = "UPDATE logs SET logs_isactive='0', logs_date_of_inactive='$date' WHERE logsid IN ($ids) " ;
            pg_query($con, $query);
            echo '<meta http-equiv="refresh" content="0;url=logs.php">';
        }
?>  
<!DOCTYPE html>
<head>
    <title>Logs</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-orders">
        <div class="container-content-header">
            <b>RECORDS</b>
        </div>
        <div class="container-inventory-add-header">
            REMOVE LOGS<br>
        </div>
        <div class="container-inventory-remove">
        <div>Are you sure you want to remove the<br> Logs for (<?php print_r($list)?>)?</div>
            <form method="POST">
            <a href="logs.php"><button class="button-inventory-remove-confirmation-no" type="button">NO</button></a>
            <button name="submit" class="button-inventory-remove-confirmation-yes2">YES</button>
            </form>
        </div>
    </div>
</body>
<?php
    }
    else {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    }
?>