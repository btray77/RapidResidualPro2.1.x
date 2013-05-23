<?php
/**
 * Class TPLManager
 * Created on Wed Jan 10 13:15:02 EST 2007 By Gregory Patmore
 * 
 * Lightweight Template File Processor
 * 
 * @author Gregory Patmore (mail at gregorypatmore dot com)
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package TPLManager
 * @access public
 * 
 */
class TPLManager {

	/**
	 * The contents of the unprocessed template file
	 *
	 * @var string
	 */
	protected $raw_text;
	
	/**
	 * The resulting hypertext output after variable processing
	 * has occurred.
	 *
	 * @var string
	 */
	protected $procd_text;

	/**
	 * The path to the template file
	 *
	 * @var string
	 */
	protected $file_path;

	/**
	 * An associative array containing the template variable names
	 * and the text string to replace in the template file
	 * set as such:
	 * $hotspots['VARNAME'] = '{VARNAME}';
	 *
	 * @var array
	 */
	protected $hotspots = array();

	/**
	 * An associative array that holds the values that will replace the
	 * template variables in the output
	 * set as such:
	 * $replacements['VARNAME'] = 'replacement value';
	 *
	 * each key should correspond to a value in the $hot_spots array
	 * or the replacement will not occur
	 *
	 * @var array
	 */
	protected $replacements = array();

	/**
	 * The Regular Expression used to find template Hotspots
	 *
	 */
	protected static $HOTSPOT_REGEX = '#\{\$([a-zA-Z0-9_]+)\}#';
	
	/**
	 * The regular expression to use if compacting the final text
	 *
	 * @var string
	 */
	protected static $COMPACT_REGEX = '#\s{2,}|\n|\r#';
	
	/**
	 * The value to replace the text found with the $COMPACT_REGEX 
	 *
	 * @var string
	 */
	protected static $COMPACT_REPLACE = ' ';

	/**
	 * Boolean flag noting whether to compress the processed output after parsing
	 *
	 * @var unknown_type
	 */
	protected static $COMPACT_RESULTS = false;
	
	/**
	 * Notes whether to check defined constants when trying to fill template hotspots
	 * true to check, false (default) to not check
	 * 
	 * Intialize as false for security. Enabling this function when you dont know what 
	 * constants are defined creates a potential security threat and should be avoided 
	 * if possible.  Nice feature if your the only one on the server though :)
	 *
	 * @var bool 
	 */
	protected static $CHECK_CONST = false;

	/**
	 * Class Constructor
	 * $tplPath - the path to the template file to use
	 * $valAry - an array of key=>value pairs representing the value to assign for each hotspot you want to fill in the template
	 *			 the format of the array is:
	 * 			 $valAry = array(
	 * 				'NAMEOFHOTSPOT' => <value>,
	 * 				'NAMEOFHOTSPOT2' => <value>
	 * 			 );
	 * @param string $tplpath
	 * @param array $vals
	 * @param boolean $proccess
	 * @return TPLManager
	 */
	public function __construct($tplPath, $valAry = false, $proccess = true) {
		
		/* Ensure that it is a good template file path */
		if(!$this->isValidTPLFile($tplPath))
			trigger_error('TPLManager:: invalid template path', E_USER_ERROR);
		
		/* ok, we got a good path, save it. */	
		else $this->file_path = $tplPath;		

		/* load the values array if passed */
		if($valAry !== false) 
			$this->loadValArray($valAry);

		/* Process the template if requested */
		if($proccess === true) 
			if(!$this->parse()) 
				trigger_error('TPLManager:: failed to process template',E_USER_ERROR);
	}
	
	/**
	 * Enables constant checking during template parsing
	 *
	 */
	public static function enableConstChecking(){
		self::$CHECK_CONST = true;
	}
	
	/**
	 * Prevents constant checking during template parsing
	 *
	 */
	public static function disableConstChecking(){
		self::$CHECK_CONST = false;
	}
	
	/**
	 * Enables compacting of the processed text after hotspot processing
	 *
	 */
	public static function enableCompacting(){
		self::$COMPACT_RESULTS = true;
	}
	
	/**
	 * Disables compacting of the processed text after hotspot processing
	 *
	 */
	public static function disableCompacting(){
		self::$COMPACT_RESULTS = false;
	}	
	
