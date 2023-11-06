<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = mysqli_query($con, "SELECT * FROM user WHERE userID = '$id'");
        $result =  mysqli_fetch_assoc($online);
        if ($result['isStaff'] == 1){
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: staff.php");
        }
        $id = $_GET['id'];
        $num = $_GET['num'];
        $prog = $_GET['prog'];
        $query = mysqli_query($con, "SELECT * FROM projectgroup WHERE pgID=$id");
        $result = mysqli_fetch_assoc($query);
        $rows = mysqli_query($con, "SELECT * FROM projectmaterials WHERE pgID=$id AND pmProgress='$prog'");
        $rows1 = mysqli_query($con, "SELECT * FROM projecttask WHERE pgID=$id AND taskProgress='$prog'");
        if (isset($_POST['submit'])){
            $taskID = $_POST['id'];
            mysqli_query($con, "UPDATE projecttask SET taskStatus=1 WHERE taskID=$taskID");
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: orderprogress.php?id=$id&num=$num&prog=$prog");
        }
?>
<!DOCTYPE html>
<head>
	<title>Current Progress</title>
	<link rel="stylesheet" href="currentprogress.css">
	<meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
	<div class="container">
		<div class="content">
			<b>Order Progress</b><br>
			<div class="content1">
				<b>Current Progress</b>
			</div>
			<div class="content2">
			<form method="GET">
				<ul>
					<input type="hidden" name="id" value="<?=$id?>" />
                    <input type="hidden" name="num" value="<?=$num?>" />
                    <input type="hidden" name="prog" value="<?=$prog?>" />
					<li><button formaction="add_material.php" >ADD MATERIALS</button></li>
					<li><?=strtoupper($result['pgStatus']);?></li>
					<li><button formaction="add_task.php" >ADD TASK</button></li>
				</ul>
			</form>
			</div>
			<div class="content4">
				<div class="content3">
					<div class="row">
						<div><b> Materials Used</b></div>
						<div><b>Qty</b></div>
					</div>
					<?php 
					foreach ($rows as $row):
					?>
					<div class="row">
						<div><?=$row['pmMaterial']?></div>
						<div><?=$row['pmQty']?></div>
					</div>
					<?php
					endforeach;
					?>
				</div>
				<div style="width: 20%"></div>
				<div class="content3">
					<div class="row">
						<div> &nbsp; </div>
						<div><b> Task List</b></div>
					</div>
					<?php 
					foreach ($rows1 as $row):
						?>
					<div class="row">
						<div>
						<form method="POST">
						<input type="hidden" name="id" value="<?=$row['taskID']?>" />
						<?php if ($row['taskStatus'] == 0){
							echo "<button name='submit'>DONE</button>";
						}
						else {
							echo "&#10004;";
						}
						?></div>
						<div><?=$row['taskDesc']?></div>
						</form>
					</div>
					<?php 
					endforeach
					?>
				</div>
			</div>
		</div>
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