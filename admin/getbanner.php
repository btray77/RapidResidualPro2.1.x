<?
include_once("session.php");
include_once("header.php");

$file=file("../html/admin/getbanner.html");
$returncontent=join("",$file);

$dir	= $bannerimg_upload_path;

if($rem == 'y')
{
	$q = "update ".$prefix."marketing_banners set banner_url='', banner_image='' where id='$bid'";
	$db->insert_data_id($q);

	$message	= '<div class="success"><img src="../images/tick.png" align="absmiddle">Banner image removed successfully</div>';
}
elseif($submit)
{
	$update	= 0;
	$set	= "product_id='".$selprod."'";

	if ($_FILES[upload_url]['name'] != '')
	{
		$image_name = $_FILES[upload_url]['name'];

		$hash = md5(uniqid(rand(),1));
		$hash = substr ($hash,0,4);
		$image_name = $hash.$image_name;

		$fullpath = $dir.$image_name;

		$res=$db->get_a_line("select banner_image from ".$prefix."marketing_banners where product_id='$pid'");
		$old_upload_url = $dir.$res[banner_image];

		if (is_file ($old_upload_url))
		{
			unlink ($old_upload_url);
		}

		if (is_uploaded_file($HTTP_POST_FILES[upload_url]['tmp_name']))
		{
			move_uploaded_file($_FILES[upload_url]['tmp_name'], $fullpath);
		}
		else
		{
			echo "Possible file upload attack. Filename: " . $HTTP_FILES[upload_url]['name'];
		}
		$set .= ", banner_image=\"$image_name\"";
		$set .= ", banner_url=''";
		$banner_url	= "";
	}
	elseif($banner_url != '')
	{
		$set .= ", banner_url=\"".$banner_url."\"";
		$set .= ", banner_image=''";
	}

	if($act == e)
	{
		$q = "update ".$prefix."marketing_banners set $set where id='$bid'";
		$db->insert_data_id($q);




		//$message	= "Banner updated successfully";

		$msg = "edit";
		header("Location: market_banners.php?msg=$msg");



	}
	else
	{
		$q = "insert into ".$prefix."marketing_banners set $set";
		$bid = $db->insert_data_id($q);
		$act = 'e';

		//$message	= "Banner inserted successfully";

		$msg = "add";
		header("Location: market_banners.php?msg=$msg");
	}
}
$old_img = "";

if($bid)
{
	$q = "select *  from ".$prefix."marketing_banners where id='$bid'";
	$r = $db->get_a_line($q);

	$selprod	= $r[product_id];

	if($r[banner_image]!='')
	$old_img = "<img src=".$bannerimg_display_path.$r[banner_image]." width=500>";
	elseif($r[banner_url]!='')
	$old_img = "<img src=".$r[banner_url]." width=500>";

	if($old_img != '')
	{
		$old_img	= "<a href='getbanner.php?rem=y&bid=$bid&act=$act'>Remove Image</a><br>".$old_img;
	}
}

$q = "select *  from ".$prefix."products";
$r = $db->get_rsltset($q);

for($i=0; $i<count($r); $i++)
{
	$id=$r[$i]['id'];
	$pname=$r[$i]['product_name'];

	if($selprod==$id)

	$prods_display.="<option value='$id' selected>$pname</option>";
	else
	$prods_display.="<option value='$id'>$pname</option>";

}



$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
$returncontent=preg_replace("/<{(.*?)}>/e","$$1",$returncontent);
echo $returncontent;


include_once("footer.php");
?>
