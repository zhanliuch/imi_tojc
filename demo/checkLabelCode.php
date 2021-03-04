<?php
session_start();
error_reporting(0);

require_once("config.php");
require_once("model/ConnectionManager.php");
require_once("model/Entity.php");
require_once("model/User.php");
require_once("model/Article.php");
require_once("model/Label.php");
require_once("log.php");
//header("Location: traceability.html");

if (isset($_POST['code'])){
	$code = $_POST['code'];
	//wh_log("code ".$code);
	$articleId = Label::checkCodeInfo($code);
	//wh_log("articleId ".$articleId);
	
	if ($articleId > 0){
		$article = Article::getArticleById($articleId);

		$title = $article->title;
		$url = $article->url;
		$summary = $article->summary;
		$datePublication =  $article->dateCreation;
		$userId = $article->user_id;

		$userName = User::getUserNameById($userId);

		/*
		wh_log("title ".$title);
		wh_log("datePublication ".$datePublication);
		wh_log("userName ".$userName);
		wh_log("url ".$url);
		*/

		$_SESSION["userName"] = $userName;
		$_SESSION["datePublication"] = $datePublication;
		$_SESSION["url"] = $url;
		$_SESSION["title"] = $title;
		$_SESSION["summary"] = $summary;
		header("Location: traceability.php");
	}else{
		echo "<script language=\"JavaScript\">\n";
		echo "alert('Labelling code is invalid');\n";
		echo "window.location='searchLabel.php'";
		echo "</script>";

		//header("Location: searchLabel.php");
	}

}



?>

