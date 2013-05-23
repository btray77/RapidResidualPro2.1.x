<?php
include_once("../session.php");
include_once("../header.php");
$GetFile = file("../../html/admin/sales.html");
$Content = join("",$GetFile);
$Title = "Member Login Information";

$targetpage = "member-detail.php"; 	//your file name  (the name of this file)
$tbl_name=$prefix."member_session_archive";		//your table name

$member_id = (int) $_GET["member_id"];

$fieldNamesArray = array(
	"field1" => "member_id",
	//"field2" => "product_id",
	//"field3" => "banner_url",
	//"field4" => "discount"
	);

	
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	
		$query = "select count(*) as cnt from $tbl_name";
	$rs_total=mysql_query($query) or die(mysql_error());
	$total_pages = mysql_fetch_array($rs_total);
	$total_pages = $total_pages['cnt'];

	/* Setup vars for query. */
	
	if(isset ($_GET["limit"])){
		$limit = $_GET["limit"];
	}else{
		$limit = 10; 								//how many items to show per page
	}

	$page = $_GET['pageno'];

	if(isset($_GET['col']) && isset($_GET['dir'])){
		$fieldName = $_GET['col'];
		$field = $fieldNamesArray[$_GET['col']];
		$dir = $_GET['dir'];

	}else{
		$fieldName = 'field1';
		$field = "member_id";
		$dir = "DESC";
	}

	if($page)
	$start = ($page - 1) * $limit; 			//first item to display on this page
	else
	$start = 0;								//if no page var is given, set start to 0

	/* Get data. */
	

	//$sql = "select *  from $tbl_name where member_id=$member_id ORDER BY `time` DESC  limit $start,$limit";
	//$p_result = mysql_query($sql);

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
		
		$pagination = $common->pagiation_simple($targetpage,$limit,$total_pages,$page,$start);
	}

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	 
	 $query = "SELECT * FROM `{$prefix}members` WHERE `id` = '{$member_id}'";
	 
	 $result = mysql_query($query) or die(mysql_error());
	 
	 $cur_member = mysql_fetch_assoc($result);
	 
	 
	  $query = "SELECT * FROM {$prefix}member_session_archive WHERE member_id = '{$member_id}' ORDER BY time DESC limit $start,$limit";
	 
	 $login_result = mysql_query($query) or die(mysql_error()); 
	 
	 
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

<link rel="stylesheet" href="../common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong><?php echo $Title = 'Memeber Report' ?></strong></p><div class="buttons">
    <a href="../member_view.php">Go back</a>
    </div>


<br>



 
 
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  
  <tr class="<?php echo $class?>">
  	<th>User Name: </th>
    <td><?php echo $cur_member['username'] ?></td>
  </tr>  
  <tr>
    <th>Full Name: </th>
    <td><?php echo $cur_member['firstname'] ?> <?php echo $cur_member['lastname'] ?></td>
   </tr> 
  
	<tr>
    <th>Email: </th>
    <td><a href="mailto:<?php echo $cur_member['email'] ?>"><?php echo $cur_member['email'] ?></a></td>
  </tr>
  <tr>
    <th>Date of Join: </th>
    <td><?php echo date("F j, Y, g:i a", strtotime($cur_member['date_joined'])) ?></td>
  </tr>      
  
  	<tr>
    	<th>Last Login: </th>
        <td><?php echo date("M j, Y, g:i a", strftime($cur_member['last_login'])) ?></td>
    </tr>
   
<!--  	<tr>
    	<th>Paypal Account: </th>
        <td><?php echo $cur_member['paypal_email'] ?></td>
    </tr>
   
  	<tr>
    	<th>Street Address: </th>
        <td><?php echo $cur_member['address_street'] ?></td>
    </tr>
  	<tr>
    	<th>Street Address: </th>
        <td><?php echo $cur_member['address_street'] ?></td>
    </tr>
  	<tr>
    	<th>City: </th>
        <td><?php echo $cur_member['city'] ?></td>
    </tr>
  	<tr>
    	<th>State: </th>
        <td><?php echo $cur_member['state'] ?></td>
    </tr>
  	<tr>
    	<th>Country: </th>
        <td><?php echo $cur_member['country'] ?></td>
    </tr>  
  	<tr>
    	<th>Skype ID: </th>
        <td><?php echo $cur_member['skypeid'] ?></td>
    </tr>  
  	<tr>
    	<th>Telephone: </th>
        <td><?php echo $cur_member['telephone'] ?></td>
    </tr>
	<tr>
    	<th>Status: </th>
        <td><?php echo $cur_member['status'] ?></td>
    </tr>
    <tr>
    	<th>Is Published? </th>
        <td><?php echo $cur_member['published'] ?></td>
    </tr>    -->
    
    <tr>
    	<th colspan="2">&nbsp;</th>
    </tr>
    
    <tr>
    	<th colspan="2"><h2>Login Details</h2></th>
    </tr>
    <tr>
    	<td width="100%" colspan="2">
        <div id="grid">
        		<table width="100%" cellspacing="0" >
                <tr>
                	<th>Time</th>
                	<th>Country</th>
                    <th>City</th>
                    <th>IP Address</th>
                    <th>Platform</th>
                    <th>Browser</th>
                    <th>Pages Visited</th>
                </tr>
                <?php while($logins = mysql_fetch_assoc($login_result)){
					
					 $query = "SELECT COUNT(*), hash, member_id FROM {$prefix}member_navigation GROUP BY hash, member_id 
								HAVING hash = '{$logins[hash]}' AND member_id = '{$logins[member_id]}'";
					$result_navi = mysql_query($query) or die(mysql_error());
					
							
					
					$navi_data = mysql_fetch_array($result_navi);
			
					
					$num_of_pages = ($navi_data[0]) ? $navi_data[0] : 0;
					
					 ?>
                     
                     

                <tr>
                	<td><?php echo date("M j, Y, g:i a", strftime($logins['time'])) ?></td>
                	<td><?php echo $logins['country'] ?></td>
                    <td><?php echo $logins['city'] ?></td>
                    <td><?php echo $logins['ip'] ?></td>
                    <td><?php echo $logins['operating_system'] ?></td>
                    <td><?php echo $logins['browser'] ?></td>
                    <td>
                    <?php if($num_of_pages>0){ ?>
                    <a href="member-navi.php?hash=<?php echo $logins['hash'] ?>&member_id=<?php echo $logins['member_id'] ?>"><?php 
				}
					
					echo $num_of_pages;			
					
					 ?> Page(s)<?php if($num_of_pages>0){ ?>
                     </a>
                     <?php } ?>
                     </td>
                </tr>
                <?php
                 if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
 $i++;} ?>
                </table><div class="pages">
		<div class="totalpages">Total: <?php if($startrec >0 ) echo $startrec; else echo "0"; ?> - 
									   <?php if($totalrec >0 ) echo $totalrec; else echo "0"; ?> of <?php echo $total_pages;?></div>
		<!--<div class="pager"><?php echo $pagination?>&nbsp;</div>--></div>
	</div>
        </td>
        
        
    </tr>
      
  

</table>


<div><a href="#top" style="text-align:center;">Move to top</a> </div>
</div>

<div class="content-wrap-bottom"></div>
</div>
<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$("a[rel^='prettyPhoto']").prettyPhoto();
		});
</script>
<?php include_once("../footer.php");?>
