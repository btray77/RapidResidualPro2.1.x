<?php 
include_once '../session.php';
include_once '../header.php';
require_once 'config/config.php';
require_once 'common.php';
function isSelected($currentValue, $limit){
		if($currentValue == $limit){

			return 'selected="selected"';
		}

	}

 ?>
<script type="text/javascript">

function checkThis(){
chk=document.list_form.deleteChk;
if(document.list_form.box1.checked == true)
	{
		for (i = 0; i < chk.length; i++)
		{
			chk[i].checked = true ;
		}
	}
	else{
		for (i = 0; i < chk.length; i++)
		{
		chk[i].checked = false ;
		}
	}

}
function confirmdelete()
{
	document.list_form.action.value = 'del';
	
	
	if(confirm('Are you sure you want to delete this content?'))
	{
		document.list_form.submit();
		return true;
	}
	else
	{
		return false;
	}
}

</script>

<?php if(isset($_GET['error'])){ echo 
'<div class="error"><img align="absmiddle" src="/images/crose.png"> '.$site_messages[$_GET['error']].'</div>';
}?>
<?php if(isset($_GET['msg'])){ echo 
'<div class="success"> <img src="/images/tick.png" border="0" align="absmiddle"> '.$site_messages[$_GET['msg']].'</div>';
}?>

<?php
    $warnMsg = "";
    if(!is_dir($root_path.$prot_down)){
        $warnMsg .= '<div class="warning"><img src="../images/warning.png" align="absmiddle"> Please create '.$prot_down.' directory via FTP.</div>';
        $configmod = substr(sprintf('%o', fileperms($root_path.$prot_down)), -4);
          if($configmod != '0777')
          {
               if(!chmod($root_path.$prot_down,777))
                                $warnMsg .='<div class="error"><img src="../../images/crose.png" align="absmiddle"> Unable to change permission of this file automatically.Please change permission of this file by using FTP software</div>';
              $warnMsg.= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> '. $prot_down .'  has  '.$configmod .' permissions. But required Permission is 0777 to access.</div>';
          }
    }

    if(!is_dir($root_path.$swf_down)){
        $warnMsg .= '<div class="warning"><img src="../images/warning.png" align="absmiddle"> Please create '.$swf_down.' directory via FTP.</div>';
        $configmod = substr(sprintf('%o', fileperms($root_path.$swf_down)), -4);
          if($configmod != '0777')
          {
               if(!chmod($root_path.$swf_down,777))
                                $warnMsg .='<div class="error"><img src="../../images/crose.png" align="absmiddle"> Unable to change permission of this file automatically.Please change permission of this file by using FTP software</div>';
              $warnMsg.= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> '. $swf_down .'  has  '.$configmod .' permissions. But required Permission is 0777 to access.</div>';
          }
    }

    echo $warnMsg;
?>

<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo "View Bucket Content";?></strong></p>

<div class="buttons">
	<a href="index.php">Go Back</a>
</div> 

<div class="buttons">
	<a href="upload_file.php?bucket=<?php echo $_GET['bucket'];?>">Add Media</a>
</div> 

<div style="float:left;text-align:left">
Select Number of rows per page:
	<form name="limitForm" action="view_bucket_contents.php?bucket=<?php echo $_GET['bucket'];?>" method="GET" style="float:left;">
	  <select name="limit" onchange="window.location.replace('view_bucket_contents.php?bucket=<?php echo $_GET['bucket'];?>&limit=' + this.options[this.selectedIndex].value)" style="width:100px;">
	 	<option value="10" <?php echo isSelected(10,$limit)?>>10</option>
	 	<option value="25" <?php echo isSelected(25,$limit)?>>25</option> 
	    <option value="50" <?php echo isSelected(50,$limit)?>>50</option>
	    <option value="100" <?php echo isSelected(100,$limit)?>>100</option>
	  </select>
	 </form>
     
</div>
<form id="list_form" name="list_form" method="post" action="actions/content_action.php" >
<input type="hidden" name="bucket_name" id="bucket_name" value="<?php echo mysql_escape_string($_GET['bucket']);?>"/>

