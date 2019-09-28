<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'PurchaseOrder\Controller\Index' => 'PurchaseOrder\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'purchase-order' => __DIR__ . '/../view',
				),
		),
);
