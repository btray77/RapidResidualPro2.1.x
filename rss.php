<?php
include_once("common/config.php");
include ("include.php");
header('Content-type: text/xml');


$q = "select sitename from ".$prefix."site_settings where id='1'";
$r = $db->get_a_line($q);
@extract($r);
$sitename = stripslashes($r['sitename']);

$date_add = date("D, d M Y H:i:s T");


//CONSTRUCT RSS FEED HEADERS
$rss_code .= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
 <channel>
  <title>".$sitename." RSS Syndication</title>
  <link>".$http_path."rss.xml</link>
  <description>Site/RSS news feed description goes here</description>
  <pubDate>".$date_add."</pubDate>
  <atom:link href=\"".$http_path."rss.xml\" rel=\"self\" type=\"application/rss+xml\" />
  ";

$get_articles = "SELECT pageid, pagename, description, pcontent, filename, linkproduct, UNIX_TIMESTAMP(date_added) AS date_added FROM ".$prefix."pages where (linkproduct='Site Root Page' or linkproduct='blog') && rss='yes' ORDER BY date_added DESC LIMIT 20";   
$articles = mysql_query($get_articles) or die(mysql_error());   

while ($article = mysql_fetch_array($articles)){ 
$dateadded=strftime( "%a, %d %b %Y %T %Z" , $article['date_added']);

if($article['linkproduct'] == 'Site Root Page')
	{
	$link = "<link>".$http_path."/content.php?page=".$article[filename]."</link>
	<guid>".$http_path."content.php?page=".$article[filename]."</guid>";
	}
elseif($article['linkproduct'] == 'blog')
	{
	$link = "<link>".$http_path."/blog.php?page=".$article[filename]."</link>
	<guid>".$http_path."/blog.php?page=".$article[filename]."</guid>";	
	}	



$description = htmlspecialchars($article['pcontent']);
$description = stripslashes($description);
$rss_code .= "
<item>
  <title>".$article[pagename]."</title>".$link."

  <description>".$article[description]."</description>
  <pubDate>".$dateadded."</pubDate>
</item>";
		  
	  
}
//CLOSE RSS FEED
$rss_code .= "
  </channel>
  </rss>
";

 
//SEND COMPLETE RSS FEED TO BROWSER
echo($rss_code);

 
?>  