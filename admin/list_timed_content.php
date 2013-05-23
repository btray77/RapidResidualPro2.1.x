<?php

include_once("session.php");
include_once("header.php");
$con=$_GET['con'];
$mysql="select * from ".$prefix."tccampaign where shortname='$con' ";
$rslt=$db->get_a_line($mysql);
$campaign = $rslt["longname"];

$GetFile = file("../html/admin/list_timed_content.html") ;
$Content = join("",$GetFile) ;

$targetpage = "list_timed_content.php?con=$con"; 	//your file name  (the name of this file)
$tbl_name=$prefix."timed_content";		//your table name

?>
<script>
function submit()
{
	document.form.submit();
}
</script>
<?php
function save_order_tr_items($ids,$orders,$db,$prefix){
	$i=0;
	foreach($orders as $order)
		{
			$sql="update ".$prefix."timed_content set `trorder`='$order' where pageid='$ids[$i]';";
			$db->insert($sql);
			$i++;
		}
		return $msg='o';
}
############# Actions ##################
switch($act)
{
	case 'd':
		if($obj_pri->canDelete($pageurl))
		{
		$msg=delete_content($pageid,$db,$prefix);
		header("location:$targetpage&msg=$msg");
		}
		else
		{
		 $msg=archive_content($pageid,0,$db,$prefix);
		 header("location:$targetpage&msg=$msg");
		}		
	break;
	case 'a':
		$msg=archive_content($pageid,$state,$db,$prefix);
		header("location:$targetpage&msg=$msg");
	break;
	case 'save':
		$msg=save_order_tr_items($id,$order,$db,$prefix);
		header("location:$targetpage&msg=$msg");
	break;	
}
############# Messages ##################
switch($msg)
{
	case 'a':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Page is Successfully Added</div>';
	break;
	case 'e':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Page is Successfully Edited</div>';
	break;	
	case 'd':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Page is Successfully Deleted</div>';
	break;
	case 'ar':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Page is successfully Unarchived!</div>';
	break;
	case 'un':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Page is successfully Archived!</div>';
	break;
	case 'o':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Order is successfully Changed!</div>';
	break;
}
############# Operation ##################

function delete_content($id,$db,$prefix)
{
	
	$db->insert("delete from ".$prefix."timed_content where pageid ='$id'");
	
	return $msg='d'; 
		
}

