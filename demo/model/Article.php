<?php

// Authors: 	Zhan Liu, Nicolas Solioz
// Created: 	2020/05/05
// Last update:	2020/07/27
require_once("log.php");
require_once("model/ConnectionManager.php");
require_once("model/Entity.php");
require_once("model/User.php");


class Article extends Entity
{
	public $id;
	public $code;
	public $title;
	public $summary;
	public $content;
	public $dateCreation;
	public $url;
	public $nbClick;
	public $nbShare;
	public $user_id;
	public $article_status_id;
	
	public function __construct()
	{
		parent::__construct("ARTICLE", "ARTICLE_");
	}

	//return all articles of the current logged in user
	public static function getByLoggedUserId($sort)
    {
        $articles = array();

        //session_start();
        $entity = new Article();
        $role = User::getRolebyUserId($_SESSION["userid"]);
        $articleStatus = 1;

        $userIdParameterInQuery = "USER_ID=" . $_SESSION["userid"] . " AND ";
        if($role==2) //editor
        {
            $articleStatus=2;
            $userIdParameterInQuery = "";
        }

        //the sort parameter is used to view the articles based on an antichronolocial or chronological order
        if($sort == "antichrono")
            $query = "SELECT * FROM ARTICLE WHERE " . $userIdParameterInQuery . "ARTICLE_STATUS_ID=" . $articleStatus . " ORDER BY ARTICLE_DATECREATION DESC";
        else
            $query = "SELECT * FROM ARTICLE WHERE " . $userIdParameterInQuery . "ARTICLE_STATUS_ID=" . $articleStatus . " ORDER BY ARTICLE_DATECREATION ASC";
        $res = $entity->getRowsFromQuery($query);

        if ($res != null)
        {
            while($row = $res->fetch_assoc())
            {
                $article = new Article();
                $article->id = $row['ARTICLE_ID'];
                $article->code = $row['ARTICLE_CODE'];
                $article->title = $row['ARTICLE_TITLE'];
                $article->summary = $row['ARTICLE_SUMMARY'];
                $article->content = $row['ARTICLE_CONTENT'];
                $article->dateCreation = $row['ARTICLE_DATECREATION'];
                $article->url = $row['ARTICLE_URL'];
                $article->nbClick = $row['ARTICLE_NBCLICK'];
                $article->nbShare = $row['ARTICLE_NBSHARE'];
                $article->user_id = $row['USER_ID'];
                $article->article_status_id = $row['ARTICLE_STATUS_ID'];

                array_push($articles, $article);
            }

            $res->close();
        }

        return $articles;
    }

    //return all articles that have been submitted for evaluation to the editor
    public static function getByLoggedUserIdHistory($sort)
    {
        $articles = array();

        //session_start();
        $entity = new Article();

        if($sort == "antichrono")
            $query = "SELECT * FROM ARTICLE WHERE USER_ID=" . $_SESSION["userid"] . " AND ARTICLE_STATUS_ID<>1 ORDER BY ARTICLE_DATECREATION DESC";
        else
            $query = "SELECT * FROM ARTICLE WHERE USER_ID=" . $_SESSION["userid"] . " AND ARTICLE_STATUS_ID<>1 ORDER BY ARTICLE_DATECREATION ASC";
        $res = $entity->getRowsFromQuery($query);

        if ($res != null)
        {
            while($row = $res->fetch_assoc())
            {
                $article = new Article();
                $article->id = $row['ARTICLE_ID'];
                $article->code = $row['ARTICLE_CODE'];
                $article->title = $row['ARTICLE_TITLE'];
                $article->summary = $row['ARTICLE_SUMMARY'];
                $article->content = $row['ARTICLE_CONTENT'];
                $article->dateCreation = $row['ARTICLE_DATECREATION'];
                $article->url = $row['ARTICLE_URL'];
                $article->nbClick = $row['ARTICLE_NBCLICK'];
                $article->nbShare = $row['ARTICLE_NBSHARE'];
                $article->user_id = $row['USER_ID'];
                $article->article_status_id = $row['ARTICLE_STATUS_ID'];

                array_push($articles, $article);
            }

            $res->close();
        }

        return $articles;
    }

	public static function getAll()
	{
		$articles = array();
		$entity = new Article();
		$res = $entity->getRowsFromQuery("SELECT * FROM ARTICLE order by ARTICLE_CREATEDATE DESC");
		
		if ($res != null)
		{
			while($row = $res->fetch_assoc())
			{
				$article = new Article();
				$article->id = $row['ARTICLE_ID'];
				$article->code = $row['ARTICLE_CODE'];
				$article->title = $row['ARTICLE_TITLE'];
				$article->summary = $row['ARTICLE_SUMMARY'];
				$article->content = $row['ARTICLE_CONTENT'];
				$article->dateCreation = $row['ARTICLE_DATECREATION'];
				$article->url = $row['ARTICLE_URL'];
				$article->nbClick = $row['ARTICLE_NBCLICK'];
				$article->nbShare = $row['ARTICLE_NBSHARE'];
				$article->user_id = $row['USER_ID'];
				$article->article_status_id = $row['ARTICLE_STATUS_ID'];
				
				array_push($articles, $article);
			}
			
			$res->close();
		}
		
		return $articles;
	}	

