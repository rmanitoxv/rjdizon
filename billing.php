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
        $query="SELECT * FROM billingstatement WHERE bs_isactive=1";
        if (isset($_GET['search1'])){
            $search=$_GET['search'];
            $query = "SELECT * FROM billingstatement WHERE bs_isactive=1 AND (bscustomer LIKE '%$search%' OR bsserial::text LIKE '%$search%' OR bstotal::text LIKE '%$search%' OR bscashier LIKE '%$search%')";
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
    <div class="container-records-logs-header">
        BILLING STATEMENTS<br>
    </div>
    <div class="container-records-logs-body">
        <form method="GET">
                <input type="search" id="site-search" name="search" placeholder="" class="input-records-logs-search"><br>
                <button name="search1" class="button-records-logs-search">SEARCH</button>
            <div class="container-records-logs-body-buttons">
                <br>
                <button name="remove" formaction="remove_billing.php" class="button-records-logs-remove">DELETE</button>
                <button name="edit" formaction="edit_billing.php" class="button-records-logs-edit">EDIT</button>
            </div>
        <div class="container-table">
            <div class="row-records-logs-header">
                <div class="cell-records-bs-header-or">Serial No.</div>
                <div class="cell-records-bs-header-customer">Customer</div>
                <div class="cell-records-bs-header-tin">Quantity</div>
                <div class="cell-records-bs-header-cashier">Unit</div>
                <div class="cell-records-bs-header-pay">Total</div>
                <div class="cell-records-bs-header-amount">Cashier</div>
                <div class="cell-records-bs-header-view">&nbsp;</div>
            </div>
            <?php
                    $i = 1;
                    $j = "blue";
                    $rows = pg_query($con, $query);
                    while ($row = pg_fetch_object($rows)){
                    $id = $row->bsid;
                ?>
                <input type="checkbox" class="cbx" id="cbx<?=$i?>" name="ids[]" value=<?=$row->bsid?> />
                <label class="logs-cbx-<?=$j?>" for="cbx<?=$i?>">
            <div class="row">
                <div><?=$row->bsserial?></div>
                <div><?=$row->bscustomer?></div>
                <div class="container-table">
                    <?php 
                        $rows1 = pg_query($con, "SELECT * FROM bsdetails WHERE bsid=$id");
                        while ($row1 = pg_fetch_object($rows1)){ ?>
                        <div>
                            <div><?=$row1->bsdquantity?></div> 
                        </div>
                    <?php  }  ?>
                </div>
                <div class="container-table">
                    <?php
                        $rows1 = pg_query($con, "SELECT * FROM bsdetails WHERE bsid=$id"); 
                        while ($row2 = pg_fetch_object($rows1)){ ?>
                        <div>
                            <div><?=$row2->bsdunit?></div>
                        </div>
                    <?php }  ?>
                </div>
                <div><?=$row->bstotal?></div>
                <div><?=$row->bscashier?></div>
                <div class="viewbutton" ><a href="view_billing.php?id=<?=$row->bsid?>"><button type="button" class="button-records-bs-view">VIEW</button></a></div>
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
    </div>
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