<?php
    session_start();
    include("../config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isadmin'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=../admin.php">';
        }
        $id = $_GET['id'];
        $prog = $_GET['prog'];
        if (isset($_POST['back'])){
            echo '<meta http-equiv="refresh" content="0;url=orderprogress.php?id=$id">';
        }
        if (isset($_POST['submit'])){
            $count = $_POST['i'];
            $fill = 0;
            for ($i=0;$i<=$count;$i++){
                $qty = $_POST['qty'.$i];
                $unit = $_POST['unit'.$i];
                $itemID = $_POST['id'.$i];
                if ((int)$qty > 0){
                    $name = $_POST['name'.$i];
                    $query = pg_query($con, "SELECT * FROM projectmaterials WHERE pmprogress='$prog' and pmmaterial='$name' and pgid='$id' LIMIT 1");
                    if (pg_num_rows($query)>0){
                        $result = pg_fetch_assoc($query);
                        $total = $qty + $result['pmqty'];
                        $pmID = $result['pmid'];
                        pg_query($con, "UPDATE projectmaterials SET pmqty='$total' WHERE pmid=$pmID");
                    }
                    else{
                        pg_query($con, "INSERT into projectmaterials (pgid, pmprogress, pmmaterial, pmqty, pmunit) VALUES('$id', '$prog', '$name', $qty, '$unit')");
                    }
                    $fill++;
                    $query = pg_query($con, "SELECT * FROM inventory WHERE itemid=$itemID");
                    $result = pg_fetch_assoc($query);
                    $left = $result['itemquantity'] - $qty;
                    pg_query($con, "UPDATE inventory SET itemquantity='$left' WHERE itemid=$itemID");
                    $query = pg_query($con, "SELECT * FROM revenue WHERE pgid=$id");
                    $result1 = pg_fetch_assoc($query);
                    $expense = (int)$result['itemppu'] * $qty + (int)$result1['rexpense']; 
                    pg_query($con, "UPDATE revenue SET rexpense=$expense");
                }
            }
            if ($fill == 0){
                    $error = "You have not added any material";
                    echo '<meta http-equiv="refresh" content="0;url=add_material.php?id=$id&prog=$prog&error=$error">';
            }
            else{
                echo '<meta http-equiv="refresh" content="0;url=orderprogress.php?id=$id&prog=$prog">';
            }
        }
?>
<!DOCTYPE html>
<head>
	<title>Add Materials</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-content-header">
        <b>ON-GOING ORDERS</b>
    </div>
	<div class="container-records-logs-header">
        ADD MATERIALS
    </div>
	<div class="container-order-add-materials">
        <table width="100%" class="table-orderinfo" cellspacing="0">
                <tr class="row-order-progress-header">
                    <td class="cell-order-progress-text" width="20%">Qty</td>
                    <td class="cell-order-progress-text" width="60%">Material Name</td>
                    <td class="cell-order-progress-text" width="20%">Stocks</td>
                </tr>
                <form method="POST">
                    <?php
                    $rows = pg_query($con, "SELECT * FROM inventory WHERE item_isactive=1");
                    $i=0;
                    while ($row = pg_fetch_object($rows)){
                        $status = $row->itemquantity / $row->itemthreshold;
                        if ($status >= 1){
                            $color = "green";
                        }
                        else if ($status == 0){
                            $color = "red";
                        }
                        else{
                            $color = "orange";
                        }
                    ?>
				<tr class="row-container-order-progress-blue">
					<input type="hidden" name="name<?=$i?>" value="<?=$row->itemname?>" />
					<input type="hidden" name="id<?=$i?>" value="<?=$row->itemid?>" />
                    <input type="hidden" name="unit<?=$i?>" value="<?=$row->itemunit?>" />
					<input type="hidden" name="i" value="<?=$i?>" />
					<td class="cell-container-order-progress"><input type="number" min="0" max="<?=$row->itemquantity?>" name="qty<?=$i?>" value="0" /></td>
					<td class="cell-container-order-progress"><b><?=$row->itemname?></b>
					<td class="cell-container-order-progress"><p style="color:<?=$color?>"> <?=$row->itemquantity?> <?=$row->itemunit?></p>
				</tr>
			    <?php
			$i++;
                    }
			?>
			</table>
            <a href="orderprogress.php?id=<?=$id?>"><button type="button" class="order-add-materials-back-button">BACK</button></a>
			<button name="submit" class="order-add-materials-done-button">DONE</button></a>
			</form>
    </div>
    </body>
<?php
    }
    else {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    }
?>


