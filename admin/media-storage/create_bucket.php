<?php 
		
include_once '../session.php';
include_once '../header.php';
require_once 'config/config.php';
require_once 'common.php';

?>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo 'Create Bucket';?></strong></p>
<div class="buttons">
	<a href="index.php">Go Back</a>
</div> 
<div class="formborder">
<form id="create_form" method="post" action="actions/buckets_action.php">
<div id="message"><?php if(isset($_GET['msg'])){ echo $site_messages[$_GET['msg']];}?></div>
	<table  border="0" cellpadding="5" cellspacing="5">
    <tbody>
     <tr>
      <th>&nbsp;</th>
      <th>Bucket Name</th>
      <th><input type="text" name="bucket_name" id="bucket_name" /></th>
      <th><input type="submit" name="create_btn" id="create_btn" value="Create Bucket" /></th>
      <th>&nbsp;</th>
    </tr>
     </tbody>
  </table>

</form>
</div>

<br />
<br />
</div>

<div class="content-wrap-bottom"></div>
</div>
<?php include_once '../footer.php';?>