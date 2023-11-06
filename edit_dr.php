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
            header ("Location: del_receipt.php");
        }
        $all_id = $_GET['ids'];
        $ids = implode(', ', $all_id);
        $count = count($all_id);
        if(isset($_POST['submit'])){
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $customer = $_POST['customer'.$i];
                $serial = $_POST['serial'.$i];
                $date = $_POST['date'.$i];
                $dateReceived = $_POST['dateReceived'.$i];              
                $query = "UPDATE deliveryreceipt SET drserial='$serial', drcustomer='$customer', drdatereceived='$dateReceived', drdate='$date' WHERE drID='$id' ";
                pg_query($con, $query);
            }
            header ("Location: del_receipt.php");
        }

?>
<!DOCTYPE html>
<head>
    <title>Delivery Receipts</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-content-header">
            <b>RECORDS</b>
        </div>
        <div class="container-inventory-add-header">
            EDIT DELIVERY RECEIPT<br>
        </div>
        <div class="container-inventory-add2">
            <div class="container-inventory-add">
            <form method="POST">
            <?php 
                for($i=0;$i<$count;$i++){
                    $id = $all_id[$i];
            $query = pg_query($con, "SELECT * FROM deliveryreceipt WHERE drID='$id'");
            while ($row = pg_fetch_object($query)){
        ?>
        <h2 style="color:white;font-size:3rem;"> Delivery Receipt # <?=$i+1?></h2>
        <table width="100%">
            <tr>
                <td class="cell-inventory-add-label">Serial Number:</td>
                <td><input class="cell-inventory-add-input" type="text" maxlength="5" name="serial<?=$i?>" value="<?=$row->drserial?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Customer Name:</td>
                <td><input class="cell-inventory-add-input" type="text" name="customer<?=$i?>" value="<?=$row->drcustomer?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Date:</td>
                <td><input class="cell-inventory-add-input" type="text" name="date<?=$i?>" value="<?=$row->drdate?>" /></td>
            </tr>
            <tr>
                <td class="cell-inventory-add-label">Date Received:</td>
                <td><input class="cell-inventory-add-input" type="text" name="dateReceived<?=$i?>" value="<?=$row->drdatereceived?>" /></td>
            </tr>
        </table>
        <?php 
            }
        }
        ?>
            <<button type="submit" name="submit" class="button-staff-add-material-submit">EDIT</button>
            </form>
            <a href="del_receipt.php"><button class="button-staff-back-material-submit">BACK</button></a>
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