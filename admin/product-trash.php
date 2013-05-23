<?php

include_once("session.php");
include_once("header.php");
$GetFile = file("../html/admin/paid_products.html");
$Content = join("", $GetFile);
$Title = "Product Management";
$targetpage = "product-trash.php";

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
    case 'r':
      $msg = restore_content($prodid, $db, $prefix);
        header("location:$targetpage?msg=$msg");
        break;
    
        
    
}


################## Functions ##################

function delete_content($id, $db, $prefix) {
  
   $sql = "select *,max(id) as id from " . $prefix . "products where id ='$id'";
    $row = $db->get_a_line($sql);
    $paypal_image = ".." . $row['image'];
    $alertpay_image = ".." . $row['alertpay_image'];
    $product_image = ".." . $row['imageurl'];
    if (!empty($paypal_image)) {
        @unlink($paypal_image);
    }
    if (!empty($alertpay_image)) {
        @unlink($alertpay_image);
    }
    if (!empty($product_image)) {
        @unlink($product_image);
    }
    $db->insert("delete from " . $prefix . "products where id ='$id'");
    return $msg = 'd';
}

function restore_content($id, $db, $prefix) {
  
    $db->insert("update " . $prefix . "products set trash = 0 where id ='$id'");
    return $msg = 'r';
}

 
        
######### Message ##########
switch ($msg) {
   
    case 'd':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Product is Successfully Deleted</div>';
        break;
	case 'r':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Product is Successfully Restored</div>';
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
$query = "SELECT COUNT(*) as num FROM $tbl_name where trash=1";
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

$sql = "SELECT * FROM $tbl_name where trash=1 ORDER BY $field $dir LIMIT $start, $limit";
$result = mysql_query($sql);

$content = '<table width="904"><tr><td width="50%" valign="bottom"><span style="float:left;">Select Number of rows per page:</span><form name="limitForm" action="' . $targetpage . '#pagination" method="GET" style="float:left;"><select name="limit" onchange="document.limitForm.submit()" style="width:100px;"><option value="10"' . isSelected(10, $limit) . '>10</option><option value="25"' . isSelected(25, $limit) . '>25</option> 
	 <option value="50" ' . isSelected(50, $limit) . '>50</option><option value="100" ' . isSelected(100, $limit) . '>100</option></select>
	 </form></td>
	 <td width="50%" align="right">';

  $content .= '<div class="buttons">
		 			<a href="paid_products.php">Products Listings</a>
		 		</div>';
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


  $restore = '<a href="' . $targetpage . '?act=r&prodid=' . $row['id'] . '"><img src="../images/admin/restore.png"  align="absmiddle" alt="trash" title="Click to restore this product" Onclick="return confirm(' . "'" . 'Are you sure! you want to restore this product?' . "'" . ')"></a>';
	
$delete = '<a href="' . $targetpage . '?act=d&prodid=' . $row['id'] . '"><img src="../images/crose.png" alt="delete" align="absmiddle" title="Click to delete this product" Onclick="return confirm(' . "'" . 'Are you sure! you want to delete this product?' . "'" . ')"></a>';	
	
    $content .= '<tr class="' . $class . '" onmouseover="this.style.backgroundColor=\'#f1ca47\'" onmouseout="this.style.backgroundColor=\'\'"> ';
    $content .='<td align="center" valign="middle" >' . $row['id'] . '</td>
					<td valign="middle" nowrap="nowrap">' . $row["product_name"] . '</td></td>
					<td align="left" valign="middle" nowrap="nowrap">' . $row["pshort"] . '</td>
					<td align="center" valign="middle" nowrap="nowrap">
					<a href="productlink.php?product_name=' . $row['pshort'] . '&iframe=true&width= 750px&height=290px" rel="prettyPhoto">
                                            <img src="../images/yellowlink.png" alt="linkImage" title="Click to go to the product page">
					</a>
					</td>
					<td align="center" valign="middle"  nowrap="nowrap">' . $prod_type . '</td>
					<td align="center" valign="middle"  >' . $manage_coaching . '</td>
					<td align="center" valign="middle">' . $row["price"] . '</td>
					<td align="left" valign="middle" nowrap="nowrap">
                                        
					<span style="padding:0 5px;">
                      <a href="edit_product.php?prodid=' . $row['id'] . '"><img src="../images/editIcon.png"   align="absmiddle" alt="edit" title="Click to edit this product" ></a> 
                    </span> 
				  	<span style="padding:0 5px;">' . $restore . '</span> 
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