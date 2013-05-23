<?php
include_once("session.php");
include_once("header.php");
include_once('class-template.php');

$obj_template= new Template_information("../templates/");
$dir_name=$obj_template->ReadFolderDirectory();

$file=file("../html/admin/pages.html");
$returncontent=join("",$file);

############# Actions ##################
switch($act)
{

	case 'd':
		if($obj_pri->canDelete($pageurl))
		{
			$msg=delete_content($pageid,$db,$prefix);
			header("location:pages.php?msg=$msg");
		}
		else
		{
		 $msg=archive_content($pageid,0,$db,$prefix);
		 header("location:pages.php?msg=$msg");
		}
		break;
	case 'a':

		$msg=archive_content($pageid,$state,$db,$prefix);
		header("location:pages.php?msg=$msg");
		break;
	case 't':

		$msg=update_theme($name,$selectdir,$db,$prefix);
		header("location:$targetpage?msg=$msg");
		exit();
		break;
}
############# Messages ##################
switch($msg)
{
	case 'a':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle">Page is successfully Added!</div>';
		break;
	case 'e':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Page is successfully Edited!</div>';
		break;
	case 'd':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Page is successfully Deleted!</div>';
		break;
	case 'ar':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Page is successfully Unarchived!</div>';
		break;
	case 'un':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Page is successfully Archived!</div>';
		break;
	case 't':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Theme successfully Assigned!</div>';
		break;
	case 'error':
		$Message = '<div class="error"><img src="../images/crose.png" align="absmiddle"> Please select on of the listed theme</div>';
		break;
}
############# Operation ##################
function delete_content($id,$db,$prefix)
{
	$GetProd = $db->get_a_line("select * from ".$prefix."pages where pageid = '$id'");
	@extract($GetProd);
	$filename = $filename;

	// If page has comments delete comments
	$db->insert("delete from ".$prefix."comments where page ='$filename' && type='content'");
	$db->insert("delete from ".$prefix."pages where pageid ='$id'");

	return $msg='d';

}

function archive_content($id,$state,$db,$prefix)
{

	$db->insert("update ".$prefix."pages set published='$state' where pageid ='$id'");
	if($state==1)
	return $msg='ar';
	else
	return $msg='un';
}

function update_theme($ids,$selectdir,$db,$prefix)
{
	$i=0;
	foreach($ids as $id)
	{
		$sql="update ".$prefix."pages set asign_template='$selectdir[$i]' where pageid ='$id';";
		$db->insert($sql);
		$i++;
	}
	return $msg='t';
}


// user access control & error handling
/*if($admin_id !='1'){
 header("Location:admin_menu.php?errorCode=300");
 }*/

/*if(isset($_GET['msg'])){
 $msg = $_GET['msg'];

 if($msg == "a"){
 $msg = '<div class="top-message" style="color:green;"><img src="../images/tick.png" align="absmiddle"> <b>Admin is Successfully Added!</b></div>';
 }else if($msg == "e"){
 $msg = '<div class="top-message" style="color:green;"><img src="../images/tick.png" align="absmiddle"> <b>Admin is Successfully Edited!</b></div>';
 }
 }
 */
// UAC end


