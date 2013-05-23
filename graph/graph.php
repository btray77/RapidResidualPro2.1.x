<?php
include('../admin/include.php');

$id=$_REQUEST['id'];

$row_start=$db->get_a_line("SELECT date_format(DATE_ADD(NOW(), INTERVAL -15 DAY),'%b') as start_month");
$start_month=$row_start['start_month'];
$row_end=$db->get_a_line("SELECT date_format(DATE_ADD(NOW(), INTERVAL 15 DAY),'%b') as end_month");
$end_month=$row_end['end_month'];


$row_start=$db->get_a_line("SELECT date_format(DATE_ADD(NOW(), INTERVAL -15 DAY),'%Y-%m-%d') as start_date");
$start_date=$row_start['start_date'];
$row_end=$db->get_a_line("SELECT date_format(DATE_ADD(NOW(), INTERVAL 15 DAY),'%Y-%m-%d') as end_date");
 $end_date=$row_end['end_date'];
$year =date('Y');
if($_REQUEST['id']==1)
{
   $title="Total Traffic of  $start_month-$end_month $year";
   $sql="SELECT date_format(visited_date,'%Y %b %d') as visited_date,
        count(id) as visited FROM `".$prefix."click_stats` where visited_date between '$start_date'
        and '$end_date' group by visited_date";
    $rows=$db->get_rsltset($sql);
    if(count($rows) > 0){
        foreach ($rows as $row){
            $str .=  '["'.$row['visited_date'].'",'.$row['visited'].'],';  
            
        }
    }
    else
    {
        echo "<div style='width: auto;margin: 19% auto;font-size:20px;color:#555'>There is no traffic in $start_month-$end_month $year</div>"; 
    }
   
}
else
{
     $title="Total Sales of  $start_month-$end_month $year";
     $sql="SELECT date_format(date,'%Y %b %d') as date,
        count(id) as orders FROM `".$prefix."orders` where date between '$start_date'
        and '$end_date' and payment_status='Completed' and txnid <> 'FREE' and txnid <> 'ALLOCATED' group by date";
    $rows=$db->get_rsltset($sql);
    if(count($rows) > 0){
        foreach ($rows as $row){
            $str .=  '["'.$row['date'].'",'.$row['orders'].'],';  
            
        }
    }
    else
    {
        echo "<div style='width: auto;margin: 19% auto;font-size:20px;color:#555'>There is no sale in $start_month-$end_month $year</div>";
        
    }
    
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  
<script class="code" type="text/javascript">
/*******************************************************************************************************/
$(document).ready(function(){
 /*****************************************************************************************************************/
 
	var line2=[	
	       	  <?php echo $str;?>  
		 ]  
	
			    
	 var plot = $.jqplot('chart<?php echo $id;?>', [line2], {
      title:"<?echo $title?>",
      axes:{
        xaxis:{
          renderer:$.jqplot.DateAxisRenderer,
          tickOptions:{
            formatString:'%b&nbsp;%#d'
          } 
        },
        yaxis:{
          tickOptions:{
           
			markSize : 6
            }
        }
      },
      highlighter: {
        show: true,
        sizeAdjust: 6.5
      },
      
  }); 
  
   
  
  
});
</script>
    </head>
    <body>
       
		<div class="jqplot-target" id="chart<?php echo $id;?>">
            
        </div>
    </body>
    </html> 
    




  
