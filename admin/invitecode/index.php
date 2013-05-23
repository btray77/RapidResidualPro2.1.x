<?php
include "../session.php";
include "../header.php";

$Title = "JV Signup/Affilate Signup Invite code";
$targetpage = "index.php"; 	//your file name  (the name of this file)
$tbl_name=$prefix."invitecode";		//your table name

############# Actions ##################
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
	case 'invite':
		$msg=setting_invite_code($invitecode,$db,$prefix);
		header("location:$targetpage?msg=$msg");
	break;
}
############# Messages ##################
switch($msg)
{
	case 'add':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Invite Code is successfully Added</div>';
	break;
	case 'edit':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Invite Code is successfully Edited</div>';
	break;	
	case 'd':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Invite Code is successfully Deleted</div>';
	break;
	case 'ar':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Invite Code is successfully Unarchived</div>';
	break;
	case 'un':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Invite Code is successfully Archived</div>';
	break;
	case 'i':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Invite Code status is successfully changed! </div>';
	break;
}
############# Operation ##################
function setting_invite_code($invitecode,$db,$prefix){
	$db->insert("update  ".$prefix."site_settings set 	affiliate_invite_code = '$invitecode'");
	return $msg='i';
}

function delete_content($id,$db,$prefix)
{
	
	$db->insert("delete from ".$prefix."invitecode where id ='$id'");
	
	return $msg='d'; 
		
}

function archive_content($id,$state,$db,$prefix)
{

	$db->insert("update ".$prefix."invitecode set published='$state' where id ='$id'");
	if($state==1)
		return $msg='ar';
	else 
		return $msg='un';
}


########## pagination ###########

$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "code",
	"field3" => "status"
	
	);

	
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	$sql = "select affiliate_invite_code from ". $prefix ."site_settings";
	$row_invite=$db->get_a_line($sql);
	
	
	$query = "select count(*) as cnt from $tbl_name";
	$row_total=$db->get_a_line($query);
	$total_pages = $row_total['cnt'];

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

	$sql = "select * from $tbl_name order by $field $dir  limit $start,$limit";
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

########## pagination ###########


?>
<?php echo $Message ?>
<link rel="stylesheet" href="../common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong><?php echo $Title ?></strong></p>


<br>



<a id="pagination"></a> 
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="40%" valign="middle">
	<span style="float:left;">Select Number of rows per page:</span>
	<form name="limitForm" action="<?php echo  $targetpage?>#pagination" method="GET" style="float:left;">
	  <select name="limit" onchange="document.limitForm.submit()" style="width:100px;">
	 	<option value="10" <?php echo isSelected(10,$limit)?>>10</option>
	 	<option value="25" <?php echo isSelected(25,$limit)?>>25</option> 
	    <option value="50" <?php echo isSelected(50,$limit)?>>50</option>
	    <option value="100" <?php echo isSelected(100,$limit)?>>100</option>
	  </select>
	 </form>
	 </td>
	 <td>
	 <form name="invitecode" action="<?php echo  $targetpage?>#pagination" method="GET" style="float:left;">
	  Activate Affiliate Invite Code
		
	 <input type="radio" value="1" <?php if($row_invite['affiliate_invite_code']==1) echo 'checked="checked"';?> name="invitecode" onclick="document.invitecode.submit();"> Yes 
	 <input type="radio" value="0" <?php if($row_invite['affiliate_invite_code']==0) echo 'checked="checked";'?> name="invitecode" onclick="document.invitecode.submit();"> No
	 <input type="hidden" value="invite" name="act"> &nbsp;&nbsp;&nbsp;
	 <div class="tool">	
		<a href="" class="tooltip" title="The affiliate signup invite code is optional and can be turned on or off by 
		checking the appropriate option. The JV Partner invite code is always required and cannot be turned off. 
		Only the affiliate signup invite code is optional.">
			<img src="../../images/toolTip.png" alt="help"/ height="20" align=absmiddle>
		</a>
		</div>
	 
	 </form>
	  </td>
	 <td>
	 
		 <?php if($obj_pri->canAdd($pageurl)){ ?>
		 	<div class="buttons">
		 		<a href="addinvitecode.php">Add Invite Code</a>
		 	</div>
		 <?php }?>
	 	<td>
	 </tr>
	
	 </table>
	
  
 
 <div id="grid"> 
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <thead>   
  <tr>
  	<th>
  	<a href="<?php echo $targetpage?>?col=field1&amp;dir=<?php echo getDirection('field1',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Id</a><span class="<?php echo getCssClass('field1',$dir,$fieldName);?>">&nbsp;</span></th>
    <th width="100" align="left">
    <a href="<?php echo $targetpage?>?col=field2&amp;dir=<?php echo getDirection('field2',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Invite Code</a><span class="<?php echo getCssClass('field2',$dir,$fieldName);?>">&nbsp;</span>
	</th>
    </th>
    <th align="left"  >Invite For</th>
    <th align="left">
     <a href="<?php echo $targetpage?>?col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Status</a><span class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>
    </th>
    
    <th align="left" width="500">Link Example</th>
    <th >Edit/Delete</th>
  </tr>
  </thead>
 <?php
