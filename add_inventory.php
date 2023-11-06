<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=staff.php">';
        }
        if(isset($_POST['submit'])){
            $name = $_POST['name'];
            $quantity = $_POST['quantity'];
            $threshold = $_POST['threshold'];
            $unit = $_POST['unit'];
            $ppu = $_POST['ppu'];
            $query = "INSERT into inventory (itemname, itemquantity, itemppu, itemunit, itemthreshold, item_isactive, item_date_of_inactive) VALUES ('$name', $quantity, $ppu, '$unit', $threshold, '1', '')";
            pg_query($con, $query);
            echo '<meta http-equiv="refresh" content="0;url=inventory.php">';
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
        ADD MATERIAL<br>
    </div>
    <div class="container-inventory-add2">
        <div class="container-inventory-add">
            <form method="POST">
                <table width="100%">
                        <tr style="text-align: left">
                            <td class="cell-inventory-add-label">Material Name:</td>
                            <td><input type="text" class="cell-inventory-add-input" name="name" required></td>
                        </tr>
                        <tr style="text-align: left">
                            <td class="cell-inventory-add-label">Quantity:</td>
                            <td><input type="number" class="cell-inventory-add-input" name="quantity" min="0" required></td>
                        </tr>
                        <tr style="text-align: left">
                            <td class="cell-inventory-add-label">Unit:</td>
                            <td><input type="text" class="cell-inventory-add-input" name="unit" required></td>
                        </tr>
                        <tr style="text-align: left">
                            <td class="cell-inventory-add-label">Price Per Unit:</td>
                            <td><input type="number" class="cell-inventory-add-input" name="ppu" min="0" required></td>
                        </tr>
                        <tr style="text-align: left">
                            <td class="cell-inventory-add-label">Low Indicator Value:</td>
                            <td><input type="number" class="cell-inventory-add-input" name="threshold" min="0" required></td>
                        </tr>
                </table>
                <button name="submit" class="button-inventory-add-material-submit">ADD</button>
            </form>
            <a href="inventory.php"><button class="button-inventory-add-material-submit">BACK</button></
        </div>
    </div>
</body>
<?php
    }
    else {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    }
?>