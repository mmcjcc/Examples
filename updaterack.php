<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Update Server Record</title>
</head>

<p>
<body>
<body bgcolor="#666699"">
<body link="#FF0000" alink="#FFFF00">
<font color='#FFFFFF'>
<body bgcolor="#666699" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF"background="bk.jpg">

	
	<a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a><table>	

		

<?php
//connect to the database
$hostname = "lbzds01"; 
$username = "sa"; 
$password = "knet"; 
MSSQL_CONNECT($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");
mssql_select_db("Jasontest");

//UPDATE Person
//SET Address = 'Stien 12', City = 'Stavanger'
//WHERE LastName = 'Rasmussen' 
$query2 = 
"
Select * from Racks

";
$result = mssql_query($query2);

$numrows = mssql_num_rows($result);


for ($i = 0; $i < $numrows; $i++)
 {
  $ServerName= mssql_result($result, $i,0);
   $RackNum = mssql_result($result, $i,1);
$query1 = 
"
UPDATE Server3
SET  Rack = '$RackNum'
WHERE Server_Name1 = '$ServerName'
";

$result2 = mssql_query($query1);
if (!$result)
{
echo "query failed\n";
exit;
}

else 
 {
   echo "Update Complete <br>";
   
 }
 }
 
?>
 

</body>
</html>
