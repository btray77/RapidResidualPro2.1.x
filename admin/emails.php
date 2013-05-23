<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/emails.html");
$returncontent=join("",$file);
$msg = $_GET['msg'];

if ($msg == 1)
{

	$message = '<div class="success"><img src="../images/tick.png" align="absmiddle">Email Inserted Successfully!</div>';
}
if ($msg == 2)
{
	$message = '<div class="success"><img src="../images/tick.png" align="absmiddle">Email Edited Successfully!</div>';

}

$mysql="select * from ".$prefix."emails order by id";
$rs=$db->get_rsltset($mysql);

preg_match("/<{email_start}>(.*?)<{email_end}>/s",$returncontent,$out);
$myvar=$out[0];
$str="";

for($i=0;$i<count($rs);$i++)
{
	if ($i%2 == 0){ $className= "standardRow";} else{ $className= "alternateRow";}
	//$className= "alternateRow";
	$mailname=str_replace("_", " ", $rs[$i]['type']);
	$mid=$rs[$i]['id'];
	$str .= preg_replace("/<{(.*?)}>/e","$$1",$myvar);
}

$returncontent=preg_replace("/<{email_start}>(.*?)<{email_end}>/s",$str,$returncontent);
$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>