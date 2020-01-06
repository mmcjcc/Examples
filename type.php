<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Server Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>

<p>
<body background="bk.jpg" link="#CCCCCC" vlink="#CCCCCC"><body bgcolor="#666699" text="#FFFFFF">
<body alink="#FFFF00"> 
<body bgcolor="#666699" text="#FFFFFF" link="#CCCCCC" vlink="#CCCCCC" alink="#CCCCCC">
<body bgcolor="#666699" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF"background="bk.jpg">

	
	<a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a><p></p>
<hr>
<p></p>
<?php
include 'classes.php'; 
 
//collect httpvars

$Server_Type = $HTTP_POST_VARS["ServerType"];

//
//connect to the database
$hostname = "lbzds01"; 
$username = "sa"; 
$password = "knet"; 
$dbName = "jasontest";
mssql_connect($hostname,$username,$password)or DIE("DATABASE FAILED TO RESPOND.");
//MSSQL_CONNECT($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");
//select database
mssql_select_db("Jasontest");
//query database


$query = "select server_name1 from server3";
$result = mssql_query($query);
if (!$result || mssql_result($result, 0,0) == '')
{
echo "Query failed, check spelling and server name\n";
exit;
}

else 
{
$numrows = mssql_num_rows($result);
//echo $numrows;
//echo (mssql_result($result, $i,0));
$pattern = $Server_Type;

for ($i = 0; $i < $numrows; $i++)
{
$check = mssql_result($result, $i,0);

if (strpos($check,$pattern))
{
echo $check ."<p>";
}
}
}
mssql_close(); //close DB connection

?>


<p>&nbsp;</p><hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>

