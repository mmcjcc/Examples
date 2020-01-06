<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Server Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

<p>

<body bgcolor="#666699" text="#FFFFFF" link="#CCCCCC" vlink="#CCCCCC" alink="#CCCCCC"background="bk.jpg">
<p><a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a>
</p>

<hr><br>
<table width="31%"  border="1">
  <tr>
    <td width="345" height="40"><form name="formx" method="post" action="find.php">
      <div align="center"><strong>Search Again </strong><br>
          <strong>Server Name: </strong>&nbsp;
          <input type="text" name="ServerName">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" name="Submit2" value="Submit"></form>
        </div>
    </form></td>
  </tr>
</table>
<?php
//include 'classes.php'; 
 
//collect httpvars
$edited = '';
$ServerName = $HTTP_POST_VARS["ServerName"];
$IP_Search = $HTTP_POST_VARS["IP_Search"];

if ($ServerName == '' & $IP_Search == '')
{
$ServerName = $HTTP_GET_VARS["id"];
}

if ($HTTP_GET_VARS["edited"] == 'true')
{
$edited = true;
}
if ($HTTP_GET_VARS["process"] == 'true')
{
$process = true;
}
if ($HTTP_GET_VARS["patch"] == 'true')
{
$patch = true;
}
if ($HTTP_GET_VARS["srvinfo"] == 'true')
{
$srvinfo = true;
}
if ($HTTP_GET_VARS["updatepatches"] == 'true')
{
$updatepatches = true;
}

//
//connect to the database
$hostname = "lbzds01"; 
$username = "sa"; 
$password = "knet"; 
$dbName = "jasontest";
mssql_connect($hostname,$username,$password)or DIE("DATABASE FAILED TO RESPOND.");
//MSSQL_CONNECT($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");
//select database
mssql_select_db($dbName);
//query database
if ($IP_Search !='')
{
  $query1 = "select * from server3 where IP LIKE '$IP_Search'";
  $result1 = mssql_query($query1);
  
if (!$result1 || mssql_result($result1, 0,0) == '')
{
echo "Query failed, check spelling and server IP\n";
exit;
}
else 
{
$ServerName = mssql_result($result1, 0,0);
}
}

$query = "select * from server3 where server_name1 LIKE '$ServerName'";
//get results
$result = mssql_query($query);
if (!$result || mssql_result($result, 0,0) == '')
{
echo "Query failed, check spelling and server name\n";
exit;
}

else 

 
 {
//$numrows = mssql_num_rows($result);
echo "<p>";

$ServerName= mssql_result($result, 0,0);
echo "<a href='edit.php?id=".$ServerName ."'>Edit This Record</a><br>";
echo "<a href='delete.php?id=".$ServerName ."'>Delete This Record</a><br>";
echo "<a href='index.php'>Return to Finder</a><br>";


$OS = mssql_result($result, 0,10);
if ($OS == 'Windows 2000'||$OS == 'Win2k Adv')
{
echo "<a href='http://dcrl/TSWeb/default.php?id=".$ServerName ."'target='_blank'>Connect via Terminal Services</a><br>";
}
$Manufac= mssql_result($result, 0,11);
  if ($Manufac == 'Compaq' || $Manufac =='COMPAQ' || $Manufac =='compaq' || $Manufac =='HP')
  {
    echo "<a href='https://".$ServerName .":2381'target='_blank'>Connect to Insight Manager for this Server (If Installed)</a><br>";
  }
$Domain= mssql_result($result, 0,3);

if ($edited == true)
{
echo "<p><font size='+1'>The modified database entry for ". $ServerName ." contains the following information :<p>"; 
}
else
{
echo "<p><font size='+2'> The Database contains the following information about " .$ServerName ." :<p></font>"; 
}
echo"<p><font size='+1'>Server Information:
<table width='80%' border='1' >
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

}//end else
//Patch Shit
if ($Domain != 'N/A')
{
$query2 = "Select * from patches where ServerName LIKE '$ServerName'";
//get results
$result2 = mssql_query($query2);
if (!$result)
{
echo "Query failed, Patch Data not avalible";
exit;
}

else
{
echo" <p>Patch Information:
<table width='80%' border='1' >
  <font size='+1'>
  <tr>
    <td><font size='+1'>Server Name: </td>
	<td><font size='+1'><br>". mssql_result($result2, 0,0). "</td>
    
  </tr>
    <tr>
    <td><font size='+1'>Service Pack: </td>
	<td><font size='+1'>" .mssql_result($result2, 0,1). "</td>
  </tr>
  <tr>
    <td><font size='+1'>Patches Installed: </td>
	 <td><font size='+1'>".mssql_result($result2, 0,2)."</td>
     </tr>
   <tr>
       <td><font size='+1'>Patches Missing: </td>
	   <td><font size='+1'>".mssql_result($result2, 0,3). "</td>
  </tr>
</table>
Last Updated: ".mssql_result($result2, 0,4);
}
}//end patch if statement

  echo "<p align=''left'><a href='find.php?id=".$ServerName ."&trace=true'>Trace Route to this Host</a></p>";
  if ($Domain != 'N/A')
  { echo "If we have access rights:<br>";
  echo "<a href='find.php?id=".$ServerName ."&process=true'>Find what Processes Are Running </a><br>";
  echo "<a href='find.php?id=".$ServerName ."&srvinfo=true'>Find Latest Info on this Host with Srvinfo</a><br>";
  echo "<a href='find.php?id=".$ServerName ."&patch=true'>Find Installed and Missing Patches without updating table (Takes Several Minutes)</a><br>";
  echo "<a href='updatepatches.php?id=".$ServerName ."&updatepatches=true'>Update Patches Table Dynamically (Takes several minutes)</a><br>";
  //Determine if we can get SNMP data by using the insight string
   }
   $snmp = $HTTP_GET_VARS["snmp"];
  if($snmp != true)
  {
  if ($Manufac == 'Compaq' || $Manufac =='COMPAQ' || $Manufac =='compaq' || $Manufac =='HP')
  {
   // echo "<p align=''left'><a href='find.php?id=".$ServerName ."&snmp=true'>Find SNMP Uptime data (If Host has insight comunity string)</a></p>";

  }
  }
 if ($snmp == true)
 {
 echo " <p> SNMP Information for Host ". $ServerName.":";
// $sysname = snmpget($ServerName, "insight", "system.sysName.0");
//get system uptime
//$sysup = snmpget($ServerName, "insight", "system.sysUpTime.0");

   $sysup = snmpget($ServerName,"insight", "system.sysUpTime.0");
echo "<p> System Uptime: ". $sysup;

 }
 
 $trace = $HTTP_GET_VARS["trace"];
 if ($trace == '' && $edited != 'true' && $snmp != 'true' && $process != 'true' && $patch != 'true' && $srvinfo != true)
 {
 
   $count  = 2;
   $host   = $ServerName;
      // replace bad chars
      $host= preg_replace ("/[^A-Za-z0-9.]/","",$host);
      echo '<body bgcolor="#FFFFFF" text="#000000"></body>';
      echo "<p>Checking if Host is Live.... <p>";
	  //echo("Ping Output:<br>"); 
      echo '<pre>';           
      //check target IP or domain
      system("ping -n $count $host");
     
      echo '</pre>';
 } //end if trace==''
 
 else if ($trace =='true')
 {
  echo "<p>";
  echo '<pre>';   
  system("tracert $ServerName");
  echo '</pre>';
 }
