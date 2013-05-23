<?php
include_once("session.php");
include_once("header.php");


#################### ACTION ##########################
switch($_POST['action']){
		
	case 'del':
	  if($obj_pri->canDelete($pageurl))	{
		$id= (int) $_POST['id'];
		$msg=delete_comments($id,$prefix,$db,$_REQUEST['check']);
		if(isset($filename) && $type == 'trcontent'){
			header("location:comment_moderation.php?pageid=$pageid&filename=$filename&type=$type&msg=$msg");
			exit();
		}else{
			header("location:comment_moderation.php?pageid=$pageid&type=$type&msg=$msg");
			exit();
		}	
	  }
	break;
	case 'publish':
		$id= (int) $_POST['id'];
		$msg=published_comments($id,$prefix,$db,$_REQUEST['check'],1);
		if(isset($filename) && $type == 'trcontent'){
			header("location:comment_moderation.php?pageid=$pageid&filename=$filename&type=$type&msg=$msg");
		}else{
			header("location:comment_moderation.php?pageid=$pageid&type=$type&msg=$msg");
		}	
		exit();
	break;
	case 'unpublish':
		$id= (int) $_POST['id'];
		$msg=published_comments($id,$prefix,$db,$_REQUEST['check'],0);
		if(isset($filename) && $type == 'trcontent'){
			header("location:comment_moderation.php?pageid=$pageid&filename=$filename&type=$type&msg=$msg");
		}else{
			header("location:comment_moderation.php?pageid=$pageid&type=$type&msg=$msg");
		}	
		exit();
	break;
	case 'read':
		$id= (int) $_POST['id'];
		$msg=checked_comments($id,$prefix,$db,$_REQUEST['check'],1);
		if(isset($filename) && $type == 'trcontent'){
			header("location:comment_moderation.php?pageid=$pageid&filename=$filename&type=$type&msg=$msg");
		}else{
			header("location:comment_moderation.php?pageid=$pageid&type=$type&msg=$msg");
		}	
		exit();
	break;
	case 'unread':
		$id= (int) $_POST['id'];
		$msg=checked_comments($id,$prefix,$db,$_REQUEST['check'],0);
		if(isset($filename) && $type == 'trcontent'){
			header("location:comment_moderation.php?pageid=$pageid&filename=$filename&type=$type&msg=$msg");
		}else{
			header("location:comment_moderation.php?pageid=$pageid&type=$type&msg=$msg");
		}	
		exit();
	break;
				
}
############# OPERATION ####################
function delete_comments($id,$prefix,$db,$selected)
{
	
	if(count($selected)> 0):
	$str='';
	foreach($selected as $sel)
	{	
		$str.=$sel.',';
	}
	$str=substr($str,0,-1);
	
	$sql="delete from ".$prefix."comments where id in($str)";
	
	$db->insert("$sql");

	$msg = "d";
	endif;
	return $msg;
}

function published_comments($id,$prefix,$db,$selected,$state)
{
	
	if(count($selected)> 0):
	$str='';
	foreach($selected as $sel)
	{	
		$str.=$sel.',';
	}
	$str=substr($str,0,-1);
	if($state==0)
	{
		$sql="update ".$prefix."comments set published=0 where id in($str)";
		$db->insert("$sql");
		$msg = "up";
	}
	else
	{	$sql="update ".$prefix."comments set published=1 where id in($str)";
		$db->insert("$sql");
		$msg = "p";
	}
	endif;
	
	return $msg;
}
function checked_comments($id,$prefix,$db,$selected,$state)
{
	
	if(count($selected)> 0):
	$str='';
	foreach($selected as $sel)
	{	
		$str.=$sel.',';
	}
	$str=substr($str,0,-1);
	if($state==0)
	{
		$sql="update ".$prefix."comments set checked=0 where id in($str)";
		$db->insert("$sql");
		$msg = "ur";
	}
	else
	{	$sql="update ".$prefix."comments set checked=1 where id in($str)";
		$db->insert("$sql");
		$msg = "r";
	}
	endif;
	
	return $msg;
}

########## pagination ###########
$tbl_name=$prefix."comments";		//your table name
// How many adjacent pages should be shown on each side?
$adjacents = 3;

/*
 First get total number of rows in data table.
 If you have a WHERE clause in your query, make sure you mirror it here.
 */
if(isset($filename) && $type == 'trcontent'){
	$query = "SELECT COUNT(*) as num FROM $tbl_name where page='$pageid' && filename='$filename' && type='$type'";
}else{
	$query = "SELECT COUNT(*) as num FROM $tbl_name where page='$pageid' && type='$type'";
}
$total_pages = mysql_fetch_array(mysql_query($query));
$total_pages = $total_pages[num];

/* Setup vars for query. */
if(isset($filename) && $type == 'trcontent'){
	$targetpage = "comment_moderation.php?pageid=$pageid&filename=$filename&type=$type"; 	//your file name  (the name of this file)
}else{
	$targetpage = "comment_moderation.php?pageid=$pageid&type=$type"; 	//your file name  (the name of this file)
}	
if(isset ($_REQUEST["limit"])){
	$limit = $_REQUEST["limit"];
}else{
	$limit = 10; 								//how many items to show per page
}

