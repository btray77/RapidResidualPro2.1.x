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
                <table  border="0" cellpadding="5" cellspacing="5">
                    <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>Distribution Type</td>
                            <td>
                                Streaming <input type="radio" name="type" value="true" checked="checked"/>
                                Download <input type="radio" name="type" value="false" />
                            </td>
                            <td></td>
                            <td>
                                <div class="tool">
                                    <a href="" class="tooltip" title="Select whether to use as a downloadable file or streaming">
                                        <img src="../../images/toolTip.png" alt="help"/></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        $mysql="Select buket_id from ".$prefix."amazon_cloud_front";
                                    $rslt=$db->get_rsltset($mysql);
                                    $used_buckets = array();
                                    if(!empty($rslt)){
                                        foreach($rslt as $used_bucket_id){
                                            $used_buckets[]=$used_bucket_id['buket_id'];
                                        }
                                    }

                        ?>
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
                                            if(!in_array($bucket['name'], $used_buckets)){


                                    ?>

                                            <option value="<?php echo $bucket['name']; ?>"><?php echo $bucket['name']; ?></option>

                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td></td>
                            <td>
                                <div class="tool">
                                    <a href="" class="tooltip" title="Select a bucket to add to Cloud Front from existing buckets">
                                        <img src="../../images/toolTip.png" alt="help"/></a>
                                </div>
                            </td>
                        </tr>
                        <!--<tr>
                            <td>&nbsp;</td>
                            <td>Specify Bucket</td>
                            <td><input type="text" name="bucket_new" value="" size="25"/></td>
                            <td></td>
                            <td>
                                <div class="tool">
                                    <a href="" class="tooltip" title="You can create new bucket specific for the Cloud Front">
                                        <img src="../../images/toolTip.png" alt="help"/></a>
                                </div>
                            </td>
                        </tr>-->
                        <tr>
                            <td>&nbsp;</td>
                            <td>CNAMEs</td>
                            <td><input type="text" name="cnames" size="25" value=""/></td>
                            <td></td>
                            <td>
                                <div class="tool">
                                    <a href="" class="tooltip" title="Enter Maximum upto 10 CNAMEs and seperate by comma(,)">
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