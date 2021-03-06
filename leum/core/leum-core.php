<?php 

// Before anything!, setup the logs so errors can be caught;
require_once "log.php";

// Get config
require_once SYS_ROOT . '/leum/conf/leum.conf.php';
require_once "message.php";

require_once "media.php";
require_once "mediaQuery.php";
require_once "tagMap.php";
require_once "tag.php";
require_once "query.php";

require_once "user.php";
require_once "user-account.php";
require_once "user-permission/role.php";
require_once "user-permission/permission.php";

require_once "plugins.php";

require_once SYS_ROOT . "/leum/utils/thumbnails.php";

class LeumCore
{
	const VERSION = 'alpha 0.1.0';
	public static $instance;
	public $pluginManager;
	public static $hooks = array();
	public static $dbc;
	public function __construct()
	{
		$pdoOptions = [
			PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES		=> false,
		];
	
		self::$dbc = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME .";charset=utf8", DB_USER, DB_PASS, $pdoOptions);

		// Load the plugins first.
		$this->pluginManager = new PluginManager(ACTIVE_PLUGINS);
		self::$instance = $this;
		// Call the initialize hook.
		self::InvokeHook('initialize');
	}

	public static function PDOPlaceholder($array)
	{
		return str_repeat('?, ', count($array) - 1) . '?';
	}
	public static function GetTotalItems($dbc)
	{
		return self::$dbc->query("SELECT found_rows()")->fetch()["found_rows()"];
	}

	public static function CreateSlug($string)
	{
		// https://web.archive.org/web/20130208144021/http://neo22s.com/slug
		// everything to lower and no spaces begin or end
		$string = strtolower(trim($string));

		$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
		$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'); 
		$string = str_replace($a, $b,$string);

		// adding - for spaces and union characters
		$find = array(' ', '&', '\r\n', '\n', '+',',');
		$string = str_replace ($find, '-', $string);

		//delete and replace rest of special chars
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$string = preg_replace ($find, $repl, $string);

		//return the friendly url
		return substr($string, 0, 32);
	}
	public static function IsValidUsername($username)
	{
		if(preg_match('/[^A-Za-z0-9!&_-]/', $username) == 0)
			return true;

		return false;
	}
	/**
	 * registers a hook event
	 * @param string $hookName name of hook. 
	 * @param callable $function the function to call when the hook is called.
	 */
	public static function AddHook($hookName, $function)
	{
		// Make sure the function can be called.
		if(!is_callable($function))
			throw new Exception("Hook function is not callable");

		// If the hook does not already exist, add it with this new hook.
		if(!isset(self::$hooks[$hookName]))
			self::$hooks[$hookName] = array();
		// Push the thing in.
		self::$hooks[$hookName][] = $function;
	}
	public static function InvokeHook($hookName, $context = null)
	{
		// Make sure the hook exists.
		if(!isset(self::$hooks[$hookName]))
			return;

		foreach (self::$hooks[$hookName] as $anon)
		{
			$anon($context);
		}
	}
	public static function WriteError($message)
	{
		if($message instanceof Exception)
		{
			$msg = $message->GetMessage() . ", " . $message->GetFile() . "(" . $message->getLine() . ")";

			Message::Create("exception", $message->GetMessage());
			Log::Write($message . PHP_EOL . $message->getTraceAsString(), Log::EXCEPTION);
		}
		else
		{
			Message::Create("error", $message);
			Log::Write($message, Log::ERROR);
		}
	}
	public static function WriteWarning($message)
	{
		Message::Create("warning", $message);
		Log::Write($message, Log::WARNING);
	}
	public static function WriteInfo($message)
	{
		Message::Create("info", $message);
		Log::Write($message, Log::INFO);
	}
}
 ?>