<?php

// Authors: 	Nicolas Solioz
// Created: 	2020/07/14
// Last update:	2020/07/27

require_once("log.php");
require_once("model/ConnectionManager.php");
require_once("model/Entity.php");


class Similarity extends Entity
{
    public $id;
    public $score;
    public $reference;
    public $article_id;
    public $report_url;

    public function __construct()
    {
        parent::__construct("SIMILARITY", "SIMILARITY");
    }

    public function getByArticleId($article_id)
    {

        $entity = new Similarity();
        $res = $entity->getRowsFromQuery("SELECT * FROM SIMILARITY WHERE SIMILARITY_ARTICLE_ID = '" . $article_id ."'");

        
        $similarity = new Similarity();

       //if (!empty($res)) {
    if ($res != null) {
        
            
            while($row = $res->fetch_assoc())
			{
                //$row = $res->fetch_assoc();

                $similarity->id = $row['SIMILARITY_ID'];
                $similarity->score = $row['SIMILARITY_SCORE'];
                $similarity->reference = $row['SIMILARITY_REFERENCE'];
                $similarity->article_id = $row['SIMILARITY_ARTICLE_ID'];
                $similarity->report_url = $row['SIMILARITY_REPORT_URL'];
            }

            $res->close();
        }

        return $similarity;
    }

    public function checkExist($article_id)
    {
        $similarity = Similarity::getByArticleId($article_id);

        if($similarity->article_id == $article_id)
            return true;
        else
            return false;
    }

    public function update()
    {
        $entity = new Similarity();

        //encoding UTF8 in order to understand accents
        $query = "SET NAMES utf8";
        $this->executeQuery($query);

        $query = "UPDATE SIMILARITY SET SIMILARITY_SCORE='".$this->score."', SIMILARITY_REFERENCE='".$this->reference."', SIMILARITY_ARTICLE_ID='".$this->article_id."', SIMILARITY_REPORT_URL='".$this->report_url."' WHERE SIMILARITY_ID=".$this->id;

        $this->executeQuery($query);
    }

    public function checkUrlEmpty($articleId)
    {
        $entity = new Similarity();
        $res = $entity->getRowsFromQuery("SELECT * FROM SIMILARITY WHERE SIMILARITY_ARTICLE_ID = '" . $articleId ."'");

        $similarity = new Similarity();
        $value = "";
        
        if ($res != null) {

            while($row = $res->fetch_assoc())
			{
                //$row = $res->fetch_assoc();

                $similarity->id = $row['SIMILARITY_ID'];
                $similarity->score = $row['SIMILARITY_SCORE'];
                $similarity->reference = $row['SIMILARITY_REFERENCE'];
                $similarity->article_id = $row['SIMILARITY_ARTICLE_ID'];
                $similarity->report_url = $row['SIMILARITY_REPORT_URL'];
            }
            $res->close();
        }

        if(strlen($similarity->report_url) < 1)
            $value = "empty";
        else
            $value = "not empty";

        return $value;
    }

    public function save()
    {
        $entity = new Similarity();

        //encoding UTF8 in order to understand accents
        $query = "SET NAMES utf8";
        $this->executeQuery($query);

        $query = "INSERT INTO SIMILARITY(SIMILARITY_SCORE, SIMILARITY_REFERENCE, SIMILARITY_ARTICLE_ID, SIMILARITY_REPORT_URL) VALUES('".$this->score."', '".$this->reference."', '".$this->article_id."', '".$this->report_url."')";

        $this->executeQuery($query);
    }

}

?>