<?php
// Authors: 	Nicolas Solioz
// Created: 	2020/05/12
// Last update:	2020/07/27

//this code was used for "ETAT DE l'ART II" to test the API and compare results with other plagiat detection systems
//we are keeping this code because it gives a good idea of how the API works

require_once 'Reports.php';

$config = [
    'apiUrl' => 'https://plagiarismsearch.com/api/v3',
    'apiUser' => '',
    'apiKey' => '',
];

$api = new Reports($config);

?>