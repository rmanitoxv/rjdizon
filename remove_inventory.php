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
        if(!isset($_GET['id'])){
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: inventory.php");
        }
        $all_id = $_GET['id'];
        $ids = implode(', ', $all_id);
        $id = pg_query($con, "SELECT * FROM inventory WHERE itemid IN ($ids) ");
        $list = array();
        while ($row = pg_fetch_object($id)){
            $list[] = $row->itemname;
        }
        $list = implode(', ', $list);
        if(isset($_POST['submit'])){
            $id = ($_GET['id']);
            $ids = implode(', ', $id);
            print_r($ids);
            $date= (date("F d, Y"));
            $query = "UPDATE inventory SET item_isactive='0', item_date_of_inactive='$date' WHERE itemid IN ($ids) " ;
            pg_query($con, $query);
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: inventory.php");
        }
?>  
<!DOCTYPE html>
<head>
	<title>Inventory</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>

    <div class="container-orders">
            <div class="container-inventory-add-header">
                REMOVE MATERIAL<br>
            </div>
            <div class="container-inventory-remove">
                Remove Material<br> (<?php print_r($list)?>)?
                <br><br><br>
            <form method="POST">
                <a href="inventory.php"><button type="button" class="button-inventory-remove-confirmation-no">NO</button></a>
                <button name="submit" class="button-inventory-remove-confirmation-yes2">YES</button>
            </div>
            </form>
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