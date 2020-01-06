<html>
<head>
<title>Edit a Server Record</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#666699" text="#FFFFFF">
<p align="center"><font size="+3"><strong> CMS DATA CENTER RESOURCE LOCATION AND 
  INFORMATION PAGE</strong> </font></p>
<p align="center"><font size="+3"></font></p>
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
$query = "select * from server3 where server_name1 LIKE '$ServerName'";
//get results
$result = mssql_query($query);
if (!$result || mssql_result($result, 0,1) == '')
{
echo "Query failed, check spelling and server name\n";
exit;
}
else 
 {
$Server_Name1 = mssql_result($result, 0,0);
$IP = mssql_result($result, 0,1);
$Rack_Number = mssql_result($result, 0,2);
$Domain = mssql_result($result, 0,3);
$Backup_Server = mssql_result($result, 0,4);
$Backup_Job = mssql_result($result, 0,5);
$Location = mssql_result($result, 0,6);
$Asset = mssql_result($result, 0,7);
$Install = mssql_result($result, 0,8);
$Status = mssql_result($result, 0,9);
$OS = mssql_result($result, 0,10);
$Manufactuer = mssql_result($result, 0,11);
$Model = mssql_result($result, 0,12);
$Serial = mssql_result($result, 0,13);
$Function = mssql_result($result, 0,14);
$Type = mssql_result($result, 0,15);
$SLA = mssql_result($result, 0,16);
$Class = mssql_result($result, 0,17);
$Admin = mssql_result($result, 0,18);
$Application_Owner = mssql_result($result, 0,19);
$Application_Contact = mssql_result($result, 0,20);
$Notes = mssql_result($result, 0,21);
}//end else

mssql_close(); //close DB connection
?>
<font size="+1">&nbsp;</p> <strong>Edit Information and submit:</strong> </font> 
<form name="form1" method="post" action="editaction.php">
  
<table width='80%' border='1' >
  <font size='+1'>
  <tr>
    <td><strong>Server Name:</strong>&nbsp 
    <input name="t1" type="text" value= <?php echo "'".$Server_Name1 . "'"; ?> >
    <td><strong>IP: </strong> 
    <input type="text" name="t2" value= <?php echo "'".$IP . "'"; ?> >
  </tr>
<tr>
    <td><strong>Rack Number: 
    <input type="text" name="t3" value= <?php echo "'".$Rack_Number . "'"; ?> >
    </strong>
    <td><strong><strong>Domain:</strong> 
    <select name="t4">
	<option value=<?php echo "'".$Domain . "'"; ?> ><?php echo $Domain; ?></option>
      <option value="HCFA.GOV">HCFA.GOV</option>
      <option value="PROD.HCFA.GOV">PROD.HCFA.GOV</option>
      <option value="DEV.HCFA.GOV">DEV.HCFA.GOV</option>
      <option value="PROXY.HCFA.GOV">PROXY.HCFA.GOV</option>
      <option value="N/A">N/A</option>
      <option value="WORKGROUP">WORKGROUP</option>
      <option value="UNKNOWN">UNKNOWN</option>
    </select>
  </tr>
  <tr>
    <td><strong>Backup Server Name:</strong> 
    <input type="text" name="t5" value= <?php echo "'".$Backup_Server . "'"; ?>>
    
	<td><strong>Backup Job Name:</strong> 
    <input type="text" name="t6" value= <?php echo "'".$Backup_Job . "'"; ?>>
  </tr>
  <tr>
	<td><strong>Server Location:</strong> 
    <input type="text" name="t7" value= <?php echo "'".$Location . "'"; ?>>
	
	<td><strong>Asset Tag:</strong> 
    <input type="text" name="t8" value= <?php echo "'".$Asset . "'"; ?>>
 </tr>
 
 <tr>
   <td><strong>Install Date: </strong> 
    <input type="text" name="t9" value= <?php echo "'".$Install . "'"; ?>>
	
   <td><strong>Status:</strong> 
    <input type="text" name="t10" value= <?php echo "'".$Status . "'"; ?>>
 
 </tr>
 
  <tr>
   <td><strong>OS:</strong> 
    <input type="text" name="t11" value= <?php echo "'".$OS . "'"; ?>>
	
   <td><strong><strong>Manufacturer:</strong> 
    <input type="text" name="t12" value= <?php echo "'".$Manufactuer . "'"; ?>>
 
 </tr>
 
   <tr>
   <td><strong><strong>Model:</strong> 
    <input type="text" name="t13" value= <?php echo "'".$Model . "'"; ?>>
	
   <td><strong><strong><strong>Serial:</strong> 
    <input type="text" name="t14" value= <?php echo "'".$Serial . "'"; ?>>
 
 </tr>
 
    <tr>
   <td><strong><strong>Function:</strong> 
    <textarea name="t15"><?php echo "$Function"; ?></textarea>
	
   <td><strong><strong><strong>Type:</strong> 
    <input type="text" name="t16" value= <?php echo "'".$Type . "'"; ?>>
 
 </tr>
 
     <tr>
   <td><strong>SLA:</strong> 
    <select name="t17">
      <option value=<?php echo "'".$SLA . "'"; ?> ><?php echo $SLA; ?></option>
	  <option value="Y">Y</option>
      <option value="N">N </option>
    </select>
	
   <td><strong>Class:</strong> 
    <input type="text" name="t18" value= <?php echo "'".$Class . "'"; ?>>
 
 </tr>
 
     <tr>
   <td><strong>Responsible Admin: 
    <input type="text" name="t19" value= <?php echo "'".$Admin . "'"; ?>>
	
   <td><strong>Application Owner: 
    <input type="text" name="t20" value= <?php echo "'".$Application_Owner . "'"; ?>>
    </strong>
  </tr>

      <tr>
   <td><strong><strong>Owner Contact: 
    <input type="text" name="t21" value= <?php echo "'".$Application_Contact . "'"; ?>>
    </strong>
	
   <td><strong>Notes: </strong> 
    <textarea name="t22" cols="40"><?php echo "$Notes"; ?> </textarea>
    <br>
  </tr>
 
