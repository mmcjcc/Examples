<html>
<head>
<title>Create Comma Deliminated File</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<body link="#CCCCCC" vlink="#CCCCCC"><body bgcolor="#666699" text="#FFFFFF">
<body alink="#FFFF00"> 
<p align="center"><font size="+3"><strong>CMS DATA CENTER RESOURCE 
  LOCATION AND INFORMATION PAGE</strong> </font></p>

<hr>
<p></p>
<?php
$hostname = "lbzds01"; 
$username = "sa"; 
$password = "knet"; 
$dbName = "jasontest"; 
//collect httpvars
$query = "Select Server_Name1 from server3";
//
mssql_connect($hostname,$username,$password)or DIE("DATABASE FAILED TO RESPOND.");
//MSSQL_CONNECT($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");
//select database
mssql_select_db("Jasontest");
//$query = "Select * from patches";
//get results
$result = mssql_query($query);
if (!$result)
{
echo "Query failed, Patch Data not avalible";
exit;
}

else
{
echo" <p>Generating.....";
$numrows = mssql_num_rows($result);
echo $numrows;
echo " Servers to Check...<p>";
$output ='';
for ($i = 0; $i < $numrows; $i++)
{

$output = $output . mssql_result($result, $i,0).", ".mssql_result($result, $i,1).", ". mssql_result($result, $i,2).", " .mssql_result($result, $i,3).", ". mssql_result($result, $i,4). "/n";
}
echo $output;
  
}



?>
</body>
</html>
