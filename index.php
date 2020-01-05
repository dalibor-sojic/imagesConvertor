<?php
include('LNImages/Image.php');

$requestedFile = (isset($_GET['file'])) ? $_GET['file'] : $argv[1];

$image = new Image();
$image->setSourceDir('/home/console/files/amazingradios/files/cdnsources/');
$image->setTargetDir('/home/console/files/amazingradios/files/cdn/');

$image->create($requestedFile);

exec('/usr/bin/php /home/console/nextcloud/occ files:scan amazingradios > /dev/null &');

header('Location: ' . $requestedFile);