	public function save()
	{
		$entity = new Article();

		$title = $entity->real_escape_string($this->title);
		$summary = $entity->real_escape_string($this->summary);
		$content = $entity->real_escape_string($this->content);

		//encoding UTF8 in order to understand accents
		$query = "SET NAMES utf8";
		$this->executeQuery($query);

		$query = "INSERT INTO ARTICLE(ARTICLE_CODE, ARTICLE_TITLE, ARTICLE_SUMMARY, ARTICLE_CONTENT, ARTICLE_DATECREATION, ARTICLE_URL, ARTICLE_NBCLICK, ARTICLE_NBSHARE, USER_ID, ARTICLE_STATUS_ID) VALUES('".$this->code."', '".$title."', '".$summary."', '".$content."', '".$this->dateCreation."', '".$this->url."', 0, 0, ".$this->user_id.", ".$this->article_status_id.")";
		$this->executeQuery($query);
	}

	public function update()
    {

        $entity = new Article();

        $title = $entity->real_escape_string($this->title);
        $summary = $entity->real_escape_string($this->summary);
        $content = $entity->real_escape_string($this->content);

        $query = "UPDATE ARTICLE SET ARTICLE_CODE='" . $this->code . "', ARTICLE_TITLE='" . $title . "', ARTICLE_SUMMARY='" . $summary . "', ARTICLE_CONTENT='" . $content . "', ARTICLE_URL='" . $this->url . "' WHERE ARTICLE_ID=" . $this->id;

        $this->executeQuery($query);

        //insert row in "ARTICLE_REVISION_HISTORY" in order to track modifications
        $query = "INSERT INTO ARTICLE_REVISION_HISTORY(HISTORY_DATE, ARTICLE_ID, USER_ID) VALUES('".date("Y-m-d H:i:s")."', '".$this->id."', '".$this->user_id."')";
        $this->executeQuery($query);
    }

	public static function getArticlesByStatus($article_status_id)
	{
		$articles = array();
		$entity = new Article();
		$res = $entity->getRowsFromQuery("SELECT * FROM ARTICLE WHERE ARTICLE_STATUS_ID = '" . $article_status_id ."' ORDER BY ARTICLE_DATECREATION DESC ");
		
		if ($res != null)
		{
			while($row = $res->fetch_assoc())
			{
				$article = new Article();
				$article->id = $row['ARTICLE_ID'];
				$article->code = $row['ARTICLE_CODE'];
				$article->title = $row['ARTICLE_TITLE'];
				$article->summary = $row['ARTICLE_SUMMARY'];
				$article->content = $row['ARTICLE_CONTENT'];
				$article->dateCreation = $row['ARTICLE_DATECREATION'];
				$article->url = $row['ARTICLE_URL'];
				$article->nbClick = $row['ARTICLE_NBCLICK'];
				$article->nbShare = $row['ARTICLE_NBSHARE'];
				$article->user_id = $row['USER_ID'];
				$article->article_status_id = $row['ARTICLE_STATUS_ID'];
				array_push($articles, $article);
			}	
		$res->close();
		}
	return $articles;
	}

	// for the information of traceability
	public static function getArticleById($id)
	{
	
		$entity = new Article();
		$res = $entity->getRowsFromQuery("SELECT * FROM ARTICLE WHERE ARTICLE_ID = '" . $id ."'");
		
		if ($res != null)
		{
			$row = $res->fetch_assoc();
			
			$article = new Article();
			$article->id = $row['ARTICLE_ID'];
			$article->code = $row['ARTICLE_CODE'];
			$article->title = $row['ARTICLE_TITLE'];
			$article->summary = $row['ARTICLE_SUMMARY'];
			$article->content = $row['ARTICLE_CONTENT'];
			$article->dateCreation = $row['ARTICLE_DATECREATION'];
			$article->url = $row['ARTICLE_URL'];
			$article->nbClick = $row['ARTICLE_NBCLICK'];
			$article->nbShare = $row['ARTICLE_NBSHARE'];
			$article->user_id = $row['USER_ID'];
			$article->article_status_id = $row['ARTICLE_STATUS_ID'];
			
		$res->close();
		}
		
		return $article ;
	}

	public function getArticleByCode($code)
    {

        $entity = new Article();
        $res = $entity->getRowsFromQuery("SELECT * FROM ARTICLE WHERE ARTICLE_CODE = '" . $code ."'");

        if ($res != null)
        {
            $row = $res->fetch_assoc();

            $article = new Article();
            $article->id = $row['ARTICLE_ID'];
            $article->code = $row['ARTICLE_CODE'];
            $article->title = $row['ARTICLE_TITLE'];
            $article->summary = $row['ARTICLE_SUMMARY'];
            $article->content = $row['ARTICLE_CONTENT'];
            $article->dateCreation = $row['ARTICLE_DATECREATION'];
            $article->url = $row['ARTICLE_URL'];
            $article->nbClick = $row['ARTICLE_NBCLICK'];
            $article->nbShare = $row['ARTICLE_NBSHARE'];
            $article->user_id = $row['USER_ID'];
            $article->article_status_id = $row['ARTICLE_STATUS_ID'];

            $res->close();
        }

        return $article ;
    }

	public function changeStatus($idArticle, $idStatus)
	{

		$this->executeQuery("UPDATE ARTICLE SET ARTICLE_STATUS_ID = " . $idStatus . " WHERE ARTICLE_ID = " . $idArticle);		
	}
}

?>