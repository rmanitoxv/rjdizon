<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userID = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("Location: staff.php");
        }
?>
<!DOCTYPE html>
<head>
	<title>Orders</title>
	<link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
	<div class="container-orders">
        <div class="container-content-header">
            <b>ON-GOING ORDERS</b>
        </div>
            <a href="addneworder.php"><button class="button-orders-addorder">ADD ORDER</button></a>
		<div class="container-orders-orderlist">
            Order List<br>
            <?php
                $query = "SELECT * FROM projectgroup WHERE pg_isActive = 1";
                $rows = pg_query($con, $query);
                while ($row = pg_fetch_object($rows)){
                    $id = $row->pgid;
                    $num = $row->pglink;
            ?>
			<form method="GET" action="ordernum.php">
                    <?php echo "<input type='hidden' name='id' value='$id'>"; ?>
                    <button type='submit' class="button-orders-orderlist-blue" onClick="parent.frame1.location='orderinfoheader.php?num=<?=$i?>'">ORDER # <?=$num?><br><b><?=$row->pgcustomer?></b></button>
            </form>
            <?php
                }
            ?>
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