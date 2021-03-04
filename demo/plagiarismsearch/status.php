<?php

/* @var $api Reports */
require_once 'init-api.php';

$id = 100500;
$data = [];

echo $api->statusAction($id, $data);
