<?php
include('LNImages/Image.php');

$image = new Image();
$image->setSourceDir('/home/console/files/amazingradios/files/cdnsources/');
$image->setTargetDir('/home/console/files/amazingradios/files/cdn/');

$image->create($_GET['file']);

exec('/usr/bin/php /home/console/nextcloud/occ files:scan amazingradios > /dev/null &');

header('Location: ' . $_GET['file']);