$page = $_GET['page'];

if(isset($_GET['dir']))
	$dir=$_GET['dir'];
else
	$dir='DESC'; 	

if($page)
$start = ($page - 1) * $limit; 			//first item to display on this page
else
$start = 0;								//if no page var is given, set start to 0

/* Get data. */
if(isset($filename) && $type == 'trcontent'){
	$sql = "SELECT * FROM $tbl_name where page='$pageid' && filename='$filename' && type='$type' order by date $dir  LIMIT $start, $limit";
}else{
	$sql = "SELECT * FROM $tbl_name where page='$pageid' && type='$type' order by date $dir  LIMIT $start, $limit";
}	
$result = mysql_query($sql);

/* Setup page vars for display. */
if ($page == 0) $page = 1;					//if no page var is given, default to 1.
$prev = $page - 1;							//previous page is page - 1
$next = $page + 1;							//next page is page + 1
$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1;						//last page minus 1

/*
 Now we apply our rules and draw the pagination object.
 We're actually saving the code to a variable in case we want to draw it more than once.
 */
$pagination = "";
if($lastpage > 1)
{
	$pagination .= "<div class=\"pagination\">";
	//previous button
	if ($page > 1)
	$pagination.= "<a href=\"$targetpage&page=$prev&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">&lt;&lt; previous</a>";
	else
	$pagination.= "<span class=\"disabled\">&lt;&lt; previous</span>";

	//pages

	if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
	{
		for ($counter = 1; $counter <= $lastpage; $counter++)
		{
			if ($counter == $page)
			$pagination.= "<span class=\"current\">$counter</span>";
			else
			$pagination.= "<a href=\"$targetpage&page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
		}
	}
	elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
	{
		//close to beginning; only hide later pages
		if($page < 1 + ($adjacents * 2))
		{
			for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
			{
				if ($counter == $page)
				$pagination.= "<span class=\"current\">$counter</span>";
				else
				$pagination.= "<a href=\"$targetpage&page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage&page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage&page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
		}
		//in middle; hide some front and some back
		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
		{
			$pagination.= "<a href=\"$targetpage&page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
			$pagination.= "<a href=\"$targetpage&page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
			$pagination.= "...";
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
			{
				if ($counter == $page)
				$pagination.= "<span class=\"current\">$counter</span>";
				else
				$pagination.= "<a href=\"$targetpage&page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage&page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage&page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
		}
		//close to end; only hide early pages
		else
		{
			$pagination.= "<a href=\"$targetpage&page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
			$pagination.= "<a href=\"$targetpage&page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
			$pagination.= "...";
			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
				$pagination.= "<span class=\"current\">$counter</span>";
				else
				$pagination.= "<a href=\"$targetpage&page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
			}
		}
	}

	//next button
	if ($page < $counter - 1)
	$pagination.= "<a href=\"$targetpage&page=$next&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">Next &gt;&gt;</a>";
	else
	$pagination.= "<span class=\"disabled\">Next &gt;&gt;</span>";
	$pagination.= "</div>\n";
}
	$selectedbox='Select Number of rows per page:<select name="limit" onchange="document.form.submit()" style="width:100px;"><option value="10"'.isSelected(10,$limit).'>10</option><option value="25"'.isSelected(25,$limit).'>25</option> 
	 <option value="50" '.isSelected(50,$limit).'>50</option><option value="100" '.isSelected(100,$limit).'>100</option></select>';	
/**************************************/

function isSelected($currentValue, $limit){
	if($currentValue == $limit){

		return 'selected="selected"';
	}

}



########## pagination ###########

//$ToReplace = "";
$GetMembers = $db->get_rsltset($sql);


//$Content = preg_replace($Pat,$ToReplace,$Content);

if($msg == "d")
{
	 $Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Comment is successfully deleted</div>';
	
}
else if($msg == "ur")
{
	$Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Comment is successfully marked as unread</div>';
	
}
else if($msg == "r")
{
	$Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Comment is successfully marked as read</div>';
	
}
else if($msg == "up")
{
	$Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Comment is successfully unpublished</div>';
	
}
else if($msg == "p")
{
	$Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Comment is successfully published</div>';
	
}
else if($msg == "a")
{
	$Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Comment is successfully saved</div>';
	
}

if($type == "squeeze")
{
	$back_button = "<a href=squeeze_view.php >Back to Squeeze</a>";
}
elseif($type == "content")
{
	$back_button = "<a href=pages.php >Back to Content</a>";
}
elseif($type == "blog")
{
	$back_button = "<a href=blog.php >Back to Blog</a>";
}
elseif(isset($filename) && $type == "trcontent")
{
	$back_button = "<a href=list_timed_content.php?con=$pageid >Back to Pages</a>";
}
elseif($type == "trcontent")
{
	$back_button = "<a href=tcampaigns.php >Back to Modules</a>";
}


include_once('../html/admin/comment_moderation.html');

include_once("footer.php");
?>