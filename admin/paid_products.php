<?php

include_once("session.php");
include_once("header.php");
$GetFile = file("../html/admin/paid_products.html");
$Content = join("", $GetFile);
$Title = "Product Management";
$targetpage = "paid_products.php";

########################## Actions  ##########################
switch ($act) {
    case 'd':
        if ($obj_pri->canDelete($pageurl)) {
            if ($prodid == '1') {
                $msg = "nodel";
                header("Location:$targetpage?msg=$msg");
                exit();
            }
            $msg = delete_content($prodid, $db, $prefix);
            header("location:$targetpage?msg=$msg");
        } else {
            $msg = archive_content($prodid, 0, $db, $prefix);
            header("location:$targetpage?msg=$msg");
        }
        break;
    case 'a':
        $msg = archive_content($prodid, $state, $db, $prefix);
        header("location:$targetpage?msg=$msg");
        break;
    case 'c':
        $msg = create_copy($prodid, $db, $prefix);
        header("location:$targetpage?msg=$msg");
        break;
    
}


################## Functions ##################

function delete_content($id, $db, $prefix) {
   
    $db->insert("update " . $prefix . "products set trash = 1 where id ='$id'");
    return $msg = 'd';
}

function archive_content($id, $state, $db, $prefix) {
    $sql = "update " . $prefix . "products set published='$state' where id ='$id'";

    $db->insert($sql);
    if ($state == 1)
        return $msg = 'a';
    else
        return $msg = 'un';
}