// All the Process Stuff
$ServerName2= '\\'.'\\'. $ServerName;
if ($process ==true)
{
echo " <p> Process Information for Host ". $ServerName.":<p>";
//$ServerName2= '\\'.'\\'. $ServerName;
$output = '';
$array1;
$output = exec('c:\pslist '. $ServerName2. ' -u hcfa.gov\midbkup -p helpme77', $array1, $returnvar);
$size = count($array1);
echo"<table width='80%' border='1' >";
for($i=7; $i <= $size; $i++)
{
 $array2;
$pattern = "[0-9] |[a-z] |[A-Z]";
$array2 = preg_split  ("/[\s,]+/",  $array1[$i]);

  $size2 = count($array2);
   if ($i == 7)
  {
  echo"
  <font size='+1'>
  <tr>
    <td><font size='+1'>".$array2[0]. "</td>
     <td><font size='+1'>".$array2[1]. "</td>
	 <td><font size='+1'>".$array2[2]. "</td>
	 <td><font size='+1'>".$array2[3]. "</td>
	 <td><font size='+1'>".$array2[4]. "</td>
	 <td><font size='+1'>".$array2[5]. "</td>
	 <td><font size='+1'>User Time </td>
	 <td><font size='+1'>Kernal Time </td>
	 <td><font size='+1'>Elapsed Time</td>
	  </tr>";
   }
   else
   {  

  echo"
  
  <font size='+1'>
  <tr>
    <td><font size='+1'>".$array2[0]. "</td>
     <td><font size='+1'>".$array2[1]. "</td>
	 <td><font size='+1'>".$array2[2]. "</td>
	 <td><font size='+1'>".$array2[3]. "</td>
	 <td><font size='+1'>".$array2[4]. "</td>
	 <td><font size='+1'>".$array2[5]. "</td>
	 <td><font size='+1'>".$array2[6]. "</td>
	 <td><font size='+1'>".$array2[7]. "</td>
	 <td><font size='+1'>".$array2[8]. "</td>
	 <td><font size='+1'>".$array2[9]. "</td>
	  </tr>";
    }
}
echo "</table>";
} //end process crap
//patch gathering test
 if ($patch ==true)
 {
  echo "<p>";
  echo '<pre>';   
  system("C:\bsa\mbsacli.exe /hf -h $ServerName -history 3 -u hcfa.gov\midbkup -p helpme77 -nosum");
  echo '</pre>';
  }


 //srvinfo test

 if ($srvinfo ==true)
 {
  echo '<pre>';   
  system("C:\psinfo.exe $ServerName2 -u hcfa.gov\midbkup -p helpme77");
  echo '</pre>';
}


//SNMP crap experiment

//$a = snmpwalk($ServerName, "insight", "");
//for ($i=0; $i < count($a); $i++) {
 // echo $a[$i]. "<p>";
//}

//$a = snmpwalkoid($ServerName, "insight", ""); 
//for (reset($a); $i = key($a); next($a)) 
//{
 //   echo "$i: $a[$i]<br>\n";
//}

mssql_close(); //close DB connection

?>
<p>&nbsp;</p><hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>