	/**
	 * Checks to see if a given file path leads to a valid file that we can use
	 *
	 * @param string $path The path to the template file
	 * @return bool whether it is a valid useable template file
	 */
	public function isValidTPLFile($path = false){
		$f = ($path !== false) ? $path : $this->file_path;
		return file_exists($f) && is_readable($f);
	}
	
	/**
	 * Checks to see whether a given value is a known hotspot for the current template
	 * NOTE : Hotspot values are checked in as case sensitive 
	 * 
	 * @param string $key
	 * @return bool 
	 */
	public function isHotspot($key){
		if(empty($this->hotspots)) $this->findHotspots();			
		return array_key_exists($key, $this->hotspots);
	}
	
	/**
	 * Sets a single replacement value for a hotspot in the current template
	 *
	 * @param string $key The name of the hotspot you want to replace
	 * @param string $val The value you want to replace the hotspot with
	 * @param bool $process Flag to trigger template processing after the replacements are updated
	 * @return bool Whether or not it was successful
	 */
	public function setVal($key, $val, $process = true){
		if(!$this->isHotspot($key))return false;
		else $this->replacements[$key] = strval($val);		
		return ($process === true) ? $this->parse() : true;
	}
	
	/**
	 * Processes an associative array of hotspot=>replacement pairs
	 *
	 * @param array $valAry the array of hotspot=>replacement pairs to process
	 * @param bool $process Flag to trigger template processing after the replacements are updated
	 * @return bool Whether or not it was successful
	 */
	public function loadValArray($valAry, $process = true){
		if(!is_array($valAry)){
			trigger_error('TPLManager::loadValArray - invalid array passed as argument', E_USER_ERROR);
			return false;	
		}			
		foreach($valAry as $k=>$v) $this->setVal($k,$v,false);				
		return ($process === true) ? $this->parse() : true;
	}
	
	/**
	 * Returns a string to replace a template hotspot with.
	 * Function first looks in the $replacements array
	 * then it checks to see if there is a defined constant to use (if enabled)
	 * otherwise it will return an empty string
	 *
	 * @param string $key The hotspot to find a replacement for
	 * @return string empty string if no replacement was found
	 */
	public function getCurrVal($key){
		if($v = $this->getLocalReplacement($key)) 
			return $v;
		
		return  (self::$CHECK_CONST === true && defined($key)) 
				? constant($key) 
				: '';
	}
	
	/**
	 * If the template file has been processed,
	 * it returns the processed hypertext output
	 * otherwise it returns false
	 *
	 * @return string
	 */
	public function getParsed(){
		if(empty($this->procd_text)) 
			if(!$this->parse())
				trigger_error('TPLManager::getParsed - failed to parse template',E_USER_ERROR);
		return self::$COMPACT_RESULTS ? $this->compact($this->procd_text) :  $this->procd_text ;
	}
	
	/**
	 * Returns an array of all the hotspot names in the template
	 *
	 * @return array|bool array of hotspot names or false if none are found
	 */
	public function getHotspotList(){		
		return (!empty($this->hotspots)) 
				? array_keys($this->hotspots)
				: ($this->findHotspots())
					? array_keys($this->hotspots)
					: false;
	}
	
	/**
	 * Returns the raw unprocessed text of the template file
	 *
	 * @return string the contents of the template file
	 */
	public function getRawTemplate(){
		if(empty($this->raw_text)){
			if(!$t = file_get_contents($this->file_path))
				throw new Exception('TPLManager::getRawTemplate - failed to retrieve the template contents');			
			$this->raw_text = $t;
		}		
		return $this->raw_text;
	}
	
	
	/**
	 * ******************************************************
	 * ******************************************************
	 * END PUBLIC INTERFACE  ********************************
	 * PROTECTED INTERFACE BELOW ****************************
	 * ******************************************************
	 * ******************************************************
	 */
	 
	
	/**
	 * removes extra spaces and returns out of the text
	 *
	 * @param string $pTxt
	 * @return string
	 */
	protected function compact($pTxt = false){
		if($pTxt === false)
			if(empty($this->procd_text))
				if(!$this->parse())	
					return false;
				else $pTxt = $this->procd_text;	
		return preg_replace(self::$COMPACT_REGEX,self::$COMPACT_REPLACE,$pTxt);
	}
	
