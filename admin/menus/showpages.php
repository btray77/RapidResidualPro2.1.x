<?php
include_once '../session.php';

if($_POST['content']!="custom"){
	
	if($_POST['content']=="content" || $_POST['content']=="blog"){
		$sql="select filename,pagename from ". $prefix ."pages where type='". $_POST['content'] . "' and published=1;";
	}else if($_POST['content']=="products")
	{
		$sql="select product_name as pagename,pshort as filename   from ". $prefix ."products where published=1;";
	}else if($_POST['content']=="squeeze")
	{
		$sql="select name as pagename, name as filename  from ". $prefix ."squeeze_pages where published=1;";
	}else if($_POST['content']=="default")
	{
		$arr_default = array('/index.php' => 'Home page',
							 '/profile.php' => 'Profile Page', 
							 '/affiliate.php' => 'Affiliate Signup Page',
							 '/jvsign.php' => 'JV Partner Signup Page',
							 '/marketplace.php' => 'Outside Marketplace',
							 '/member/index.php' => 'Member home page',
							 '/member/marketplace.php' => 'Inside Marketplace',
							 '/logout.php' => 'Logout',
							 '/login.php' => 'Login',
							 '/downloads.php' => 'My Downloads & Coaching');
	}

	if($_POST['content'] == "default")
	{
		$str="";
		if(count($arr_default)>0)
		{
			$str.='<select name="page_content" id="page_content" onchange="changeurl()">
			<option value="0"> Select</option>';
			foreach($arr_default as $key => $value)
			{
			 $str.='<option value="' . $key . '">' . $value . '</option>';
			}
		$str.="</select>";
		}		
	}else{
		$data_row=$db->get_rsltset($sql);
		$str="";
		if(count($data_row)>0)
		{
			
			$str.='<select name="page_content" id="page_content" onchange="changeurl()">
			<option value="0"> Select</option>';
			
			foreach($data_row as $row)
			{
			 $str.='<option value="' . $row['filename'] . '">' . $row['pagename'] . '</option>';
			}
		$str.="</select>";
		
		}
	}
	
	
 echo $str; 
}
?>