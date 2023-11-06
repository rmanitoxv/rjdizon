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
        if(!isset($_GET['ids'])){
            header ("Location: logs.php");
        }
        $all_id = $_GET['ids'];
        $ids = implode(', ', $all_id);
        $count = count($all_id);
        if(isset($_POST['submit'])){
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $customer = $_POST['customer'.$i];
                $serial = $_POST['serial'.$i];
                $po = $_POST['po'.$i];
                $total = $_POST['total'.$i];
                $payable = $_POST['payable'.$i];
                $date = $_POST['date'.$i];                
                $query = "UPDATE logs SET logsbsserial='$serial', logspo='$po', logscustomer='$customer', logstotal='$total', logspayable='$payable', logsdate='$date' WHERE logsid='$id' ";
                pg_query($con, $query);
            }
            header ("Location: logs.php");
        }
?>
<!DOCTYPE html>
<head>
    <title>Logs</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
<div class="container-content-header">
        <b>RECORDS</b>
    </div>
    <div class="container-inventory-add-header">
        EDIT LOGS<br>
    </div>
    <div class="container-inventory-add2">
        <div class="container-inventory-add">
        <form method="POST">
        <?php 
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $query = pg_query($con, "SELECT * FROM logs WHERE logsid='$id'");
                while ($row = pg_fetch_object($query)){
        ?>
        <h2 style="color:white;font-size:3rem;"> Logs # <?=$i+1?></h2>
        <table width="100%">
            <tr>
                <td class="cell-inventory-add-label">Customer Name:</td>
                <td><input class="cell-inventory-add-input" type="text" name="customer<?=$i?>" value="<?=$row->logscustomer?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">BS Serial No.:</td>
                <td><input class="cell-inventory-add-input" type="text" name="serial<?=$i?>" value="<?=$row->logsbsserial?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">P.O. No.:</td>
                <td><input class="cell-inventory-add-input" type="text" name="po<?=$i?>" value="<?=$row->logspo?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Total:</td>
                <td><input class="cell-inventory-add-input" type="number" min="0" name="total<?=$i?>" value="<?=$row->logstotal?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Payable:</td>
                <td><input class="cell-inventory-add-input" type="number" min="0" name="payable<?=$i?>" value="<?=$row->logspayable?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Date:</td>
                <td><input class="cell-inventory-add-input" type="text" name="date<?=$i?>" value="<?=$row->logsdate?>" /></td>
            </tr>
        </table>
        <hr>
        <?php 
            }
        }
        ?>
            <button type="submit" name="submit" class="button-staff-add-material-submit">EDIT</button>
            </form>
            <a href="logs.php"><button class="button-staff-back-material-submit">BACK</button></a>
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