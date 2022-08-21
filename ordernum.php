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
        $id = $_GET['id'];
        $query=pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$id");
        $result=pg_fetch_assoc($query);
?>
<!DOCTYPE html>
<head>
	<title>Order Information</title>
	<link rel="stylesheet" href="style.css">
	<meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
	<div class="container-content-header">
        <b>ON-GOING ORDERS</b>
    </div>
	<div class="container-orderinfo-details">
		Order # <?=$result['pglink']?><br>

		<table width="100%" class="table-orderinfo" cellspacing="0">
				<tr class="row-container-orderlist-blue">
                    <td class="cell-orderlist-label">Customer Name:</td>
                    <td class="cell-orderlist-info"><?=$result['pgcustomer']?></td>
                </tr>
                <tr class="row-container-orderlist-grey">
                    <td class="cell-orderlist-label">Delivery Address:</td>
                    <td class="cell-orderlist-info"><?=$result['pgaddress']?></td>
                </tr>
                <tr class="row-container-orderlist-blue">
                    <td class="cell-orderlist-label">Tracking Number:</td>
                    <td class="cell-orderlist-info"><?=strtoupper($result['pglink'])?></td>
                </tr>
                <tr class="row-container-orderlist-grey">
                    <td class="cell-orderlist-label">Date Ordered:</td>
                    <td class="cell-orderlist-info"><?=$result['pgdate']?></td>
                </tr>
                <tr class="row-container-orderlist-blue">
                    <td class="cell-orderlist-label">Total Price:</td>
                    <td class="cell-orderlist-info"><?=$result['pgtotal']?> Pesos</td>
                </tr>
                <tr class="row-container-orderlist-grey">
                    <td class="cell-orderlist-label">Current Handler:</td>
                    <td class="cell-orderlist-info"><?=$result['pghandler']?></td>
                </tr>

		</table>
	</div>
	<div class="container-orderinfo-progress">
            Current Progress
            <table width="100%" class="table-orderinfo" cellspacing="0">
                <tr class="row-container-orderlist-progress">
                    <td width="10%"></td>
                    <td>
					<?php 
						if ($result['pgstatus']=="Product Design"){
							echo "<img src='img/productdesign.png';>";
						}
						if ($result['pgstatus']=="CTP Processing"){
							echo "<img src='img/ctpProcessing.png'>";
						}
						if ($result['pgstatus']=="Proofing"){
							echo "<img src='img/proofing.png'>";
						}
						if ($result['pgstatus']=="Mass Production"){
							echo "<img src='img/massproduction.png'>";
						}
						if ($result['pgstatus']=="Delivery"){
							echo "<img src='img/delivery.png'>";
						}
					?>
					</td>
                    <td class="cell-orderlist-progress-edit"><?=strtoupper($result['pgstatus'])?><br>
						<form method="GET" action="billingstatement.php">
							<?php echo "<input type='hidden' name='id' value='$id'>";
							$sql = pg_query($con, "SELECT * from projecttask WHERE pgid=$id and taskprogress='Delivery' and taskdesc='Prepare documents'");
							if (pg_num_rows($sql) > 0){
								$task = pg_fetch_assoc($sql);
								if ($task['taskstatus'] == 0){ ?>
									<button class="button-orderlist-progress-editprogress">PREPARE DOCUMENTS</button>
								<?php } }?>
					
                    </td>
                    <td class="cell-orderlist-progress-buttons">

						<div class="sidebuttons">
								<button class="button-orderlist-progress-delete" formaction="delete_order.php" >DELETE</button><br>
								<?php $sql = pg_query($con, "SELECT * from projecttask WHERE pgid=$id and taskprogress='Delivery' and taskdesc='Prepare documents'");
								if (pg_num_rows($sql) > 0){
									$task = pg_fetch_assoc($sql);
									if ($task['taskstatus'] == 1){
								?>
								<button class="button-orderlist-progress-complete" formaction="input_or.php" >COMPLETE</button>
								<?php } }?>
							</div>
						</form>

                    </td>
                </tr>
            </table>
        </div>

</body>
<?php
    }
    else {
        header("Location: login.php");
        exit();
    }
?>