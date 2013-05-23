<?php
include_once("common/config.php");
include ("include.php");

// get contact page info from database
$q = "select * from ".$prefix."site_settings";
$v = $db->get_a_line($q);
$contact_page = $v['links'];
$contact_page = stripslashes($contact_page);
$contact_page 			= str_replace('™', '&trade;', $contact_page);
$contact_page			= str_replace('©', '&#169;', $contact_page);

// display contact page
$pagecontent =   preg_replace ("/\[\[(.*?)\]\]/e", "$$1", $contact_page);
$pagecontent = preg_replace("/[$]/","&#36;",$pagecontent);

include_once ("template.php");
$Content 		= preg_replace("/{{(.*?)}}/e","$$1",$Content);
$Content 		= preg_replace("/<{(.*?)}>/e","$$1",$Content);
echo $Content;
?>