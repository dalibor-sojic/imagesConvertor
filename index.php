<?php
include('LNImages/Image.php');
include('LNImages/CompareImages.php');

$requestedFile = (isset($_GET['file'])) ? $_GET['file'] : $argv[1];

$image = new Image();
$image->setSourceDir('/home/console/files/amazingradios/files/cdnsources/');
$image->setTargetDir('/home/console/files/amazingradios/files/cdn/');

$image->lossy = 1;
$image->create($requestedFile);

$image->lossy = 0;
$image->create($requestedFile);

$compared = new CompareImages('/home/console/files/amazingradios/files/cdn/'.$requestedFile.'1', '/home/console/files/amazingradios/files/cdn/'.$requestedFile.'0');

echo $compared;


exec('/usr/bin/php /home/console/nextcloud/occ files:scan amazingradios > /dev/null &');

header('Location: ' . $requestedFile);
