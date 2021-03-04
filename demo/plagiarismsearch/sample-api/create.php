<?php

/* @var $api Reports */
require_once 'init-api.php';

$data = [
    'text' => 'PlagiarismSearch.com – advanced online plagiarism checker available 24/7. PlagiarismSearch.com is a leading plagiarism checking website that will provide you with an accurate report during a short timeframe. Prior to submitting your home assignments, run them through our plagiarism checker to make sure your content is authentic.'
];

echo $api->createAction($data);
