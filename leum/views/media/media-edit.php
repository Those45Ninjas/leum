<?php
require_once SYS_ROOT . "/views/view.class.php";

class MediaView extends View
{
	function Singular ($item, $classString = "")
	{
		echo "<div class=\"tag $classString\">$item->id, $item->title, $item->source</div>";
	}

	function DoTable ($items, $tableClass="", $rowClass="", $emptyStr = "-")
	{
		$columns = array
		(
			"id"	=> "ID",
			"title"		=> "Title",
			"source"	=> "Source",
			"path"		=> "Path",
			"date"		=> "Date Added"
		);
		View::CreateTable($columns, $items, $tableClass, $rowClass, $emptyStr);
	}
}
?>