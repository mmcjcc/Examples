<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Server Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>

<p>
<body background="bk.jpg" link="#CCCCCC" vlink="#CCCCCC"><body bgcolor="#666699" text="#FFFFFF">
<body alink="#FFFF00"> 
<body bgcolor="#666699" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF"background="bk.jpg">

	
	<a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a><p></p>
<hr>
<p></p>
<?php
  
//connect to the database
$hostname = "lbzds01"; 
$username = "sa"; 
$password = "knet"; 
$dbName = "jasontest"; 
//collect httpvars

$ServerName = $HTTP_GET_VARS["id"];
$IP_Search = $HTTP_POST_VARS["IP_Search"];
$done = false;
if ($HTTP_GET_VARS["updatepatches"] == 'true')
{
$updatepatches = true;
}

//
mssql_connect($hostname,$username,$password)or DIE("DATABASE FAILED TO RESPOND.");
//MSSQL_CONNECT($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");
//select database
mssql_select_db("Jasontest");
//query database
echo "<font size='+2'> Updating Patches for " .$ServerName ." :<p></font>"; 
echo "<p><img src='clock4.gif' width='67' height='67'></p>";
//$array3;
?>
<?php
$output = exec('C:\bsa\mbsacli.exe /hf -h '.$ServerName.' -history 3 -u server\server-adm -p sner3vuv -nosum', $array3, $returnvar);
$size3 = count($array3);


for($i=0; $i <= $size3; $i++)
{
echo" . ";
if (preg_match("/Patch Found/", $array3[$i]))
{
//echo $array3[$i];
$patchesfound = $patchesfound . $array3[$i] . ",";
//$array4 = preg_split  ("/[\s,]+/",  $array3[$i]);
}
/*if (preg_match("/Patch NOT Found/", $array3[$i]))
{
echo $array3[$i];
$patchesnotfound = $patchesnotfound . $array3[$i] . ",";
//$array4 = preg_split  ("/[\s,]+/",  $array3[$i]);
} */
if (preg_match("/SP/", $array3[$i]))
 {
//echo $array3[$i];
$servicepackfound = $servicepackfound . $array3[$i] . ",";
 }
}//end 4 loop
$array6 ='';
$output2 = exec('C:\bsa\mbsacli.exe /hf -h '.$ServerName.' -u server\server_adm -p sner3vuv', $array6, $returnvar);

//$output2 = exec('C:\bsa\mbsacli.exe /hf -h '.$ServerName.' -u hcfa.gov\midbkup -p helpme77', $array6, $returnvar);
$size4 = count($array6);
for($h=0; $h <= $size4; $h++)
{

if (preg_match("/Patch NOT Found/", $array6[$h]))
 {
//echo $array3[$j];
$patchesnotfound = $patchesnotfound . $array6[$h] . ",";
//echo $patchesnotfound;
 }
}  //end 4
//grep for patches found
$patterns[0] = "/Patch/";
$patterns[1] = "/Found/";
$replacements[0]="";
$replacements[1]="";
$patchesfound2 = preg_replace($patterns, $replacements, $patchesfound);
//grep for patches not found
$patterns2[0] = "/Patch/";
$patterns2[1] = "/Found/";
$patterns2[2] = "/NOT/";
$replacements2[0]="";
$replacements2[1]="";
$replacements2[2]="";
$patchesnotfound2 = preg_replace($patterns2, $replacements2, $patchesnotfound);
//grep for service packs
$patterns3[0] = "/\*/";
$replacements3[0]="";
$servicepackfound2 = preg_replace($patterns3, $replacements3, $servicepackfound);


//echo $patchesfound2;
$query3 = "Update patches
SET Patch_Installed = '$patchesfound2', Service_Pack = '$servicepackfound2', Patch_Missing = '$patchesnotfound2', LastUpdate = getdate()
WHERE ServerName = '$ServerName'  ";
//get results
$result3 = mssql_query($query3);
if (!$result3)
{
echo "Query failed";
exit;
}
else 
 {
   echo "Update Complete";
   echo "<meta HTTP-EQUIV = 'Refresh' CONTENT = '0; URL = find.php?id=$ServerName&edited=true'>";
 }

mssql_close(); //close DB connection
?>

<hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>

