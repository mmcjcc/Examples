<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Updating Patch Table</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>

<p>
<body background="bk.jpg" link="#CCCCCC" vlink="#CCCCCC"><body bgcolor="#666699" text="#FFFFFF">
<body alink="#FFFF00"> 
<body bgcolor="#666699" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF"background="bk.jpg">

	
	<a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a><p></p> </p>

<hr>
<p></p>
<?php
  
//connect to the database
$hostname = "lbzds01"; 
$username = "sa"; 
$password = "knet"; 
$dbName = "jasontest"; 
//collect httpvars
$query = "Select ServerName from patches";
//
mssql_connect($hostname,$username,$password)or DIE("DATABASE FAILED TO RESPOND.");
//MSSQL_CONNECT($hostname,$username,$password) or DIE("DATABASE FAILED TO RESPOND.");
//select database
mssql_select_db("Jasontest");
$result = mssql_query($query);
if (!$result)
{
echo "Query failed";
exit;
}
else {
echo "<p><img src='clock4.gif' width='67' height='67'></p>";
$numrows = mssql_num_rows($result);
echo $numrows;
echo " Servers to Check...<p>";

for ($i = 36; $i < 37; $i++)
{

$ServerNames = $ServerNames. " -h " . mssql_result($result, $i, 0). " ";
}
//echo "<font size='+2'> Updating Patches for " .$ServerName ." :<p></font>"; 
echo  $ServerNames;
$array3 ='';
$output = exec('C:\bsa\mbsacli.exe /hf '.$ServerNames.' -u hcfa.gov\midbkup -p helpme77 -t 128', $array3, $returnvar);

$patchesfound ='';
$patchesnotfound='';
$servicepackfound='';
$patchesfound2='';
$patchesnotfound2='';
$servicepackfound2='';
$size3 = count($array3);
$FirstRun = 0;
echo $size3. "<p>";
$ServerName = mssql_result($result, 0, 0)
for($j=0; $j <= $size3; $j++)
{
 echo "Scanning a Line <p><p>";
 for ($i = 0; $i < $numrows; $i++)
 {
 $name = mssql_result($result, $i, 0);
 echo "Checking for name match, comparing". $name." with" . $array3[$j]."<p>";

if ((strpos($array3[$j], $name)!=false))
{
echo "Found some shit <p>";
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
// end grep
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
  $patchesfound ='';
 $patchesnotfound='';
 $servicepackfound='';
 $patchesfound2='';
 $patchesnotfound2='';
 $servicepackfound2='';
 $ServerName= $name ;
 }
 //if ((strpos($array3[$j], $name)!=false) && ($FirstRun == 0))
// {
// $FirstRun= $FirstRun + 1;
// $ServerName= $name;
// echo "Found some shit <p>";
 //}
 }

if (preg_match("/Patch Found/", $array3[$j]))
{
//echo $array3[$j];
$patchesfound = $patchesfound . $array3[$j] . ",";
//$array4 = preg_split  ("/[\s,]+/",  $array3[$i]);
}
if (preg_match("/SP/", $array3[$j]))
{
//echo $array3[$j];
$servicepackfound = $servicepackfound . $array3[$j] . ",";
}
if (preg_match("/Patch NOT Found/", $array6[$h]))
{
//echo $array3[$j];
$patchesnotfound = $patchesnotfound . $array6[$h] . ",";
}
//echo "<p>";
 
}//end 4 loop

 
   echo "Update Complete";
   //echo "<meta HTTP-EQUIV = 'Refresh' CONTENT = '0; URL = index.php?'>";
 

//mssql_close(); //close DB connection
} //end if result

?>


<hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>


