<?php
include('LNImages/Image.php');
include('LNImages/CompareImages.php');

$requestedFile = (isset($_GET['file'])) ? $_GET['file'] : $argv[1];

$image = new Image();
$image->setSourceDir('/home/console/files/amazingradios/files/cdnsources/');
$image->setTargetDir('/home/console/files/amazingradios/files/cdn/');

$image->lossy = 1;
$image->create('/tmp1');

$image->lossy = 0;
$image->create('/tmp2');

$compared = new CompareImages('tmp1', 'tmp2');

echo $compared;


exec('/usr/bin/php /home/console/nextcloud/occ files:scan amazingradios > /dev/null &');

header('Location: ' . $requestedFile);
