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
        if(!isset($_GET['ids'])){
            header ("Location: revenue.php");
        }
        $all_id = $_GET['ids'];
        $ids = implode(', ', $all_id);
        $id = pg_query($con, "SELECT * FROM revenue WHERE rid IN ($ids) ");
        $list = array();
        while ($row = pg_fetch_object($id)){
            $pgid = $row->pgid;
            $query = pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$pgid");
            $result = pg_fetch_assoc($query);
            $list[] = $result['pglink'];
        }
        $list = implode(', ', $list);
        if(isset($_POST['submit'])){
            $date= (date("F d, Y"));
            $query = "UPDATE revenue SET r_isactive='0', r_date_of_inactive='$date' WHERE rid IN ($ids) " ;
            pg_query($con, $query);
            header ("Location: revenue.php");
        }
?>  
<!DOCTYPE html>
<head>
    <title>Sales Revenues</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-orders">
        <div class="container-content-header">
            <b>RECORDS</b>
        </div>
        <div class="container-inventory-add-header">
            REMOVE SALES REVENUE<br>
        </div>
        <div class="container-inventory-remove">
        <div>Are you sure you want to remove the<br> Sales Revenue for the Tracking Number (<?php print_r($list)?>)?</div>
            <form method="POST">
                <a href="revenue.php"><button type="button" class="button-inventory-remove-confirmation-no">NO</button></a>
                <button name="submit" class="button-inventory-remove-confirmation-yes2">REMOVE</button>
            </form>
        </div>
    </div>
</body>
<?php
    }
    else {
        header("Location: login.php");
        exit();
    }
?>