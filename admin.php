<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userID = $id");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=staff/staff.php">';
        }
?>
<DOCTYPE! html>
<head>
	<title>ADMIN | RJ Dizon Printing Press</title>
</head>
<body>
    <iframe src="nav.php" width="25%" height="99%" frameBorder=0 class="iframe-navbar"></iframe>
    <iframe src="overview.php" name="contentframe" width="74.5%" frameBorder=0 height="99%" class="iframe-content"></iframe>
</body>
</html>
<?php
    }
    else {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    }
?>