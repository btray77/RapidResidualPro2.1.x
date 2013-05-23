<?php
include "session.php";
include "header.php";
$GetFile = file("../html/admin/tcampaigns.html");
$Content = join("",$GetFile);
$Title = "Time-Released Content Modules";
$targetpage="tcampaigns.php";
switch($act)
{
	
	case 'd':
		if($obj_pri->canDelete($pageurl))
		{
			$msg=delete_content($id,$db,$prefix);
			header("location:$targetpage?msg=$msg");
		}
		else
		{
		 $msg=archive_content($id,0,$db,$prefix);
		 header("location:$targetpage?msg=$msg");
		}		
	break;
	case 'a':
		$msg=archive_content($id,$state,$db,$prefix);
		header("location:$targetpage?msg=$msg");
	break;	
}

############# Messages ##################
switch($msg)
{
	case 'a':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Module has been Successfully Added</div>';
	break;
	case 'e':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Module has been Successfully Edited</div>';
	break;	
	case 'd':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Module has been Successfully Deleted</div>';
	break;
	case 'ar':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Module is successfully Unarchived</div>';
	break;
	case 'un':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Module is successfully Archived</div>';
	break;
	case 'prod':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Module is currently in use by a product and can not be deleted.</div>';
	break;
}
############# Operation ##################
function delete_content($id,$db,$prefix)
{
	$GetProd = $db->get_a_line("select * from ".$prefix."tccampaign where id = '$id'");
	@extract($GetProd);
	$shortname	= $shortname;
	// Are any products currently using this campaign? If so display error message.
	$q = "select count(*) as cnt from ".$prefix."products where tcontent='$shortname'";
	$r = $db->get_a_line($q);
	if($r[cnt] != 0)
	{
		$msg = "prod";
		header("Location: tcampaigns.php?msg=$msg");
		exit();
	}


	// Delete any timed content under this campaign
	$db->insert("delete from ".$prefix."timed_content where campaign ='$shortname'");

	// Delete campaign
	$db->insert("delete from ".$prefix."tccampaign where id ='$id'");
	$msg = "d" ;
	
	return $msg='d'; 
		
}

function archive_content($id,$state,$db,$prefix)
{
	echo $sql="update ".$prefix."tccampaign set published='$state' where id ='$id'";
	
	$db->insert($sql);
	if($state==1)
		return $msg='ar';
	else 
		return $msg='un';
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
			
		$q = "select count(*) as cnt from ".$prefix."comments where type='trcontent' && page='$filename' && date
			between '$now_start' and '$now_end' && checked=0";
		$r22 = $db->get_a_line($q);

		$count1 = $r22[cnt];
			
		$q_total = "select count(*) as cnt from ".$prefix."comments where type='trcontent' && page='$filename'";
		$r_total = $db->get_a_line($q_total);
		$total = $r_total[cnt];
			
		$q_unread = "select count(*) as cnt from ".$prefix."comments where type='trcontent' && page='$filename' && checked=0";
		$r_unread = $db->get_a_line($q_unread);
		$unread = $r_unread[cnt];
			
		if($unread > 0)
		$unread_img='&nbsp;<img src="../images/admin/unread.png" alt="unread" align="absmiddle" title="'.$unread.' Unread Comments" />';
		else
		$unread_img='';
			
			
		if($total > '0' && $count1 == '0' )
		{
			$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&type=trcontent">
				<img src="../images/comments2.png" alt="commentsImage" title="Click To Moderate Comments for This Content Page" ></a>
				<br />'.$total.'<span>'.$unread_img.'</span>';
		}
		elseif($total > '0' && $count1 > '0')
		{
			$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&type=trcontent">
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

$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "name",
	"field3" => "comments",
	"field4" => "date_added"
	);

	$tbl_name=$prefix."tccampaign";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	$query = "SELECT COUNT(*) as num FROM $tbl_name";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];

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
		$fieldName = 'field1';
		$field = "id";
		$dir = "ASC";
	}

	if($page)
	$start = ($page - 1) * $limit; 			//first item to display on this page
	else
	$start = 0;								//if no page var is given, set start to 0

	/* Get data. */

	$sql = "SELECT * FROM $tbl_name ORDER BY $field $dir LIMIT $start, $limit";
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
	function time_based_content($con,$db,$prefix)
	{
		$q = "select count(*) as cnt from ".$prefix."timed_content where campaign='$con' and published=1";
		$r = $db->get_a_line($q);
		return $r[cnt];
	}
	
	
################ Pagination Ends ###################

