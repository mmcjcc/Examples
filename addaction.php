<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Update Server Record</title>
</head>

<p>
<body>
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
Insert Into Server3 (Server_Name1, IP,Rack, [Domain] , Backup_Server, Backup_Job, Server_Location, Asset_Tag , Installation_Date, Status, OS, 
Manufacturer, Model, Serial, [Function], Type, SLA , Class , Responsible_Admin, Application_Owner, Owner_Contact , Notes) 
VALUES ('$v1', '$v2', '$v3', '$v4', '$v5' , '$v6', '$v7', '$v8', '$v9', '$v10', '$v11', '$v12', '$v13','$v14', '$v15',
'$v16', '$v17','$v18', '$v19', '$v20','$v21', '$v22')";

$result = mssql_query($query);
if (!$result)
{
echo "query failed\n";
exit;
}
if ($v4 != 'NA')
{
$query2 = 
"
Insert Into Patches (ServerName) Values ('$v1')";

$result2 = mssql_query($query2);
if (!$result2)
{
echo "query failed\n";
exit;
}
else 
 {
   echo "Insert Complete";
   echo "<meta HTTP-EQUIV = 'Refresh' CONTENT = '0; URL = find.php?id=$v1&edited=true'>";
 }
 }
?>
 

</body>
</html>
