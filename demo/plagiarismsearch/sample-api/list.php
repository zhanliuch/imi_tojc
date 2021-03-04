<?php

/* @var $api Reports */
require_once 'init-api.php';

$data = [
    'page' => 2,
    'limit' => 5,
    'show_relations' => 0,
];

echo $api->indexAction($data);
