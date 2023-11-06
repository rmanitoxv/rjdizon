<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("Location: staff.php");
        }
        if(!isset($_GET['id'])){
            header ("Location: receipt.php");
        }
        $all_id = $_GET['id'];
        $ids = implode(', ', $all_id);
        $count = count($all_id);
        if(isset($_POST['submit'])){
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $customer = $_POST['customer'.$i];
                $serial = $_POST['serial'.$i];
                $tin = $_POST['tin'.$i];
                $cashier = $_POST['cashier'.$i];
                $status = $_POST['status'.$i];
                if ($status == "partial"){
                    $partial = 1;
                    $full = 0;
                }
                else {
                    $partial = 0;
                    $full = 1;
                }
                $amount = $_POST['amount'.$i];                
                $query = "UPDATE officialreceipt SET orserial='$serial', orcustomer='$customer', ortin='$tin', orcashier='$cashier', or_ispartial='$partial', or_isfull='$full', oramount='$amount' WHERE orid='$id' ";
                pg_query($con, $query);
            }
            header ("Location: officialreceipt.php");
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
        <b>RECORDS</b>
    </div>
    <div class="container-inventory-add-header">
        EDIT OFFICIAL RECEIPT<br>
    </div>
    <div class="container-inventory-add2">
        <div class="container-inventory-add">
        <form method="POST">
        <?php 
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $query = pg_query($con, "SELECT * FROM officialreceipt WHERE orID='$id'");
                while ($row = pg_fetch_object($query)){
        ?>
        <h2 style="color:white;font-size:3rem;"> Official Receipt # <?=$i+1?></h2>
        <table width="100%">
            <tr>
                <td class="cell-inventory-add-label">Serial Number:</td>
                <td><input class="cell-inventory-add-input" type="text" name="serial<?=$i?>" value="<?=$row->orserial?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Customer:</td>
                <td><input class="cell-inventory-add-input" type="text" name="customer<?=$i?>" value="<?=$row->orcustomer?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">TIN:</td>
                <td><input class="cell-inventory-add-input" type="text" name="tin<?=$i?>" value="<?=$row->ortin?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Cashier:</td>
                <td><input class="cell-inventory-add-input" type="text" name="cashier<?=$i?>" value="<?=$row->orcashier?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Pay Status:</td>
                <td><?php
                    if ($row->or_ispartial == '1'){ ?>
                    <select class="cell-inventory-add-input" name="status<?=$i?>" >
                        <option value="partial" selected>Partial</option>
                        <option value="full">Full</option>
                    </select>
                    <?php } 
                    else {
                    ?>
                    <select class="cell-inventory-add-input" name="status<?=$i?>">
                        <option value="full" selected>Full</option>
                        <option value="partial">Partial</option>
                    </select>
                <?php } ?></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Amount:</td>
                <td><input class="cell-inventory-add-input" type="number" min="0" name="amount<?=$i?>" value="<?=$row->oramount?>" /></td>
            </tr>
        </table>
        <hr>
        <?php 
            }
        }
        ?>
            <button type="submit" name="submit" class="button-staff-add-material-submit">EDIT</button>
            </form>
            <a href="officialreceipt.php"><button class="button-staff-back-material-submit">BACK</button></a>
        </div>
    </div>
</div>
</body>
<?php
    }
    else {
        header("Location: login.php");
        exit();
    }
?>