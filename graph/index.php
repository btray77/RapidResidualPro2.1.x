<?php
include('../admin/include.php');



$row_start=$db->get_a_line("SELECT date_format(DATE_ADD(NOW(), INTERVAL -15 DAY),'%b') as start_month");
$start_month=$row_start['start_month'];
$row_end=$db->get_a_line("SELECT date_format(DATE_ADD(NOW(), INTERVAL 15 DAY),'%b') as end_month");
$end_month=$row_end['end_month'];


$row_start=$db->get_a_line("SELECT date_format(DATE_ADD(NOW(), INTERVAL -15 DAY),'%Y-%m-%d') as start_date");
$start_date=$row_start['start_date'];
$row_end=$db->get_a_line("SELECT date_format(DATE_ADD(NOW(), INTERVAL 15 DAY),'%Y-%m-%d') as end_date");
 $end_date=$row_end['end_date'];
$year =date('Y');

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
        echo "There is no traffic in $start_month-$end_month $year"; 
    }
   


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
.jqplot-target{
height: 220px;
width: 550px;
position: relative;
padding:5px;
margin-left:3px;
}

#loading{
width: auto;
margin: 19% auto;
display: none;
}

</style>
    
    
 <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.min.js"></script><![endif]-->
  <script language="javascript" type="text/javascript" src="jquery_002.js"></script>
  <script language="javascript" type="text/javascript" src="jquery.js"></script>
  <link href="../common/newLayout/css.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="css/jquery.css">
  <link rel="stylesheet" type="text/css" href="css/graph.css">
  <!-- title -->
  <title>Data Point Highlighting, Tooltips and Cursor Tracking | jqPlot</title>
  <!-- and title -->

  <!-- jqPlot renderers and plugins -->
  <script class="include" language="javascript" type="text/javascript" src="plugins/jqplot.highlighter.js"></script>
  
  <script class="include" language="javascript" type="text/javascript" src="plugins/jqplot.dateAxisRenderer.js"></script>
  <!-- end jqPlot renderers and plugins -->

 
<script class="code" type="text/javascript">
/*******************************************************************************************************/
$(document).ready(function(){
 
 $(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
                if(activeTab=='#tab1')
                {
                    dataString = 1;
                    $("#loading").css('display','block');
                        $("#tab1").html('');
                        $("#tab1").css('display','block');
                        $.ajax({
                        type: "POST",
                        url: "graph.php",
                        data: 'id='+dataString,
                        dataType: "html",
                        cacheBoolean:false,
                        success: function(data) {
						$("#loading").css('display','none');		
                           $("#tab1").html('');
                            $("#tab1").append(data);
                             
                        },
                             error: function(){
                                $(activeTab).append("Unable to find file");
                            }
                        });
                }
                else if(activeTab=='#tab2')
                {
                    dataString = 2;
                     
                        $("#tab1").html('');
                        $("#tab1").css('display','block');
                        $("#loading").css('display','block');
                        $.ajax({
                        type: "POST",
                        url: "graph.php",
                        data: 'id='+dataString,
                        dataType: "html",
                        cacheBoolean:false,
                        success: function(data) {
                              $("#loading").css('display','none');		
                           $("#tab1").html('');
                           $("#tab1").append(data);
                           
                        },
                             error: function(){
                                $(activeTab).append("Unable to find file");
                            }
                        });
                }   
                       
                  
                    
               
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});
 /*****************************************************************************************************************/
 
var line1=[ <?php echo $str;?> ]  
	
			    
	 var plot = $.jqplot('chart1', [line1], {
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
      }
     
  }); 
  
  
  
  
  
  
});
</script>
  </head>
    <body>
  <div id="tabscontainer">
            <ul class="tabs">
                    <li><a href="#tab1">Monthly Traffic</a></li>
                    <li><a href="#tab2">Monthly Sale</a></li>
            </ul>
            <div class="tab_container">
                 <div id="loading"><img src="/images/admin/loader.gif" border="0"></div>
                    <div id="tab1" class="tab_content">
                        <div class="jqplot-target" id="chart1"></div>
                   
             </div>        
</div>	
	  
    


    </body>
</html>

  
