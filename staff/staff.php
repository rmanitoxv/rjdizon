<?php
    session_start();
    include("../config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isadmin'] == 1){
            header ("Location: ../admin.php");
        }
?>
<DOCTYPE! html>

<head>
	<title>HEAD</title>
</head>
<body>
    <iframe src="nav.php" width="25%" height="99%" frameBorder=0 class="iframe-navbar"></iframe>
    <iframe src="orders.php" name="contentframe" width="74.5%" frameBorder=0 height="99%" class="iframe-content"></iframe>
</body>
</html>
<?php
    }
    else {
        header("Location: ../login.php");
        exit();
    }
?>