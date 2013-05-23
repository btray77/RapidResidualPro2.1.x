<?php

class database
{
	//=====================================================================================
	function database()//Constructor to connect to the database
	{
		include("../../common/config.php");
		$DB_HOST	=	$host; // hostname
		$DB_NAME	=	$dbname; // database name
		$DB_USER	=	$dbuser; // database username
		$DB_PASS	=	$dbpass; // database password

		
		$dbh = mysql_connect ($DB_HOST, $DB_USER, $DB_PASS);
		mysql_selectdb($DB_NAME);
		return $dbh;
		mysql_close($dbh);
  	}//function database()

	//=====================================================================================

	//=====================================================================================

	function get_rsltset($mysql) //Retrieves a resultset based on the query
	{
		//    $result = mysql_query($sqlqry);
    		if (! ($result = mysql_query ("$mysql")))//$mysql is for the query
    		{
      			$men = mysql_errno();
      			$mem = mysql_error();
      			echo ("<h4>$mysql  $men $mem</h4>");
      			exit;
    		}
		else
    		{
			$xx = 0 ;
      			while ( $row = mysql_fetch_array ($result) ) 
      			{
        			$rsltset[$xx] = $row;
				$xx++ ;
      			}
      			mysql_free_result($result);
      			return $rsltset;  
    		}
 	}//function get_rsltset()

	//=====================================================================================

	//=====================================================================================

	function get_a_line($sqlqry)//Retrieves a single record based on the query
  	{
     		if (! ($result = mysql_query ("$sqlqry")))
     		{
        		$men = mysql_errno();
        		$mem = mysql_error();
        		echo ("<h4>$sqlqry  $men $mem</h4>");
        		exit;
     		}
		$line = mysql_fetch_array ($result);
     		mysql_free_result ($result);
     		return $line;
  	}//function get_a_line()

	//=====================================================================================

	//====================================================================================

	function insert($mysql)
  	{
      		if (! (mysql_query ("$mysql")))//$mysql is for the query
        	{
          		$men = mysql_errno();
          		$mem = mysql_error();
          		echo ("<h4>$mysql  $men $mem</h4>");
	  		exit;
        	}
	}//function insert()

	//===================================================================================

	//===================================================================================

	function insert_data_id($mysql)
 	{
		if (!mysql_query ("$mysql"))
		{
			$men = mysql_errno();
			$mem = mysql_error();
			echo ("<h4>$mysql  $men $mem</h4>");
			exit;
		}
 		$r=mysql_insert_id();
  		return $r;
	}//end insert data id

	//====================================================================================

  	//====================================================================================

	function get_single_column ($mysql)
  	{
     		$x = 0;
     		$result = mysql_query($mysql);
     		while ( $row = mysql_fetch_array ($result) ) 
     		{
       			$q[$x] = $row[0];
       			$x++;
     		}
     		mysql_free_result ($result);
     		return $q;
	}//access using $q[1]["fieldname"] or $q[1][3] etc

	//======================================================================================

	//======================================================================================	

	function check($table,$column,$v1)
	{
     		if (! $result=mysql_query ("select * from $table where $column ='$v1'"))
		{
	       		$men = mysql_errno();
         		$mem = mysql_error();
         		echo ("<h4>$mysql  $men $mem</h4>");
         		exit();
		}
     		$row=mysql_fetch_array ($result);
     		mysql_free_result ($result);
     		if ($row[0])
    			$var =  1;
     		else
			$var =  0;
     		return $var;
	}//function check()

	//=====================================================================================

	//=====================================================================================

	function check_edit($table,$column1,$v1,$column2,$v2)	
	{
		if (! $result=mysql_query ("select * from $table where $column2 !=$v2 and $column1='$v1' "))
		{
        		$men = mysql_errno();
         		$mem = mysql_error();
         		echo ("<h4>$mysql  $men $mem</h4>");
         		exit();
		}
    		$row=mysql_fetch_array ($result);
     		mysql_free_result ($result);
     		if ($row[0])
			$var =  1;
     		else
			$var =  0;
     		return $var;
	}//function check_edit()

	//=====================================================================================

	

}//end of class database
?>