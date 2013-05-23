<?php
include_once("session.php");
include_once("header.php");
$Title = "Product Short Url Management";
$targetpage = "product-shorturl.php"; 	//your file name  (the name of this file)
$tbl_name=$prefix."products_short";		//your table name

######### Actions  ##########


switch($act)
{
    
	case 'd':
		if($obj_pri->canDelete($pageurl))
		{
			$msg=delete_content($id,$db,$prefix);
			header("location:$targetpage?pid=$pid&msg=$msg");
		}
		else
		{
		 $msg=archive_content($id,0,$db,$prefix);
		 header("location:$targetpage&msg=$msg");
		}
	break;
	case 'ar':
		$msg=archive_content($id,$state,$db,$prefix);
		header("location:$targetpage?pid=$pid&msg=$msg");
	break;	
}

######### Message ##########
switch($msg)
{
	case 'add':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Short Url is successfully Added!</div>';
	break;
	case 'edit':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Short Url is successfully Edited!</div>';
	break;	
	case 'd':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Short Url is Successfully Deleted!</div>';
	break;
	case 'ar':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Short Url is successfully Unarchived!</div>';
	break;
	case 'un':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Short Url is successfully Archived!</div>';
	break;
}
########## Functions ##################

function delete_content($id,$db,$prefix)
{
	$db->insert("delete from ".$prefix."products_short where id ='$id'");
	return $msg='d'; 
	
}

