<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/view_all_admins.html");
$returncontent=join("",$file);
$tbl_name=$prefix."admin_settings";	
$targetpage = "view_all_admins.php"; 	//your file name  (the name of this file)
// user access control & error handling
switch($act)
{
	case 'd':
		if($obj_pri->getRole() == 2 || $obj_pri->getRole() == 1)
		{
			if($obj_pri->canDelete($pageurl)){
			$msg=delete_content($id,$db,$prefix);
			header("location:$targetpage?msg=$msg");
			}
		}
		else
		{
		 $msg=archive_content($id,0,$db,$prefix);
		 header("location:$targetpage?msg=$msg");
		}
	break;
	case 'ar':
		$msg=archive_content($id,$state,$db,$prefix);
		header("location:$targetpage?msg=$msg");
	break;	
}

if(isset($_GET['msg'])){
	$msg = $_GET['msg'];

	if($msg == "a"){
		$msg = '<div class="success" ><img src="../images/tick.png" align="absmiddle"> Admin is successfully added!</div>';
	}
	else if($msg == "e"){
		$msg = '<div class="success" ><img src="../images/tick.png" align="absmiddle"> Admin is successfully edited!</div>';
	}
	else if($msg == "ac"){
		$msg = '<div class="success" ><img src="../images/tick.png" align="absmiddle"> Admin is successfully activated!</div>';
	}
	else if($msg == "dc"){
		$msg = '<div class="success" ><img src="../images/tick.png" align="absmiddle"> Admin is successfully de-activated!</div>';
	}
else if($msg == "del"){
		$msg = '<div class="success" ><img src="../images/tick.png" align="absmiddle"> Member is successfully deleted!</div>';
	}
}

// UAC end
//echo $_SERVER['QUERY_STRING'];
########## Functions ##################

function delete_content($id,$db,$prefix)
{
	$db->insert("delete from ".$prefix."admin_settings where id ='$id'");
	return $msg='del'; 
	
}

