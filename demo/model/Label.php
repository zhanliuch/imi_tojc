<?php

// Authors: 	Zhan Liu
// Created: 	2020/05/05
// Last update:	2020/05/05

require_once("log.php");
require_once("model/ConnectionManager.php");
require_once("model/Entity.php");

class Label extends Entity
{
	public $id;
	public $dateCreation;
	public $code;
	public $qrcode;
	public $user_id;
	public $article_id;
	
	public function __construct()
	{
		parent::__construct("Label", "LABEL_");
	}
	
	public static function getAll()
	{
		$labels = array();
		
		$entity = new Label();
		//$res = $entity->getAllRows();
		$res = $entity->getRowsFromQuery("SELECT * FROM Label order by LABEL_CREATEDATE DESC");
		
		if ($res != null)
		{
			while($row = $res->fetch_assoc())
			{
				$label = new Label();
				$label->id = $row['LABEL_ID'];
				$label->dateCreation = $row['LABEL_DATECRATIOM'];
				$label->code = $row['LABEL_CODE'];
				$label->qrcode = $row['LABEL_QRCODE'];
				$label->user_id = $row['USER_ID'];
				$label->article_id = $row['ARTICLE_ID'];
				
				array_push($labels, $label);
			}
			
			$res->close();
		}
		
		return $labels;
	}	

	public function save()
	{
		$entity = new Label();

		$query = "INSERT INTO LABEL(LABEL_CODE, LABEL_DATECREATION, LABEL_QRCODE, USER_ID, ARTICLE_ID) VALUES('".$this->code."', '".date("Y-m-d H:i:s")."', '".$this->qrcode."', ".$this->user_id.", ".$this->article_id.")";		
		$this->executeQuery($query);
	}

	public static function getArticleIdByCode($labelCode)
	{
		$article_id = null;
		
		$entity = new Label();
		$res = $entity->getRowsFromQuery("SELECT ARTICLE_ID FROM LABEL WHERE LABEL_CODE = '" . $labelCode ."'");
		
		if ($res != null)
		{
			$row = $res->fetch_assoc();
			$article_id = $row['ARTICLE_ID'];
			
			$res->close();
		}
		
		return $article_id ;
	}

	public static function checkCodeInfo($code)
	{
		$articleId = 0;

		$entity = new Label();
		$res = $entity->getRowsFromQuery("SELECT * FROM LABEL WHERE LABEL_CODE = '" . $code . "'");
		
		
		//if ($res != null)
		if($row = $res->fetch_assoc())
		{
			wh_log("SELECT");
			//$row = $res->fetch_assoc();
			$articleId = $row['ARTICLE_ID'];
			
			$res->close();
		}
		//wh_log("articleId: ".$articleId);
		return $articleId ;
	}

	
}

?>