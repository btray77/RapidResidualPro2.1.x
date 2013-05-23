<?php
//echo '<pre>'; print_r($_GET); echo '</pre>';exit;
include_once("session.php");
include_once("header.php");
$GetFile = file("../html/admin/member_view.html");
$Content = join("",$GetFile);
$Title = "Member Management";
$targetpage = "member_view.php";



if($_GET["block_member"]){
	$query = "UPDATE `{$prefix}members` SET is_block=1 WHERE id=" . (int) $_GET["block_member"];
	mysql_query($query) or die(mysql_error());
	header("Location: $targetpage?msg=block");
	exit;
}

if($_GET["unblock_member"]){
 	$query = "UPDATE `{$prefix}members` SET is_block=0, report_abuse=0 WHERE id=" . (int) $_GET["unblock_member"];
	mysql_query($query) or die(mysql_error());
	header("Location: $targetpage?msg=unblock");
	exit;
}

if($_GET["action"]=="export"){
		$query = "SELECT `firstname`, `lastname`, `username`, `email`,  `paypal_email`, `address_street`, `address_city`, `address_state`, `address_zipcode`, `address_country`, `telephone`, `skypeid`, `status` FROM `{$prefix}members`";
		$result = mysql_query($query) or die(mysql_error());
		
		$rows[] = "First Name, 	Last Name, 	User Name, 	Email Address, 	PayPal Email, 	Postal Address, City, State / County, Postal / Zip Code, Country, Telephone Number, Skype Id, Status";
		
		$user_type = array(NULL, 'Affiliate', 'Member', 'JV Partner');
		
		$csv_file = "../dumper/csv/export-members.csv";
		
		//$csv_file = "csv/" . date('Ymd-His') . '.csv';
		
		$fp = fopen($csv_file, 'w');
		
		while($members=mysql_fetch_assoc($result)){
				
				
				if(!empty($members['telephone'])) $members['telephone'] = "\"{$members[telephone]}\"";
				
				$user_type_index = $members['status'];
				
				$members['status'] = $user_type[$user_type_index];
				
				$rows[] = implode(", ", $members);
				
				$rs[]  = $members;
				
				$csv_saved = @fputcsv (  $fp ,  $members);
				
						
		}
		
		$csv_data = implode("\n", $rows);
		
		
		
		
		
		//$csv_saved = @fwrite($fp, $csv_data);
		
}


######### Actions  ##########
switch($act)
{
	case 'd':
		if($obj_pri->canDelete($pageurl))
		{
			$msg=delete_content($pageid,$db,$prefix);
			header("location:$targetpage?msg=$msg");
		}
		else
		{
		 $msg=archive_content($pageid,0,$db,$prefix);
		 header("location:$targetpage?msg=$msg");
		}
		break;
	case 'a':
	$msg=archive_content($pageid,$state,$db,$prefix);
	header("location:$targetpage?msg=$msg");
	break;	
}

######### Message ##########
switch($msg)
{
	case 'e':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle">Member is successfully Added!</div>';
	break;
	case 'e':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Member is successfully Edited!</div>';
	break;	
	case 'd':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Member is successfully Deleted!</div>';
	break;
	case 'ad':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> New Member Added Successfully</div>';
	break;
	case 'a':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Member is successfully Unarchived!</div>';
	break;
	case 'un':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Member is successfully Archived!</div>';
	break;
	case 'block':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Member is successfully Blocked!</div>';
	break;
	case 'unblock':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Member is successfully Unblocked!</div>';
	break;
	case 'msent':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Email is successfully Sent.</div>';
	break;
}

########## Functions ##################

function delete_content($id,$db,$prefix)
{
	$mysql="delete from ".$prefix."member_products where member_id='$id'";
	$db->insert($mysql);

	// Delete member
	$db->insert("delete from ".$prefix."members where id ='$id'");

	
	return $msg='d'; 
		
}

