<html>
<head>
<title>Edit a Server Record</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#666699" background="bk.jpg" text="#FFFFFF">
<a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a><p></p>
<hr>

<?php

//connect to the database
$hostname = "lbzds01"; 
$username = "sa"; 
$password = "knet"; 
$dbName = "jasontest"; 
//collect httpvars
$ServerName = $HTTP_GET_VARS["id"];
//
mssql_connect($hostname,$username,$password)or DIE("DATABASE FAILED TO RESPOND.");

//select database
mssql_select_db("Jasontest");
//query database
$query = "DELETE from server3 where server_name1 = '$ServerName'";
//get results
$result = mssql_query($query);
if (!$result)
{
echo "Query failed, check spelling and server name\n";
exit;
}
else 
 {
   echo" Deleting ";
   for($i=0; $i<250; $i++)
   {
   ?>
<?php
sleep(.7);
echo ".";
?>
<?php
}
?>
<?php
   echo "<p>";
   echo "Delete Complete";
 }
?>
<?php
sleep(1.5);
?>
<?php
   echo "<meta HTTP-EQUIV = 'Refresh' CONTENT = '0; URL = index.php'>";
?>
<p>&nbsp;</p><hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>