function create_copy($prodid, $db, $prefix){
    $sql="select * from ".$prefix."products where id=$prodid";
    $row = $db->get_a_line($sql);
    
    $get_selected_oto = $otolist;
    $oto_dd = $db->get_oto($get_selected_oto);
    $product_name           = $row["product_name"].'-'.$row['id'];
    $product_name           = $db->quote($product_name);
   
    $pshort                 = preg_replace('/([^a-z0-9])+/i', '_', $row['pshort']);
    $pshort                 = preg_replace('/\_$/', '', $pshort);
    $pshort                 =$pshort.'-'.$row['id'];
    $pshort                 = $db->quote($pshort);
    $price                  = $db->quote($row["price"]);
    $commission             = $db->quote($row["commission"]);
    $jvcommission           = $db->quote($row["jvcommission"]);
    $image                  = $db->quote($row["image"]);
    $index_page             = $db->quote($row["index_page"]);
    $download_form          = $db->quote($row["download_form"]);
    $prodtype               = $db->quote($row["prodtype"]);
    $marketplace            = $db->quote($row["marketplace"]);
    $prod_description       = $db->quote($row["prod_description"]);
    $otocheck               = $db->quote($row["otocheck"]);
    $one_time_offer         = $db->quote($row['one_time_offer']);
    $otodowncheck           = $db->quote($row['otodowncheck']);
    $click_bank_url         = $db->quote($row['click_bank_url']);
    $down_one_time_offer    = $db->quote($row['down_one_time_offer']);
    $otolist                = $db->quote($row["otolist"]);
    $psponder               = $db->quote($row["psponder"]);
    $no_text                = $db->quote($row["no_text"]);
    $qlimit                 = $db->quote($row["qlimit"]);
    $quantity_cap           = $db->quote($row["quantity_cap"]);
    $quantity_met_page      = $db->quote($row["quantity_met_page"]);
    $subscription_active    = $db->quote($row["subscription_active"]);
    $period1_active         = $db->quote($row["period1_active"]);
    $period1_value          = $db->quote($row["period1_value"]);
    $period1_interval       = $db->quote($row["period1_interval"]);
    $srt                    = $db->quote($row["srt"]);
    $amount1                = $db->quote($row["amount1"]);
    $period2_active         = $db->quote($row["period2_active"]);
    $period2_value          = $db->quote($row["period2_value"]);
    $period2_interval       = $db->quote($row["period2_interval"]);
    $amount2                = $db->quote($row["amount2"]);
    $period3_value          = $db->quote($row["period3_value"]);
    $period3_interval       = $db->quote($row["period3_interval"]);
    $amount3                = $db->quote($row["amount3"]);
    $squeezename            = $db->quote($row["squeezename"]);
    $squeeze_check          = $db->quote($row["squeeze_check"]);
    $pp_header              = $db->quote($row["pp_header"]);
    $pp_return              = $db->quote($row["pp_return"]);
    $tcontent               = $db->quote($row["tcontent"]);
    $coaching               = $db->quote($row["coaching"]);
    $click_bank_url         = $db->quote($row['click_bank_url']);
    $add_in_sidebar       	= $db->quote($row['add_in_sidebar']);
	$member_marketplace     = $db->quote($row['member_marketplace']);
	$button_html            = $db->quote($row['button_html']);
	$button_forum         	= $db->quote($row['button_forum']);
	$button_link         	= $db->quote($row['button_link']);
    
	
	
    $set = "product_name			= {$product_name},";
    $set .= "pshort				= {$pshort},";
    $set .= "index_page				= {$index_page},";
    $set .= "download_form			= {$download_form},";
    $set .= "image				= '" . mysql_real_escape_string(str_replace("..", "", $file_path_paypal)) . "',";
    $set .= "alertpay_image			= '" . mysql_real_escape_string(str_replace("..", "", $file_path_alertpay)) . "',";
    $set .= "commission				= {$commission},";
    $set .= "jvcommission			= {$jvcommission},";
    $set .= "price      			= {$price},";
    $set .= "imageurl                           = '" . mysql_real_escape_string(str_replace("..", "", $imageurl)) . "',";
    $set .= "prod_description                   = {$prod_description},";
    $set .= "marketplace  			= {$marketplace},";
    $set .= "otocheck  				= {$otocheck},";
    $set .= "one_time_offer                     = {$one_time_offer},";
    $set .= "otodowncheck  			= {$otodowncheck},";
    $set .= "down_one_time_offer                = {$down_one_time_offer},";
    $set .= "psponder  				= {$psponder},";
    $set .= "no_text  				= {$no_text},";
    $set .= "quantity_cap		  	= {$quantity_cap},";
    $set .= "qlimit		  		= {$qlimit},";
    $set .= "quantity_met_page                  = {$quantity_met_page},";
    $set .= "subscription_active                = {$subscription_active},";
    $set .= "period1_active                     = {$period1_active},";
    $set .= "period1_value                      = {$period1_value},";
    $set .= "period1_interval                   = {$period1_interval},";
    $set .= "srt			  	= {$srt},";
    $set .= "amount1  				= {$amount1},";
    $set .= "period2_active                     = {$period2_active},";
    $set .= "period2_value                      = {$period2_value},";
    $set .= "period2_interval                   = {$period2_interval},";
    $set .= "amount2  				= {$amount2},";
    $set .= "period3_value                      = {$period3_value},";
    $set .= "period3_interval                   = {$period3_interval},";
    $set .= "amount3  				= {$amount3},";
    $set .= "squeezename  			= {$squeezename},";
    $set .= "squeeze_check                      = {$squeeze_check},";
    $set .= "pp_header 	 			= {$pp_header}, ";
    $set .= "pp_return	 	 		= {$pp_return},";
    $set .= "tcontent  				= {$tcontent},";
    $set .= "coaching	 	 		= {$coaching},";
    $set .= "prodtype				= {$prodtype},";
  
	$set .= "click_bank_url			= {$click_bank_url},";
	$set .= "add_in_sidebar		= {$add_in_sidebar},";
	$set .= "member_marketplace	= {$member_marketplace},";
	$set .= "button_html		= {$button_html},";
	$set .= "button_forum		= {$button_forum},";
	$set .= "button_link		= {$button_link}";
    $sql="insert into " . $prefix . "products set $set";
    $pid = $db->insert_data_id($sql);
    return 'copy';
   
    
}
 
        
######### Message ##########
switch ($msg) {
    case 'add':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Product is Successfully Added</div>';
        break;
    case 'paid':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Product is Successfully Edited</div>';
        break;
    case 'd':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Product is Successfully Deleted</div>';
        break;
    case 'all':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Products allocated to members successfully</div>';
        break;
    case 'nodel':
        $Message = '<div class="error"><img src="../images/crose.png" align="absmiddle"> Default product can not be deleted.</div>';
        break;
    case 'a':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Product is successfully Unarchived!</div>';
        break;
    case 'un':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Product is successfully Archived!</div>';
        break;
    case 'copy':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> A new copy of this product is successfully created!</div>';
        break;
}