$i=0;
 while($row=mysql_fetch_array($result)){
 if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
			
			
		if($row["date_added"]){
			$dateAdded= date("M d, Y g:i a",strtotime($row["date_added"]));
		}else {
			$dateAdded= "-";
		}
	if($obj_pri->canDelete($pageurl))
		{
			if($row['published']==0){
			$delete='<a href="'.$targetpage.'?act=d&pageid='.$row['id'].'">
			<img src="../../images/cross-gray.png" alt="editImage" title="Click to delete this invite code" Onclick="return confirm('."'".'Are you sure! you want to delete this code?'."'".')">
			</a>';
			}
			else
			{
			$delete='<a href="'.$targetpage.'?act=d&pageid='.$row['id'].'">
			<img src="../../images/crose.png" alt="editImage" title="Click to delete this invite code" Onclick="return confirm('."'".'Are you sure! you want to delete this code?'."'".')">
			</a>';
			}
		}
		else{
			
			if($row['published']==0){		
				$delete='<a href="'.$targetpage.'?act=a&pageid='.$row['id'].'&state=1">
					<img src="../../images/cross-gray.png" alt="editImage" title="Click to unarchive this invite code">
			</a>';
				
			}
			else{
				$delete='<a href='.$targetpage.'?act=a&pageid='.$row['id'].'&state=0">
			<img src="../../images/crose.png" alt="editImage" title="Click to archive this invite code">
			</a>';}
				
			}
 	
 	?>
  <tr class="<?php echo $class?>">
  	<td valign="middle" ><?php echo $row['id']?></td>
    <td valign="middle" style="text-align:left"><?php echo $row['code']?></td>
    <td nowrap="nowrap" style="text-align:left"><?php if($row['invitefor']==1) echo 'JV Signup'; else echo "<span style='color:red'>Affiliate Signup<span>" ?> </td>
    <td valign="middle" style="text-align:left"><?php if($row['status']==0) echo 'Unused'; else echo "<span style='color:red'>Used<span>" ?> </td>
    
    <td valign="middle" style="text-align:left">
	 <?php if($row['invitefor']==1){?>
	 	<a href="<?php echo $http_path?>/jvsign.php?code=<?php echo $row['code'];?>" title="Invite code for JV signup's" target="_blank">
	 		<?php echo $http_path?>/jvsign.php?code=<?php echo $row['code'];?>
	 	</a>
	 <?php } else {?>
	 	<a href="<?php echo $http_path?>/affiliate.php?code=<?php echo $row['code'];?>" title="Invite code for Affiliate Signup's" target="_blank">
	 		<?php echo $http_path?>/affiliate.php?code=<?php echo $row['code'];?>
	 	</a>	
	 <?php }?>   
    
    </td>
    
    <td valign="middle"> 
    <div style="float:left;padding-left:16px;">
    	<a href="addinvitecode.php?id=<?php echo $row['id']?>"> <img src="../../images/editIcon.png" alt="editImage" title="Click to edit this invite code" > </a>
    </div>
   <div style=" float:right;padding-left:10px;"> <?php echo $delete?></div>
    
    </td>
  </tr>
  
<?php
 if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
 $i++;} ?>
</table>
<div class="pages">
		<div class="totalpages">Total: <?php echo $startrec; ?> - <?php echo $totalrec; ?> of  <?php echo $total_pages;?></div>
		<div class="pager"><?php echo $pagination?>&nbsp;</div></div>
		<div><a href="#top" style="text-align:center;">Move to top</a>
</div>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$("a[rel^='prettyPhoto']").prettyPhoto();
		});
</script>
<?php 
include "../footer.php";
?>