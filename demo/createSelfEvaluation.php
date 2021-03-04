<?php
// Authors: 	Nicolas Solioz
// Created: 	2020/27/05
// Last update:	2020/07/27

session_start();
error_reporting(0);

require_once("config.php");
require_once("model/ConnectionManager.php");
require_once("model/Entity.php");
require_once("model/Article.php");
require_once("model/Indicator.php");
require_once("model/IndicatorCategory.php");
require_once("model/SelfEvaluationScore.php");
require_once("model/SelfEvaluation.php");
require_once("model/Similarity.php");
require_once("log.php");
require_once("log.php");
require_once("createPlagiarismReport.php");

$values = array_values($_POST);
$article_id = $values[0];

$score = 0;

foreach (array_slice($_POST,1)as $key => $value) {
//key = id of input = choice + id of indicator
//value = value of the input (selected value of user)

    //get id of indicator
    $indicator_id = ltrim($key,"choice");

    //get id of indicator category
    $indicator_cat_id = Indicator::getCategory($indicator_id);

    //get weight of indicator category
    $weight = Indicator::getPonderation($indicator_id);
    $score += $value*$weight;

    //Insert into Self Evaluation only the values we have selected
    //if the indicator is not in the table, we know it's value is zero)
    if($value != 0) {
        $selfEvaluation = new SelfEvaluation();
        $selfEvaluation->indicator_id = $indicator_id;
        $selfEvaluation->validation = $value;
        $selfEvaluation->article_id = $article_id;
        $selfEvaluation->save();
    }
}

//Insert the score
$scoreEvaluation = new SelfEvaluationScore();
$scoreEvaluation->score = $score;
$scoreEvaluation->article_id = $article_id;
$scoreEvaluation->save();

//Change the status
$article = Article::getArticleById($article_id);
$article->changeStatus($article_id, 2);

header("Location: list-articles.php");

?>