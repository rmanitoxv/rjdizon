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
        if(!isset($_GET['ids'])){
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: billing.php");
        }
        $all_id = $_GET['ids'];
        $ids = implode(', ', $all_id);
        $count = count($all_id);
        if(isset($_POST['submit'])){
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $result = pg_query($con, "SELECT COUNT(*) as total FROM bsdetails WHERE bsid=$id");
                $result = pg_fetch_assoc($result);
                $count1 = (int)$result['total'];
                $rows2 = pg_query($con, "SELECT * FROM bsdetails WHERE bsid=$id");
                $serial = $_POST['serial'.$i];
                $customer = $_POST['customer'.$i];
                $date = $_POST['date'.$i];
                $tin = $_POST['tin'.$i];
                $j = 0;
                while ($row2 = pg_fetch_object($rows2)){
                    $id1 = $row2->bsdid;
                    $quantity = $_POST['quantity'.$i.$j];
                    $unit = $_POST['unit'.$i.$j];
                    $desc = $_POST['desc'.$i.$j];
                    $price = $_POST['price'.$i.$j];
                    pg_query($con, "UPDATE bsdetails SET bsdquantity=$quantity, bsdunit='$unit', bsddescription='$desc', bsdprice=$price WHERE bsdid='$id1' ");
                    $j++;
                }
                $total = $_POST['total'.$i];
                $cashier = $_POST['cashier'.$i];              
                $query = "UPDATE billingstatement SET bsserial=$serial, bstin='$tin', bscustomer='$customer', bstotal=$total, bscashier='$cashier', bsdate='$date' WHERE bsid='$id' ";
                pg_query($con, $query);
            }
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: billing.php");
        }

?>
<!DOCTYPE html>
<head>
    <title>Billing Statements</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
<div class="container-content-header">
        <b>RECORDS</b>
    </div>
    <div class="container-inventory-add-header">
        EDIT BILLING STATEMENT<br>
    </div>
    <div class="container-inventory-add2">
        <div class="container-inventory-add">
        <form method="POST">
        <?php 
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $rows = pg_query($con, "SELECT * FROM billingstatement WHERE bsid='$id'");
                while ($row = pg_fetch_object($rows)){
                $rows1 = pg_query($con, "SELECT * FROM bsdetails WHERE bsid=$id");
                $result = pg_query($con, "SELECT COUNT(*) as total FROM bsdetails WHERE bsid=$id");
                $result = pg_fetch_assoc($result);
                $count1 = (int)$result['total'];
        ?>
        <h2 style="color:white;font-size:3rem;"> Billing Statement # <?=$i+1?></h2>
        <table width="100%">
            <tr>
                <td class="cell-inventory-add-label">Serial Number:</td>
                <td><input type="text" maxlength="5" class="cell-inventory-add-input" name="serial<?=$i?>" value="<?=$row->bsserial?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Customer:</td>
                <td><input type="text" class="cell-inventory-add-input" name="customer<?=$i?>" value="<?=$row->bscustomer?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Date:</td>
                <td><input type="text" class="cell-inventory-add-input" name="date<?=$i?>" value="<?=$row->bsdate?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Style:</td>
                <td><input type="text" class="cell-inventory-add-input" name="style<?=$i?>" value="<?=$row->bsbstyle?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">TIN:</td>
                <td><input type="text" class="cell-inventory-add-input" name="tin<?=$i?>" value="<?=$row->bstin?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Total:</td>
                <td><input type="number" class="cell-inventory-add-input" min="0" name="total<?=$i?>" value="<?=$row->bstotal?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Cashier:</td>
                <td><input type="text" class="cell-inventory-add-input" name="cashier<?=$i?>" value="<?=$row->bscashier?>" /></td>
            </tr>
        </table>
        <?php 
        $j=0;
        while ($row1 = pg_fetch_object($rows1)){ ?>
            <h3 style="color:white;font-size:2rem;"> Billing Statement Detail # <?=$j+1?></h3>
            <table width="100%">
                <tr>
                    <td class="cell-inventory-add-label">Quantity:</td>
                    <td><input type="number" class="cell-inventory-add-input" min="0" name="quantity<?=$i?><?=$j?>" value="<?=$row1->bsdquantity?>" /></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">Unit:</td>
                    <td><input type="text" class="cell-inventory-add-input" name="unit<?=$i?><?=$j?>" value="<?=$row1->bsdunit?>" /></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">Description:</td>
                    <td><input type="text" class="cell-inventory-add-input" name="desc<?=$i?><?=$j?>" value="<?=$row1->bsddescription?>" /></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">Price:</td>
                    <td><input type="number" class="cell-inventory-add-input" min="0" name="price<?=$i?><?=$j?>" value="<?=$row1->bsdprice?>" /></td>
                </tr>
            </table>
        <?php $j++;
            } ?>
            <hr>
        <?php 
            }
        }
    ?>
            <button type="submit" name="submit" class="button-staff-add-material-submit">EDIT</button>
            </form>
            <a href="billing.php"><button class="button-staff-back-material-submit">BACK</button></a>
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