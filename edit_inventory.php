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
            header ("Location: inventory.php");
        }
        $all_id = $_GET['id'];
        $ids = implode(', ', $all_id);
        $count = count($all_id);
        if(isset($_POST['submit'])){
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $name = $_POST['name'.$i];
                $qty = $_POST['qty'.$i];
                $unit = $_POST['unit'.$i];
                $ppu = $_POST['ppu'.$i];
                $th = $_POST['th'.$i];
                $query = "UPDATE inventory SET itemname='$name', itemppu='$ppu', itemunit='$unit', itemthreshold='$th', itemquantity='$qty' WHERE itemid='$id' ";
                pg_query($con, $query);
            }
            header ("Location: inventory.php");
        }
?>
<!DOCTYPE html>
<head>
	<title>Inventory</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>

    <div class="container-content-header">
        <b>INVENTORY</b>
    </div>
    <div class="container-inventory-add-header">
        EDIT MATERIAL<br>
    </div>

    <div class="container-inventory-add2">
        <div class="container-inventory-add">
            <form method="POST">
            <?php 
                for($i=0;$i<$count;$i++){
                    $id = $all_id[$i];
                    $query = pg_query($con, "SELECT * FROM inventory WHERE itemid='$id'");
                    while ($row = pg_fetch_object($query)){
            ?>
            <table width="100%">
                        
                        <tr>
                            <td class="cell-inventory-add-label">Material Name:</td>
                            <td style="text-align: left;"><input type="text" class="cell-inventory-add-input" name="name<?=$i?>" value="<?=$row->itemname?>"/></td>
                        </tr>
                        <tr>
                            <td class="cell-inventory-add-label">Quantity:</td>
                            <td style="text-align: left;"><input type="number" class="cell-inventory-add-input" min="0" name="qty<?=$i?>" value="<?=$row->itemquantity?>" /></td>
                        </tr>
                        <tr>
                            <td class="cell-inventory-add-label">Unit:</td>
                            <td style="text-align: left;"><input type="text" class="cell-inventory-add-input" name="unit<?=$i?>" value="<?=$row->itemunit?>" style="width: 10rem;" /></td>
                        </tr>
                        <tr>
                            <td class="cell-inventory-add-label">Price per Unit:</td>
                            <td style="text-align: left;"><input type="number" class="cell-inventory-add-input"  min="0" name="ppu<?=$i?>" value="<?=$row->itemppu?>" style="width: 10rem;" /></td>
                        </tr>
                        <tr>
                            <td class="cell-inventory-add-label">Low Indicator Value:</td>
                            <td style="text-align: left;"><input type="number" class="cell-inventory-add-input" min="0" name="th<?=$i?>" value="<?=$row->itemthreshold?>" /></td>
                        </tr>
            </table>
            <hr>
            <?php 
                }
            }
            ?>
                <div class="row">
                    <a href="inventory.php"><button type="button" class="button-inventory-add-material-submit">BACK</button></a>
                    <button name="submit" class="button-inventory-add-material-submit">EDIT</button>
                </div>
            </form>
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