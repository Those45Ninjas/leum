<?php
require_once 'api.class.php';
require_once '../../functions.php';

class LeumApi extends API
{
	public function hello ($args)
	{
		return $data = array(
			'message' => "Hello World!",
			'user' => $args[0]
		);
	}
	public function Media ($args)
	{
		require_once 'media.php';

		$min = $this->verb == "min";
		$db = DBConnect();
		switch ($this->method)
		{
			case 'GET':
				if(isset($args[0]))
					return Media::Get($db, $args[0], $min);
				else
					return Media::Get($db, null, $min);
				break;
			case 'DELETE':
				if(isset($args[0]))
					return Media::Delete($db, $args[0]);
				else
					throw new Exception("Invalid Arguments");
				break;
			//TODO: Create Put as well and separate the creation and modification of media.
			case 'POST':
				if(isset($args[0]))
					return Media::Post($db, $this->request, $args[0]);
				else
					return Media::Post($db, $this->request);
				break;
		}
	}
}


?>
