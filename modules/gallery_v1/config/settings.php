<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'image' => array(
		// method to create image (one of Resizer methods)
		'method'  => 'fit_into_box',
		'size'    => 1536,
		'quality' => 85,
	),

	'thumb' => array(
		// prefix for thumbnails, put something that will not
		// collide with your usual file names
		'prefix'  => '__',
		// method to create thumbnail (one of Resizer methods)
		'method'  => 'fit_into_box',
		'size'    => 200,
		'quality' => 85,
	),
);