$content = '
	 <table width="904"><tr><td width="50%" valign="bottom"><span style="float:left;">Select Number of rows per page:</span><form name="limitForm" action="'.$targetpage.'#pagination" method="GET" style="float:left;">
	 <select name="limit" onchange="document.limitForm.submit()" style="width:100px;"><option value="10"'.isSelected(10,$limit).'>10</option><option value="25"'.isSelected(25,$limit).'>25</option> 
	 <option value="50" '.isSelected(50,$limit).'>50</option><option value="100" '.isSelected(100,$limit).'>100</option></select>
	 </form></td>
	 <td width="50%" align="right"> ';
	 if($obj_pri->canAdd($pageurl))
	 {
		 $content .= '<div class="buttons">
		 	<a href="add_campaign.php">Add New Module </a>
		 </div>';
	 }
	$content .= '</td></tr> </table> ';

	$content .= '<table id="table" border="0" cellpadding="5" cellspacing="0"
													width="904" bgcolor="#FFFFFF" style="border: #000000 solid 4px;">
													<thead>
														<tr class="list_results_colhead" id="pagination">
															<th title="click to sort by this field" nowrap="nowrap" >
																<a href="'.$targetpage.'?col=field1&amp;dir='.getDirection('field1',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">Id</a>
																<span class="'.getCssClass('field1',$dir,$fieldName).'">&nbsp;</span>
															</th>
															<th title="click to sort by this field" align="left">
																<a href="'.$targetpage.'?col=field2&amp;dir='.getDirection('field2',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
																Module Name</a>
																<span class='.getCssClass('field2',$dir,$fieldName).'>&nbsp;</span>
															</th>
															<th width="465" align="left"><span>Description</span></th>
															<th width="50"><span>Comments</span></th>
															<th align="center"><span>Time Based Content</span></th>
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
	$content_time_based=time_based_content($row['shortname'],$db,$prefix);	
	if($obj_pri->canDelete($pageurl))
		{
			if($row['published']==0){
			$delete='<a href="'.$targetpage .'?act=d&id='.$row['id'].'">
			<img src="../images/cross-gray.png" alt="editImage" title="Click to delete this module" Onclick="return confirm('."'".'Are you sure! you want to delete this module?'."'".')">
			</a>';
			}
			else
			{
			$delete='<a href="'.$targetpage .'?act=d&id='.$row['id'].'">
			<img src="../images/crose.png" alt="editImage" title="Click to delete this module" Onclick="return confirm('."'".'Are you sure! you want to delete this module?'."'".')">
			</a>';
			}
		}
		else{
			
			if($row['published']==0){		
				$delete='<a href="'.$targetpage .'?act=a&id='.$row['id'].'&state=1">
					<img src="../images/cross-gray.png" alt="editImage" title="Click to unarchive this module">
			</a>';
				
			}
			else{
				$delete='<a href='.$targetpage .'?act=a&id='.$row['id'].'&state=0">
			<img src="../images/crose.png" alt="editImage" title="Click to archive this module">
			</a>';}
				
			}
		
		$content .= '<tr class="'.$class.'" onmouseover="this.style.backgroundColor=\'#f1ca47\'" onmouseout="this.style.backgroundColor=\'\'"> ' ;
		$content .='<td align="center" valign="middle" >'.$row['id'].'</td>
					<td valign="middle" >
						
						'.$row["longname"].'
						
					</td>
					<td align="left" valign="middle" >'. $row["description"] .'</td>
					<td align="center" valign="middle" nowrap>'.getContentComments($row['shortname'], $row['linkproduct'], $prefix).'</td>
					<td align="center" valign="middle" nowrap><!-- <img src="../images/admin/file.png" border="0" alt="Time-based content"><br> -->
					<a href="list_timed_content.php?con='.$row['shortname'].'">
					
					'. $content_time_based .'</a></td>
					<td align="center" valign="middle">
						<div style="float:left;padding-left:16px;"><a href="edit_campaign.php?&id='.$row['id'].'">
							<img src="../images/editIcon.png" alt="editImage" title="Click to edit this module" ></a>
						 </div> 
						<div style=" float:right;padding-left:10px;">'.$delete.'</div></td>				
				</tr>';



		$i++ ;}
	  
		if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
		
				
		$content .= '</table><div class="pages">';
		$content .= '<div class="totalpages">Total: '. $startrec .' - '. $totalrec .' of  '.$total_pages.'</div>';
		$content .= '<div class="pager">'.$pagination.'&nbsp;</div></div>';
		$content .= '<div><a href="#top" style="text-align:center;">Move to top</a></div>';
		
$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content ;
include "footer.php";




?>