<?php

/* @var $api Reports */
require_once 'init-api.php';

$data = [];
$files = [
    'file' => realpath('pdf-sample.pdf'),
];

echo $api->createAction($data, $files);