function archive_content($id,$state,$db,$prefix)
{
	$sql="update ".$prefix."products_short set published='$state' where id ='$id'";
	
	$db->insert("$sql");
	if($state==1)
		return $msg='ar';
	else 
		return $msg='un';
}
################## PAGINATION ########################
$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "short_url",
	"field3" => "redirect_url",
	//"field4" => "discount"
	);

	
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	
	$query = "select count(*) as cnt from $tbl_name where product_id ='$pid'";
	$rs_total=mysql_query($query);
	$total_pages = mysql_fetch_array($rs_total);
	$total_pages = $total_pages['cnt'];

	/* Setup vars for query. */
	
	if(isset ($_GET["limit"])){
		$limit = $_GET["limit"];
	}else{
		$limit = 10; 			//how many items to show per page
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
	

	$sql = "select *  from $tbl_name where product_id ='$pid' order by $field $dir  limit $start,$limit";
	$result = mysql_query($sql);

	

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
        
        function get_product_type($db,$prefix,$pid)
        {
                $q = "select prodtype from ".$prefix."products where id='$pid'";
                $r = $db->get_a_line($q);
                $prodtype = $r['prodtype'];
                return $prodtype;

        }     
        
$prodtype = get_product_type($db,$prefix,$pid);

?>
<link rel="stylesheet" href="../common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong><?php echo $Title ?></strong></p>
<br>
<style>
.textarea{
    background-color: #FFF9DB;
    border: 1px solid #FAE572;
    font-family: tahoma;
    font-size: 13px;
    height: 53px;
    white-space: nowrap;
    width: 541px;
}
</style>
<a id="pagination"></a> 
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="50%" valign="middle">
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
                
	 <?php  if($obj_pri->canAdd($pageurl)){?>
                  <div class="buttons">
	 	 <a href="add-product-shorturl.php?pid=<?php echo $pid?>">Add New URL</a>
                  </div>
	<?php } ?>
             <div class="buttons">
                     <a href="paid_products.php">Products Listings</a> 
                 </div>
             	   
	</td>
	 </tr>
	
	 </table>
	
  
 
 <div id="grid"> 
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <thead>   
  <tr>
  	<th width="4">
  	<a href="<?php echo $targetpage?>?col=field1&amp;dir=<?php echo getDirection('field1',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Id</a><span class="<?php echo getCssClass('field1',$dir,$fieldName);?>">&nbsp;</span></th>
    <th align="left">
    <a href="<?php echo $targetpage?>?col=field2&amp;dir=<?php echo getDirection('field2',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	URL's</a><span class="<?php echo getCssClass('field2',$dir,$fieldName);?>">&nbsp;</span>
    </th>
    <!--<th align="left">
    <a href="<?php echo $targetpage?>?col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Nickname</a><span class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>
    </th> 
    <th>URL</th>-->
    <th>Edit/Delete</th>
  </tr>
  </thead>
 <?php
$i=0;
 while($row=mysql_fetch_array($result)){
 if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
 	
 	$list_url = '<a href='.$http_path.'/yes/'.$row['product_id'].'/'.$row['short_url'].' target = _blank >'.$http_path.'/yes/'.$row['product_id'].'/'.$row['short_url'].'</a>';
 	
 	if($obj_pri->canDelete($pageurl)){
	if($row['published']==0){
			$delete='<a href="'.$targetpage.'?act=d&pid='. $pid .'&id='.$row['id'].'">
			<img src="../images/cross-gray.png" alt="editImage" title="Click to delete this URL" Onclick="return confirm('."'".'Are you sure! you want to delete this URL?'."'".')">
			</a>';
			}
			else
			{
			$delete='<a href="'.$targetpage.'?act=d&pid='. $pid .'&id='.$row['id'].'">
			<img src="../images/crose.png" alt="editImage" title="Click to delete this URL" Onclick="return confirm('."'".'Are you sure! you want to delete this URL?'."'".')">
			</a>';
			}	
		}
		else{
			if($row['published']==0){		
				$delete='<a href="'.$targetpage.'?act=ar&pid='. $pid .'&id='.$row['id'].'&state=1">
			<img src="../images/cross-gray.png" alt="editImage" title="Click to unarchive this URL">
			</a>';
			}
			else
				$delete='<a href="'.$targetpage.'?act=ar&pid='. $pid .'&id='.$row['id'].'&state=0">
			<img src="../images/crose.png" alt="editImage" title="Click to archive this URL">
			</a>';
			}	
 		
  	
 	?>
  <tr class="<?php echo $class?>">
  	<td valign="middle"><?php echo $row['id']?></td>
    <td valign="middle" style="text-align:left;">
    
    <span style="color:green">Redirect URL: <?php echo $row['redirect_url']?></span>
    <BR>
    <span style="font-weight:bold">New URL:</span><br><?php echo $list_url;?><br>
    <?php echo $list_url;?>/affiliate_name<br>
    <?php if($prodtype!='Clickbank'){
    echo $list_url;?>/affiliate_name/coupon_code<br>
    <?php } ?>
    <textarea class="textarea">
    <?php echo $http_path.'/yes/'.$row['product_id'].'/'.$row['short_url'].''?>&nbsp;
    <?php echo $http_path.'/yes/'.$row['product_id'].'/'.$row['short_url'].'/'?>affiliate_name &nbsp;
    <?php if($prodtype!='Clickbank') { echo $http_path.'/yes/'.$row['product_id'].'/'.$row['short_url'].'/'?>affiliate_name/coupon_code <?php } ?>&nbsp;
    </textarea>  
    
    
    </td>
    <!-- <td valign="middle"><?php echo $row['nickname']?></td>
    <!--  <td valign="middle"><?php echo $http_path.'/likes/'.$row['nickname']?></td>-->
    
    <td valign="middle">
	    <div style="float:left;padding-left:16px;">
			<a href="add-product-shorturl.php?pid=<?php echo $pid ?>&id=<?php echo $row['id']?>">
				<img src="../images/editIcon.png" alt="editImage" title="Click to edit this URL" >
		</a> </div>
		<div style="float:left;padding-left:16px;">
    <?php echo $delete;?>
    </div></td>
  </tr>
  
<?php

 $i++;}
 if($start==0 && $totalrec > 0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
 ?>
</table>

		<div><a href="#top" style="text-align:center;">Move to top</a>
</div>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>

<?php include_once("footer.php");?>
