<?php

// Authors: 	Zhan Liu, Nicolas Solioz
// Created: 	2020/05/05
// Last update:	2020/07/27

class IndicatorCategory extends Entity
{
	public $id;
	public $name;
	public $weight;
	
	public function __construct()
	{
		parent::__construct("IndicatorCategory", "INDICATOR_CAT_");
	}
	
	public static function getAll()
	{
		$indicatorCategories = array();
		
		$entity = new IndicatorCategory();
		$res = $entity->getAllRows();
		
		if ($res != null)
		{
			while($row = $res->fetch_assoc())
			{
				$indicatorCategory = new IndicatorCategory();
				$indicatorCategory->id = $row['INDICATOR_CAT_ID'];
				$indicatorCategory->name = $row['INDICATOR_CAT_NAME'];
				$indicatorCategory->weight = $row['INDICATOR_CAT_WEIGHT'];
				
				array_push($indicatorCategories, $indicatorCategory);
			}
			
			$res->close();
		}
		
		return $indicatorCategories;
	}
	
	public static function getIndicatorCategoryByID($cat_id)
	{
		$indicatorcat = null;
		
		$entity = new IndicatorCategory();
		$res = $entity->getRowById($cat_id);
		
		if ($res != null)
		{
			$row = $res->fetch_assoc();
			
			$indicatorcat = new IndicatorCategory();
			$indicatorcat->id = $row['INDICATOR_CAT_ID'];
			$indicatorcat->name = $row['INDICATOR_CAT_NAME'];
			$indicatorcat->weight = $row['INDICATOR_CAT_WEIGHT'];
			$res->close();
		}
		
		return $indicatorcat;
	}

	public static function getWeightCategory($cat_id)
    {
        $entity = new IndicatorCategory();
        $res = $entity->getRowsFromQuery("SELECT * FROM INDICATOR_CATEGORY WHERE INDICATOR_CAT_ID = '" . $cat_id ."'");
        $weight = 1;

        if ($res != null)
        {
            $row = $res->fetch_assoc();

            $weight = $row['INDICATOR_CAT_WEIGHT'];

            $res->close();
        }

        return $weight ;
    }

}

?>