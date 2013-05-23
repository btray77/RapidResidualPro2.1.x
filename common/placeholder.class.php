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
     * 			 the format of the array is:
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
        if (!$this->isValidTPLFile($tplPath))
            trigger_error('TPLManager:: invalid template path', E_USER_ERROR);
        /* ok, we got a good path, save it. */
        else
            $this->file_path = $tplPath;
        /* load the values array if passed */
        if ($valAry !== false)
            $this->loadValArray($valAry);
        /* Process the template if requested */
        if ($proccess === true)
            if (!$this->parse())
                trigger_error('TPLManager:: failed to process template', E_USER_ERROR);
    }
    /**
     * Enables constant checking during template parsing
     *
     */
    public static function enableConstChecking() {
        self::$CHECK_CONST = true;
    }
    /**
     * Prevents constant checking during template parsing
     *
     */
    public static function disableConstChecking() {
        self::$CHECK_CONST = false;
    }
    /**
     * Enables compacting of the processed text after hotspot processing
     *
     */
    public static function enableCompacting() {
        self::$COMPACT_RESULTS = true;
    }
    /**
     * Disables compacting of the processed text after hotspot processing
     *
     */
    public static function disableCompacting() {
        self::$COMPACT_RESULTS = false;
    }
    /**
     * Checks to see if a given file path leads to a valid file that we can use
     *
     * @param string $path The path to the template file
     * @return bool whether it is a valid useable template file
     */
    public function isValidTPLFile($path = false) {
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
    public function isHotspot($key) {
        if (empty($this->hotspots))
            $this->findHotspots();
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
    public function setVal($key, $val, $process = true) {
        if (!$this->isHotspot($key))
            return false;
        else
            $this->replacements[$key] = strval($val);
        return ($process === true) ? $this->parse() : true;
    }
    /**
     * Processes an associative array of hotspot=>replacement pairs
     *
     * @param array $valAry the array of hotspot=>replacement pairs to process
     * @param bool $process Flag to trigger template processing after the replacements are updated
     * @return bool Whether or not it was successful
     */
    public function loadValArray($valAry, $process = true) {
        if (!is_array($valAry)) {
            trigger_error('TPLManager::loadValArray - invalid array passed as argument', E_USER_ERROR);
            return false;
        }
        foreach ($valAry as $k => $v)
            $this->setVal($k, $v, false);
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
    public function getCurrVal($key) {
        if ($v = $this->getLocalReplacement($key))
            return $v;
        return (self::$CHECK_CONST === true && defined($key)) ? constant($key) : '';
    }
    /**
     * If the template file has been processed,
     * it returns the processed hypertext output
     * otherwise it returns false
     *
     * @return string
     */
    public function getParsed() {
        if (empty($this->procd_text))
            if (!$this->parse())
                trigger_error('TPLManager::getParsed - failed to parse template', E_USER_ERROR);
        return self::$COMPACT_RESULTS ? $this->compact($this->procd_text) : $this->procd_text;
    }
    /**
     * Returns an array of all the hotspot names in the template
     *
     * @return array|bool array of hotspot names or false if none are found
     */
    public function getHotspotList() {
        return (!empty($this->hotspots)) ? array_keys($this->hotspots) : ($this->findHotspots()) ? array_keys($this->hotspots) : false;
    }
    /**
     * Returns the raw unprocessed text of the template file
     *
     * @return string the contents of the template file
     */
    public function getRawTemplate() {
        if (empty($this->raw_text)) {
            if (!$t = file_get_contents($this->file_path))
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
    protected function compact($pTxt = false) {
        if ($pTxt === false)
            if (empty($this->procd_text))
                if (!$this->parse())
                    return false;
                else
                    $pTxt = $this->procd_text;
        return preg_replace(self::$COMPACT_REGEX, self::$COMPACT_REPLACE, $pTxt);
    }
    /**
     * Returns the value in the $replacements array matching the given key
     *
     * @param string $key
     * @return string|bool the current replacement value, or false if not found
     */
    protected function getLocalReplacement($key) {
        return array_key_exists($key, $this->replacements) ? $this->replacements[$key] : false;
    }
    /**
     * Finds all the hotspots in the current template and loads them into the $hotspots array
     * 
     * @return boolean Whether or not the operation succeeded
     */
    protected function findHotspots() {
        if (preg_match_all(self::$HOTSPOT_REGEX, $this->getRawTemplate(), $out)) {
            foreach ($out[1] as $k => $v)
                if (!array_key_exists($v, $this->hotspots))
                    $this->hotspots[$v] = $out[0][$k];
            return true;
        }else
            return false;
    }
    /**
     * Processes the template file text by replacing the
     * template variables with the replacement values
     *
     * @return bool true if the template processed without incident
     */
    protected function parse() {
        /* locate the hotspots if we havent already */
        if (empty($this->hotspots))
            $this->findHotspots();
        $fRepl = array();
        foreach ($this->hotspots as $h => $spot)
            $fRepl[$spot] = $this->getCurrVal($h);
        $this->procd_text =
                str_replace(
                array_keys($fRepl), array_values($fRepl), $this->getRawTemplate()
        );
        return (!empty($this->procd_text)) ? true : false;
    }
    function getUsername($db, $prefix, $dbfield) {
        $sql_member = "SELECT firstname,lastname, $dbfield FROM " . $prefix . "members WHERE id = '" . $_SESSION['memberid'] . "'";
        $row_member = $db->get_a_line($sql_member);
        $username = $row_member['firstname']." ". $row_member['lastname'];
        if ($row_member['username']) {
            // For Affiliate/JV users
            $sql_aj = "SELECT status, ref,
       (select count(*) from " . $prefix . "members where ref = '" . $row_member['username'] . "') as totalaff,
       (select count(*) from " . $prefix . "members where username = '" . $row_member['username'] . "' and (paypal_email != '' || alertpay_email != '' || clickbank_email != '')) as pay_emails
       FROM " . $prefix . "members
       WHERE id = '" . $_SESSION['memberid'] . "'";
            $row_aj = $db->get_a_line($sql_aj);
            $user_role = $row_aj['status'];
            if ($user_role == '1') {
                // Affiliate link
                $linkk = 'affiliate_home.php';
            } elseif ($user_role == '2') {
                // Member Link
                $linkk = 'affiliate_home.php';
            } elseif ($user_role == '3') {
                // JV Link
                $linkk = 'affiliate_home.php';
            }
            /* if($row_aj['ref'] != 'None'){
              $aj_user = "<br>Referred By: ".$row_aj['ref'];
              }else{
              $aj_user = '';
              }
             */
            if ($linkk && $row_aj['totalaff'] > 0) {
                $link_final = "<a href='" . $linkk . "' title='View Report'>" . $username . "</a>";
            } else {
                if ($row_aj['ref'] != 'None' && !empty($row_aj['ref']) ) {
                    $link_final = "<a href='" . $linkk . "' title='View Report'>" . $username . "</a><br>Referred By: " . $row_aj['ref'];
                } else {
                    $link_final = "<a href='" . $linkk . "' title='View Report'>" . $username . "</a>";
                }
            }
            $username_token = '<span class="username">Logged in as: ' . $link_final . '</span>';
        } else {
            $username_token = '';
        }
        return $username_token;
    }
    /*     * *************************************************** */
    //
    //
	/*     * *************************************************** */
    function getPlaceHolders($hotspots,$itemname) {
        if (!include("config.php"))
            die('Sorry! unable to get config.php');
        $db = new database();
        $data = array();
		
        if (!is_array($hotspots)) {
            $temp = explode('_', $hotspots);
            $dbtable = $temp[0];
            $dbfield = $temp[1];
            $data = $this->getDbData($dbtable, $dbfield, $db, $prefix,$itemname);
        } else {
            foreach ($hotspots as $key => $items) {
                $temp = explode('_', $items);
                $dbtable = $temp[0];
                $dbfield = $temp[1];
                $str = $dbtable . "_" . $dbfield;
                $data[$str] = $this->getDbData($dbtable, $dbfield, $db, $prefix,$itemname);
            }
        }
        return $data;
    }
    function getDbData($dbtable, $dbfield, $db, $prefix,$itemname) {
	
        switch ($dbtable) {
            case 'menu':
                return $this->getMenus($db, $prefix, $dbfield);
                break;
            case 'settings':
                return $this->getSiteSettings($db, $prefix, $dbfield,$itemname);
                break;
             case 'image':
              return $this->getSlider($db,$prefix,$dbfield,'left');
              break;
            /*  case 'right':
              return $this->getModule($db,$prefix,$dbfield,'right');
              break; */
            case 'template':
                return $this->getCustomCss($db, $prefix, $dbfield);
                break;
            case 'members':
                return $this->getUsername($db, $prefix, $dbfield);
                break;
            case 'js':
                return $this->getClock();
        }
    }
    function getModule($db, $prefix, $dbfield, $position) {
        //$str="<h1 style='text-transform: uppercase;'>$position Module</h1>";
        $str = '';
        return $str;
    }
    function getCustomCss($db, $prefix, $template) {
        $sql = "SELECT custom_body,custom_header,custom_content,custom_footer from  " . $prefix . "template where name='$template'";
        $row = $db->get_a_line($sql);
        $styles = '<style>';
        if (!empty($row['custom_body'])) {
            $body = explode(';', $row['custom_body']);
            foreach ($body as $key => $itmes) {
                $property = explode(':', $itmes);
                if ($property[0] == 'background-color')
                    $body_background_color = "background-color:" . $property[1] . ";background-image:none;";
                else if ($property[0] == 'font-size')
                    $body_font_size = "font-size:" . $property[1] . 'px;';
                else if ($property[0] == 'color')
                    $body_font_color = "color:" . $property[1] . ';';
                else if ($property[0] == 'font-family')
                    $body_font_family = "font-family:" . $property[1] . ';';
            }
            $styles.="body{" . $body_background_color . $body_font_size . $body_font_color . $body_font_family . "}\n";
            $styles.="h1,h2,h3,a{" . $body_font_color . $body_font_family . "}\n";
        }
        if ($row['custom_header']) {
            $header = explode(';', $row['custom_header']);
            foreach ($header as $key => $itmes) {
                $property = explode(':', $itmes);
                if ($property[0] == 'background-color')
                    $header_background_color = "background-color:" . $property[1] . ";background-image:none;";
                else if ($property[0] == 'font-size')
                    $header_font_size = "font-size:" . $property[1] . 'px;';
                else if ($property[0] == 'color')
                    $header_font_color = "color:" . $property[1] . ';';
                else if ($property[0] == 'font-family')
                    $header_font_family = "font-family:" . $property[1] . ';';
            }
            $styles.="#header,.header{" . $header_background_color . $header_font_size . $header_font_color . $header_font_family . "}\n";
            $styles.="#header,.header h1,\n
			  #header,.header h2,\n
			  #header,.header h3,\n
			  #header,.header div,\n
			  #header,.header p {" . $header_font_color . $header_font_family . "}\n";
            $styles.="#header,.header a{" . $header_font_size . $header_font_color . $header_font_family . "}\n";
        }
        if ($row['custom_content']) {
            $content = explode(';', $row['custom_content']);
            foreach ($content as $key => $itmes) {
                $property = explode(':', $itmes);
                if ($property[0] == 'background-color')
                    $content_background_color = "background-color:" . $property[1] . ";background-image:none;";
                else if ($property[0] == 'font-size')
                    $content_font_size = "font-size:" . $property[1] . 'px;';
                else if ($property[0] == 'color')
                    $content_font_color = "color:" . $property[1] . ';';
                else if ($property[0] == 'font-family')
                    $content_font_family = "font-family:" . $property[1] . ';';
            }
            $styles.="#content,.content{" . $content_background_color . $content_font_size . $content_font_color . $content_font_family . "}\n";
            $styles.="#content,.content h1,\n
			   #content,.content h2,\n
			   #content,.content h3,\n
			   #content,.content p {" . $content_font_color . $content_font_family . "}\n";
            $styles.="#content,.content a{" . $content_font_size . $content_font_color . $content_font_family . "}\n";
        }
        if ($row['custom_footer']) {
            $footer = explode(';', $row['custom_footer']);
            foreach ($footer as $key => $itmes) {
                $property = explode(':', $itmes);
                if ($property[0] == 'background-color')
                    $footer_background_color = "background-color:" . $property[1] . ";background-image:none;";
                else if ($property[0] == 'font-size')
                    $footer_font_size = "font-size:" . $property[1] . 'px;';
                else if ($property[0] == 'color')
                    $footer_font_color = "color:" . $property[1] . ';';
                else if ($property[0] == 'font-family')
                    $footer_font_family = "font-family:" . $property[1] . ';';;
            }
            $styles.="#footer,.footer{" . $footer_background_color . $footer_font_size . $footer_font_color . $footer_font_family . "}\n";
            $styles.="#footer,.footer h1,\n
			#footer,.footer h2,\n
			#footer,.footer h3,\n
			#footer,.footer p {" . $footer_font_color . $footer_font_family . "}\n";
            $styles.="#footer,.footer a{" . $footer_font_size . $footer_font_color . $footer_font_family . "}\n";
        }
        $styles.="</style>\n";
        $str.="<script src=\"/common/newLayout/jquery/jquery.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
        $str.="<script src=\"/scripts/validate.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
		$str.="<link type=\"text/css\" rel=\"stylesheet\" href=\"/common/newLayout/core.css\" />\n";
        $str.="<link rel=\"stylesheet\" href=\"/common/newLayout/prettyPhoto.css\" type=\"text/css\"/>\n";
        $str.="<link type=\"text/css\" rel=\"stylesheet\" href=\"/templates/$template/css/template.css\" />\n";
        $str.="<script src=\"/common/newLayout/jquery/jquery.prettyPhoto.js\" type=\"text/javascript\"/></script>\n";
      	$str.='<script src="/common/fancybox13/jquery.easing-1.3.pack.js" type="text/javascript"></script>
        <script src="/common/fancybox13/jquery.mousewheel-3.0.2.pack.js" type="text/javascript"></script>
        <script src="/common/fancybox13/jquery.fancybox-1.3.1.pack.js" type="text/javascript"></script>
        <link href="/common/fancybox13/jquery.fancybox-1.3.1.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript">
            $(document).ready(function () {
                $(\'a[rel^=lightbox]\').fancybox();
		$(\'a[rel^=prettyPhoto]\').prettyPhoto();
            });
        </script>
		<script src="/admin/Editor/scripts/common/mediaelement/mediaelement-and-player.min.js" type="text/javascript"></script>
        <link href="/admin/Editor/scripts/common/mediaelement/mediaelementplayer.min.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript">
        $(document).ready(function () {
	 		$("audio,video").mediaelementplayer();
        }); 
	</script>
	';				
        return $str;
    }
    function getMenus($db, $prefix, $dbfield) {
        $sql = "SELECT i.name,i.url,i.target,i.nofollow FROM `" . $prefix . "menus_items` i," . $prefix . "menus m 
		where i.menuid=m.id and menu_alias='menu_$dbfield'
	     and i.published=1 and m.published=1 order by i.`order` ASC; ";
        $rows = $db->get_rsltset($sql);
        if (count($rows) > 0) {
            $str = '<ul>';
            foreach ($rows as $row) {
                if ($row['target'] == 1)
                    $target = 'target="_blank"';
                else
                    $target='';
                if ($row['nofollow'] == 1)
                    $follow = 'rel="follow"';
                $name = stripslashes($row['name']);
                $str.="<li><a href='$row[url]'  $target $follow >$name</a></li>";
            }
            $str.='</ul>';
            return $str;
        }
    }
    function getSiteSettings($db, $prefix, $dbfield,$item_name) {
        $sql = "SELECT $dbfield from  " . $prefix . "site_settings";
        $rows = $db->get_a_line($sql);
		if($dbfield=='sitename')
	        return $rows[$dbfield].' - '.$item_name;
		else
			return $rows[$dbfield];	
    }
    function getClock() {
        $clock_token = '
		<script type="text/javascript" src="/scripts/function.js"></script>
		<span class="rt" id="clock"></span>';
        return $clock_token;
    }
	function getSlider()
	{
if($_SERVER['SCRIPT_NAME']=='/index.php'){
	return ' <div class="slider">
	<div id="bg"></div>
		          <div id="carousel">
                    <div>
                      <h5>It all starts with a</h5>
                      <h3>Great Vision</h3>
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eget augue quis quam dignissim consectetur ac sit amet nisl. In hac habitasse platea. Eget augue quis quam dignissi lorem lipsum adispicing dolor sit amet.</p>
                      <a href="#" class="green-btn">FREE Quote</a> <img class="img-front" src="/templates/diggy/css/images/front-img.png" alt="dot1" width="263" height="436" /> <img class="img-mid" src="/templates/diggy/css/images/img-mid.png" alt="dot2" width="230" height="363" /> <img class="img-back" src="/templates/diggy/css/images/img-back.png" alt="dot3" width="195" height="304" /> </div>
		            <div>
                      <h5>It all starts with a</h5>
		              <h3>Great Vision</h3>
		              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eget augue quis quam dignissim consectetur ac sit amet nisl. In hac habitasse platea. Eget augue quis quam dignissi lorem lipsum adispicing dolor sit amet.</p>
		              <a href="#" class="green-btn">FREE Quote</a> <img class="img-front" src="/templates/diggy/css/images/front-img.png" alt="dot1" width="263" height="436" /> <img class="img-mid" src="/templates/diggy/css/images/img-mid.png" alt="dot2" width="230" height="363" /> <img class="img-back" src="/templates/diggy/css/images/img-back.png" alt="dot3" width="195" height="304" /> </div>
		            <div>
                      <h5>It all starts with a</h5>
		              <h3>Great Vision</h3>
		              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eget augue quis quam dignissim consectetur ac sit amet nisl. In hac habitasse platea. Eget augue quis quam dignissi lorem lipsum adispicing dolor sit amet.</p>
		              <a href="#" class="green-btn">FREE Quote</a> <img class="img-front" src="/templates/diggy/css/images/front-img.png" alt="dot1" width="263" height="436" /> <img class="img-mid" src="/templates/diggy/css/images/img-mid.png" alt="dot2" width="230" height="363" /> <img class="img-back" src="/templates/diggy/css/images/img-back.png" alt="dot3" width="195" height="304" /> </div>
		            <div>
                      <h5>It all starts with a</h5>
		              <h3>Great Vision</h3>
		              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eget augue quis quam dignissim consectetur ac sit amet nisl. In hac habitasse platea. Eget augue quis quam dignissi lorem lipsum adispicing dolor sit amet.</p>
		              <a href="#" class="green-btn">FREE Quote</a> <img class="img-front" src="/templates/diggy/css/images/front-img.png" alt="dot1" width="263" height="436" /> <img class="img-mid" src="/templates/diggy/css/images/img-mid.png" alt="dot2" width="230" height="363" /> <img class="img-back" src="/templates/diggy/css/images/img-back.png" alt="dot3" width="195" height="304" /> </div>
	              </div>
		          <div class="pagination"></div>
		          <a id="prev" href="#"></a> <a id="next" href="#"></a></div>';
				  }
				  else
				  return "";
	}
}
?>