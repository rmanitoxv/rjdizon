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
		$query = pg_query($con, "SELECT * FROM logs WHERE pgid=$id");
		$result = pg_fetch_assoc($query);
		$total = $result['logspayable'];
		if (isset($_POST['submit'])){
			$_SESSION['pgid']=$id;
			$_SESSION['orserial']=$_POST['orserial'];
			$_SESSION['tin']=$_POST['tin'];
			$_SESSION['cashier']=$_POST['cashier'];
			$_SESSION['oramount']=$_POST['oramount'];
			header ("HTTP/1.1 301 Moved Permanently");
header ("Location: complete_order.php");
		}
?>
<!DOCTYPE html>
<head>
	<title>Official Receipt</title>
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
					<td><input type="text"  name="orserial" minlength="5" maxlength="5" class="add-new-order-input-text" required /></td>
				</tr>
				<tr>
                    <td height="15px"></td>
                </tr>
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">TIN</td>
					<td><input type="text"  name="tin" class="add-new-order-input-text" /></td>
				</tr>
				<tr>
                    <td height="15px"></td>
                </tr>
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Cashier</td>
					<td> <input type="text"  name="cashier" class="add-new-order-input-text" required /></td>
				</tr>
				<tr>
                    <td height="15px"></td>
                </tr>
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Amount</td>
					<td><input type="number"  name="oramount" min="0" max="<?=$total?>" class="add-new-order-input-text" required /></td>
				</tr>
				<input type="hidden" name="id" value=<?=$id?> />
			</table>
			<a href="ordernum.php?id=<?=$id?>"><button type="button" class="add-new-order-button">BACK</button></a>
			<button name="submit" class="add-new-order-button">NEXT</button>
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