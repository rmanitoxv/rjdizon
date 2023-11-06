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
        $online = pg_query($con, "SELECT * FROM accounts INNER JOIN staff ON accounts.userid = staff.userid WHERE accounts.userid = '$id'");
        $result =  pg_fetch_assoc($online);
        $role = strtoupper($result['staffposition']);
        $id = $_GET['id'];
		if ($role == "ARTIST"){
            echo '<meta http-equiv="refresh" content="0;url=orderprogress1.php?id=$id&num=$num">';
        }
        $query=pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$id");
        $result=pg_fetch_assoc($query);
        $prog = $result['pgstatus'];
        $customer = $result['pgcustomer'];
        $rows1 = pg_query($con, "SELECT * FROM projecttask WHERE pgid=$id AND taskprogress='$prog'");
        $progress = array("Product Design", "Proofing", "CTP Processing", "Mass Production", "Delivery");
        $handler = array("Artist", "Office Clerk", "Office Clerk", "General Manager");
        $tasks = array(array("Receive design", "Buy plate", "Print design to plate"), array("Buy materials", "Produce single product", "Send product for approval"), array("Bring materials to second site", "Mass produce product", "Send products to first site"), array("Prepare documents", "On Delivery", "Delivered"));
        if (isset($_POST['done'])){
            $taskID = $_POST['id'];
            $desc = $_POST['desc'];
            $date = (date("F d, Y"));
            $tz = 'Asia/Manila';
            $timestamp = time();
            $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
            $dt->setTimestamp;
            $time = $dt->format('H:i:s');
            pg_query($con, "UPDATE projecttask SET taskstatus=1 WHERE taskid=$taskID");
            $notif = "$role just finished the $desc task for $customer.";
            pg_query($con, "INSERT into notifications (notif, ndate, ntime) VALUES ('$notif', '$date', '$time')");
            $rows = pg_query($con, "SELECT * FROM projecttask WHERE pgid=$id AND taskprogress='$prog' AND taskstatus=0");
            if (pg_num_rows($rows)==0){
                for ($i=0;$i<count($progress);$i++){
                    if ($progress[$i] == $prog){
                        $next = $progress[$i+1];
                        $h = $handler[$i];
                        $n = $i+2;
                        pg_query($con, "UPDATE projectgroup SET pgstatus='$next', pghandler='$h', pgsn=$n WHERE pgid=$id");
                        foreach ($tasks[$i] as $row){
                            pg_query($con, "INSERT into projecttask (pgid, taskprogress, taskdesc, taskstatus) VALUES ($id, '$next', '$row', 0)");
                        }
                    }
                }
                echo '<meta http-equiv="refresh" content="0;url=orders.php">';
            }
            else{
                echo '<meta http-equiv="refresh" content="0;url=orderprogress.php?id=$id">';
            }
        }
        if (isset($_POST['undo'])){
            $taskID = $_POST['id'];
            pg_query($con, "UPDATE projecttask SET taskstatus=0 WHERE taskid=$taskID");
            echo '<meta http-equiv="refresh" content="0;url=orderprogress.php?id=$id">';
        }
        
		$rows = pg_query($con, "SELECT * FROM projectmaterials WHERE pgid=$id AND pmprogress='$prog'");
?>
<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="../style.css">
	<title>Current Progress</title>
	
</head>
<body>
	<div class="container-orders">
		<div class="container-content-header">
            <b>ON-GOING ORDERS</b>
        </div>
		<div class="container-records-logs-header">
        	ORDER #<?=$result['pglink']?><br>
    	</div>
		<div class="container-records-logs-header">
        	Customer <?=$result['pgcustomer']?><br>
    	</div>
		<div class="">
		<br>
        <div class="container-order-progress-2">
            <div class="container-order-materials-used">
                <table width="100%" class="table-orderinfo" cellspacing="0">
                    <tr class="row-order-progress-header">
                        <td class="cell-order-progress-text" width="70%">Materials Used</td>
                        <td class="cell-order-progress-text" width="30%">Qty</td>
                    </tr>
					<?php 
					$j = "blue";
					while ($row = pg_fetch_object($rows)){
					?>
					<tr class="row-container-order-progress-<?=$j?>">
						<td class="cell-container-order-progress"><?=$row->pmmaterial?></td>
						<td class="cell-container-order-progress"><?=$row->pmqty?></td>
					</tr>
					<?php
					if ($j == "blue"){
                        $j = "grey";
                    }
                    else{
                        $j = "blue";
                    }
                    }
					?>
				</table>
				<form method="GET">
					<input type="hidden" name="id" value="<?=$id?>" />
					<input type="hidden" name="num" value="<?=$num?>" />
					<input type="hidden" name="prog" value="<?=$prog?>" />
					<button class="orderprogress-button" formaction="add_material.php">Add Materials</button>
				</form>
            </div>
            <div class="container-order-tasks-list">
                <table width="100%" class="table-orderinfo" cellspacing="0" align="center">
                    <tr class="row-order-progress-header">
                        <td class="cell-order-progress-text" colspan="3">Tasks List</td>
                    </tr>
					<?php 
                        $j = "blue";
                        while ($row = pg_fetch_object($rows1)){
                    ?>
					<tr class="row-container-order-progress-<?=$j?>">
                        <td width="20%"><div class="cell-container-order-progress">
						<form method="POST">
						<input type="hidden" name="id" value="<?=$row->taskid?>" />
						<input type="hidden" name="desc" value="<?=$row->taskdesc?>" />
						<?php if ($row->taskstatus == 0){
							echo "&nbsp;";
						}
						else {
							echo "<img src='../img/check.png'>";
						}
						?></div></td>
						<td class="cell-container-order-progress" width="60%"><?=$row->taskdesc?></td>
                        <td class="cell-container-order-progress">
                        <?php if ($row->taskstatus == 1){
							echo "<button class='button-staff-done' name='undo'>UNDO</button>";
						}
						else {
							echo "<button class='button-staff-done' name='done'>DONE</button>";
						}
                        ?>
                        </td>
						</form>
					</tr>
					<?php 
                        if ($j == "blue"){
                            $j = "grey";
                        }
                        else{
                            $j = "blue";
                        }
                    }
					?>
				</div>
			</div>
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