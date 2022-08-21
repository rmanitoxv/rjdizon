<?php
    session_start();
    include("../config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isadmin'] == 1){
            header ("Location: ../admin.php");
        }
        $online = pg_query($con, "SELECT * FROM accounts INNER JOIN staff ON accounts.userID = staff.userid WHERE accounts.userid = '$id'");
        $result =  pg_fetch_assoc($online);
        $role = $result['staffposition'];
        $status = 0;
        if ($role == "Artist"){
            $status = '1';
        }
        if ($role == 'Finance'){
            $s = [2, 3, 4];
            $status = implode(', ', $s);
        }
        if ($role == 'Binder' || $role == 'Cutter'){
            $s = [3, 4];
            $status = implode(', ', $s);
        }
        
?>
<!DOCTYPE html>
<head>
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='../logout.php';">
    <title>Orders</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container-orders">
        <div class="container-content-header">
            <b>ON-GOING ORDERS</b>
        </div>
        <div class="container-orders-orderlist">
        Order List<br>
            <?php
                $query = "SELECT * FROM projectgroup WHERE pg_isactive = 1 AND pgsn in ($status)";
                $rows = pg_query($con, $query);
                $i = 1;
                while ($row = pg_fetch_object($rows)){
                    $id = $row->pgid;
            ?>
			<form method="GET" action="orderprogress.php">
                    <?php echo "<input type='hidden' name='id' value='$id'>";?>
                    <button type='submit' class="button-orders-orderlist-blue" onClick="parent.frame1.location='orderinfoheader.php?num=<?=$i?>'">ORDER #<?=$row->pglink?> | <?=$row->pgstatus?></button>
            </form>
            <?php
                $i++;
                }
            ?>
		</div>
    </div>
</body>
<?php
    }
    else {
        header("Location: ../login.php");
        exit();
    }
?>