	/**
	 * Returns the value in the $replacements array matching the given key
	 *
	 * @param string $key
	 * @return string|bool the current replacement value, or false if not found
	 */
	protected function getLocalReplacement($key){
		return array_key_exists($key, $this->replacements) 
			 ? $this->replacements[$key] 
			 : false;
	}		
	
	/**
	 * Finds all the hotspots in the current template and loads them into the $hotspots array
	 * 
	 * @return boolean Whether or not the operation succeeded
	 */
	protected function findHotspots(){
		if(preg_match_all(self::$HOTSPOT_REGEX, $this->getRawTemplate(), $out)){
			foreach($out[1] as $k=>$v) 
				if(!array_key_exists($v,$this->hotspots))
					$this->hotspots[$v] = $out[0][$k];
			return true;
		}else return false;
	}
	
	/**
	 * Processes the template file text by replacing the
	 * template variables with the replacement values
	 *
	 * @return bool true if the template processed without incident
	 */
	protected function parse(){
		
		/* locate the hotspots if we havent already */
		if(empty($this->hotspots)) 
			$this->findHotspots();
		
		$fRepl = array();		
		foreach($this->hotspots as $h=>$spot)
			$fRepl[$spot] = $this->getCurrVal($h);
			
		$this->procd_text = 
			str_replace(
				array_keys($fRepl),
				array_values($fRepl),
				$this->getRawTemplate()
			);
			
		return (!empty($this->procd_text))? true : false;
	}
	/******************************************************/
	//
	//
	/******************************************************/
	function getPlaceHolders(array $hotspots){
		include $_SERVER['DOCUMENT_ROOT'].'/common/config.php';
		
		$db= new database();
		$data=array();
		foreach($hotspots as $key => $items)
		{
			$temp=explode('_',$items);
			$dbtable=$temp[0];
			$dbfield=$temp[1];
			$data[$dbfield] =	$this->getDbData($dbtable,$dbfield,$db,$prefix);
			
		}
			
		return $data;
		
	}
	
	function getDbData($dbtable,$dbfield,$db,$prefix){
		
		switch($dbtable)
		{
			
			case 'menu':
				return $this->getMenus($db,$prefix,$dbfield);
				break;
			case 'settings':
				return $this->getSiteSettings($db,$prefix,$dbfield);
				break;
			case 'page':
				return $this->getPageContent($db,$prefix,$dbfield);
				break;
			case 'comments':
				return $data = $this->getPageComments($db,$prefix,$dbfield);
				break;	
			//case 'product':
			//	return $this->getPageContent($db,$prefix,'page');
			//	break;
			
		}
		
	}
	
	function getPageComments($db,$prefix,$dbfield)
	{
		$sql="SELECT `$dbfield` from  ".$prefix."comments where page='$_GET[page]' and type='content'";
	 	$rows=$db->get_rsltset($sql);
	 	foreach($rows as $row)
	 	{
	 		$data[]=$row[$dbfield];
	 	}
	 	return $data;
	}
	
	function getPageContent($db,$prefix,$dbfield)
	{
		$sql="SELECT $dbfield from  ".$prefix."pages where filename='$_GET[page]'";
	 	$rows=$db->get_a_line($sql);
	 	return $rows[$dbfield];
	}
	
	
	function getMenus($db,$prefix,$dbfield){
		$sql="SELECT i.name,i.url,i.target,i.nofollow FROM `".$prefix."menus_items` i,".$prefix."menus m 
		where i.menuid=m.id and menu_alias='menu_$dbfield'
	     and i.published=1 and m.published=1 order by i.`order` ASC; ";
	 	$rows=$db->get_rsltset($sql);
		if(count($rows) > 0){
		 	$str='<ul>';
			foreach($rows as $row){
				if($row['target']==1)
					$target='target="_blank"';
				if($row['nofollow']==1)
					$follow='rel="follow"';
				$str.="<li><a href='$row[url]'  $target $follow >$row[name]</a></li";					
			}
			$str.='</ul>';	
		return $str;
		}
	}
	function getSiteSettings($db,$prefix,$dbfield){
		$sql="SELECT $dbfield from  ".$prefix."site_settings";
	 	$rows=$db->get_a_line($sql);
	 	return $rows[$dbfield];
	}
	
}
?>