<?php
// Authors: 	Nicolas Solioz
// Created: 	2020/05/12
// Last update:	2020/07/27

//this code was used for "ETAT DE l'ART II" to test the API and compare results with other plagiat detection systems
//we are keeping this code because it gives a good idea of how the API works

/* @var $api Reports */

require_once 'init-api.php';

    for($x = 51; $x<=63; $x++)
    {
        $fileread = "C:/Users/Nicolas Solioz/Documents/HES/TB/OJILTRA/web/ps/ps-" . $x . ".txt";
        $handle = fopen($fileread, "r");
        $contents = fread($handle, filesize($fileread));

        $values = explode(',', $contents);
        $valuesParsed = explode(':', $values[2]);
        $reportID = $valuesParsed[2];

        $data = [];

        $myfile = fopen("report-" . $x . ".txt", "w") or die("Unable to open file!");

        fwrite($myfile, $api->viewAction($reportID, $data));
    };

echo "done";