########## pagination ###########
$fieldNamesArray = array(
    "field1" => "id",
    "field2" => "product_name",
    "field3" => "price"
);

$tbl_name = $prefix . "products";  //your table name
// How many adjacent pages should be shown on each side?
$adjacents = 3;

/*
  First get total number of rows in data table.
  If you have a WHERE clause in your query, make sure you mirror it here.
 */
$query = "SELECT COUNT(*) as num FROM $tbl_name where trash=0";
$total_pages = mysql_fetch_array(mysql_query($query));
$total_pages = $total_pages[num];

/* Setup vars for query. */
//your file name  (the name of this file)
if (isset($_GET["limit"])) {
    $limit = $_GET["limit"];
} else {
    $limit = 10;         //how many items to show per page
}

$page = $_GET['page'];

if (isset($_GET['col']) && isset($_GET['dir'])) {
    $fieldName = $_GET['col'];
    $field = $fieldNamesArray[$_GET['col']];
    $dir = $_GET['dir'];
} else {
    $fieldName = 'field1';
    $field = "id";
    $dir = "DESC";
}

if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;        //if no page var is given, set start to 0

/* Get data. */



/* Setup page vars for display. */
if ($page == 0)
    $page = 1;     //if no page var is given, default to 1.
$prev = $page - 1;       //previous page is page - 1
$next = $page + 1;       //next page is page + 1
$lastpage = ceil($total_pages / $limit);  //lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1;      //last page minus 1

/*
  Now we apply our rules and draw the pagination object.
  We're actually saving the code to a variable in case we want to draw it more than once.
 */
$pagination = "";
if ($lastpage > 1) {
    $pagination .= "<div class=\"pagination\">";
    //previous button
    if ($page > 1)
        $pagination.= "<a href=\"$targetpage?page=$prev&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">&lt;&lt; previous</a>";
    else
        $pagination.= "<span class=\"disabled\">&lt;&lt; previous</span>";

    //pages
    if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page)
                $pagination.= "<span class=\"current\">$counter</span>";
            else
                $pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
        }
    }
    elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
        //close to beginning; only hide later pages
        if ($page < 1 + ($adjacents * 2)) {
            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
            }
            $pagination.= "...";
            $pagination.= "<a href=\"$targetpage?page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
            $pagination.= "<a href=\"$targetpage?page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
        }
        //in middle; hide some front and some back
        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
            $pagination.= "<a href=\"$targetpage?page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
            $pagination.= "<a href=\"$targetpage?page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
            $pagination.= "...";
            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
            }
            $pagination.= "...";
            $pagination.= "<a href=\"$targetpage?page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
            $pagination.= "<a href=\"$targetpage?page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
        }
        //close to end; only hide early pages
        else {
            $pagination.= "<a href=\"$targetpage?page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
            $pagination.= "<a href=\"$targetpage?page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
            $pagination.= "...";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
            }
        }
    }

    //next button
    if ($page < $counter - 1)
        $pagination.= "<a href=\"$targetpage?page=$next&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">Next &gt;&gt;</a>";
    else
        $pagination.= "<span class=\"disabled\">Next &gt;&gt;</span>";
    $pagination.= "</div>\n";
}

function getDirection($currentField, $dir, $fieldName) {

    if ($fieldName == $currentField && $dir == 'DESC') {
        return "ASC";
    } else if ($fieldName == $currentField && $dir == 'ASC') {

        return "DESC";
    } else {

        return "ASC";
    }
}

function getCssClass($currentField, $dir, $fieldName) {

    if ($fieldName == $currentField && $dir == 'DESC') {
        return "sortDesc";
    } else if ($fieldName == $currentField && $dir == 'ASC') {

        return "sortAsc";
    } else {

        return "";
    }
}

function isSelected($currentValue, $limit) {
    if ($currentValue == $limit) {

        return 'selected="selected"';
    }
}

################### END OF PAGINATION ####################

$sql = "SELECT * FROM $tbl_name where trash=0 ORDER BY $field $dir LIMIT $start, $limit";
$result = mysql_query($sql);