function archive_content($id,$state,$db,$prefix)
{

	$db->insert("update ".$prefix."members set published='$state' where id ='$id'");
	if($state==1)
		return $msg='a';
	else 
		return $msg='un';
}

########## pagination ###########





$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "firstname",
	"field3" => "username",
	"field4" => "date_joined",
	"field5" => "email"
	);

	$tbl_name=$prefix."members";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	$query = "select count(*) as num from $tbl_name";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];

	/* Setup vars for query. */
	 	//your file name  (the name of this file)
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
		$dir = "DESC";
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
	 
	function getContentComments($filename, $linkproduct, $prefix){

		if($linkproduct == 'blog')
		{
			include_once("../common/database.class.php");
			include_once("../common/common.class.php");
			$db = new database();
			$common = new common();
			$now_start=date("Y-m-d 00:00:00");
			$now_end=date("Y-m-d 23:59:59");
			$q = "select count(*) as cnt from ".$prefix."comments where type='blog' && page='$filename' && date
			between '$now_start' and '$now_end' && checked=0";
			
			$r22 = $db->get_a_line($q);
			$count1 = $r22[cnt];
			
			$q_total = "select count(*) as cnt from ".$prefix."comments where type='blog' && page='$filename'";
			$r_total = $db->get_a_line($q_total);
			$total = $r_total[cnt];
			
			 $q_unread = "select count(*) as cnt from ".$prefix."comments where type='blog' && page='$filename' && checked=0";
			$r_unread = $db->get_a_line($q_unread);
			$unread = $r_unread[cnt];
	
			if($unread > 0)
				$unread_img='&nbsp;<img src="../images/admin/unread.png" alt="unread" align="absmiddle"  title="'.$unread.' Unread Comments" />';		
			else 
				$unread_img='';
				
			if($total > '0' && $count1 == '0' )
			{
				$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&type=blog">
				<img src="../images/comments2.png" alt="commentsImage" title="Click To Moderate Comments for This Content Page" ></a>
				<br />'.$total.'<span>'.$unread_img.'</span>';
			}
			elseif($total > '0' && $count1 > '0')
			{
				$commentlink='<a href="comment_moderation.php?pageid='.$filename.'&type=blog">
				<img src="../images/comments2.png" alt="commentsImage" title="Click To Moderate Comments for This Content Page" ></a>
				<br />'.$total.' ('.$count1.') <span class="new">New</span><span>'.$unread_img.'</span>';
			}
			else{$commentlink="None";}
		}
		else
		{
			$commentlink = "None";
		}

		return $commentlink;
	}
	###### end pagination ##########

	$content = '
	 <table width="904"><tr><td width="50%" valign="bottom"><span style="float:left;">Select Number of rows per page:</span><form name="limitForm" action="'.$targetpage.'#pagination" method="GET" style="float:left;">
	 <select name="limit" onchange="document.limitForm.submit()" style="width:100px;"><option value="10"'.isSelected(10,$limit).'>10</option><option value="25"'.isSelected(25,$limit).'>25</option> 
	 <option value="50" '.isSelected(50,$limit).'>50</option><option value="100" '.isSelected(100,$limit).'>100</option></select>
	 </form></td>
	 <td width="50%" align="right">';
	 
	 $content .= '<div class="buttons">
		 	 <a href="?action=export" >Export members (CSV)</a></div>
		 </div>';
	
	 if($obj_pri->canAdd($pageurl))
	 {
		 $content .= '<div class="buttons">
		 	 <a href="member_add.php" >Add Member</a></div>
		 </div>';
	 }
	$content .= '		
	 </td></tr>
	 </table> ';

	$content .= '<table id="table" border="0" cellpadding="5" cellspacing="0"
													width="904" bgcolor="#FFFFFF" style="border: #000000 solid 4px;">
													<thead>
														<tr class="list_results_colhead" id="pagination">
															<th title="click to sort by this field" nowrap="nowrap">
															<a href="'.$targetpage.'?col=field1&amp;dir='.getDirection('field1',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">Id</a>
															<span
																class="'.getCssClass('field1',$dir,$fieldName).'">&nbsp;</span>
															</th>
															
															<th title="click to sort by this field" align="left">
															<a href="'.$targetpage.'?col=field2&amp;dir='.getDirection('field2',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
															Member Name</a>
															<span class='.getCssClass('field2',$dir,$fieldName).'>&nbsp;</span></th>
															<th title="click to sort by this field" align="left">
															<a href="'.$targetpage.'?col=field3&amp;dir='.getDirection('field3',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
															User Name</a>
															<span class='.getCssClass('field3',$dir,$fieldName).'>&nbsp;</span></th>
															<th >
															<a href="'.$targetpage.'?col=field4&amp;dir='.getDirection('field4',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
															Date Added</a><span
																class="'.getCssClass('field4',$dir,$fieldName).'">&nbsp;</span>
																</th>
															<th >
															<a href="'.$targetpage.'?col=field5&amp;dir='.getDirection('field5',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
															Email</a>
															<span class="'.getCssClass('field5',$dir,$fieldName).'">&nbsp;</span>
															
															
															</th>
															<th>Status</th>
															<th>Reported</th>
															<th><span>Options</span><span class=>&nbsp;</span></th>
															
														</tr>
													</thead>';

	$i = 0;

	while($row = mysql_fetch_array($result))
	{
		if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
			
			
		if($row["date_joined"]){
			$dateAdded= date("M d, Y",strtotime($row["date_joined"]));
		}else {
			$dateAdded= "-";
		}
	if($row["status"] == '1')
	{
		$status = "Affiliate";
	}
	elseif($row["status"] == '2')
	{
		$status = "Member";
	}
	elseif($row["status"] == '3')
	{
		$status = "JV Partner";
	}
	
	if($obj_pri->canDelete($pageurl)){
	if($row['published']==0){
			$delete='<a href="'.$targetpage.'?act=d&pageid='.$row['id'].'"><img src="../images/cross-gray.png" alt="editImage" title="Click to delete this member" Onclick="return confirm('."'".'Are you sure! you want to delete this member?'."'".')"></a>';
			}
			else
			{
			$delete='<a href="'.$targetpage.'?act=d&pageid='.$row['id'].'"><img src="../images/crose.png" alt="editImage" title="Click to delete this member" Onclick="return confirm('."'".'Are you sure! you want to delete this member?'."'".')"></a>';
			}	
		}
		else{
			if($row['published']==0){		
				$delete='<a href="'.$targetpage.'?act=a&pageid='.$row['id'].'&state=1"><img src="../images/cross-gray.png" alt="editImage" title="Click to unarchive this member"></a>';
			}
			else
				$delete='<a href="'.$targetpage.'?act=a&pageid='.$row['id'].'&state=0"><img src="../images/crose.png" alt="editImage" title="Click to archive this member"></a>';
			}

                        if($row['report_abuse']=='1')
                            $report_abuse='<span style="padding:0 5px;""><img src="../images/admin/report.png" border="0" alt="Report Abuse"></span>'; 
                        else
                            $report_abuse="";

                        
		if($row['is_block']=='0') 
		$option_is_block='<a href="'.$targetpage.'?block_member='. $row['id'] .'">
			<img src="../images/admin/active.png" border="0" alt="Active"></a>';
		else 
		$option_is_block='<a href="'.$targetpage.'?unblock_member='. $row['id'] .'">
			<img src="../images/admin/deactive.png" border="0" alt="Block"></a>';	
		
		$content .='<tr class="'.$class.'" onmouseover="this.style.backgroundColor=\'#f1ca47\'" onmouseout="this.style.backgroundColor=\'\'"> ' ;
		$content .='<td align="center" valign="middle" >'.$row['id'].'</td>
														
														<td align="left" valign="middle" >
														<a href="signuplink.php?randomstring='. $row["randomstring"] .'&iframe=true&width=600px&height=150px" onclick="return confirm(\'Are you sure you want to send an email?\');">
														'.$row["firstname"].' '.$row["lastname"].'</a></td></td>
														<td align="left" valign="middle" >
														<a href="amlogin.php?mrand='.$row["randomstring"].'" target="_blank">'.$row["username"].'</a></td>
														
														<td align="left" valign="middle" nowrap="nowrap">'.$dateAdded.'</td>
														<td align="left" valign="middle" nowrap><a href="single_mail.php?mid='.$row["id"].'">'.
														 $row["email"]
														  .'</td>
														<td align="center" valign="middle">
														'.$status.'
														</td>
                                                                                                                
                                                                                                                <td align="center" valign="middle">
														'.$report_abuse.'
														</td>


														
														<td align="left" valign="middle" nowrap="nowrap">
														<span style="width:auto;padding:0 5px;"><a href="reports/member-detail.php?member_id='.$row['id'].'"><img src="../images/view_detail.png" alt="DetailImage" width="16" height="16" title="Click to view details" ></a> </span>
														<span style="width:auto;padding:0 5px;"><a href="member_edit.php?act=e&id='.$row['id'].'"><img src="../images/editIcon.png" alt="editImage" title="Click to edit this member" ></a></span>
                                                                                                                <span style="width:auto;padding:0 5px;">'.$option_is_block.'</span>
                                                                                                                <span style="width:auto;padding:0 5px;">'.$delete.'</span>';												'
														</td>
														
													</tr>';



		$i++ ;}
	  
		if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
		
				
		$content .= '</table><div class="pages">';
		$content .= '<div class="totalpages">Total: '. $startrec .' - '. $totalrec .' of  '.$total_pages.'</div>';
		$content .= '<div class="pager">'.$pagination.'&nbsp;</div></div>';
		
		$content .= '<div><a href="#top" style="text-align:center;">Move to top</a></div>';
	  		   

		$Content=preg_replace("/{{(.*?)}}/e","$$1",$Content);
		echo $Content;



