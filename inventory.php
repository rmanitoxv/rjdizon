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
?>
<!DOCTYPE html>
<head>
	<title>Inventory</title>
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-content-header">
        <b>INVENTORY</b>
    </div>
    <div class="container-inventory">
        <div class="container-inventory-buttons">
        <form method="GET">
            <a href="add_inventory.php"><button type="button" class="button-inventory-add">ADD</button></a>
            <button name="remove" formaction="remove_inventory.php" class="button-inventory-remove">DELETE</button>
            <button name="edit" formaction="edit_inventory.php" class="button-inventory-edit">EDIT</button>
            <a href="download_inventory.php"> <button type="button" class="button-inventory-download">DOWNLOAD</button></a>
        </div>
        <div class="container-inventory-list">
            <table width="100%" class="table-inventory-list" cellspacing="0">
                <tr>
                    <td class="row-inventory-list-header">Item Name</td>
                    <td class="row-inventory-list-header">Quantity</td>
                    <td class="row-inventory-list-header">Status</td>
                </tr>
            </table>
            <div class="container-table">
            <?php
                $query="SELECT * FROM inventory WHERE item_isActive=1";
                $rows = pg_query($con, $query);
                $i = 1;
                $j = "blue";
                while ($row = pg_fetch_object($rows)){
            ?>
            <input type="checkbox" class="cbx" id="cbx<?=$i?>" name="id[]" value=<?=$row->itemid?> />
            <label class="cbx1-<?=$j?>" for="cbx<?=$i?>">
            <div class="row-inventory-list-materials">
                <div><?=$row->itemname?></div>
                <div><?=$row->itemquantity?> <?=$row->itemunit?></div>
                <div><?php 
                $status = $row->itemquantity / $row->itemthreshold;
                if ($status >= 1){
                    echo "<p class='cell-inventory-list-materials-okay'>OK</p>";
                }
                else if ($status == 0){
                    echo "<p class='cell-inventory-list-materials-empty'>NO STOCKS!</p>";
                }
                else {
                    echo "<p class='cell-inventory-list-materials-low'>LOW STOCK</p>";
                }
                ?></div>
            </div>
            </label>
            <?php 
                if ($j == "blue"){
                    $j = "grey";
                }
                else{
                    $j = "blue";
                }
                $i++;
            }
            ?>
        </div>
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