<?php


class CompareImages {

	public function __construct($img1, $img2) {

		// init the image objects
		$image1 = new imagick();
		$image2 = new imagick();

		// set the fuzz factor (must be done BEFORE reading in the images)
		// $image1->SetOption('fuzz', '2%');

		// read in the images
		$image1->readImage($img1);
		$image2->readImage($img2);

		// compare the images using METRIC=1 (Absolute Error)
		$result = $image1->compareImages($image2, Imagick::METRIC_MEANSQUAREERROR);

		// print out the result
		return $result[1] * 1000;

	}

}

