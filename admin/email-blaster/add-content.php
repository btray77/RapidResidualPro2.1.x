<?php
include_once("../session.php");
include_once("../header.php");
$Title = "Email Blaster Settings";
$table = $prefix . "email_content";
$module_name = 'newsletter';
if (count($_POST) > 0) {
    foreach ($_POST as $key => $items) {
        $$key = trim($items);
    }
    $published = $_POST['published'];
    $group_id = $_POST['group'];
    $published = $published == "on" ? "1" : "0";
    $subject = addslashes($subject);
    $body = addslashes($contentbody);
    //$days	=addslashes($days);
    if ($id > 0) {
        $sql = "update $table set `subject`='$subject', `body`='$body', `group_id`=$group_id,`published`=$published  where `id`='$id'";
        $db->insert($sql);
        $content_id = $id;
		$db->insert("UPDATE ". $prefix ."email_blaster_group_members SET content_id=$content_id , mail_status=0 where group_id=$group_id");
    } else {
        $sql = "insert $table set `subject`='$subject', `body`='$body', `group_id`=$group_id,  `published`=$published; ";
       $content_id = $db->insert_data_id($sql);

		
		$db->insert("UPDATE ". $prefix ."email_blaster_group_members SET content_id=$content_id , mail_status=0 where group_id=$group_id");
    }
    header("Location: contents.php?msg=s");
}
if ($id > 0) {
    $sql = "SELECT * FROM $table where id=$id";
    $row = $db->get_a_line($sql);
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
</script>
<link href="css/theme.css" rel="stylesheet" type="text/css" />
<div class="content-wrap">
    <div class="content-wrap-top"></div>
    <div class="content-wrap-inner">
        <p style="font-weight:bold"><?php
if (empty($_GET['id']))
    $label = "Add"; else
    $label = "Edit"; echo $label
?> Content</p>
        <div class="buttons">
            <a href="contents.php">View Content</a>
        </div>
        <div style="width:100%;float:left">
            <form method="post" name="content" id="content" action="">
                <?php if (!empty($_SESSION['success'])) {
                    ?>
                    <div class="success">Record is successfully saved.</div>
                    <?php unset($_SESSION['success']);
                } ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td width="16%" valign="top" nowrap="nowrap"><label>Subject:<span class="style1">*</span></label></td>
                        <td width="84%" nowrap="nowrap">
                            <input name="subject" type="text" value="<?php echo stripslashes($row['subject']); ?>" size="40" maxlength="60" class="required" />    </td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap="nowrap"><label>Email  Content:</label></td>
                        <td nowrap="nowrap">
                            <textarea name="contentbody" cols="100" rows="8" class="required" id="contentbody"><?php echo stripslashes($row['body']); ?></textarea>
                            <script>
                        
                        var oEdit1 = new InnovaEditor("oEdit1");
                        oEdit1.width = 700;
                        oEdit1.height = 450;
                        oEdit1.groups = [
                            ["grpEdit1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
                            ["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
                            ["grpEdit3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
                            ["grpEdit4", "", ["CharsDialog", "Line", "BRK","CustomTag"]],
                            ["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo", "FullScreen"]]
                        ];
                        /*Enable Custom File Browser */
                        oEdit1.fileBrowser = "/admin/Editor/assetmanager/asset.php";
                        /*Define "CustomTag" dropdown */
                        oEdit1.arrCustomTag=    [["First Name","{\{first_name\}}"],
                            ["Last Name","{\{last_name\}}"]
                        ];//Define custom tag selection//Define custom tag selection
                        /*Apply stylesheet for the editing content*/
                        oEdit1.css = "/admin/Editor/styles/default.css";
                        /*Render the editor*/
                      
                        
                        oEdit1.REPLACE("contentbody");
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap="nowrap">Group : <span class="style1">*</span></td>
                        <td nowrap="nowrap">
                            <select name="group">
                                <?php
                                $sql = " SELECT id,name from " . $prefix . "email_blaster_groups where published=1";
                                $groups = $db->get_rsltset($sql);
                                foreach ($groups as $group) {
                                    if ($group["id"] == $row['group_id'])
                                        $selected = "selected";
                                    else
                                        $selected = "";
                                    $name = stripslashes($group["name"]);
                                    echo '<option value="' . $group["id"] . '" ' . $selected . '>' . $name . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap="nowrap">Published:</td>
                        <td nowrap="nowrap">
                            <?php
                            if ($row['published'] == 1)
                                $checked = "checked"; else
                                $checked = "";
                            ?>
                            <input type="checkbox" name="published" <?php echo $checked; ?> /></td>
                    </tr>
                    <?php if ($action == 'edit') { ?>
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>"  />
                    <?php } ?>
                    <tr>
                        <td nowrap="nowrap"></td>
                        <td nowrap="nowrap"><input type="submit" name="submit" value=" Save " /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="content-wrap-bottom"></div>
</div>
<?php include_once("../footer.php"); ?>
	