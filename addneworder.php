<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userID = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: staff.php");
        }
		if (isset($_POST['submit'])){
			$_SESSION['name'] = $_POST['name'];
			$_SESSION['po'] = $_POST['po'];
			$_SESSION['num'] = $_POST['num'];
			header ("HTTP/1.1 301 Moved Permanently");
header ("Location: generaldetails.php");
		}
?>
<!DOCTYPE html>
<head>
	<title>Add New Order</title>
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
	<div class="container-add-new-order">
		<div class="content">
			<form method="POST"><br>
			<table width="100%" class="table-orderinfo" cellspacing="0">
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Customer Name</td>
					<td style="text-align: left"><input class="add-new-order-input-text" type="text"  name="name" required /></td>
				</tr>
				<tr>
                    <td height="15px"></td>
                </tr>
				<tr>
					<td class="cell-add-new-order-text">PO Number</td>
					<td style="text-align: left"><input type="text" class="add-new-order-input-text" name="po" /></td>
				</tr>
				<tr>
                    <td height="15px"></td>
                </tr>
				<tr>
					<td class="cell-add-new-order-text">Number of Projects</td>
					<td style="text-align: left"><input type="number" min ="1" value="1" name="num" class="add-new-order-input-number" required /></td>
				</tr>
				<tr>
					<!-- <td colspan="2"><font size="5px">Customer provided P.O Number?<br>Create from previous orders!</font></td> -->
					<!-- <td><button class="createbtn">CREATE</button></td> -->
				</tr>
			</table>
		</div>
		<a href="#" onClick="history.go(-1)"><button class="add-new-order-button" >BACK</button></a>
		<button class="add-new-order-button" name="submit">ADD</button>
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