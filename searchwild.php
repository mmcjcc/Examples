<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Server Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

<p>

<body bgcolor="#666699" text="#FFFFFF" link="#CCCCCC" vlink="#CCCCCC" alink="#CCCCCC"background="bk.jpg">
<a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a><p></p>
<hr>
<p></p>
<?php
//include 'classes.php'; 
 
//collect httpvars
$Type = $HTTP_POST_VARS["Type"];
$Details = $HTTP_POST_VARS["Details"];
//echo $Type ."<br>";
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

if ($Type=='All')
{
$query = "select * from server3 Order BY [domain]";
}
else if ($Type=='Windows')
{
$query = "select * from server3 where [domain] <> 'N/A' AND [domain] <> 'UNKNOWN' ORDER BY [Domain]";
}
else if ($Type=='Non_Windows')
{
$query = "select * from server3 where [domain] LIKE 'N/A'";
}
else if ($Type=='DEV.HCFA.GOV')
{
$query = "select * from server3 WHERE [domain] LIKE 'DEV.HCFA.GOV'";
}

else if ($Type=='PROD.HCFA.GOV')
{
$query = "select * from server3 WHERE [domain] LIKE 'PROD.HCFA.GOV'";
}
else if ($Type=='HCFA.GOV')
{
$query = "select * from server3 WHERE [domain] LIKE 'HCFA.GOV'";
}
else
{
$query = "select * from server3";
$TypeSearch = true;
}
$query = "select * from server3 Order BY [domain]";
//echo" The Query was ".$query ."<br>";
//get results
$result = mssql_query($query);
if (!$result || mssql_result($result, 0,0) == '')
{
echo "Query failed, check spelling and server name\n";
exit;
}


$numrows = mssql_num_rows($result);
//echo "The number of database hits are ".$numrows."<br>";
for ($i = 0; $i < $numrows; $i++)
 {

$pattern = $Type;
for ($j=0; $j<=21; $j++)
{
$check = mssql_result($result, $i,$j);
//echo "Checking ".$check. "<br>";
if (stristr($check,$pattern)!='')
 {
 //echo"Match Found <br>";
$ServerName= mssql_result($result, $i,0);
echo "<font size='+1'><a href='edit.php?id=".$ServerName ."'>Edit This Record</a>, ";
echo "<a href='delete.php?id=".$ServerName ."'>Delete This Record</a>, ";

$OS = mssql_result($result, $i,10);
if ($OS == 'Windows 2000'||$OS == 'Win2k Adv')
{
echo "<a href='http://dcrl/TSWeb/default.php?id=".$ServerName ."'target='_blank'>Connect via Terminal Services</a>,";
}
$Manufac= mssql_result($result, $i,11);
  if ($Manufac == 'Compaq' || $Manufac =='COMPAQ' || $Manufac =='compaq' || $Manufac =='HP')
  {
    echo "<a href='https://".$ServerName .":2381'target='_blank'>Connect to Insight Manager</a>";
  }
$Domain= mssql_result($result, $i,3);
echo"<font size='+1'>
<table width='80%' border='1' >
  <font size='+1'>
  <tr>
    <td><font size='+1'>Server Name: ". mssql_result($result, $i,0). "</td>
    <td><font size='+1'>IP: " .mssql_result($result, $i,1). "</td>
  </tr>
  <tr>
    <td><font size='+1'>Rack Number: ".mssql_result($result, $i,2)."</td>
    <td><font size='+1'>Domain: ".mssql_result($result, $i,3). "</td>
  </tr>
  <tr>
    <td><font size='+1'>Backup Server Name: ". mssql_result($result, $i,4)."</td>
    <td><font size='+1'>Backup Job Name: ". mssql_result($result, 0,5). "</td>
  </tr>
  <tr>
    <td><font size='+1'>Server Location: ". mssql_result($result, $i,6)."</td>
    <td><font size='+1'>Asset Tag: ".mssql_result($result, $i,7)."</td>
  </tr>
  <tr>
    <td>";
if (mssql_result($result, $i,8) != '')
{
echo "<font size='+1'>Install Date: ". mssql_result($result, $i,8);

}
else 
{
echo "<font size='+1'>Install Date: Not Provided ";
}
echo"</td>
    <td><font size='+1'>Status: ".mssql_result($result, $i,9)." </td>
  </tr>
  <tr>
    <td><font size='+1'>OS: ". mssql_result($result, $i,10)."</td>
    <td><font size='+1'>Manufacturer: ". mssql_result($result, $i,11)."</td>
  </tr>
  <tr>
    <td><font size='+1'>Model: ". mssql_result($result, $i,12)."</td>
    <td><font size='+1'>Serial: ".mssql_result($result, $i,13)."</td>
  </tr>
  <tr>
    <td><font size='+1'>Function: ".mssql_result($result, $i,14). "</td>
    <td><font size='+1'>Type: ".mssql_result($result, $i,15)."</td>
  </tr>
  <tr>
    <td><font size='+1'>SLA: ".mssql_result($result, $i,16). "</td>
    <td><font size='+1'>Class: ".mssql_result($result, $i,17)."</td>
  </tr>
  <tr>
    <td><font size='+1'>Responsible Admin: ".mssql_result($result, $i,18)."</td>
    <td><font size='+1'>Application Owner: ".mssql_result($result, $i,19)."</td>
  </tr>
  <tr>
    <td><font size='+1'>Owner Contact: ".mssql_result($result, $i,20). "</td>
    <td><font size='+1'>Notes: ".mssql_result($result, $i,21)."</td>
  </tr>
  
  </tr>
</table><p>"; 
 }
}

 }//end for
 

mssql_close(); //close DB connection

?>


<p>&nbsp;</p><hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>