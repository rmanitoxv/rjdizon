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
        if(!isset($_GET['id'])){
            header ("Location: officialreceipt.php");
        }
        $all_id = $_GET['id'];
        $ids = implode(', ', $all_id);
        $id = pg_query($con, "SELECT * FROM officialreceipt WHERE orid IN ($ids) ");
        $list = array();
        while ($row = pg_fetch_object($id)){
            $list[] = $row->orcustomer;
        }
        $list = implode(', ', $list);
        if(isset($_POST['submit'])){
            $id = ($_GET['id']);
            $ids = implode(', ', $id);
            print_r($ids);
            $date= (date("F d, Y"));
            $query = "UPDATE officialreceipt SET or_isactive='0', or_date_of_inactive='$date' WHERE orid IN ($ids) " ;
            pg_query($con, $query);
            header ("Location: officialreceipt.php");
        }
?>  
<!DOCTYPE html>
<head>
    <title>Official Receipt</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-orders">
        <div class="container-content-header">
            <b>RECORDS</b>
        </div>
        <div class="container-inventory-add-header">
            REMOVE OFFICIAL RECEIPT<br>
        </div>
        <div class="container-inventory-remove">
        <div>Are you sure you want to remove the<br> Official Receipt for (<?php print_r($list)?>)?</div>
            <form method="POST">
            <a href="officialreceipt.php"><button class="button-inventory-remove-confirmation-no" type="button">NO</button></a>
            <button name="submit" class="button-inventory-remove-confirmation-yes2">YES</button>
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