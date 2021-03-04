<?php
// Authors: 	Nicolas Solioz
// Created: 	2020/29/05
// Last update:	2020/07/27

function downloadPlagiarismResults($code, $articleId, $url, $result)
{

// Insert the plagiarism report into the database, only if it doesn't exist! *****************************************************************************
    $similarity = new Similarity();
    $similarity = $similarity->getByArticleId($articleId);

    if($similarity->article_id != $articleId)
    {
        $similarity->article_id = intval($articleId);
        $similarity->score = intval($result);
        $similarity->reference = intval($code);
        $similarity->report_url = strval($url);
        $similarity->save();
    }
}
?>