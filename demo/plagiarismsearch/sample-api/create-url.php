<?php

/* @var $api Reports */
require_once 'init-api.php';

$data = [
    'url' => 'http://che.org.il/wp-content/uploads/2016/12/pdf-sample.pdf', // public url
];

echo $api->createAction($data);
