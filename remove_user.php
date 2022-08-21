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
            header ("Location: manage_staff.php");
        }
        $all_id = $_GET['ids'];
        $ids = implode(', ', $all_id);
        $id = pg_query($con, "SELECT * FROM accounts WHERE userid IN ($ids) ");
        $list = array();
        while ($row = pg_fetch_object($id)){
            $list[] = $row->uname;
        }
        $list = implode(', ', $list);
        if(isset($_POST['submit'])){
            $id = ($_GET['ids']);
            $ids = implode(', ', $id);
            print_r($ids);
            $date= (date("F d, Y"));
            $query = "UPDATE accounts SET user_isActive='0', user_date_of_inactive='$date' WHERE userid IN ($ids)" ;
            pg_query($con, $query);
            header ("Location: manage_staff.php");
        }
?>  
<!DOCTYPE php>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Staff</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-orders">
        <div class="container-content-header">
            <b>RECORDS</b>
        </div>
        <div class="container-inventory-add-header">
            REMOVE STAFF<br>
        </div>
        <div class="container-inventory-remove">
        <div>Are you sure you want to remove the<br> STAFF ACCOUNT for (<?php print_r($list)?>)?</div>
            <form method="POST">
            <a href="manage_staff.php"><button class="button-inventory-remove-confirmation-no" type="button">NO</button></a>
            <button name="submit" class="button-inventory-remove-confirmation-yes2">YES</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
    }
    else {
        header("Location: login.php");
        exit();
    }
?>