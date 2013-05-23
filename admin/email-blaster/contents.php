<?php 
include_once("../session.php");
include_once("../header.php");
$Title = "Email Content";
$tbl_name=$prefix."email_content";		//your table name
$targetpage = "contents.php"; 	//your file name  (the name of this file)
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
	case 's':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Content is Successfully Saved</div>';
	break;
	
	case 'd':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Content is Successfully Deleted</div>';
	break;
	case 'ar':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Content is Successfully Published </div>';
	break;
	case 'un':
		$Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Content is Successfully Unpublished</div>';
	break;
}
if($_GET["msg"]=="send"){
        $Message = '<div class="success"><img src="../../images/tick.png" align="absmiddle"> Mail Send Successfully!</div>';
}
############# Operation ##################
function delete_content($id,$db,$prefix)
{
	
	$db->insert("delete from ".$prefix."email_content where id ='$id'");
	$db->insert("delete from ".$prefix."email_group where content_id ='$id'");
	return $msg='d'; 
		
}
function archive_content($id,$state,$db,$prefix)
{
	$db->insert("update ".$prefix."email_content set published='$state' where id ='$id'");
	if($state==1)
		return $msg='ar';
	else 
		return $msg='un';
}
################## PAGINATION ########################
$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "firstname",
	"field3" => "email",
	"field4" => "username",
	"field5" => "telephone",
	"field6" => "status",
	"field7" => "DATEDIFF(NOW(),FROM_UNIXTIME(last_login))"
	);
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	$sql = "select count(*) as total from $tbl_name;";
	$row_total= $db->get_a_line($sql);
	$total_pages = $row_total['total'];
	/* Setup vars for query. */
	if(isset ($_GET["limit"])){
		$limit = $_GET["limit"];
	}else{
		$limit = 25; 								//how many items to show per page
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
	$sql = " SELECT m.*, 
	(select count(id) from ".$prefix."email_group where content_id=m.id) as total
	from $tbl_name m  order by $field $dir  limit $start,$limit";
	$result = $db->get_rsltset($sql);
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
	?>
<link
	href="css/theme.css" rel="stylesheet" type="text/css" />
<?php echo $Message;?>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p style="font-weight: bold"><?php echo $Title ?></p>
<div class="buttons">
<a href="add-content.php">Add Group Content</a>
</div>
<a id="pagination"></a>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%" valign="middle"><span style="float: left;">Select
		Number of rows per page:</span>
		<form name="limitForm" action="<?php echo  $targetpage?>#pagination"
			method="GET" style="float: left;"><select name="limit"
			onchange="document.limitForm.submit()" style="width: 100px;">
			<option value="10" <?php echo isSelected(10,$limit)?>>10</option>
			<option value="25" <?php echo isSelected(25,$limit)?>>25</option>
			<option value="50" <?php echo isSelected(50,$limit)?>>50</option>
			<option value="100" <?php echo isSelected(100,$limit)?>>100</option>
		</select> <input type="hidden" name="filter" value="all"></form>
		</td>
		<td></td>
	</tr>
</table>
<div id="grid">
<table class="notsortable" width="95%" border="0" align="center"
	cellpadding="2" cellspacing="0">
	<thead>
		<tr>
			<th width="4"><a
				href="<?php echo $targetpage?>&col=field1&amp;dir=<?php echo getDirection('field1',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
			Id</a><span
				class="<?php echo getCssClass('field1',$dir,$fieldName);?>">&nbsp;</span>
			</th>
			<th align="left" width="300"><a
				href="<?php echo $targetpage?>&col=field2&amp;dir=<?php echo getDirection('field2',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
			Subject</a><span
				class="<?php echo getCssClass('field2',$dir,$fieldName);?>">&nbsp;</span>
			</th>
			<th align="center" ><a
				href="<?php echo $targetpage?>&col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
			Sent</a><span
				class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>
			</th>
			<th align="center" ><a
				href="<?php echo $targetpage?>&col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
			Pending</a><span
				class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>
			</th>
			
			<th align="left"><a
				href="<?php echo $targetpage?>&col=field4&amp;dir=<?php echo getDirection('field4',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
			Created Date</a><span
				class="<?php echo getCssClass('field4',$dir,$fieldName);?>">&nbsp;</span>
			</th>
			<th align="right" style="text-align:right;"><a
				href="<?php echo $targetpage?>&col=field5&amp;dir=<?php echo getDirection('field5',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
			Operation</a><span
				class="<?php echo getCssClass('field5',$dir,$fieldName);?>">&nbsp;</span></th>
		</tr>
	</thead>
	<?php
	$i=0;
	if($total_pages > 0)
	{
		foreach ($result as $row){
			if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
			if($obj_pri->canDelete($pageurl))
		{
			
			$delete=' <a href="'.$targetpage.'?act=d&id='.$row['id'].'"><img src="../../images/crose.png" alt="delete" title="Click to delete this content" Onclick="return confirm('."'".'Are you sure! you want to delete this content?'."'".')"></a>';
			
		}
		
			
			if($row['published']==0){		
				$published=' <a href="'.$targetpage.'?act=a&id='.$row['id'].'&state=1"><img src="../../images/admin/unpublished.png" alt="unpublished" title="Click to publish this content"></a>';
				
			}
			else{
				$published=' <a href='.$targetpage.'?act=a&id='.$row['id'].'&state=0><img src="../../images/admin/published.png" alt="published" title="Click to unpublish this content"></a>';}
				
			
 	
			
			
			
			
			?>
	<tr class="<?php echo $class?>">
		<td valign="middle"><?php echo $row['id']?></td>
		<td valign="middle" style="text-align: left;"><?php echo stripslashes($row['subject'])?></td>
		<td valign="middle" style="text-align: center;">
                <?php
                        if($row["group_id"]>0){
                            $sql = "select count(mail_status) as total_members from ".$prefix."email_blaster_group_members where mail_status=1 and content_id= $row[id] and group_id=".$row["group_id"];
                            $sent = $db->get_a_line($sql);
                            echo $sent['total_members'];
                        } else {
                            echo "0";
                        }
                ?>
          </td>
		<td valign="middle" style="text-align: center;">
                    <?php
                        if($row["group_id"]>0){
                            $sql = "select count(mail_status) as total_members from ".$prefix."email_blaster_group_members where mail_status=0 and content_id= $row[id] and group_id=".$row["group_id"];
                            $pending = $db->get_a_line($sql);
                            echo $pending['total_members'];
                        } else {
                            echo "0";
                        }
                ?>
          </td>
		<td valign="middle"><?php  echo $row['created_date'];?></td>
        <td valign="middle" colspan="1" style="text-align:right;">
                    <?php 
				if($row['published']==1){?>
                   <a href="mailtopendings.php?gid=<?php echo $row["group_id"]?>&cid=<?php echo $row["id"]?>"><img src="../../images/admin/sendemail.gif" alt="Send email" title="Click to send email" ></a>&nbsp;
        <?php } ?>
    	<a href="add-content.php?id=<?php echo $row['id']?>"><img src="../../images/editIcon.png" alt="editImage" title="Click to edit this content" ></a><?php echo $published;?>&nbsp;<?php echo $delete?>     </td>
	</tr>
	<?php
	$i++; }
	if($start==0 && $totalrec > 0){$totalrec=$startrec+$i; $startrec=$start+1;}
	else {$startrec=$start;$totalrec=$startrec+$i;}
	} else {
		echo "<tr><td colspan='9' style='text-align:center'>Sorry no record forund</td></tr>";
	}
	?>
</table>
<div class="pages">
<div class="totalpages">Total: <?php if($startrec >0 ) echo $startrec; else echo "0"; ?>
- <?php if($totalrec >0 ) echo $totalrec; else echo "0"; ?> of <?php echo $total_pages;?></div>
<div class="pager"><?php echo $pagination?>&nbsp;</div>
</div>
<div><a href="#top" style="text-align: center;">Move to top</a></div>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
	<?php  include_once("../footer.php");?>