$fieldNamesArray = array(
	"field1" => "pageid",
	"field2" => "pagename",
	"field3" => "linkproduct",
	"field4" => "date_added"
	);

	$tbl_name=$prefix."pages";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	$query = "SELECT COUNT(*) as num FROM $tbl_name where type !='blog'";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];

	/* Setup vars for query. */
	$targetpage = "pages.php"; 	//your file name  (the name of this file)
	if(isset ($_GET["limit"])){
		$limit = $_GET["limit"];
	}else{
		$limit = 10; 								//how many items to show per page
	}

	$page = $_GET['page'];

	if(isset($_GET['col']) && isset($_GET['dir'])){
		$fieldName = $_GET['col'];
		$field = $fieldNamesArray[$_GET['col']];
		$dir = $_GET['dir'];

	}else{
		$fieldName = 'field1';
		$field = "pageid";
		$dir = "DESC";
	}

	if($page)
	$start = ($page - 1) * $limit; 			//first item to display on this page
	else
	$start = 0;								//if no page var is given, set start to 0

	/* Get data. */

	$sql = "SELECT * FROM $tbl_name where type !='blog' ORDER BY $field $dir LIMIT $start, $limit";
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
		$pagination.= "<a href=\"$targetpage?page=$prev&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">&lt;&lt; previous</a>";
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
				$pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
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
					$pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
					else
					$pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
					else
					$pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
				}
			}
		}

		//next button
		if ($page < $counter - 1)
		$pagination.= "<a href=\"$targetpage?page=$next&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">Next &gt;&gt;</a>";
		else
		$pagination.= "<span class=\"disabled\">Next &gt;&gt;</span>";
		$pagination.= "</div>\n";
	}



	function getDirection($currentField, $dir, $fieldName){

		if($fieldName == $currentField && $dir== 'DESC')
		{
			return "ASC";

		}else if($fieldName == $currentField && $dir== 'ASC'){

			return "DESC";
		}else {

			return "ASC";
		}
	}

	function getCssClass($currentField, $dir, $fieldName){

		if($fieldName == $currentField && $dir== 'DESC')
		{
			return "sortDesc";

		}else if($fieldName == $currentField && $dir== 'ASC'){

			return "sortAsc";
		}else {

			return "";

		}
	}
	function isSelected($currentValue, $limit){
		if($currentValue == $limit){

			return 'selected="selected"';
		}
		 
	}
	/************************************************************************************/
	function select_Dir($dir_name,$pageid,$selected){
		if($selected=='default') $default=	'selected="selected"'; 	else $default='';
		if($selected=='none')  	 $none=		'selected="selected"'; 	else $none='';

		$str='<select name="selectdir[]" onchange="document.squeeze.submit();">';


		$str.='<option value="default"'. $default .'>default</option>';
		foreach($dir_name as $name){
				
			if($selected == $name)
			$select='selected="selected"';
			else
			$select="";
			//echo  "$name----$selected -----$select <br>";
			$str.='<option value="' . $name . '" '. $select .'>'. $name .'</option>';
		}
		$str.='</select">
	<input type="hidden" value="'. $pageid .'" name="name[]">
	<input type="hidden" value="t" name="act">
	';
		return $str;
	}
	/************************************************************************************/
	function getContentComments($filename, $linkproduct, $prefix){

		//if($linkproduct == 'Site Root Page')
		//{
		include_once("../common/database.class.php");
		include_once("../common/common.class.php");
		$db = new database();
		$common = new common();
		$now_start=date("Y-m-d 00:00:00");
		$now_end=date("Y-m-d 23:59:59");
			
		$q = "select count(*) as cnt from ".$prefix."comments where type='content' && page='$filename' && date
			between '$now_start' and '$now_end' && checked=0";
		$r22 = $db->get_a_line($q);

		$count1 = $r22[cnt];
			
		$q_total = "select count(*) as cnt from ".$prefix."comments where type='content' && page='$filename'";
		$r_total = $db->get_a_line($q_total);
		$total = $r_total[cnt];
			
		$q_unread = "select count(*) as cnt from ".$prefix."comments where type='content' && page='$filename' && checked=0";
		$r_unread = $db->get_a_line($q_unread);
		$unread = $r_unread[cnt];
			
		if($unread > 0)
		$unread_img='&nbsp;<img src="../images/admin/unread.png" alt="unread" align="absmiddle" title="'.$unread.' Unread Comments" />';
		else
		$unread_img='';
			
			
		if($total > '0' && $count1 == '0' )
		{
			$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&type=content">
				<img src="../images/comments2.png" alt="commentsImage" title="Click To Moderate Comments for This Content Page" ></a>
				<br />'.$total.'<span>'.$unread_img.'</span>';
		}
		elseif($total > '0' && $count1 > '0')
		{
			$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&type=content">
				<img src="../images/comments2.png" alt="commentsImage" title="Click To Moderate Comments for This Content Page" ></a>
				<br />'.$total.' ('.$count1.') <span class="new">New</span><span>'.$unread_img.'</span>';
		}
		else{$commentlink="None";}
			
			
		/*if($count1 == '0')
			{
			$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&type=content"><img src="../images/comments2.png" alt="commentsImage" title="Click To Moderate Comments for This Content Page" ></a>';
			}
			elseif($count1 > '0')
			{
			$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&type=content"><img src="../images/comments2.png" alt="commentsImage" title="Click To Moderate Comments for This Content Page" ></a><br /> ('.$count1.') New';
			}*/
		//}
		//else
		//{
		//	$commentlink = "--";
		//}

		return $commentlink;
	}

	$content = '
	 <table width="904"><tr><td width="50%" valign="bottom"><span style="float:left;">Select Number of rows per page:</span>
	 <form name="limitForm" action="'.$targetpage.'#pagination" method="GET" style="float:left;">
	 	<select name="limit" onchange="document.limitForm.submit()" style="width:100px;">
		 	<option value="10"'.isSelected(10,$limit).'>10</option>
		 	<option value="25"'.isSelected(25,$limit).'>25</option> 
			<option value="50" '.isSelected(50,$limit).'>50</option>
			<option value="100" '.isSelected(100,$limit).'>100</option>
		</select>
	 </form>
	 
	 </td>
	 <td width="50%" align="right">';

	if($obj_pri->canAdd($pageurl))
	{
		$content .= '<div class="buttons">
		 	<a href="page-add.php">Add New Content Page</a>
		 </div>';
	}
	$content .= ' </td></tr>
	 </table> ';
	$content .= '
	<form name="squeeze" action"" method="post">
	<table id="table" border="0" cellpadding="5" cellspacing="0"
													width="904" bgcolor="#FFFFFF" style="border: #000000 solid 4px;">
													<thead>
														<tr class="list_results_colhead" id="pagination">


															<th title="click to sort by this field"><a href="'.$targetpage.'?col=field1&amp;dir='.getDirection('field1',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">Id</a><span
																class="'.getCssClass('field1',$dir,$fieldName).'">&nbsp;</span></th>
															<th title="click to sort by this field" align="left"><a
																href="'.$targetpage.'?col=field2&amp;dir='.getDirection('field2',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">Page Name</a><span class='.getCssClass('field2',$dir,$fieldName).'>&nbsp;</span></th>
															<th><a href="'.$targetpage.'?col=field3&amp;dir='.getDirection('field3',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">Access</a><span
																class="'.getCssClass('field3',$dir,$fieldName).'">&nbsp;</span></th>
															<th width="148px"><a href="'.$targetpage.'?col=field4&amp;dir='.getDirection('field4',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">Date Added</a><span
																class="'.getCssClass('field4',$dir,$fieldName).'">&nbsp;</span></th>
															<th width="100"><span>Choose Theme</span></th>	
															<th width="50"><span>Comments</span></th>
															<th><span>Links</span></th>
															<th><span>Edit/Delete</span><span class=>&nbsp;</span></th>
															
														</tr>
													</thead>';



	$i = 0;

	while($row = mysql_fetch_array($result))
	{
		if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
			
			
		if($row["date_added"]){
			$dateAdded= date("M d, Y g:i a",strtotime($row["date_added"]));
		}else {
			$dateAdded= "-";
		}

		$select_dir = select_Dir($dir_name,$row['pageid'],$row['asign_template']);

		$content .= '<tr class="'.$class.'" onmouseover="this.style.backgroundColor=\'#f1ca47\'" onmouseout="this.style.backgroundColor=\'\'"> ' ;
		switch($row["linkproduct"]){
			case 'rapidresidualpro':
				$access=1;
				break;
			case 'Site Root Page':
			case 'Legal':	
				$access=2;
				break;
			
			default:
				$access=3;
				break;
		}



		if($obj_pri->canDelete($pageurl))
		{
			if($row['published']==0){
				$delete='<a href="pages.php?act=d&pageid='.$row['pageid'].'">
			<img src="../images/cross-gray.png" alt="editImage" title="Click to delete this content page" Onclick="return confirm('."'".'Are you sure! you want to delete this page?'."'".')">
			</a>';
			}
			else
			{
				$delete='<a href="pages.php?act=d&pageid='.$row['pageid'].'">
			<img src="../images/crose.png" alt="editImage" title="Click to delete this content page" Onclick="return confirm('."'".'Are you sure! you want to delete this page?'."'".')">
			</a>';
			}
		}
		else{
			if($row['published']==0){
				$delete='<a href="pages.php?act=a&pageid='.$row['pageid'].'&state=1">
			<img src="../images/cross-gray.png" alt="editImage" title="click to unarchive this content page">
			</a>';
			}
			else
			$delete='<a href="pages.php?act=a&pageid='.$row['pageid'].'&state=0">
			<img src="../images/crose.png" alt="editImage" title="click to archive this content page">
			</a>';
		}
		if($row["linkproduct"]=='Legal')
		$delete='';
			
			
		$content .='<td align="center" valign="middle" >'.$row['pageid'].'</td>
		<td valign="middle" width="300px">
			<a href="view-content-page.php?pageid='.$row['pageid'].'&type=content">'.$row["pagename"].'</a>
		</td>
		
		<td align="center" valign="middle" width="102px">'.$row["linkproduct"].'</td>
		<td align="center" valign="middle">'.$dateAdded.'</td>
		<td align="center" valign="middle" nowrap> ' . $select_dir . ' </td>
		<td align="center" valign="middle" nowrap>'.getContentComments($row['filename'], $row['linkproduct'], $prefix).'</td>
		
		<td align="center" valign="middle">
		<a href="pagelink.php?pageid='. $row['pageid'] .'&access='.$access.'&iframe=true&width=600px&height=220px" rel="prettyPhoto">
			<img src="../images/yellowlink.png" alt="linkImage" title="Click to go to the content page">
		</a>
		</td>
		<td align="center" valign="middle">
		<div style="float:left;padding-left:16px;"><a href="page-edit.php?act=e&pageid='.$row['pageid'].'">
		<img src="../images/editIcon.png" alt="editImage" title="Click to edit this content page" ></a> </div>';
		$content.='<div style=" float:right;padding-left:10px;"">'. $delete .' </div>
		</td>
		
	</tr>';



		$i++ ;}
		if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}


		$content .= '</table></form><div class="pages">';
		$content .= '<div class="totalpages">Total: '. $startrec .' - '. $totalrec .' of  '.$total_pages.'</div>';
		$content .= '<div class="pager">'.$pagination.'&nbsp;</div></div>';
		$content .= '<div><a href="#top" style="text-align:center;">Move to top</a></div>';
		 





		$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
		echo $returncontent;

		include 'footer.php';