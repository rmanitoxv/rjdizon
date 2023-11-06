<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID']) && isset($_SESSION['username'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userID = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("Location: staff.php");
        }
        $name=$_SESSION['name'];
        $num=$_SESSION['num'];
        $address = NULL;
        $address=$_SESSION['address'];
        if(isset($_POST['submit'])){
            $total=0;
            $date= (date("F d, Y"));
            if (!isset($_SESSION['po'])){
                $po = '';
            }
            else{
                $po = $_SESSION['po'];
            }
            $link = md5(time().$name.$date);
            $link = substr($link,0,6);
            $pgquery = "INSERT into projectgroup (pgpo, pgcustomer, pgaddress, pgdate, pgtotal, pgstatus, pghandler, pgsn, pglink, pg_isactive, pg_date_of_inactive) VALUES('$po','$name','$address', '$date', $total, 'Product Design', 'Artist', 1, '$link', 1, '')";
            pg_query($con, $pgquery);
            $get_id = "(SELECT * FROM projectgroup WHERE pglink='$link' LIMIT 1)";
            $get = pg_query($con, $get_id);
            $id1 = pg_fetch_assoc($get);
            $id = $id1['pgid'];
            pg_query($con, "INSERT into revenue (pgid, rincome, rexpense, rdate, r_isactive, r_date_of_inactive) VALUES($id, 0, 0, '$date', 1, '')");
            for ($i=1;$i<=$num;$i++){
                $desc = $_POST['desc'.$i];
                $qty = $_POST['qty'.$i];
                $unit = $_POST['unit'.$i];
                $ppu = $_POST['ppu'.$i];
                $total = $total + ($ppu * $qty);
                $pquery = "INSERT into project (pgid, projectdesc, projectprice, projectqty, projectunit) VALUES($id, '$desc', '$ppu', '$qty', '$unit')";
                pg_query($con, $pquery);
            }
            $update = "UPDATE projectgroup SET pgtotal=$total WHERE pgid=$id";
            pg_query($con, $update);
            $insert = pg_query($con, "INSERT INTO logs (pgid, logsbsserial, logspo, logscustomer, logstotal, logspayable, logsdate, logs_isactive, logs_date_of_inactive) VALUES($id, 0, '$po', '$name', $total, $total, '$date', 1, '')");
            $logsID = pg_last_oid($insert);
            for ($i=1;$i<=$num;$i++){
                $projDesc = $_POST['desc'.$i];
                $projPrice = $_POST['ppu'.$i];
                $projQty = $_POST['qty'.$i];
                $projUnit = $_POST['unit'.$i];
                pg_query($con, "INSERT INTO quotation (logsid, qprice, qquantity, qunit, qdescription, q_isactive, q_date_of_inactive) VALUES('$logsID', $projPrice, $projQty, '$projUnit', '$projDesc', 1, '')");
            }
            $tasks = ["Receive design", "Produce design", "Send approved design"];
            foreach ($tasks as $task){
                pg_query($con, "INSERT into projecttask (pgid, taskprogress, taskdesc, taskstatus) VALUES ($id, 'Product Design', '$task', 0)");
            }
            header ("Location: orders.php");
        }
        
?>
<!DOCTYPE html>
<head>
	<title>Project Details</title>
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
    <br>
	<div class="container-add-new-order-project-details">
		<form method="POST">
		<?php 
            for ($i=1;$i<=$num;$i++){
        ?>
		    - - - - - - - Project No. <?=$i?> Details - - - - - - -
			<table width="100%" class="table-orderinfo" cellspacing="0">
				<tr class="row-container-add-new-order">
					<td class="cell-add-new-order-text" width="30%">Project Description</td>
					<td style="text-align:left"><input class="add-new-order-input-text" type="text" name="desc<?=$i?>" required /></td>
				</tr>
                <tr>
                    <td height="15px"></td>
                </tr>
				<tr>
					<td class="cell-add-new-order-text" width="30%">Quantity</td>
					<td style="text-align:left"><input type="number" class="add-new-order-input-number" min="1" name="qty<?=$i?>" required/></td>
				</tr>
                <tr>
                    <td height="15px"></td>
                </tr>
				<tr>
					<td class="cell-add-new-order-text">Unit</td>
					<td style="text-align:left"><select name="unit<?=$i?>" class="add-new-order-input-text" style="width:30%;">
						<option value="pad">Pad</option>
						<option value="booklet">Booklet</option>
						<option value="boxes">Boxes</option>
					</select></td>
				</tr>
                <tr>
                    <td height="15px"></td>
                </tr>
				<tr>
					<td class="cell-add-new-order-text">Price per Unit</td>
					<td style="text-align:left"><input class="add-new-order-input-number" type="number" min="1" name="ppu<?=$i?>" required /></td>
				</tr>
			</table>
				<br/>
		<?php
            }
        ?>
			<a href="orders.php"><button type="button" class="add-new-order-button" >BACK</button></a>
			<a href="addneworder.php"><button type="button" class="add-new-order-side-buttons-left" ><</button></a>
			<button name="submit" class="add-new-order-button">ADD</button>
		</form>
	</div>	
</body>
<?php
    }
    else {
        header("Location: login.php");
        exit();
    }
?>