$content = '<table width="904"><tr><td width="50%" valign="bottom"><span style="float:left;">Select Number of rows per page:</span><form name="limitForm" action="' . $targetpage . '#pagination" method="GET" style="float:left;"><select name="limit" onchange="document.limitForm.submit()" style="width:100px;"><option value="10"' . isSelected(10, $limit) . '>10</option><option value="25"' . isSelected(25, $limit) . '>25</option> 
	 <option value="50" ' . isSelected(50, $limit) . '>50</option><option value="100" ' . isSelected(100, $limit) . '>100</option></select>
	 </form></td>
	 <td width="50%" align="right">';

if ($obj_pri->canAdd($pageurl)) {
    $content .= '<div class="buttons">
		 			<a href="allocate_product.php">Allocate Products</a>
		 		</div> ';
    $content .= '<div class="buttons">
		 			<a href="add_product.php">Add New Product</a>
		 		</div>';
}
 if (mysql_num_rows(mysql_query("SELECT id FROM $tbl_name where trash=1")) > 0) {
 	 $content .= '<div class="buttons">
		 			<a href="product-trash.php">Trash Manager</a>
		 		</div>';
 }
$content .= '		
	 </td></tr>
	 </table> ';

$content .= '<table id="table" border="0" cellpadding="5" cellspacing="0" width="904" bgcolor="#FFFFFF" style="border: #000000 solid 4px;">
            <thead>
                    <tr class="list_results_colhead" id="pagination">
                            <th title="click to sort by this field" nowrap="nowrap">
                            <a href="' . $targetpage . '?col=field1&amp;dir=' . getDirection('field1', $dir, $fieldName) . '&amp;limit=' . $limit . '#pagination">Id</a>
                            <span class="' . getCssClass('field1', $dir, $fieldName) . '">&nbsp;</span>
                                    </th>
                            <th title="click to sort by this field" align="left">
                            <a href="' . $targetpage . '?col=field2&amp;dir=' . getDirection('field2', $dir, $fieldName) . '&amp;limit=' . $limit . '#pagination">
                            Product Title</a>
                            <span class=' . getCssClass('field2', $dir, $fieldName) . '>&nbsp;</span></th>
                            <th align="left">Short Name</th>
                            <th>Links</th>
                            <th>Product Type</th>
                            <th ><span>Coaching</span></th>
                            <th><a href="' . $targetpage . '?col=field3&amp;dir=' . getDirection('field3', $dir, $fieldName) . '&amp;limit=' . $limit . '#pagination">
                            Price</a>
                            <span class=' . getCssClass('field3', $dir, $fieldName) . '>&nbsp;</span></th>
                            <th align="left"><span>Operation</span><span class=>&nbsp;</span></th>

                    </tr>
            </thead>';

$i = 0;

