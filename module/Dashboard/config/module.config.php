<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'Dashboard\Controller\Index' => 'Dashboard\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'dashboard' => __DIR__ . '/../view',
				),
		),
);
