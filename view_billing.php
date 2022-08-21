<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf(array('enable_remote' => true));;
$id = $_GET['id'];
$php = "bs.php";
ob_start(); require $php; 
$html = ob_get_clean();
ob_end_clean();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream('Billing Statement', array('Attachment'=>0));
?>