<?php

// Authors: 	Zhan Liu, Nicolas Solioz
// Created: 	2020/05/05
// Last update:	2020/07/27

class SelfEvaluationScore extends Entity
{
    public $id;
    public $score;
    public $article_id;

    public function __construct()
    {
        parent::__construct("SELF_EVALUATION_SCORE", "SELF_EVALUATION_SCORE");
    }

    public static function getAll()
    {
        $evaluationScores = array();

        $entity = new SelfEvaluationScore();
        $res = $entity->getRowsFromQuery("SELECT * FROM SELF_EVALUATION_SCORE");

        if ($res != null)
        {
            while($row = $res->fetch_assoc())
            {
                $evaluationScore = new SelfEvaluation();
                $evaluationScore->id = $row['SELF_EVALUATION_SCORE_ID'];
                $evaluationScore->score = $row['SCORE'];
                $evaluationScore->article_id = $row['ARTICLE_ID'];

                array_push($evaluationScores, $evaluationScore);
            }

            $res->close();
        }

        return $evaluationScores;
    }

    public static function getScoreArticle($article_id)
    {

        $entity = new SelfEvaluationScore();
        $res = $entity->getRowsFromQuery("SELECT * FROM SELF_EVALUATION_SCORE WHERE ARTICLE_ID=" . $article_id);

        $score=0;

        if ($res != null)
        {
            while($row = $res->fetch_assoc())
            {
                $score = $row['SCORE'];
            }

            $res->close();
        }

        return $score;
    }

    public function save()
    {
        $entity = new SelfEvaluationScore();
        //error_log("contect:".$content, 3, "log/my-errors.log");

        $query = "INSERT INTO SELF_EVALUATION_SCORE(SCORE, ARTICLE_ID) VALUES('".$this->score."', ".$this->article_id.")";
        $this->executeQuery($query);

        //save to evaluation score, to decide if we use weight to the indicator, otherwise only check the number of validations
    }

}

?>