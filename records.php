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
?>
<!DOCTYPE html>
<head>
	<title>Records</title>
    <link rel="stylesheet" href="style.css">
	<meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
	<div class="container-content-header">
        <b>RECORDS</b>
    </div>
	<div class="container-records">
		<td><a href="logs.php"><button class="button-records-logs">LOGS</button></a></td>
		<td><a href="billing.php"><button class="button-records-deliveryreceipts">BILLING STATEMENT</button></a></td>
		<td><a href="officialreceipt.php"><button class="button-records-officialreceipts">OFFICIAL RECEIPTS</button></a></td>
		<td><a href="del_receipt.php"><button class="button-records-deliveryreceipts">DELIVERY RECEIPTS</button></a></td>
        <td><a href="revenue.php"><button class="button-records-deliveryreceipts">SALES REVENUE</button></a></td>
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