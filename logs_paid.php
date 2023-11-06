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
        $id=$_GET['id'];
        $query = pg_query($con, "SELECT * FROM logs WHERE logsID=$id");
        $result = pg_fetch_assoc($query);
        $payable = (int)$result['logspayable'];
        if (isset($_POST['submit'])){
            $date= (date("F d, Y"));
            $orSerial=$_POST['orserial'];
            $customer=$result['logscustomer'];
            $tin=$_POST['tin'];
            $orAmount=(int)$_POST['oramount'];
            $payable = (int)$result['logspayable'] - $orAmount;
            $id1 = $result['pgid'];
            if ($payable>0){
                $partial = 1;
                $full = 0;
            }
            else {
                $partial = 0;
                $full = 1;
            }
            $orCashier=$_POST['cashier'];
            pg_query($con, "INSERT INTO officialreceipt (pgid, orserial, ordate, orcustomer, ortin, oramount, or_ispartial, or_isfull, orcashier, or_isactive, or_date_of_inactive) VALUES($id1, '$orSerial', '$date', '$customer', '$tin', $orAmount, $partial, $full, '$orCashier', '1', '')");
            pg_query($con, "UPDATE logs SET logspayable='$payable' WHERE logsid=$id");
            $query = pg_query($con, "SELECT * FROM revenue WHERE pgid=$id1");
			$r = pg_fetch_assoc($query);
			$income = (int)$r['rincome'] + $orAmount;
			pg_query($con, "UPDATE revenue SET rincome='$income' WHERE pgid=$id1");
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: logs.php");
        }
?>
<!DOCTYPE php>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-content-header">
        <b>ON-GOING ORDERS</b>
    </div>
    <div class="container-add-new-order-details">
        Input Official Receipt Details
    </div>
    <div class="container-add-new-order">
		<div class="content">
		<form method="POST">
			<table width="100%" class="table-orderinfo" cellspacing="0">
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Serial Number</td>
					<td><input type="text" name="orserial" minlength="5" maxlength="5" class="add-new-order-input-text"required /></td>
				</tr>
                <tr>
                    <td height="15px"></td>
                </tr>
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">TIN</td>
					<td><input type="text" name="tin" class="add-new-order-input-text"/></td>
				</tr>
                <tr>
                    <td height="15px"></td>
                </tr>
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Cashier</td>
					<td> <input type="text" name="cashier" class="add-new-order-input-text"required /></td>
				</tr>
                <tr>
                    <td height="15px"></td>
                </tr>
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Amount</td>
					<td><input type="number" name="oramount" min="0" max="<?=$payable?>" class="add-new-order-input-text"required /></td>
				</tr>
			</table>
			<a href="logs.php"><button type="button" class="add-new-order-button">BACK</button></a>
			<button name="submit" class="add-new-order-button">NEXT</button>
        </form>
		</div>
	</div>	
</body>
</html>
<?php
    }
    else {
        header ("HTTP/1.1 301 Moved Permanently");
header ("Location: login.php");
        exit();
    }
?>