function archive_content($id,$state,$db,$prefix)
{
	$sql="update ".$prefix."admin_settings set status='$state' where id ='$id'";
	
	$db->insert("$sql");
	if($state==1)
		return $msg='ac';
	else 
		return $msg='dc';
}
/*
if(isset($_SERVER['QUERY_STRING']))
{
	 $querystring=$_SERVER['QUERY_STRING'];
}

if($_GET['action'])
{
	echo $action=$_GET['action'];
	echo $state=$_GET['state'];
	echo $id=$_GET['id'];
	$set='`status`='.$state.' where id='.$id;
	$mid = $db->insert_data_id("update  ".$prefix."admin_settings set $set");
	if($state==1)
    	header("Location: view_all_admins.php?msg=ac");
    else	
    	header("Location: view_all_admins.php?msg=dc");
}
*/
$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "username",
	"field3" => "lastlogin",
	"field4" => "role"
	);

		//your table name
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

	}
	else{
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


	$content = '<a id="pagination"></a><div>
	 <table width="100%"><tr><td width="50%" valign="bottom"><span style="float:left;">Select Number of rows per page:</span><form name="limitForm" action="view_all_admins.php#pagination" method="GET" style="float:left;">
	 <select name="limit" onchange="document.limitForm.submit()" style="width:100px;"><option value="10"'.isSelected(10,$limit).'>10</option><option value="25"'.isSelected(25,$limit).'>25</option> 
	 <option value="50" '.isSelected(50,$limit).'>50</option><option value="100" '.isSelected(100,$limit).'>100</option></select>
	 </form></td><td width="50%" align="right">';
	if($obj_pri->canAdd($pagename))
	{ 
	$content.= '
	 <div class="buttons">
	 <a href="admin_add.php"  >Add New Admin User</a></div>';
	}
	$content .= ' </td></tr>
	 </table>
	 </div>';
	$content .= '<table id="table" border="0" cellpadding="5" cellspacing="0"
													width="874" bgcolor="#FFFFFF" style="border: #000000 solid 4px;">
				<thead>
					<tr class="list_results_colhead" id="pagination">
						<th title="click to sort by this field" >
							<a href="'.$targetpage.'?col=field1&amp;dir='.getDirection('field1',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">Id</a>
							<span class="'.getCssClass('field1',$dir,$fieldName).'">&nbsp;</span>
						</th>
						<th title="click to sort by this field" align="left">
							<a href="'.$targetpage.'?col=field2&amp;dir='.getDirection('field2',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
							User Name</a><span class='.getCssClass('field2',$dir,$fieldName).'>&nbsp;</span>
						</th>
						<th align="left">
						<a href="'.$targetpage.'?col=field3&amp;dir='.getDirection('field3',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
							Web Master Email</a><span class='.getCssClass('field3',$dir,$fieldName).'>&nbsp;</span>
						
						</th>
						<th align="left">
						<a href="'.$targetpage.'?col=field4&amp;dir='.getDirection('field4',$dir,$fieldName).'&amp;limit='.$limit.'#pagination">
							Role</a><span class='.getCssClass('field4',$dir,$fieldName).'>&nbsp;</span>
						</th>
						<th align="left"><span>Last Login Time</span><span class=>&nbsp;</span></th>';
						if($obj_pri->canEdit($pagename))
						{ 
						$content .= '<th><span>Edit</span><span class=>&nbsp;</span></th>';
						}
						if($obj_pri->getRole() == 2 || $obj_pri->getRole() == 1)
						{ 	
						$content .= '<th>Status</th>';
						}
					$content .= '</tr>
				</thead>';



	$i = 0;

	while($row = mysql_fetch_array($result))
	{
		if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
			
			
		if($row["lastlogin"]){
			$lastLogin= date("M d, Y g:i a",$row["lastlogin"]);
		}else {
			$lastLogin= "-";
		}
		switch($row['role'])
		{
			case 1:
					$role='Super Administrator';
				break;
			case 2:
					$role='Administrator';
				break;
			case 3:
					$role='Content Management Admin';
				break;
			case 4:
					$role='Product Manamement Admin';
				break;
			case 5:
					$role='Help Desk and Member Management';
				break;
			case 6:
					$role='Affilate Manager Privileges';
				break;
                        case 7:
					$role='Design Admin';
				break;
			default:
				$role='N/A';
				break;						
		}
		
		if($row['status']=='1'){
			if($row['role']==1 && $obj_pri->getRole()==1)
				{
					$status='<a href="'.$targetpage.'?act=ar&id='. $row['id'] .'&state=0">
						<img src="../images/admin/active.png" border="0" alt="Active"></a>';
				}
			else if($row['role'] >1 && ($obj_pri->getRole() == 2) || $obj_pri->getRole() == 1) 
				{
				$status='<a href="'.$targetpage.'?act=ar&id='. $row['id'] .'&state=0">
						<img src="../images/admin/active.png" border="0" alt="Active"></a>';
				}
			}
		else { 
			if($row['role']==1 && $obj_pri->getRole()==1)
				{
					$status='<a href="'.$targetpage.'?act=ar&id='. $row['id'] .'&state=1">
					<img src="../images/admin/deactive.png" border="0" alt="Block"></a>';
				}
			else if($row['role'] >1 && ($obj_pri->getRole() == 2) || $obj_pri->getRole() == 1) 
				{
					$status='<a href="'.$targetpage.'?act=ar&id='. $row['id'] .'&state=1">
					<img src="../images/admin/deactive.png" border="0" alt="Block"></a>';
				}
			}
			
		
		$content .= '<tr class="'.$class.'" onmouseover="this.style.backgroundColor=\'#f1ca47\'" onmouseout="this.style.backgroundColor=\'\'"> ' ;



			
		$content .='<td align="center" valign="middle" >'.$row['id'].'</td>
						<td align="left" valign="middle">'.$row["username"].'</td>
						<td align="left" valign="middle">'.$row["webmaster_email"].'</td>
						<td align="left" valign="middle">'.$role.'</td>
						<td align="left" valign="middle">'.$lastLogin.'</td>';
				if($obj_pri->canEdit($pagename))
				{ 				
				$content .='<td align="center" valign="middle">';
					if($row['role']==1 && $obj_pri->getRole()==1){
					$content .='<a href="admin_edit.php?id='.$row['id'].'"><img src="../images/editIcon.png" alt="editImage" title="Click To Edit this admin"></a>';
					}
					else if($row['role'] >1 && ($obj_pri->getRole() == 2) || $obj_pri->getRole() == 1) {
				$content .='<a href="admin_edit.php?id='.$row['id'].'"><img src="../images/editIcon.png" alt="editImage" title="Click To Edit this admin"></a>';
						}
				$content .='</td>';
				}	
				if($obj_pri->getRole() == 2 || $obj_pri->getRole() == 1)
				{ 					
				$content .='<td align="left" valign="middle">'.$status.' &nbsp;';
				if($row['role'] >1){ 
				if($obj_pri->canDelete($pageurl)){
				$content .='<a href="'.$targetpage.'?act=d&id='. $row['id'] .'"><img src="../images/crose.png" border="0" alt="Delete" Onclick="return confirm('."'".'Are you sure! you want to delete this Member?'."'".')"></a>';				}
				}
				$status='';
				$content .='</td>';
				}
				$content .='</tr>';



		$i++ ;}
		if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
		
				
		$content .= '</table><div class="pages">';
		$content .= '<div class="totalpages">Total: '. $startrec .' - '. $totalrec .' of  '.$total_pages.'</div>';
		$content .= '<div class="pager">'.$pagination.'&nbsp;</div></div>';
		$content .= '<div><a href="#top" style="text-align:center;">Move to top</a></div>';
		 





		$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
		echo $returncontent;




		include_once("footer.php");

		?>