/*if($act == "d")
{
	// If member has paid products then delete them
	$mysql="delete from ".$prefix."member_products where member_id='$id'";
	$db->insert($mysql);

	// Delete member
	$db->insert("delete from ".$prefix."members where id ='$id'");
	$msg = "d";
}*/




########## pagination ###########

/*$q = "select count(*) as cnt from ".$prefix."members";
$r = $db->get_a_line($q);
$count = $r[cnt];
$records=50;
$links = "member_view.php?";

if($page=="")
{
	$page=1;
}

$start=($page-1)*$records;
$Content=$common->print_page_break3($db,$Content,$count,$records,$links,$page);

########## pagination ###########

$ChangeColor = 1;
$ToReplace = "";

$GetMembers = $db->get_rsltset("select * from ".$prefix."members order by id asc limit $start, $records");

for($i = 0; $i < count($GetMembers); $i++)
{
	if($ChangeColor == 0)
	{
		$bgcolor = "#eaeaea";
		$ChangeColor = 1;
	}
	else
	{
		$bgcolor = "#FFFFFF";
		$ChangeColor = 0;
	}
	@extract($GetMembers[$i]);

	if($status == '1')
	{
		$status = "Affiliate";
	}
	elseif($status == '2')
	{
		$status = "Member";
	}
	elseif($status == '3')
	{
		$status = "JV Partner";
	}
	$ToReplace .= preg_replace($Ptn,"$$1",$SelectedContent);
}
$Content = preg_replace($Pat,$ToReplace,$Content);


/****************************************************************************/



/*if($msg == "a")
{
	$Message = "Member is Successfully Added";
}
else if($msg == "e")
{
	$Message = "Member is Successfully Edited";
}
else if($msg == "d")
{
	$Message = "Member is Successfully Deleted";
}
*/

include_once("footer.php");

if($csv_saved){
?>
<script>
$(document).ready(function (){
window.location.replace('<?php echo $csv_file ?>');
}
);
</script>

<?php } ?>