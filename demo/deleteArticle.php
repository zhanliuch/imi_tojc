<?php
// Authors: 	Nicolas Solioz
// Created: 	2020/25/05
// Last update:	2020/07/27

session_start();
error_reporting(0);

require_once("config.php");
require_once("model/ConnectionManager.php");
require_once("model/Entity.php");
require_once("model/Article.php");
require_once("log.php");

// *** USED https://www.codegrepper.com/code-examples/delphi/how+to+get+current+url+in+php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
else
    $url = "http://";
// Append the host(domain name, ip) to the URL.
$url.= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL
$url.= $_SERVER['REQUEST_URI'];

// Use parse_url() function to parse the URL
// and return an associative array which
// contains its various components
$url_components = parse_url($url);

// Use parse_str() function to parse the
// string passed via URL
parse_str($url_components['query'], $params);
// ***

$articleId = $params['articleId'];
$article = Article::getArticleById($articleId);
$article->delete();

header("Location: list-articles.php");

?>


