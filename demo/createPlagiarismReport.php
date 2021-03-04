<?php
// Authors: 	Nicolas Solioz
// Created: 	2020/24/05
// Last update:	2020/07/27

//code used to create a plagiarism report using PlagiarismSearch API
function createPlagiarismReport($code, $content) {

require_once "plagiarismsearch/init-api.php";
require_once "plagiarismsearch/Reports.php";
require_once "log.php";
/* @var $api Reports */

$data = [
'callback_url' => 'localhost/plagiarismsearch-callback.php',
];

//STEP 1 : Create text file with raw text data from article ******************************************************************************
$articleFile = fopen("plagiarismsearch/articles/article-" . $code . ".txt", "w") or die("Unable to open file!");
fwrite($articleFile, $content);

//STEP 2 : Create plagiarism report using created text file ******************************************************************************
$files = [
'file' => realpath(str_replace('\\', '/',__DIR__) . "/plagiarismsearch/articles/article-" . $code . ".txt"),
];
$plagiarismFileW = fopen("plagiarismsearch/report/plagiarismreport-" . $code . ".txt", "w") or die("Unable to open file!");
//wh_log("FileW");
fwrite($plagiarismFileW, $api->createAction($data, $files));

fclose($articleFile);



// STEP 3 : Delete text file ******************************************************************************
unlink($articleFile);

header("Location: list-articles.php");

}

?>