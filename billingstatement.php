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
        $id = $_GET['id'];
        if (isset($_POST['submit'])){
            $_SESSION['serial'] = $_POST['bsserial'];
            $_SESSION['bstyle'] = $_POST['bstyle'];
            $_SESSION['tin'] = $_POST['tin'];
            $_SESSION['cashier'] = $_POST['bscashier'];
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: deliveryreceipt.php?id=$id");
        }
?>
<!DOCTYPE html>
<head>
	<title>Billing Statement</title>
	<link rel="stylesheet" href="style.css">
	<meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
	<div class="container-content-header">
        <b>ON-GOING ORDERS</b>
    </div>
    <div class="container-add-new-order-details">
        Input Billing Statement Details
    </div>
	<div class="container-add-new-order">
		<form method="POST">
		
			<table width="100%" class="table-orderinfo" cellspacing="0">
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Serial Number</td>
					<td><input type="text"  name="bsserial" minlength="5" maxlength="5" class="add-new-order-input-text" required /></td>
				</tr>
				<tr>
                    <td height="15px"></td>
                </tr>
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Tin</td>
					<td><input type="text"  name="tin" maxlength="15" pattern="\d{3}[\-]\d{3}[\-]\d{3}[\-]\d{3}" placeholder="xxx-xxx-xxx-xxx" class="add-new-order-input-text"/></td>
				</tr>
				<tr>
                    <td height="15px"></td>
                </tr>
				<tr>
					<td class="cell-add-new-order-text" width="30%">Business Style</td>
					<td><input type="text" name="bstyle" class="add-new-order-input-text"/></td>
				</tr>
				<tr>
                    <td height="15px"></td>
                </tr>
				<tr>
					<td class="cell-add-new-order-text" width="30%">Cashier</td>
					<td><input type="text"  name="bscashier" class="add-new-order-input-text"/></td>
				</tr>
			</table>
			<a href="ordernum.php?id=<?=$id?>" style="margin-right: 20rem;"><button type="button" class="add-new-order-button">BACK</button></a>
			<button name="submit" class="add-new-order-button">NEXT</button>
		
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