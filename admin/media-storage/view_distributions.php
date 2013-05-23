<?php
include_once '../session.php';
include_once '../header.php';
require_once 'config/config.php'; // S3 Amizon config file.
require_once 'common.php'; // S3 Amizon config file.
?>

<?php
$GetSettings = $db->get_a_line("select cloud_fornt from " . $prefix . "site_settings where id='1'");
if ($GetSettings['cloud_fornt'] != '1') {
    header("Location: index.php");
}
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
              action="actions/cloud_front_action.php">
            <div class="buttons">
                    <a href="index.php">Go Back</a>
            </div>
            <div class="buttons">
                <a href="cloud_front.php"> Add CloudFront </a>
            </div>
            

            <div id="grid">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <th>Origin</th>
                            <th>Status</th>
                            <th>State</th>
                            <th>Last Modified</th>
                            <!--<th>Edit</th>-->
                            <th>Delivery Method</th>
                            <th style="text-align: left; padding: 5px;">
                                <input type="checkbox" onchange="checkThis()" value="" name="box1" id="box1" />
                                <input type="image" src="/images/admin/delete.gif" title="Delete Selected Bucket" name="action" align="absmiddle" value="del" onclick="return confirmdelete()">
                                <input type="hidden" name="action" value="" /> Delete
                            </th>
                        </tr> 

                        <?php

                        //Get List of CloudFront Distributions
                        // Generate configuration XML
                        $cdn = new AmazonCloudFront($setting_array['aws_access_key'],$setting_array['aws_secret_key']);
                        $standard_distributions = $cdn->get_distribution_list();
                        $streaming_distributions = $cdn->get_streaming_distribution_list();
                        $response = array_merge($standard_distributions,$streaming_distributions);


                        if (!sizeof($response) == 0) {

                                $streaming_distributions = $cdn->get_streaming_distribution_list();

                                foreach($response as $dist_id){

                                if(in_array($dist_id, $streaming_distributions)){

                                    $dist_info = $cdn->get_distribution_info($dist_id, array('Streaming' => true));
                                    $streaming = true;

                                }else{

                                    $dist_info = $cdn->get_distribution_info($dist_id);
                                }
                               


                        ?>

                                <tr>
                                    <td style="text-align: center" nowrap="nowrap"><?php echo ($streaming == true)? $dist_info->body->StreamingDistributionConfig->S3Origin->DNSName : $dist_info->body->DistributionConfig->S3Origin->DNSName;   ?></td>

                                    <td style="text-align: center" nowrap="nowrap"><?php echo $dist_info->body->Status; ?></td>
                                    <td style="text-align: center" nowrap="nowrap">
                                    <?php
                                        if($streaming == true){
                                            if($dist_info->body->StreamingDistributionConfig->Enabled == 'true')
                                            {
                                                echo 'Enabled';
                                            }else{
                                                echo 'Disabled';
                                            }
                                        }else{
                                            if($dist_info->body->DistributionConfig->Enabled == 'true'){
                                                echo 'Enabled';
                                            }else{
                                                echo 'Disabled';
                                            }
                                        }
                                    ?>
                                    </td>
                                    <td style="text-align: left" nowrap="nowrap"><?php echo $dist_info->body->LastModifiedTime; ?></td>
                                    <!--<td style="text-align: center" nowrap="nowrap"><a href="">Edit</a></td>-->
                                    <td style="text-align: center" nowrap="nowrap"><?php echo ($streaming == true)? 'Streaming' : 'Download';   ?></td>
                                    <td style="text-align: left" nowrap="nowrap">
                                        <?php
                                        if($streaming == true){
                                            if($dist_info->body->StreamingDistributionConfig->Enabled == 'true')
                                            {
                                                echo '<a href="actions/cloud_front_action.php?do=disable&id='.$dist_info->body->Id.'">Disable</a>';
                                            }else{
                                        ?>
                                        <input type="checkbox" value="<?php echo $dist_info->body->Id; ?>"  id="deleteChk" <?php echo ($dist_info->body->Status != 'Deployed')? 'disabled="disabled" name="disable"':'name="delete[]"';?>/>Delete
                                        <?php if($dist_info->body->Status == 'Deployed'){?>

                                         | <a href="actions/cloud_front_action.php?do=enable&id=<?php echo $dist_info->body->Id;?>">Enable</a>

                                        <?php
                                                }
                                            }
                                        }else{
                                            if($dist_info->body->DistributionConfig->Enabled == 'true'){
                                                echo '<a href="actions/cloud_front_action.php?do=disable&id='.$dist_info->body->Id.'">Disable</a>';
                                            }else{
                                        ?>
                                         <input type="checkbox" value="<?php echo $dist_info->body->Id; ?>"  id="deleteChk" <?php echo ($dist_info->body->Status != 'Deployed')? 'disabled="disabled" name="disable"':'name="delete[]"';?>/>Delete
                                         <?php if($dist_info->body->Status == 'Deployed'){?>
                                         
                                         | <a href="actions/cloud_front_action.php?do=enable&id=<?php echo $dist_info->body->Id;?>" >Enable</a>

                                        <?php
                                                 }
                                            }
                                        }
                                        ?>
                                        
                                    </td>
                                </tr>

                        <?php
                            }
                        } else {
                        ?>

                            <tr>
                                <td colspan="6" align="center">
                                    <h3>No Distribution Found!</h3>
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