<?php 
define('TPL_DIR','templates_legacy');
require_once('/opt/unetlab/html/includes/init.php');
$file=$argv[1];
$lab = new Lab($file, 0);
$lab -> save();
?>
