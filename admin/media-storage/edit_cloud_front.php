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
    }
?>
<?php
    if (isset($_GET['msg'])) {
        echo
        '<div class="success"> <img src="/images/tick.png" border="0" align="absmiddle"> ' . $site_messages[$_GET['msg']] . '</div>';
    }
?>

    <div class="content-wrap">
        <div class="content-wrap-top"></div>
        <div class="content-wrap-inner">
            <p><strong><?php echo 'Add Bucket to Cloud Front'; ?></strong></p>
            <div class="buttons">
                <a href="view_distributions.php">Go Back</a>
            </div>
            <div class="formborder">
                <form id="create_form" method="post" action="actions/cloud_front_action.php">
                    <div id="message"><?php
    if (isset($_GET['msg'])) {
        echo $site_messages[$_GET['msg']];
    }
?></div>

                <?php
                     $cdn = new AmazonCloudFront($setting_array['aws_access_key'],$setting_array['aws_secret_key']);
                     // Get distribution info
                     // Pull streaming distributions
                    $streaming_distributions = $cdn->get_streaming_distribution_list();

                    if(in_array($id, $streaming_distributions)){

                                $response = $cdn->get_distribution_info(mysql_escape_string($_GET['id']), array(
                                'Streaming' => true
                                    ));
                                $streaming = true;

                        }else{

                                 $response = $cdn->get_distribution_info(mysql_escape_string($_GET['id']));
                        }


                    if($response->isOK()){

                    }
                ?>

                <table  border="0" cellpadding="5" cellspacing="5">
                    <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>Distribution Type</td>
                            <td>
                                Download <input type="radio" name="type" value="false" checked="checked"/>
                                Streaming <input type="radio" name="type" value="true"/> 
                            </td>
                            <td></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>Select Bucket</td>
                            <td>
                                <select name="bucket_name">
                                    <option value="" selected="">Select Amazon S3 Bucket</option>
                                    <?php
                                    $buckets = $s3->listBuckets(true);
                                    if (!empty($buckets['buckets'])) {
                                        foreach ($buckets['buckets'] as $bucket) {
                                    ?>

                                            <option value="<?php echo $bucket['name']; ?>"><?php echo $bucket['name']; ?></option>

                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>Specify Bucket</td>
                            <td><input type="text" name="bucket_new" value=""/></td>
                            <td></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>CNAMEs</td>
                            <td><textarea name="cnames" col="7" rows="5"></textarea></td>
                            <td></td>
                            <td>
                                <div class="tool">
                                    <a href="" class="tooltip" title="Enter Maximum upto 10 CNAMEs and seperate by comma(,).">
                                        <img src="../../images/toolTip.png" alt="help"/></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td><input type="submit" name="create_dis" id="create_dis" value="Add to CloudFront" /></td>
                            <td></td>
                            <td>&nbsp;</td>
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
<?php include_once '../footer.php'; ?>