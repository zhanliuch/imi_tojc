<?php

// Authors: 	Zhan Liu, Nicolas Solioz
// Created: 	2020/05/05
// Last update:	2020/07/27

class Indicator extends Entity
{
	public $id;
	public $title;
	public $description;
	public $cat_id;
	public $graduation;
	
	public function __construct()
	{
		parent::__construct("INDICATOR", "INDICATOR_");
	}
	
	public static function getAll()
	{
		$indicators = array();
		
		$entity = new Indicator();
		$res = $entity->getAllRows();
		
		if ($res != null)
		{
			while($row = $res->fetch_assoc())
			{
				$indicator = new Indicator();
				$indicator->id = $row['INDICATOR_ID'];
				$indicator->title = $row['INDICATOR_TITLE'];
				$indicator->description = $row['INDICATOR_DESCRIPTION'];
				$indicator->cat_id = $row['INDICATOR_CAT_ID'];
				$indicator->graduation = $row['INDICATOR_GRADUATION'];
				
				array_push($indicators, $indicator);
			}
			
			$res->close();
		}
		
		return $indicators;
	}
	
	public static function getIndicatorsByCat($cat_id)
	{

		$indicators = array();
		$entity = new Indicator();
		$res = $entity->getRowsFromQuery("SELECT * FROM INDICATOR WHERE INDICATOR_CAT_ID = '" . $cat_id ."'");
		
		if ($res != null)
		{
			while($row = $res->fetch_assoc())
			{
			    $indicator = new Indicator();
				$indicator->id = $row['INDICATOR_ID'];
				$indicator->title = $row['INDICATOR_TITLE'];
				$indicator->description = $row['INDICATOR_DESCRIPTION'];
				$indicator->cat_id = $row['INDICATOR_CAT_ID'];

				array_push($indicators, $indicator);
			}	
		$res->close();
		}
	return $indicators ;
	}

	public function getIndicatorCategory($cat_id)
    {
        $entity = new Indicator();
        $res = $entity->getRowsFromQuery("SELECT * FROM INDICATOR_CATEGORY WHERE INDICATOR_CAT_ID = '" . $cat_id ."'");

        if ($res != null)
        {
            while($row = $res->fetch_assoc())
            {

                $category = $row['INDICATOR_CAT_ID'];

            }
            $res->close();
        }
        return $category;

    }

    public static function getCategory($id)
    {
        $entity = new Indicator();
        $res = $entity->getRowsFromQuery("SELECT * FROM INDICATOR WHERE INDICATOR_ID = '" . $id ."'");

        if ($res != null)
        {
            while($row = $res->fetch_assoc())
            {

                $category = $row['INDICATOR_CAT_ID'];

            }
            $res->close();
        }
        return $category;
    }

    public static function getPonderation($id)
    {
        $entity = new Indicator();
        $res = $entity->getRowsFromQuery("SELECT * FROM INDICATOR WHERE INDICATOR_ID = '" . $id ."'");

        if ($res != null)
        {
            while($row = $res->fetch_assoc())
            {

                $ponderation = $row['INDICATOR_PONDERATION'];

            }
            $res->close();
        }
        return $ponderation;
    }

}

?>