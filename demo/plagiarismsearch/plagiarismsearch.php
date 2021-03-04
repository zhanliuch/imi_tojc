<?php
// Authors: 	Nicolas Solioz
// Created: 	2020/05/12
// Last update:	2020/07/27

//this code was used for "ETAT DE l'ART II" to test the API and compare results with other plagiat detection systems
//we are keeping this code because it gives a good idea of how the API works

/* @var $api Reports */

require_once 'init-api.php';

    $data = [
        'callback_url' => 'localhost:8080/plagiarismsearch-callback.php',
    ];

    //loop from 1 to 50
    for($x = 1; $x<=63; $x++)
    {
        $files = [
            'file' => realpath('C:/Users/Nicolas Solioz/Documents/HES/TB/OJILTRA/Sprint 4/raw article text/' . $x . '.txt'),
        ];

        $myfile = fopen("ps-" . $x . ".txt", "w") or die("Unable to open file!");
        fwrite($myfile, $api->createAction($data, $files));
    }
    echo "done";



