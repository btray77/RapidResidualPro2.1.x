<?php
include_once '../session.php';
include_once '../header.php';
require_once 'dumper.class.php';
$FILEPATH = $_SERVER[DOCUMENT_ROOT] .'/dumper/sql_dumps'; 
$objDumper = new dumper($db,$dbname);

if (isset($_REQUEST['task']) && $_REQUEST['task'] == 'b') {
     $objDumper->exportDb();
	 
	header('Location: index.php');
}
$backupPath = $_SERVER['SCRIPT_NAME'] . '?task=b';

if (isset($_REQUEST['delete'])) {
	  $objDumper->deleteFiles($FILEPATH,$_REQUEST['delete']);
}
?>
<script>

function checkThis(){
	chk=document.myform.deleteChk;
if(document.myform.box1.checked == true)

{
	for (i = 0; i < chk.length; i++){
		chk[i].checked = true ;
	}
}
else{
	for (i = 0; i < chk.length; i++){
	chk[i].checked = false ;
	}
}

}


</script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo $name;?></strong></p>

<form action="" method="post" name="myform">
  <div align="right"><img alt="Db backup" src="/images/admin/dbbackup.png" align="absmiddle">
  <a href="<?php echo $backupPath; ?>"> Database Backup</a>
 
  	<img alt="delete" src="/images/crose.png" align="absmiddle" > <a href="javascript:" onclick="document.myform.submit()"> Delete </a>
 
 </div>
  <div id="grid">
  <table  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th>File Name</th>
      <th>Backup Date</th>
      <th>Size</th>
      <th>Download</th>
      <th><input id="box1" type="checkbox" name="box1" value="" onchange="checkThis()" /></th>
    </tr>
    <?php
    $files = $objDumper->listdirByDate($FILEPATH);
    foreach ($files as $file) {
        if (strlen($file) > 10) {
            $timestamp = substr($file, 0, strpos($FILEPATH.'/'.$file, '.sql'));
            $fileDateTime = date("Y-m-d H:i:s", $timestamp);
			$filesize = $objDumper->formatbytes($FILEPATH.'/'.$file);
    ?>
    <tr>
      <td style="text-align:center"><?php echo $file; ?></td>
      <td style="text-align:center"><?php echo $fileDateTime; ?></td>
      <td style="text-align:center"><?php echo $filesize;?></td>
      <td style="text-align:center"><a href="/admin/sqldump/download.php?download_file=<? echo $file ?>" target="_blank"><img src="/images/admin/download.png" alt="download"></a></td>
      <td style="text-align:center"><input id="deleteChk" type="checkbox" name="delete[]" value="<?php echo $file; ?>" /></td>
    </tr>
    <?php
        }
    }
    ?>
  </table>
 </div>
</form>
</div>
<div class="content-wrap-bottom"></div>
</div>
<?php include_once '../footer.php';?>