</table>
  <p align="left"> <strong>Server Name:</strong>&nbsp 
    <input name="t1" type="text" value= <?php echo "'".$Server_Name1 . "'"; ?> >
  </p>
  <p align="left"><strong>IP: </strong> 
    <input type="text" name="t2" value= <?php echo "'".$IP . "'"; ?> >
  </p>
  <p align="left"><strong>Rack Number: 
    <input type="text" name="t3" value= <?php echo "'".$Rack_Number . "'"; ?> >
    </strong></p>
  <p align="left"><strong>Domain:</strong> 
    <select name="t4">
	<option value=<?php echo "'".$Domain . "'"; ?> ><?php echo $Domain; ?></option>
      <option value="HCFA.GOV">HCFA.GOV</option>
      <option value="PROD.HCFA.GOV">PROD.HCFA.GOV</option>
      <option value="DEV.HCFA.GOV">DEV.HCFA.GOV</option>
      <option value="PROXY.HCFA.GOV">PROXY.HCFA.GOV</option>
      <option value="N/A">N/A</option>
      <option value="WORKGROUP">WORKGROUP</option>
      <option value="UNKNOWN">UNKNOWN</option>
    </select>
  </p>
  <p align="left"><strong>Backup Server Name:</strong> 
    <input type="text" name="t5" value= <?php echo "'".$Backup_Server . "'"; ?>>
  </p>
  <p align="left"><strong>Backup Job Name:</strong> 
    <input type="text" name="t6" value= <?php echo "'".$Backup_Job . "'"; ?>>
  </p>
  <p align="left"><strong>Server Location:</strong> 
    <input type="text" name="t7" value= <?php echo "'".$Location . "'"; ?>>
  </p>
  <p align="left"> <strong>Asset Tag:</strong> 
    <input type="text" name="t8" value= <?php echo "'".$Asset . "'"; ?>>
  </p>
  <p align="left"><strong>Install Date: </strong> 
    <input type="text" name="t9" value= <?php echo "'".$Install . "'"; ?>>
  </p>
  <p align="left"><strong>Status:</strong> 
    <input type="text" name="t10" value= <?php echo "'".$Status . "'"; ?>>
  </p>
  <p align="left"><strong>OS:</strong> 
    <input type="text" name="t11" value= <?php echo "'".$OS . "'"; ?>>
  </p>
  <p align="left"><strong>Manufacturer:</strong> 
    <input type="text" name="t12" value= <?php echo "'".$Manufactuer . "'"; ?>>
  </p>
  <p align="left"> <strong>Model:</strong> 
    <input type="text" name="t13" value= <?php echo "'".$Model . "'"; ?>>
  </p>
  <p align="left"><strong>Serial:</strong> 
    <input type="text" name="t14" value= <?php echo "'".$Serial . "'"; ?>>
  </p>
  <p align="left"><strong>Function:</strong> 
    <textarea name="t15"><?php echo "$Function"; ?></textarea>
  </p>
  <p align="left"><strong>Type:</strong> 
    <input type="text" name="t16" value= <?php echo "'".$Type . "'"; ?>>
  </p>
  <p align="left"><strong>SLA:</strong> 
    <select name="t17">
      <option value=<?php echo "'".$SLA . "'"; ?> ><?php echo $SLA; ?></option>
	  <option value="Y">Y</option>
      <option value="N">N </option>
    </select>
  </p>
  <p align="left"><strong>Class:</strong> 
    <input type="text" name="t18" value= <?php echo "'".$Class . "'"; ?>>
  </p>
  <p align="left"><strong>Responsible Admin: 
    <input type="text" name="t19" value= <?php echo "'".$Admin . "'"; ?>>
    </strong></p>
  <p align="left"><strong>Application Owner: 
    <input type="text" name="t20" value= <?php echo "'".$Application_Owner . "'"; ?>>
    </strong></p>
  <p align="left"><strong>Owner Contact: 
    <input type="text" name="t21" value= <?php echo "'".$Application_Contact . "'"; ?>>
    </strong></p>
  <p align="left"><strong>Notes: </strong> 
    <textarea name="t22"><?php echo "$Notes"; ?> </textarea>
    <br>
  </p>
  <p align="left"> 
    <input type="submit" name="Submit" value="Submit">
  </p>
</form>
<p>&nbsp;</p><hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>
