<?php
    session_start();
    include("config.php");
    $id = $_GET['id'];
    $query=pg_query($con, "SELECT * FROM deliveryreceipt WHERE drid=$id");
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
    <title>Delivery Receipt</title>
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body> 
    <table>
        <tr>
            <td width="20%"> <img src="./img/oldlogo.png" width="100%">
            <td width="90%"> <center>
            <h1>RJ DIZON PRINTING PRESS</h1>
            2242 P.Binay St. Brgy. Bangkal, Makati City, Philippines 1233 <br/>
            Tel. Nos.: 986-8394 / Fax: 889-1269<br>
            E-mail: rjdizonprintingpress@yahoo.com<br>
            Ruben DJ. Dizon Jr. -Prop.<br>
            VAT Reg.Tin: 222-925-125-00000</td>
            <td width="10%">&nbsp;</td>
            </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="35%"><h2><u>DELIVERY RECEIPT</u></h2></td>
            <td width="30%">&nbsp;</td>
            <td width="35%"><h2 style="color:red"><center>No. <?=$result['drserial']?></center></h2></td>
        </tr>
        <tr>
            <td colspan="2">DELIVERED TO: <?=$result['drcustomer']?></td>
            
        </tr>
        <tr>
            <td colspan="2">ADDRESS: <?=$result['draddress']?> </td>
            <td>DATE: <?=$result['drdate']?></td>
        </tr>
        <tr>
            <td colspan="2">TIN: <?=$result['drtin']?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">BUSINESS STYLE: <?=$result['drbstyle']?> </td>
            <td>P.O. No.: <?=$result['drpo']?></td>
        </tr>
    </table>
    <table width="100%" border=1 style="border-spacing: 0;">
        <tr>
            <td><b>Quantity</td>
            <td><b>Unit</td>
            <td><b>Description</td>
        </tr>
        <?php
            $rows=pg_query($con, "SELECT * FROM drdetails WHERE drid=$id");
            $i=1;
            while ($row = pg_fetch_object($rows)){
        ?>
        <tr>
            <td><?=$row->drdquantity?></td>
            <td><?=$row->drdunit?></td>
            <td><?=$row->drddescription?></td>
        </tr>
        <?php
        $i+=1; 
            }
        $i = 10 - $i;
        for ($j=0;$j<=$i;$j++){
        ?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php
        }
        ?>
    </table>
    <table width="100%" style="border-spacing:1rem;">
        <tr>
            <td width="35%">&nbsp;</td>
            <td colspan="2">Received the above goods in good order and condition</td>
        </tr>
        <tr>
            <td width="35%"><center><?=$result['drauthor'];?></td>
            <td width="35%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-top: 1px solid black"><center>Prepared by</td>
            <td style="border-top: 1px solid black"><center>Signature Over Printed Name</td>
            <td style="border-top: 1px solid black"><center>Date Received</td>
        </tr>
    </table>

</body>
</html>