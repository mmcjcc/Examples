<html>
<head>
<title>Add a Server Record</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#666699" text="#FFFFFF" background="bk.jpg">
<p></p>
<p><a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a></p>
<hr>




<font size="+1">&nbsp;</p> <strong>Insert Information and Submit:</strong> </font> 
<form name="form1" method="post" action="addaction.php">
  
<table width='80%' border='1' >
  <font size='+1'>
  <tr>
    <td> <strong>Server Name</td>
	<td>  
      <input name="t1" type="text" value= <?php echo "'".$Server_Name1 . "'"; ?> >
    <td> <strong>IP</td>
	<td>
    <input type="text" name="t2" value= <?php echo "'".$IP . "'"; ?> >
  </tr>
<tr>
    <td> <strong>Rack Number:</td>
	<td>
    <input type="text" name="t3" value= <?php echo "'".$Rack_Number . "'"; ?> >
    <td> <strong>Domain</td>
    <td>
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
    <td> <strong>Backup Server Name</td>
	<td> 
    <input type="text" name="t5" value= <?php echo "'".$Backup_Server . "'"; ?>>
    <td> <strong>Backup Job Name</td>
	<td>
    <input type="text" name="t6" value= <?php echo "'".$Backup_Job . "'"; ?>>
  </tr>
  <tr>
	<td> <strong>Server Location</td>
	<td>
    <input type="text" name="t7" value= <?php echo "'".$Location . "'"; ?>>
	<td> <strong>Asset Tag</td>
	<td>
    <input type="text" name="t8" value= <?php echo "'".$Asset . "'"; ?>>
 </tr>
 
 <tr>
   <td> <strong>Install Date</td>
   <td> 
    <input type="text" name="t9" value= <?php echo "'".$Install . "'"; ?>>
  <td> <strong>Status</td>
   <td>
    <select name="t10">
	<option value= <?php echo $Status; ?>></option></option>
      <option value="Online">Online</option>
      <option value="Offline">Offline</option>
      <option value="Stagging">Stagging</option>
      <option value="Warehouse">Warehouse</option>
     
    </select>
 
 </tr>
 
  <tr>
   <td> <strong>OS</td>
   <td>
    <input type="text" name="t11" value= <?php echo "'".$OS . "'"; ?>>
	<td> <strong>Manufactuer</td>
   <td>
      <select name="t12">
	<option value=<?php echo $Manufactuer; ?>></option>
      <option value="HP">HP</option>
      <option value="Compaq">Compaq</option>
      <option value="IBM">IBM</option>
      <option value="Sun">Sun</option>
      <option value="Appliance">Appliance</option>
      <option value="Other">Other</option>
    </select>
 
 </tr>
 
   <tr>
   <td> <strong>Model</td>
   <td><strong>
    <input type="text" name="t13" value= <?php echo "'".$Model . "'"; ?>>
	<td> <strong>Serial</td>
   <td>
    <input type="text" name="t14" value= <?php echo "'".$Serial . "'"; ?>>
 
 </tr>
 
    <tr>
	<td> <strong>Function</td>
   <td><strong> 
    <textarea name="t15" cols="30" rows="4"><?php echo "$Function"; ?></textarea>
	<td> <strong>Type</td>
   <td>
    <input type="text" name="t16" value= <?php echo "'".$Type . "'"; ?>>
 
 </tr>
 
     <tr>
	 <td> <strong>SLA</td>
   <td>
    <select name="t17">
      <option value=<?php echo "'".$SLA . "'"; ?> ><?php echo $SLA; ?></option>
	  <option value="Y">Y</option>
      <option value="N">N </option>
    </select>
	<td> <strong>Class</td>
   <td>
    <select name="t18">
	<option value=<?php echo $Class ; ?> ><?php echo $Class ; ?></option>
      <option value="Novell File & Print">Novell File & Print</option>
      <option value="E-mail">E-mail</option>
      <option value="Communication">Communication</option>
      <option value="NT/Citrix/App">NT/Citrix/App</option>
      <option value="Internet Gateway">Internet Gateway</option>
	  <option value="Unix">Unix</option>
	  <option value="Security">Security</option>
    </select>
 <td>
 </tr>
 
 </tr>
 
     <tr>
	 <td> <strong>Responsible Admin</td>
   <td>
    <input type="text" name="t19" value= <?php echo "'".$Admin . "'"; ?>>
	<td> <strong>Application Owner</td>
   <td>
      <input type="text" name="t20" value= <?php echo "'".$Application_Owner . "'"; ?>>
    </strong>
  </tr>

      <tr>
   <td> <strong>Owner Contact</td>
   <td>
    <input type="text" name="t21" value= <?php echo "'".$Application_Contact . "'"; ?>>
    </strong>
	<td> <strong>Notes</td>
   <td>
    <textarea name="t22" cols="30" rows="4"><?php echo "$Notes"; ?> </textarea>
  
  </tr>

