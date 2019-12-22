<?php
include('LNImages/ImageSource.php');
include('LNImages/Image.php');


$requestedImage = "/res/amazing-80s.jpg";

$image = new Image();
$image->setSourceDir('./sources');
$image->setTargetDir('./cdn');

$image->create($requestedImage);


