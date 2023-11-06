<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: staff/staff.php");
        }
        $id = $_SESSION['pgid'];
		$query = pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$id");
		$result1 = pg_fetch_assoc($query);
        if (isset($_POST['yes'])){
			$query = pg_query($con, "SELECT * FROM logs WHERE pgid=$id");
			$result = pg_fetch_assoc($query);
			$num = $result1['pglink'];
			$payable = (int)$result['logspayable'];
			$date= (date("F d, Y"));
			$tz = 'Asia/Manila';
            $timestamp = time();
            $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
            $dt->setTimestamp;
            $time = $dt->format('H:i:s');
			$orSerial=$_SESSION['orserial'];
			$customer=$result['logscustomer'];
			$tin=$_SESSION['tin'];
			$orAmount=(int)$_SESSION['oramount'];
			$payable = $result['logspayable'] - $orAmount;
			if ($payable>0){
				$partial = 1;
				$full = 0;
			}
			else {
				$partial = 0;
				$full = 1;
			}
			$orCashier=$_SESSION['cashier'];
			pg_query($con, "INSERT INTO officialreceipt (pgid, orserial, ordate, orcustomer, ortin, oramount, or_ispartial, or_isfull, orcashier, or_isactive, or_date_of_inactive) VALUES('$id', $orSerial, '$date', '$customer', '$tin', $orAmount, $partial, $full, '$orCashier', 1, '')");
			pg_query($con, "UPDATE logs SET logspayable='$payable' WHERE pgid=$id");
            pg_query($con, "UPDATE projectgroup SET pg_isactive=0, pg_date_of_inactive='$date', pgstatus='Complete', pghandler='None' WHERE pgid=$id");
			pg_query($con, "UPDATE projecttask SET taskstatus=1 WHERE pgid=$id");
			$query = pg_query($con, "SELECT * FROM revenue WHERE pgid=$id");
			$r = pg_fetch_assoc($query);
			$income = (int)$r['rincome'] + $oramount;
			pg_query($con, "UPDATE revenue SET rIncome='$income' WHERE pgid=$id");
			$notif = "GENERAL MANAGER just Finished the Order for $customer";
            pg_query($con, "INSERT into notifications (notif, ndate, ntime) VALUES ('$notif', '$date', '$time')");
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: orders.php");
        }
?>
<!DOCTYPE html>
<head>
	<title>Complete Order</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="container-orders">
		<div class="container-content-header">
            <b>ON-GOING ORDERS</b>
        </div>
		<div class="container-orderinfo-details-complete">
            ORDER #<?=$result1['pglink']?><br>
        </div>
		<div class="container-orderinfo-details-complete2">
			Complete Order #(<?=$result1['pglink']?>)?
			<br><br><br><br>
			<form method="POST">
				<a href="ordernum.php?id=<?=$id?>"><button class="button-orderinfo-confirmation-no" type="button">NO</button></a>
				<button class="button-orderinfo-confirmation-yes" type="submit" name="yes">YES</button>
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