</table>
  </p>
  <p align="left"> 
    <input type="submit" name="Submit" value="Submit">
  </p>
</form>
<p><font size="+1">Example:</font></p>
<?php
$hostname = "lbzds01"; 
$username = "sa"; 
$password = "knet"; 
$dbName = "jasontest";
mssql_connect($hostname,$username,$password)or DIE("DATABASE FAILED TO RESPOND.");
//MSSQL_CONNECT($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");
//select database
mssql_select_db("Jasontest");
//query database

$query = "select * from server3 where server_name1 LIKE 'CONDH01'";
//get results
$result = mssql_query($query);
if (!$result || mssql_result($result, 0,0) == '')
{
echo "Query failed, check spelling and server name\n";
exit;
}

echo"
<p><table width='80%' border='1' >
  <font size='+1'>
  <tr>
    <td><font size='+1'>Server Name: ". mssql_result($result, 0,0). "</td>
    <td><font size='+1'>IP: " .mssql_result($result, 0,1). "</td>
  </tr>
  <tr>
    <td><font size='+1'>Rack Number: ".mssql_result($result, 0,2)."</td>
    <td><font size='+1'>Domain: ".mssql_result($result, 0,3). "</td>
  </tr>
  <tr>
    <td><font size='+1'>Backup Server Name: ". mssql_result($result, 0,4)."</td>
    <td><font size='+1'>Backup Job Name: ". mssql_result($result, 0,5). "</td>
  </tr>
  <tr>
    <td><font size='+1'>Server Location: ". mssql_result($result, 0,6)."</td>
    <td><font size='+1'>Asset Tag: ".mssql_result($result, 0,7)."</td>
  </tr>
  <tr>
    <td>";
if (mssql_result($result, 0,8) != '')
{
echo "<font size='+1'>Install Date: ". mssql_result($result, 0,8);

}
else 
{
echo "<font size='+1'>Install Date: Not Provided ";
}
echo"</td>
    <td><font size='+1'>Status: ".mssql_result($result, 0,9)." </td>
  </tr>
  <tr>
    <td><font size='+1'>OS: ". mssql_result($result, 0,10)."</td>
    <td><font size='+1'>Manufacturer: ". mssql_result($result, 0,11)."</td>
  </tr>
  <tr>
    <td><font size='+1'>Model: ". mssql_result($result, 0,12)."</td>
    <td><font size='+1'>Serial: ".mssql_result($result, 0,13)."</td>
  </tr>
  <tr>
    <td><font size='+1'>Function: ".mssql_result($result, 0,14). "</td>
    <td><font size='+1'>Type: ".mssql_result($result, 0,15)."</td>
  </tr>
  <tr>
    <td><font size='+1'>SLA: ".mssql_result($result, 0,16). "</td>
    <td><font size='+1'>Class: ".mssql_result($result, 0,17)."</td>
  </tr>
  <tr>
    <td><font size='+1'>Responsible Admin: ".mssql_result($result, 0,18)."</td>
    <td><font size='+1'>Application Owner: ".mssql_result($result, 0,19)."</td>
  </tr>
  <tr>
    <td><font size='+1'>Owner Contact: ".mssql_result($result, 0,20). "</td>
    <td><font size='+1'>Notes: ".mssql_result($result, 0,21)."</td>
  </tr>
  
  </tr>
</table>";
?>
<hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>