<div id="grid">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
      <th>Title</th>
      <th>Access</th>
      <th>Creation Date</th>
      <!--<th>Size(KB)</th>-->
      <th>Token</th>
      
      <th style="text-align:left;padding:5px;" nowrap="nowrap">
      <input type="checkbox" onchange="checkThis()" value="" name="box1" id="box1" />
     	<input type="image" src="/images/admin/delete.gif" title="Delete Selected Content" name="action"  align="absmiddle"  value="del"  onclick="return confirmdelete()"  >
		Delete	
     	<input type="hidden" name="action" value="" />
	</th>
      
    </tr>
    
		<?php
        
                if($_GET['bucket'] != 'local'){ //  Amazon S3 Used
				
               // $lists = $s3->getBucket(mysql_escape_string($_GET['bucket']));
    		
                    //if(!empty($lists)){
                    //foreach ($lists as $list){
                           // $size = explode('.',$list['size']/1024);
					//}
					
					
					/*
					 First get total number of rows in data table.
					 If you have a WHERE clause in your query, make sure you mirror it here.
					 */
					
					$query = "SELECT COUNT(*) as cnt FROM ".$prefix."amazon_s3 WHERE bucket_id ='".$_GET['bucket']."' ";
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
					
				
					$sql = "SELECT * FROM ".$prefix."amazon_s3 WHERE bucket_id = '".$_GET['bucket']."' order by publish_date DESC  limit $start,$limit";
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
						$targetpage = "view_bucket_contents.php?bucket=".$_GET['bucket'];
						$pagination = $common->pagiation_simple($targetpage,$limit,$total_pages,$page,$start, "");
					}
				
                            if($total_pages['cnt'] > 0){
							
							while($myResult = mysql_fetch_array($result)){
					
					
					
                            //$query = "SELECT id, title, short_name, custom_token FROM ".$prefix."amazon_s3 WHERE content_id ='".$_GET['bucket']."' ";
                            //$myResult = $db->get_a_line($query);


                        ?>
					 
				<tr>
				  <td style="text-align:left">
				 <?php echo $myResult['title'];?></a><br>
				 Short Name: <?php echo $myResult['short_name'];?><br>
				 
				  </td>
				   <td ><?php if($myResult['content_access']=="Private") echo '<font color=red>'.$myResult['content_access'].'</font>'; else echo $myResult['content_access'];?></td>
				  <td><?php echo date('Y-m-d H:i:s', $myResult['creation_date']);//echo date('Y-m-d H:i:s', $list['time']);?></td>
				  <!--<td><?php echo round($myResult['content_size']/1024,1);//echo $size[0];?></td>-->
				  <td nowrap="nowrap">{{<?php echo $myResult['custom_token'];?>}}</td>
                  <td style="text-align:left" nowrap="nowrap">
                  <input type="checkbox" value="<?php echo $myResult['content_id'];?>" name="delete[]" id="deleteChk" /> Delete
                  <img src="/images/editIcon.png" align="absmiddle" border="0" alt="Edit <?php echo $myResult['title'];?>">
                  <a href="edit_upload_file.php?bucket=<?php echo $_GET['bucket']?>&C_id=<?php echo $myResult['id'];?>">Edit</a>
                  <img src="/images/admin/view-file.png" align="absmiddle" border="0" alt="View <?php echo $myResult['title'];?>">
                   <a href="view_uploaded_file.php?C_id=<?php echo $myResult['id'];?>"> View </a>
                  </td>
				 
				 
				  
				</tr>
				
			<?php	
				}
			}else{
			?>
				
				<tr>
				  <td colspan="12" align="center"><h3>Empty Bucket! <?php echo $_GET['bucket'];?></h3></td>
				</tr>
          <?php
			}

                }elseif($_GET['bucket'] == 'local'){ // Local Storage Used
				
				
					/*
					 First get total number of rows in data table.
					 If you have a WHERE clause in your query, make sure you mirror it here.
					 */
					
					$query = "SELECT COUNT(*) as cnt FROM ".$prefix."amazon_s3 WHERE storage_location = 'local'";
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
					
				
					$sql = "SELECT * FROM ".$prefix."amazon_s3 WHERE storage_location = 'local' order by publish_date DESC  limit $start,$limit";
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
						$targetpage = "view_bucket_contents.php?bucket=".$_GET['bucket'];
						$pagination = $common->pagiation_simple($targetpage,$limit,$total_pages,$page,$start, "");
					}
				
                            if($total_pages['cnt'] > 0){
							
							while($myResult = mysql_fetch_array($result)){
                        ?>

				<tr>
				  <td style="text-align:left">
				 <?php echo $myResult['title'];?><br>
				 Short Name: <?php echo $myResult['short_name'];?><br>
				
				  </td>
					<td ><?php if($myResult['content_access']=="Private") echo '<font color=red>'.$myResult['content_access'].'</font>'; else echo $myResult['content_access'];?></td>
				  <td><?php echo date('Y-m-d H:i:s', $myResult['creation_date']);?></td>
				 <!-- <td><?php echo round($myResult['content_size']/1024,1);?></td>-->
				  <td nowrap="nowrap">{{<?php echo $myResult['custom_token'];?>}}</td>
                  <td style="text-align:left">
                  <input type="hidden" name="storage_type" value="local">
                  <input type="checkbox" value="<?php echo $myResult['content_id'];?>" name="delete[]" id="deleteChk" /> Delete
                  <img src="/images/editIcon.png" align="absmiddle" border="0" alt="Edit <?php echo $myResult['title'];?>">
                  <a href="edit_upload_file.php?bucket=<?php echo $_GET['bucket']?>&C_id=<?php echo $myResult['id'];?>">Edit</a>
                  <img src="/images/admin/view-file.png" align="absmiddle" border="0" alt="View <?php echo $myResult['title'];?>">
                   <a href="view_uploaded_file.php?C_id=<?php echo $myResult['id'];?>&bucket=local"> View </a>
                  </td>



				</tr>

			<?php
				}
			}else{
			?>

				<tr>
				  <td colspan="12" align="center"><h3>Empty Bucket! <?php echo $_GET['bucket'];?></h3></td>
				</tr>
          <?php
			}

                }
              
          ?>
  
      </tbody>
      </table>
</div>
</form>
<br />
<br />
<div class="pager"><?php echo $pagination?>&nbsp;</div></div>
<div class="content-wrap-bottom"></div>
</div>

</div>
<?php include_once '../footer.php';?>