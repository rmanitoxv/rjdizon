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
        if(!isset($_GET['ids'])){
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: del_receipt.php");
        }
        $all_id = $_GET['ids'];
        $ids = implode(', ', $all_id);
        $id = pg_query($con, "SELECT * FROM deliveryreceipt WHERE drid IN ($ids) ");
        $list = array();
        while ($row = pg_fetch_object($id)){
            $list[] = $row->drcustomer;
        }
        $list = implode(', ', $list);
        if(isset($_POST['submit'])){
            $id = ($_GET['ids']);
            $ids = implode(', ', $id);
            print_r($ids);
            $date= (date("F d, Y"));
            $query = "UPDATE deliveryreceipt SET dr_isactive='0', dr_date_of_inactive='$date' WHERE drid IN ($ids) " ;
            pg_query($con, $query);
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: del_receipt.php");
        }
?>
<!DOCTYPE html>
<head>
    <title>Delivery Receipt</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-orders">
        <div class="container-content-header">
            <b>RECORDS</b>
        </div>
        <div class="container-inventory-add-header">
            REMOVE DELIVERY RECEIPT<br>
        </div>
        <div class="container-inventory-remove">
        <div>Are you sure you want to remove the<br> Delivery Receipt for (<?php print_r($list)?>)?</div>
            <form method="POST">
            <a href="del_receipt.php"><button class="button-inventory-remove-confirmation-no" type="button">NO</button></a>
            <button name="submit" class="button-inventory-remove-confirmation-yes2">YES</button>
            </form>
        </div>
    </div>
</body>
<?php
    }
    else {
        header ("HTTP/1.1 301 Moved Permanently");
header ("Location: login.php");
        exit();
    }
?>