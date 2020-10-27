<?php
include('LNImages/Image.php');
include('LNImages/CompareImages.php');

$requestedFile = (isset($_GET['file'])) ? $_GET['file'] : $argv[1];

$image = new Image();
$image->setSourceDir('/home/rtk/Documents/Projects');
$image->setTargetDir('/home/rtk/Desktop/');

$image->lossy = 1;

try {
    $image->create($requestedFile);
}
catch (SourceNotFoundException $ex) {
    echo $ex->getError();
}

// $image->lossy = 0;
// $image->create($requestedFile);

// $compared = new CompareImages('/home/console/files/amazingradios/files/cdn/'.$requestedFile.'1', '/home/console/files/amazingradios/files/cdn/'.$requestedFile.'0');

// echo $compared;


#exec('/usr/bin/php /home/console/nextcloud/occ files:scan amazingradios > /dev/null &');

header('Location: ' . $requestedFile);
