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
			$query = pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$id");
            $result = pg_fetch_assoc($query);
            $serial = $_SESSION['serial'];
            $customer = $result['pgcustomer'];
            $date = (date("F d, Y"));
            $style = $_SESSION['bstyle'];
            $tin = $_SESSION['tin'];
            $total = $result['pgtotal'];
            $cashier = $_SESSION['cashier'];
            pg_query($con, "INSERT INTO billingstatement (pgid, bsserial, bscustomer, bsdate, bsbstyle, bstin, bstotal, bscashier, bs_isactive, bs_date_of_inactive) VALUES($id, $serial, '$customer', '$date', '$style', '$tin', $total, '$cashier', 1, '')");
            $get = pg_query($con, "SELECT bsid FROM billingstatement WHERE pgid='$id'");
            $get1 = pg_fetch_assoc($get);
            $bsid = $get1['bsid'];
            $rows = pg_query($con, "SELECT * FROM project WHERE pgid=$id");
            while ($row = pg_fetch_object($rows)){
                $qty = $row->projectqty;
                $unit = $row->projectunit;
                $desc = $row->projectdesc;
                $price = $row->projectprice;
                pg_query($con, "INSERT INTO bsdetails (bsid, bsdquantity, bsdunit, bsddescription, bsdprice) VALUES($bsid, $qty, '$unit', '$desc', $price)");
            }
            $query = pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$id");
            pg_query($con, "UPDATE logs SET logsbsserial WHERE pgid=$id");
            $result = pg_fetch_assoc($query);
            $serial = $_POST['drserial'];
            $author = $_POST['drauthor'];
            $customer = $result['pgcustomer'];
            $address = $result['pgaddress'];
            $date = (date("F d, Y"));
            $po = $result['pgpo'];
            pg_query($con, "INSERT INTO deliveryreceipt (pgid, drserial, drcustomer, draddress, drdate, drtin, drpo, drbstyle, drauthor, drdatereceived, dr_isactive, dr_date_of_inactive) VALUES($id, $serial, '$customer', '$address', '$date', '$tin', '$po', '$style', '$author', '$date', 1, '')");
            $get = pg_query($con, "SELECT drid FROM deliveryreceipt WHERE drSerial='$serial'");
            $get1 = pg_fetch_assoc($get);
            $drid = $get1['drid'];
            $rows = pg_query($con, "SELECT * FROM project WHERE pgid=$id");
            while ($row = pg_fetch_object($rows)){
                $qty = $row->projectqty;
                $unit = $row->projectunit;
                $desc = $row->projectdesc;
                $time = (date("H:i:s"));
                pg_query($con, "INSERT INTO drdetails (drid, drddescription, drdquantity, drdunit) VALUES($drid, '$desc', '$qty', '$unit')");
            }
            pg_query($con, "UPDATE projecttask SET taskStatus=1 WHERE pgid=$id and taskprogress='Delivery' and taskdesc='Prepare documents'");
            $notif = "GENERAL MANAGER just finished the Prepare Documents task for $customer";
            pg_query($con, "INSERT into notifications (notif, ndate, ntime) VALUES ('$notif', '$date', '$time')");
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: ordernum.php?id=$id");
        }
?>
<!DOCTYPE html>
<head>
	<title>Delivery Receipt</title>
	<link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-content-header">
        <b>ON-GOING ORDERS</b>
    </div>
    <div class="container-add-new-order-details">
        Input Delivery Receipt Details
    </div>
	<div class="container-add-new-order">
		<div class="content">
		<form method="POST">
			<table width="100%" class="table-orderinfo" cellspacing="0">
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Serial Number</td>
					<td><input type="text" minlength="5" maxlength="5"  name="drserial" class="add-new-order-input-text" required /></td>
				</tr>
                <tr>
                    <td height="15px"></td>
                </tr>
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Author</td>
					<td><input type="text"  name="drauthor" class="add-new-order-input-text"/></td>
				</tr>
			</table>
			<a href="#" onclick="history.go(-1)"><button class="add-new-order-button">BACK</button></a>
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