while ($row = mysql_fetch_array($result)) {
    if ($i % 2 == 0) {
        $class = "standardRow";
    } else {
        $class = "alternateRow";
    }


    if ($row["date_added"]) {
        $dateAdded = date("M d, Y g:i a", strtotime($row["date_added"]));
    } else {
        $dateAdded = "-";
    }

    if ($row['coaching'] == 'no') {
        $manage_coaching = 'Disabled';
    } elseif ($row['coaching'] == 'yes') {
        $q = "select count(*) as cnt from " . $prefix . "member_messages where product='$row[pshort]' && checked='0'";
        $r22 = $db->get_a_line($q);
        $count1 = $r22[cnt];

        if ($count1 == '0') {
            $manage_coaching = '<a href=manage_coaching.php?pshort=' . $row['pshort'] . '>Manage Coaching</a>';
        } elseif ($count1 > '0') {
            $manage_coaching = '<a href=manage_coaching.php?pshort=' . $row['pshort'] . '>Manage Coaching</a> <span class="new">(' . $count1 . ' New)</span>';
        }
    }
    switch ($row['prodtype']) {
        case 'free':
            $prod_type = "Free";
            break;

        case 'paid':
            $prod_type = "Paid";
            break;

        case 'OTO':
            $prod_type = "OTO";
            break;
        case 'Clickbank':
            $prod_type = "Click Bank";
            break;
    }

    switch ($home_page_product) {
        case '1':
            $home_page_product = "Yes";
            break;

        case '0':
            $home_page_product = "No";
            break;
    }


    if ($obj_pri->canDelete($pageurl)) {
        if ($row['published'] == 0) {
            $delete = '<a href="' . $targetpage . '?act=d&prodid=' . $row['id'] . '"><img src="../images/addmin/trash-empty.png" alt="trash" title="Click to delete this product" Onclick="return confirm(' . "'" . 'Are you sure! you want to delete this product?' . "'" . ')"></a>';
        } else {
            $delete = '<a href="' . $targetpage . '?act=d&prodid=' . $row['id'] . '"><img src="../images/admin/trash-empty.png" alt="trash" title="Click to delete this product" Onclick="return confirm(' . "'" . 'Are you sure! you want to delete this product?' . "'" . ')"></a>';
        }
    } else {
        if ($row['published'] == 0) {
            $delete = '<a href="' . $targetpage . '?act=a&prodid=' . $row['id'] . '&state=1"><img src="../images/cross-gray.png" alt="editImage" title="Click to unarchive this product"></a>';
        }
        else
            $delete='<a href="' . $targetpage . '?act=a&prodid=' . $row['id'] . '&state=0"><img src="../images/crose.png" alt="editImage" title="Click to archive this product"></a>';
    }
    if($row['id']==1){
        $delete='<img src="../images/admin/default-product.png" alt="default product" title="Default Product can not be deleted."  >';
        }
		
	if((int) $row["price"]<=0)	$price = $row["amount3"];
	else $price = $row["price"];
	$sub = ($row["subscription_active"]==1)? 'Subscription':'';
    $content .= '<tr class="' . $class . '" onmouseover="this.style.backgroundColor=\'#f1ca47\'" onmouseout="this.style.backgroundColor=\'\'"> ';
    $content .='<td align="center" valign="middle" >' . $row['id'] . '</td>
					<td valign="middle" >' . $row["product_name"] . '</td></td>
					<td align="left" valign="middle" >' . $row["pshort"] .'</td>
					<td align="center" valign="middle" nowrap="nowrap">
					<a href="productlink.php?product_name=' . $row['pshort'] . '&iframe=true&width= 750px&height=290px" rel="prettyPhoto">
                                            <img src="../images/yellowlink.png" alt="linkImage" title="Click to go to the product page">
					</a>
					</td>
					<td align="center" valign="middle"  nowrap="nowrap">' . $prod_type . '<br /><small>'. $sub .'</small>'.  '</td>
					<td align="center" valign="middle"  >' . $manage_coaching . '</td>
					<td align="center" valign="middle">' . $price . '</td>
					<td align="left" valign="middle" nowrap="nowrap">
                                        
					<span style="padding:0 5px;">
                                           <a href="edit_product.php?prodid=' . $row['id'] . '"><img src="../images/editIcon.png" alt="editImage" title="Click to edit this product" ></a> 
                                         </span> 
                                        <span style=" padding:0 5px;"">
                                           <a href="' . $targetpage . '?act=c&prodid=' . $row['id'] . '"><img src="../images/admin/copy.png" alt="copy" title="Click here to create a copy this product" ></a>
                                        </span>    
                                        <span style=" padding:0 5px;"">
                                           <a href="product-shorturl.php?pid=' . $row['id'] . '"><img src="../images/admin/redirect.png" alt="redirect" title="Click here to create a redirect URL for this product" ></a>
                                        </span> 
										   
										   
                                        <span style=" padding:0 5px;"">' . $delete . '<span>
                                           
                                            
					</td>
				</tr>';
    $i++;
}

if ($start == 0) {
    $totalrec = $startrec + $i;
    $startrec = $start + 1;
} else {
    $startrec = $start;
    $totalrec = $startrec + $i;
}


$content .= '</table><div class="pages">';
$content .= '<div class="totalpages">Total: ' . $startrec . ' - ' . $totalrec . ' of  ' . $total_pages . '</div>';
$content .= '<div class="pager">' . $pagination . '&nbsp;</div></div>';
$content .= '<div><a href="#top" style="text-align:center;">Move to top</a></div>';





$Content = preg_replace("/{{(.*?)}}/e", "$$1", $Content);
echo $Content;
include_once("footer.php");
?>