<?php
    session_start();
    include("config.php");
    $id = $_GET['id'];
    $query=pg_query($con, "SELECT * FROM officialreceipt WHERE orid=$id");
    $result=pg_fetch_assoc($query);
    $pgid = $result['pgid'];
    $query1=pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$pgid");
    $result1=pg_fetch_assoc($query1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt</title>
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body> 
<table width="100%">
        <tr>
            <td width="15%"> <img src="./img/oldlogo.png" width="100%">
            <td width="85%"> <center>
            <h1>RJ DIZON PRINTING PRESS</h1>
            2242 P.Binay St. Brgy. Bangkal, Makati City, Philippines 1233 <br/>
            Tel. Nos.: 986-8394 / Fax: 889-1269<br>
            E-mail: rjdizonprintingpress@yahoo.com<br>
            Ruben DJ. Dizon Jr. -Prop.<br>
            VAT Reg.Tin: 222-925-125-00000</td>
            </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="35%"><h2>OFFICIAL RECEIPT</h2></td>
            <td width="30%">&nbsp;</td>
            <td width="35%"><h2 style="color:red"><center><?=$result['orserial']?></center></h2></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right">Date</td>
            <td style="border-bottom: 1px solid black"><center><?=$result['ordate']?></td>
        </tr>
    </table>
    <p style="font-size:1.5rem">
    RECEIVED from <u>     <?=$result['orcustomer']?>     </u> with TIN <u>     <?=$result['ortin']?>     </u> and address at <u>     <?=$result1['pgaddress']?>     </u>, the sum of <u>     <?=$result['oramount']?>     </u> pesos in 
    <?php
    if ($result['or_ispartial'] == 1){
        echo ("Partial");
    }
    else{
        echo ("Full");
    }
    ?>
    payment for Tracking Number <u>     <?=$result1['pglink']?>     </u>
    </p>
    <table width="100%">
        <tr>
            <td width="50%">&nbsp;</td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="10%" style="text-align:right">By:</td>
                        <td style="border-bottom: 1px solid black"><center><?=$result['orcashier']?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><center>Cashier/Authorized Representative</center></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <u><i>THIS OFFICIAL RECEIPT SHALL BE VALID FOR FIVE (5) YEARS FROM THE DATE OF ATP.</i></u>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>