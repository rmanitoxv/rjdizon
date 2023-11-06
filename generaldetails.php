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
		$name=$_SESSION['name'];
        $num=$_SESSION['num'];
		$po=$_SESSION['po'];
		if (isset($_POST['submit'])){
			$_SESSION['address']=$_POST['address'];
			header ("Location: projectdetails.php");
		}
    
?>
<!DOCTYPE html>
<head>
	<title>General Details</title>
	<link rel="stylesheet" href="style.css">
	<meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
	<div class="container-content-header">
        <b>ON-GOING ORDERS</b>
    </div>
	<div class="container-add-new-order-details">
        Add New Order
    </div>
	<div class="container-add-new-order-general-details">
		<br>
		<table>
			<form method="POST">
			<table width="100%" class="table-orderinfo" cellspacing="0">
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Customer Name</td>
					<td><input type="text" name="name" value=<?=$name?> class="add-new-order-input-text" required/></td>
				</tr>
				<tr>
                    <td height="15px"></td>
                </tr>
				<tr>
					<td class="cell-add-new-order-text" width="30%">Delivery Address</td>
					<td><input type="text" name="address" class="add-new-order-input-text" required /></td>
				</tr>
			</table>
			<input type="hidden" name="num" value=<?=$num?> />
			<input type="hidden" name="po" value=<?=$po?> />
			<button class="add-new-order-button" formaction="orders.php">BACK</button>
			<button class="add-new-order-side-buttons" formaction="addneworder.php"><</button>
			<button class="add-new-order-side-buttons" name="submit">></button>
			</form>
		</table>
	</div>	
</body>
<?php
    }
    else {
        header("Location: login.php");
        exit();
    }
?>