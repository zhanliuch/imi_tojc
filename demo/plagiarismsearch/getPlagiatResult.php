<?php
// Authors: 	Nicolas Solioz
// Created: 	2020/05/12
// Last update:	2020/07/27

//this code was used for "ETAT DE l'ART II" to test the API and compare results with other plagiat detection systems
//we are keeping this code because it gives a good idea of how the API works

/* @var $api Reports */

require_once 'init-api.php';

$myfile = fopen("plagiat-results.txt", "w") or die("Unable to open file!");

for($x = 51; $x<=63; $x++)
{
    $fileread = "C:/Users/Nicolas Solioz/Documents/HES/TB/OJILTRA/web/ps/report/report-" . $x . ".txt";
    $handle = fopen($fileread, "r");
    $contents = fread($handle, filesize($fileread));

    $contentsParsed = explode(',"plagiat":', $contents);
    $contentsParsedTwice = explode(',', $contentsParsed[1]);
    $result = $contentsParsedTwice[0];

    fwrite($myfile, $result);
    fwrite($myfile, "%");
    fwrite($myfile, "\r\n");
}
