<?php
include_once("../session.php");
include_once("../header.php");
$Title = "Email Blaster Settings";
$table = $prefix . "email_blaster_groups";
$module_name = 'newsletter';
$filter = $_GET["fid"];
$filter_type = $_GET["type"];
if (!empty($filter)) {
    $targetpage = "addgroup.php?filter=$filter";  //your file name  (the name of this file)
}
else
    $targetpage = "addgroup.php?filter=all";  //your file name  (the name of this file)
    if (count($_POST) > 0) {
    foreach ($_POST as $key => $items) {
        $$key = trim($items);
    }
    $published = $_POST['published'];
    $published = $published == "on" ? "1" : "0";
    $subject = addslashes($subject);
    if ($id > 0) {
        $sql = "update $table set `name`='$subject', `published`=$published, filter_type='$filter_type', filter_id=$filter where `id`='$id'";
        $db->insert($sql);
        $group_id = $id;
    } else {
        $sql = "insert $table set `name`='$subject',  `published`=$published, filter_type='$filter_type', filter_id=$filter; ";
        $group_id = $db->insert_data_id($sql);
    }
    if (count($_POST['users']) > 0) {
        $db->insert("delete from " . $prefix . "email_blaster_group_members where group_id=$group_id");
        foreach ($_POST['users'] as $memberid) {
            $db->insert("insert " . $prefix . "email_blaster_group_members set group_id=$group_id,member_id=$memberid");
        }
    } else {
        $db->insert("delete from " . $prefix . "email_blaster_group_members where group_id=$group_id");
    }
    header("Location: groups.php?msg=s");
}
if ($id > 0) {
    $sql = "SELECT * FROM $table where id=$id";
    $row = $db->get_a_line($sql);
}
if ($_GET['type'] == 'm') {
    switch ($filter) {
        case '1':
            $WHEREX = '';
            break;
        case '2':
            $WHEREX = ' where is_block=0';
            break;
        case '3':
            $WHEREX = ' where is_block=1';
            break;
        case '4':
            $WHEREX = " where ref <> 'None' and ref <> ''";
            break;
        case '5':
            $WHEREX = "where status=2 AND paypal_email ='' AND alertpay_email =''";
            break;
        case '6':
            $WHEREX = " where (status=1 OR status=2) AND ( paypal_email <> '' OR  alertpay_email <> '' )";
            break;
        case '7':
            $WHEREX = ' where status=3';
            break;
    }
}
?>
<style type="text/css">
    .style1 {color: #FF0000}
</style>
<script>
    $(document).ready(function(){
        $("#content").validate();
    });
    function checkAll(field)
    {
        var checks = document.getElementsByName('users[]');
        for (i = 0; i < checks.length; i++)
        checks[i].checked = true ;
}
function uncheckAll(field)
{
    var checks = document.getElementsByName('users[]');
    for (i = 0; i < checks.length; i++)
    checks[i].checked = false ;
}
$(document).ready(function(){
$("#content").validate();
});
</script>
<link href="css/theme.css" rel="stylesheet" type="text/css" />
<div class="content-wrap">
    <div class="content-wrap-top"></div>
    <div class="content-wrap-inner">
        <p style="font-weight:bold"><?php
if (empty($_GET['id']))
    $label = "Add"; else
    $label="Edit"; echo $label
?> Group</p>
        <div class="buttons">
            <a href="groups.php">View Groups</a>
        </div>
        <div style="width:100%;float:left">
            <form method="post" name="content" id="content" action="">
                <?php if (!empty($_SESSION['success'])) {
 ?>
                    <div class="success">Record is successfully saved.</div>
                <?php unset($_SESSION['success']);
                } ?>
                <table  border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td width="16%" valign="top" ><label>Group Name:<span class="style1">*</span></label></td>
                        <td width="80%" >
                            <input name="subject" type="text" value="<?php echo stripslashes($row['name']); ?>" size="40" maxlength="60" class="inputbox required" />    </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td >
                            <?php
                            if ($_GET['id']) {
                                $id_is = "&id=" . $_GET["id"];
                            }
                            //$target_url=
                            ?>
                            <div class="filter" style="margin-bottom:10px;">
                                <fieldset>
                                    <legend>Filter By Members</legend>
                                    <input type="radio" name="filter" value="1" <?php if (($filter_type == 'm' and $filter == 1) || ($filter_type != 'p' and $filter == ""))
                                echo 'checked="checked"'; ?> onclick="window.location='addgroup.php?type=m&fid=1<?php echo $id_is ?>'"> All
                                    <input type="radio" name="filter" value="2" <?php if ($filter_type == 'm' and $filter == 2)
                                echo 'checked="checked"'; ?> onclick="window.location='addgroup.php?type=m&fid=2<?php echo $id_is ?>'"> Active
                                    <input type="radio" name="filter" value="3" <?php if ($filter_type == 'm' and $filter == 3)
                                echo 'checked="checked"'; ?> onclick="window.location='addgroup.php?type=m&fid=3<?php echo $id_is ?>'"> Block
                                    <input type="radio" name="filter" value="4" <?php if ($filter_type == 'm' and $filter == 4)
                                echo 'checked="checked"'; ?> onclick="window.location='addgroup.php?type=m&fid=4<?php echo $id_is ?>'"> Has referer
                                    <input type="radio" name="filter" value="5" <?php if ($filter_type == 'm' and $filter == 5)
                                echo 'checked="checked"'; ?> onclick="window.location='addgroup.php?type=m&fid=5<?php echo $id_is ?>'"> Member
                                    <input type="radio" name="filter" value="6" <?php if ($filter_type == 'm' and $filter == 6)
                                echo 'checked="checked"'; ?> onclick="window.location='addgroup.php?type=m&fid=6<?php echo $id_is ?>'"> Affiliate member
                                    <input type="radio" name="filter" value="7" <?php if ($filter_type == 'm' and $filter == 7)
                                echo 'checked="checked"'; ?> onclick="window.location='addgroup.php?type=m&fid=7<?php echo $id_is ?>'"> JV Partners
                                </fieldset>
                            </div>
                            <div class="filter" style="margin-bottom:10px;">
                                <fieldset>
                                    <legend>Filter By Products</legend>
                                    <script type="text/javascript">
                                function test()
                                {
                                    document.content.submit();
                                }
                                    </script>
                                    <select name="product"  onchange="window.location='addgroup.php?type=p&fid='+this.value+'<?php echo $id_is ?>'">
                                        <?php
                                        $products = $db->get_rsltset("select id,product_name from " . $prefix . "products");
                                        foreach ($products as $product) {
                                            if ($_GET["type"] == 'p' && $product["id"] == $_GET["fid"])
                                                $selected = "selected";
                                            else
                                                $selected="";
                                            echo ' <option value="' . $product["id"] . '" ' . $selected . '>' . $product["product_name"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" ><label>Assign Members</label> </td>
                        <td >
                            <div class="header" style="margin-left:0px;width: 99%;">
                                <a href="javascript:" onclick="checkAll()">Select all</a> | <a href="javascript:" onclick="uncheckAll()">Unselect all</a>
                            </div>
                            <?php
                                        // echo $sql = "select a.id,a.firstname,a.lastname from " . $prefix . "members a, " . $prefix . "member_products b where a.id=b.member_id and b.product_id=".$_POST['products'];
                                        //  exit;
                                        if ($_GET["type"] == 'p') {
                                            $sql = "select a.id,a.firstname,a.lastname from " . $prefix . "members a, " . $prefix . "member_products b where a.id=b.member_id and b.product_id=" . $_GET['fid'];
                                        } else {
                                            $sql = "select id, firstname, lastname from " . $prefix . "members $WHEREX";
                                        }
                                        $results = $db->get_rsltset($sql);
                                        if ($results) {
                                            foreach ($results as $row_user) {
                                                if (!empty($_GET[id]))
                                                    $group_id = $_GET[id];
                                                else
                                                    $group_id=0;
                                                $sql_checked = "select count(id) as total from " . $prefix . "email_blaster_group_members where member_id = $row_user[id] and group_id=$group_id";
                                                $row_total = $db->get_a_line($sql_checked);
                                                $row_total['total'] . "<br>";
                                                if ($row_total['total'] > 0 and ($row['filter_type']==$_GET['type'] and $row['filter_id']==$_GET['fid']))
                                                    $checked = "checked";
                                                else
                                                    $checked="";
                            ?>
                                                <div class="username">
                                                    <input type="checkbox" name="users[]" value="<?php echo $row_user['id'] ?>" <?php echo $checked; ?> /><?php echo $row_user['firstname'] . ' ' . $row_user['lastname'] ?>
                                                </div>
                            <?php
                                            }
                            } else {
                                echo "<div style='clear:both;margin:40px 10px 10px 50px;'><font color='red'>No Record Found !</font></div>";
                            }
                            ?>
                                    </tr>
                                    <tr>
                                        <td valign="top" >Published:</td>
                                        <td >
                            <?php
                                            if ($row['published'] == 1)
                                                $checked = "checked"; else
                                                $checked="";
                            ?>
                                            <input type="checkbox" name="published" <?php echo $checked; ?> /></td>
                                    </tr>
                    <?php if ($action == 'edit') {
                    ?>
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>"  />
                    <?php } ?>
                                            <tr>
                                                <td ></td>
                                                <td ><input type="submit" name="save" value=" Save "  /></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div class="content-wrap-bottom"></div>
                        </div>
<?php include_once("../footer.php"); ?>
	