function archive_content($id,$state,$db,$prefix)
{

	$db->insert("update ".$prefix."timed_content set published='$state' where pageid ='$id'");
	if($state==1)
		return $msg='ar';
	else 
		return $msg='un';
}

	/************************************************************************************/
	function getContentComments($filename, $linkproduct, $prefix, $page){
		//if($linkproduct == 'Site Root Page')
		//{
		include_once("../common/database.class.php");
		include_once("../common/common.class.php");
		$db = new database();
		$common = new common();
		$now_start=date("Y-m-d 00:00:00");
		$now_end=date("Y-m-d 23:59:59");
			
		$q = "select count(*) as cnt from ".$prefix."comments where type='trcontent' && page='$filename' && filename = '$page' && date
			between '$now_start' and '$now_end' && checked=0";
		$r22 = $db->get_a_line($q);

		$count1 = $r22[cnt];
			
		$q_total = "select count(*) as cnt from ".$prefix."comments where type='trcontent' && page='$filename' && filename = '$page'";
		$r_total = $db->get_a_line($q_total);
		$total = $r_total[cnt];
			
		$q_unread = "select count(*) as cnt from ".$prefix."comments where type='trcontent' && page='$filename' && filename = '$page' && checked=0";
		$r_unread = $db->get_a_line($q_unread);
		$unread = $r_unread[cnt];
			
		if($unread > 0)
		$unread_img='&nbsp;<img src="../images/admin/unread.png" alt="unread" align="absmiddle" title="'.$unread.' Unread Comments" />';
		else
		$unread_img='';
			
			
		if($total > '0' && $count1 == '0' )
		{
			$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&filename='.$page.'&type=trcontent">
				<img src="../images/comments2.png" alt="commentsImage" title="Click To Moderate Comments for This Content Page" ></a>
				<br />'.$total.'<span>'.$unread_img.'</span>';
		}
		elseif($total > '0' && $count1 > '0')
		{
			$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&filename='.$page.'&type=trcontent">
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

/**********************************************************/



########## pagination ###########

$fieldNamesArray = array(
	"field1" => "pageid",
	"field2" => "pagename",
	"field3" => "filename",
	"field4" => "available"
	);

	
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	
	$query = "select count(*) as cnt from $tbl_name where `campaign`='$con'";
	$rs_total=mysql_query($query);
	$total_pages = mysql_fetch_array($rs_total);
	$total_pages = $total_pages['cnt'];

	/* Setup vars for query. */
	
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
		$fieldName = 'field4';
		$field = "trorder";
		$dir = "ASC";
	}

	if($page)
	$start = ($page - 1) * $limit; 			//first item to display on this page
	else
	$start = 0;								//if no page var is given, set start to 0

	/* Get data. */

	$sql = "select * from $tbl_name where campaign='$con' order by $field $dir limit $start, $limit";
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
	 
 ##### end pagination ##########

	$content = '
	<div class="">Module:'.$con.'</div>
	 <table width="904"><tr><td width="50%" valign="bottom">
         <span style="float:left;">Select Number of rows per page:</span>
         <form name="limitForm" action="'.$targetpage.'#pagination" method="GET" style="float:left;">
         <input type="hidden" name="con" value="'. $_REQUEST['con'] .'">    
	 <select name="limit" onchange="document.limitForm.submit()" style="width:100px;">
             <option value="10"'.isSelected(10,$limit).'>10</option>
             <option value="25"'.isSelected(25,$limit).'>25</option> 
             <option value="50" '.isSelected(50,$limit).'>50</option>
             <option value="100" '.isSelected(100,$limit).'>100</option>
          </select>
	 </form></td>
	 <td width="50%" align="right"> ';
	
	 if($obj_pri->canAdd($pageurl))
	 {
		 $content .= '<div class="buttons">
		 	<a href="add_timed_content.php?con='.$con.'">Add New Content Page</a>
		 </div>';
	 }
	 $content .= '<div class="buttons">
		 	<a href="tcampaigns.php">Back to Modules</a>
		 </div>';
	$content .= '</td></tr> </table> ';

	$content .= '<form action="'.$_SERVER[PHP_SELF].'?con='.$con.'" method="post" name="form" id="form"><table id="table" border="0" cellpadding="5" cellspacing="0"
													width="904" bgcolor="#FFFFFF" style="border: #000000 solid 4px;">
													<thead>
														<tr class="list_results_colhead" id="pagination">
															<th title="click to sort by this field" nowrap="nowrap" >
																<a href="'.$targetpage.'&col=field1&amp;dir='.getDirection('field1',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
																Id</a>
																<span class="'.getCssClass('field1',$dir,$fieldName).'">&nbsp;</span>
															</th>
															<th title="click to sort by this field" align="left" width="457">
																<a href="'.$targetpage.'&col=field2&amp;dir='.getDirection('field2',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
																Page Name</a>
																<span class='.getCssClass('field2',$dir,$fieldName).'>&nbsp;</span>
															</th>
															
															<th width="88">
															<a href="'.$targetpage.'&col=field3&amp;dir='.getDirection('field3',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
																File Name</a>
																<span class="'.getCssClass('field3',$dir,$fieldName).'">&nbsp;</span>
															
															</th>
															<th width="80" nowrap="nowrap" style="text-align:center">
																Orders
																<img src="'.$http_path.'/images/admin/save.png" alt="save" align="absmiddle" onclick="submit()" />
																<input type="hidden" name="act" value="save">
																													
															</th>
															<th align="left" width="50"><span>Comments</span></th>
															<th width="200">
															<a href="'.$targetpage.'&col=field4&amp;dir='.getDirection('field4',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
																Available After Days</a>
																<span class="'.getCssClass('field4',$dir,$fieldName).'">&nbsp;</span>
															
															</th>
															
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
//view-content-page.php?pageid='.$row['name'].'&type=squeeze
/*
 * squeezelink.php?page='. $row['name'] .'&iframe=true&width=600px&height=150px" rel="prettyPhoto
 * 
 */
		
	if($obj_pri->canDelete($pageurl))
		{
			if($row['published']==0){
			$delete='<a href="'.$targetpage.'&act=d&pageid='.$row['pageid'].'">
			<img src="../images/cross-gray.png" alt="editImage" title="Click to delete this time content" Onclick="return confirm('."'".'Are you sure! you want to delete this page?'."'".')">
			</a>';
			}
			else
			{
			$delete='<a href="'.$targetpage.'&act=d&pageid='.$row['pageid'].'">
			<img src="../images/crose.png" alt="editImage" title="Click to delete this time content" Onclick="return confirm('."'".'Are you sure! you want to delete this page?'."'".')">
			</a>';
			}
		}
		else{
			
			if($row['published']==0){		
				$delete='<a href="'.$targetpage.'&act=a&pageid='.$row['pageid'].'&state=1">
					<img src="../images/cross-gray.png" alt="editImage" title="Click to unarchive this time content">
			</a>';
				
			}
			else{
				$delete='<a href='.$targetpage.'&act=a&pageid='.$row['pageid'].'&state=0">
			<img src="../images/crose.png" alt="editImage" title="Click to archive this time content">
			</a>';}
				
			}
	
		
		$content .= '<tr class="'.$class.'" onmouseover="this.style.backgroundColor=\'#f1ca47\'" onmouseout="this.style.backgroundColor=\'\'"> ' ;
		$content .='<td align="center" valign="middle" >'.$row['pageid'].'</td>
					<td valign="middle" width="300px">'.$row["pagename"].' </td>
					<td align="center" valign="middle" nowrap>'.$row["filename"].'</td>
					<td align="center" valign="middle" nowrap>
					<input type="text" value="'.$row['trorder'].'" name="order[]" size="3" maxlength="3" style="text-align:center"/>
    				<input type="hidden" value="'.$row['pageid'].'" name="id[]" />
					</td>
					<td align="center" valign="middle" nowrap>'.getContentComments($row['campaign'], $row['linkproduct'], $prefix, $row['filename']).'</td>
					<td align="center" valign="middle">'.$row["available"].'</td>
					<td align="center" valign="middle">
						<div style="float:left;padding-left:16px;"><a href="edit_timed_content.php?act=e&id='.$row['pageid'].'&con='.$con.'">
							<img src="../images/editIcon.png" alt="editImage" title="Click to edit this time content" ></a>
						 </div> 
						<div style=" float:right;padding-left:10px;">'.$delete.'</div></td>				
				</tr>';



		$i++ ;}
	  
		if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
		
				
		$content .= '</table></form><div class="pages">';
		$content .= '<div class="totalpages">Total: '. $startrec .' - '. $totalrec .' of  '.$total_pages.'</div>';
		$content .= '<div class="pager">'.$pagination.'&nbsp;</div></div>';
		$content .= '<div><a href="#top" style="text-align:center;">Move to top</a></div>';





/*$q = "select count(*) as cnt from ".$prefix."timed_content where campaign='$con'";
$r = $db->get_a_line($q);
$count = $r[cnt];
$records=10;
$links = "list_timed_content.php?con=$con&";

if($page=="")
{
	$page=1;
}

$start=($page-1)*$records;
$Content=$common->print_page_break3($db,$Content,$count,$records,$links,$page);

########## pagination ###########

$ChangeColor = 1 ;
$ToReplace = "" ;

$GetMembers = $db->get_rsltset("select * from ".$prefix."timed_content where campaign='$con' order by available asc limit $start, $records") ;

for($i = 0 ; $i < count($GetMembers) ; $i++)
{
	if($ChangeColor == 0)
	{
		$bgcolor = "#eaeaea" ;
		$ChangeColor = 1;
	}
	else
	{
		$bgcolor = "#FFFFFF" ;
		$ChangeColor = 0;
	}

	@extract($GetMembers[$i]) ;

	$ToReplace .= preg_replace($Ptn,"$$1",$SelectedContent) ;
}
$Content = preg_replace($Pat,$ToReplace,$Content) ;

*/

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
include_once("footer.php");
?>