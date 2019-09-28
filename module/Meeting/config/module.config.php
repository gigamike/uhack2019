<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'Meeting\Controller\Index' => 'Meeting\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'meeting' => __DIR__ . '/../view',
				),
		),
);
