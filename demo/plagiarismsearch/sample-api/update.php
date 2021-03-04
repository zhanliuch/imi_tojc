<?php

/* @var $api Reports */
require_once 'init-api.php';

$id = 100500;

$updateReportFields = ['notified' => time()];

$data = ['report' => $updateReportFields];

echo $api->updateAction($id, $data);
