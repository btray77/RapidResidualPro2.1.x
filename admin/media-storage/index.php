<?php
include_once '../session.php';
include_once '../header.php';
require_once 'config/config.php'; // S3 Amizon config file.
require_once 'common.php'; // S3 Amizon config file.
?>

<?php
if (isset($_GET['error'])) {
    echo
    '<div class="error"><img align="absmiddle" src="/images/crose.png"> ' . $site_messages[$_GET['error']] . '</div>';
} ?>
<?php
if (isset($_GET['msg'])) {
    echo
    '<div class="success"> <img src="/images/tick.png" border="0" align="absmiddle"> ' . $site_messages[$_GET['msg']] . '</div>';
}
?>

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
        <p><strong><?php echo 'Media Manager'; ?></strong></p>
        <script type="text/javascript">

            function checkThis(){
                chk=document.bucket_form.deleteChk;
                if(document.bucket_form.box1.checked == true)
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
                document.bucket_form.action.value = 'del';
	
	
                if(confirm('Are you sure you want to delete this bucket?'))
                {
                    document.bucket_form.submit();
                    return true;
                }
                else
                {
                    return false;
                }
            }


        </script> <!-- onclick=return confirmdelete()-->

        <form id="bucket_form" name="bucket_form" method="post"
              action="actions/buckets_action.php">
            <div class="buttons"><a href="create_bucket.php"> Create Bucket </a></div>
            <?php
            $GetSettings = $db->get_a_line("select cloud_fornt from " . $prefix . "site_settings where id='1'");
            if ($GetSettings['cloud_fornt'] == '1') {
            ?>
                <div class="buttons"><a href="view_distributions.php">CloudFront Distributions </a></div>
            <?php
            } 
            ?>
            <div id="grid">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <th>Bucket Name</th>
                            <th>Creation Date</th>
                            <th>Content</th>
                            <th style="text-align: left; padding: 5px;"><input type="checkbox"
                                                                               onchange="checkThis()" value="" name="box1" id="box1" /> <input
                                                                               type="image" src="/images/admin/delete.gif"
                                                                               title="Delete Selected Bucket" name="action" align="absmiddle"
                                                                               value="del" onclick="return confirmdelete()"> <input type="hidden"
                                                                               name="action" value="" /> Delete</th>
                            <th></th>

                        </tr>

                        <tr>
                            <td>Local Uploads</td>
                            <td></td>

                            <td style="text-align: center" nowrap="nowrap"><img
                                    src="/images/admin/view-file.png" align="absmiddle" border="0"
                                    alt="View Content"> <a href="view_bucket_contents.php?bucket=local">View(<?php
									 $sql = "select count(id) as total from " . $prefix . "amazon_s3 WHERE  storage_location <> 'S3'";
									$rs_local = mysql_query($sql);
									$row_total = mysql_fetch_array($rs_local);
									 echo $row_total['total']; ?>)</a>
                                <img src="/images/admin/add-file.png" align="absmiddle" border="0"
                                     alt="Add Content"> <a href="upload_file.php?bucket=local">Add</a></td>

                            <td style="text-align: left"></td>
                            <td style="text-align: left"></td>

                        </tr>

                        <?php

                        $buckets = $s3->listBuckets(true);

                        if (!empty($buckets['buckets'])) {
                            foreach ($buckets['buckets'] as $bucket) {
                        ?>

                                <tr>
                                    <td><?php echo $bucket['name']; ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $bucket['time']); ?></td>

                                    <td style="text-align: center" nowrap="nowrap"><img
                                            src="/images/admin/view-file.png" align="absmiddle" border="0"
                                            alt="View Content"> <a
                                            href="view_bucket_contents.php?bucket=<?php echo $bucket['name']; ?>">View(<?php echo mysql_num_rows(mysql_query("select id from " . $prefix . "amazon_s3 where bucket_id='" . $bucket['name'] . "';")); ?>)</a>
                                        <img src="/images/admin/add-file.png" align="absmiddle" border="0"
                                             alt="Add Content"> <a
                                             href="upload_file.php?bucket=<?php echo $bucket['name']; ?>">Add</a></td>

                                    <td style="text-align: left"><input type="checkbox"
                                                                        value="<?php echo $bucket['name']; ?>" name="delete[]" id="deleteChk" />
    			Delete</td>
                                    <td></td>
                            </tr>

                        <?php
                            }
                        } else {
                        ?>

                            <tr>
                                <td colspan="6" align="center">
                                    <h3>No Bucket Found!</h3>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
                </table>
            </div>

        </form>
        <br />
        <br />
    </div>

    <div class="content-wrap-bottom"></div>
</div>
<?php include_once '../footer.php'; ?>