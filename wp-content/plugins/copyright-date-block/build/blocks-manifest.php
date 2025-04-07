<?php
// This file is generated. Do not modify it manually.
return array(
	'copyright-date-block' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'copyright-date/copyright-date-block',
		'version' => '0.1.0',
		'title' => 'Copyright Date Block',
		'category' => 'widgets',
		'icon' => 'calendar',
		'description' => 'Add a copyright date to your post and Page.',
		'example' => array(
			
		),
		'attributes' => array(
			'startingYear' => array(
				'type' => 'string',
				'default' => 2000
			)
		),
		'supports' => array(
			'html' => false,
			'align' => true
		),
		'textdomain' => 'copyright-date-block',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
	)
);
