<?php

include "session.php";
include "header.php";
$GetFile = file("../html/admin/aweber.html");
$Content = join("", $GetFile);
$Title = "Aweber Management";

/* if($act == "d")
  {
  $db->insert("delete from ".$prefix."responders where id ='$id'");
  $msg = "d" ;
  }

  $Pat = "/<{Begin}>(.*?)<{End}>/s";
  preg_match($Pat,$Content,$Output);
  $SelectedContent = $Output[1]; */

switch ($act) {
    case 'd':
        if ($obj_pri->canDelete($pageurl)) {

            $msg = delete_content($id, $db, $prefix);
            header("location:aweber.php?msg=$msg");
        } else {
            $msg = archive_content($id, 0, $db, $prefix);
            header("location:aweber.php?msg=$msg");
        }
        break;
    case 'a':

        $msg = archive_content($id, $state, $db, $prefix);
        header("location:aweber.php?msg=$msg");
        break;
}
#################### Messages ########################
switch ($msg) {
    case 'add':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle">Responder is Successfully Added!</div>';
        break;
    case 'edit':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Responder is Successfully Edited!</div>';
        break;
    case 'd':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Responder is Successfully Deleted!</div>';
        break;
    case 'a':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Responder is successfully Unarchived!</div>';
        break;
    case 'un':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Responder is successfully Archived!</div>';
        break;
}

################# END MESSAGES ######################.
########## Functions ##################

function delete_content($id, $db, $prefix) {
    $db->insert("delete from " . $prefix . "responders where id ='$id'");
    return $msg = 'd';
}

function archive_content($id, $state, $db, $prefix) {

    $db->insert("update " . $prefix . "responders set `published`='$state' where id ='$id';");
    if ($state == 1)
        return $msg = 'a';
    else
        return $msg = 'un';
}

########## pagination ###########

$fieldNamesArray = array(
    "field1" => "id",
    "field2" => "rspname2"
);

$tbl_name = $prefix . "responders";  //your table name
// How many adjacent pages should be shown on each side?
$adjacents = 3;

/*
  First get total number of rows in data table.
  If you have a WHERE clause in your query, make sure you mirror it here.
 */

$query = "select count(*) as cnt from " . $tbl_name . " where rspname ='Aweber'";
$total_pages = mysql_fetch_array(mysql_query($query));
$total_pages = $total_pages[cnt];

/* Setup vars for query. */
$targetpage = "aweber.php";  //your file name  (the name of this file)
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

$sql = "select * from $tbl_name where rspname ='Aweber' order by $field $dir  limit $start,$limit";
$result = mysql_query($sql);

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

###### end pagination ##########
$content = '
	 <table width="904"><tr><td width="50%" valign="bottom"><span style="float:left;">Select Number of rows per page:</span><form name="limitForm" action="' . $targetpage . '#pagination" method="GET" style="float:left;">
	 <select name="limit" onchange="document.limitForm.submit()" style="width:100px;"><option value="10"' . isSelected(10, $limit) . '>10</option><option value="25"' . isSelected(25, $limit) . '>25</option> 
	 <option value="50" ' . isSelected(50, $limit) . '>50</option><option value="100" ' . isSelected(100, $limit) . '>100</option></select>
	 </form></td>
	 <td width="50%" align="right">';

if ($obj_pri->canAdd($pageurl)) {
    $content .= '<div class="buttons">
			<a href="addaweber.php">Add New Responder</a>
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
                                        <span
                                                class="' . getCssClass('field1', $dir, $fieldName) . '">&nbsp;</span>
                                         </th>
                                         <th title="click to sort by this field" nowrap="nowrap" width="83%">
                                        <a href="' . $targetpage . '?col=field2&amp;dir=' . getDirection('field2', $dir, $fieldName) . '&amp;limit=' . $limit . '#pagination">Responder Name</a>
                                        <span
                                                class="' . getCssClass('field2', $dir, $fieldName) . '">&nbsp;</span>
                                         </th>
                                        <th width="200"><span>Edit/Delete</span><span class=>&nbsp;</span></th>

                                </tr>
                        </thead>';

$i = 0;

while ($row = mysql_fetch_array($result)) {
    if ($i % 2 == 0) {
        $class = "standardRow";
    } else {
        $class = "alternateRow";
    }




    if ($obj_pri->canDelete($pageurl)) {
        if ($row['published'] == 0) {
            $delete = '<a href="aweber.php?act=d&id=' . $row['id'] . '">
			<img src="../images/cross-gray.png" alt="editImage" title="Click to delete this content page" Onclick="return confirm(' . "'" . 'Are you sure! you want to delete this page?' . "'" . ')">
			</a>';
        } else {
            $delete = '<a href="aweber.php?act=d&id=' . $row['id'] . '">
			<img src="../images/crose.png" alt="editImage" title="Click to delete this content page" Onclick="return confirm(' . "'" . 'Are you sure! you want to delete this page?' . "'" . ')">
			</a>';
        }
    } else {
        if ($row['published'] == 0) {
            $delete = '<a href="aweber.php?act=a&id=' . $row['id'] . '&state=1">
			<img src="../images/cross-gray.png" alt="editImage" title="Click to unarchive this content page.">
			</a>';
        }
        else
            $delete='<a href="aweber.php?act=a&id=' . $row['id'] . '&state=0">
			<img src="../images/crose.png" alt="editImage" title="Click to archive this content page">
			</a>';
    }

    $content .= '<tr class="' . $class . '" onmouseover="this.style.backgroundColor=\'#f1ca47\'" onmouseout="this.style.backgroundColor=\'\'"> ';
    $content .='<td align="center" valign="middle" >' . $row['id'] . '</td>
                <td valign="middle"  align="center">						
                ' . $row["rspname2"] . '
                </td>
                <td align="center" valign="middle">
                <div style="float:left;padding-left:16px;">
                <a href="editaweber.php?act=e&id=' . $row['id'] . '"><img src="../images/editIcon.png" alt="editImage" title="Click To Edit This Content Page" ></a> </div> 
                <div style=" float:right;padding-left:10px;"">' . $delete . '
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
include "footer.php";
?>