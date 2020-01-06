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

$v1 = $HTTP_POST_VARS["t1"];
$v2 = $HTTP_POST_VARS["t2"];
$v3 = $HTTP_POST_VARS["t3"];
$v4 = $HTTP_POST_VARS["t4"];
$v5 = $HTTP_POST_VARS["t5"];
$v6= $HTTP_POST_VARS["t6"];
$v7= $HTTP_POST_VARS["t7"];
$v8= $HTTP_POST_VARS["t8"];
$v9= $HTTP_POST_VARS["t9"];
$v10= $HTTP_POST_VARS["t10"];
$v11 = $HTTP_POST_VARS["t11"];
$v12 = $HTTP_POST_VARS["t12"];
$v13= $HTTP_POST_VARS["t13"];
$v14 = $HTTP_POST_VARS["t14"];
$v15 = $HTTP_POST_VARS["t15"];
$v16 = $HTTP_POST_VARS["t16"];
$v17 = $HTTP_POST_VARS["t17"];
$v18= $HTTP_POST_VARS["t18"];
$v19= $HTTP_POST_VARS["t19"];
$v20= $HTTP_POST_VARS["t20"];
$v21= $HTTP_POST_VARS["t21"];
$v22= $HTTP_POST_VARS["t22"];
//connect to the database
$hostname = "lbzds01"; 
$username = "sa"; 
$password = "knet"; 
MSSQL_CONNECT($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");
mssql_select_db("Jasontest");

//UPDATE Person
//SET Address = 'Stien 12', City = 'Stavanger'
//WHERE LastName = 'Rasmussen' 


$query = 
"
UPDATE Server3
SET  IP = '$v2', Rack = '$v3', [Domain] = '$v4', Backup_Server = '$v5' , Backup_Job = '$v6', Server_Location = '$v7',
Asset_Tag = '$v8', Installation_Date = '$v9', Status = '$v10', OS = '$v11', Manufacturer = '$v12', Model = '$v13',
Serial = '$v14', [Function] = '$v15', Type = '$v16', SLA = '$v17', Class = '$v18', Responsible_Admin = '$v19', Application_Owner = '$v20',
Owner_Contact = '$v21', Notes = '$v22' 
WHERE Server_Name1 = '$v1'
";

$result = mssql_query($query);
if (!$result)
{
echo "query failed\n";
exit;
}

else 
 {
   echo "Update Complete";
   echo "<meta HTTP-EQUIV = 'Refresh' CONTENT = '0; URL = find.php?id=$v1&edited=true'>";
 }
?>
 

</body>
</html>
