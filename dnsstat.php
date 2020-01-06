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
<p><strong>HISTORICAL DNS INFORMATION</p></strong><p></p>
<?php

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

$query = "select * from dnsstats Order BY dns_time";
//get results
$result = mssql_query($query);
if (!$result || mssql_result($result, 0,0) == '')
{
echo "Query failed";
exit;
}

else 
 {
//$numrows = mssql_num_rows($result);
echo"<strong>";
$numrows = mssql_num_rows($result);
//////////////////////////////////////////////////// Totals
echo  "Total Records on File: ".$numrows."<br>";
$totalq=0;
$totalto=0;
$totalqps=0;
for ($i = 0; $i < $numrows; $i++)
{
$date = mssql_result($result, 0,0);
$ServerName= mssql_result($result, $i, server_name);
$totalq = $totalq+ mssql_result($result, $i, total_qurries);
$totalto = $totalto+ mssql_result($result, $i, timeouts);
$totalqps = $totalqps+ mssql_result($result, $i, qps);
$divider=$divider+1;

 }//end for
 echo"<p><font size='+1'>Information:
   <table width='80%' border='1' >
  <font size='+1'>
  <tr>
   
    <td> ";
 echo"<strong>";
 echo "Totals since ".$date;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
    echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Timeouts'>";
  echo"<p><strong>Timeouts: ".$totalto;
  echo"<br>Total Queries: ".$totalq;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
  echo"Total Queries per Second: ".$totalqps/$divider;
  //echo"<p>";
  echo"</td>";
  $totalq=0;
$totalto=0;
$totalqps=0;
$divider=0;

////////////////////////////////////////////////////////////////////////REC
  for ($m = 0; $m < $numrows; $m++)
{
if  (mssql_result($result, $m,rec)=='y')
{
$date = mssql_result($result, 0,0);
$ServerName= mssql_result($result, $m, server_name);
$totalq = $totalq + mssql_result($result, $m, total_qurries);
$totalto = $totalto + mssql_result($result, $m, timeouts);
$totalqps = $totalqps+ mssql_result($result, $m, qps);
$divider=$divider+1;

}
 }//end for
 echo" <td> ";
 echo"<strong>";

 echo "Total Recursive since ".$date;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Recursive+Timeouts'>";
  echo"<p><strong>Timeouts: ".$totalto;
  echo"<br>Total Queries: ".$totalq;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
  echo"Total Queries per Second: ".$totalqps/$divider;
  //echo"<p>";
  echo"</td></tr>";
$totalq=0;
$totalto=0;
$totalqps=0;
$divider=0;

sleep(0.8);

//////////////////////////////////////////////////////////nonrec
  for ($n = 0; $n < $numrows; $n++)
{
if  (mssql_result($result, $n,rec)=='n')
{
$date = mssql_result($result, 0,0);
$ServerName= mssql_result($result, $n, server_name);
$totalq = $totalq + mssql_result($result, $n, total_qurries);
$totalto = $totalto + mssql_result($result, $n, timeouts);
$totalqps = $totalqps+ mssql_result($result, $n, qps);
$divider=$divider+1;

}
 }//end for
  echo"<tr> <td> ";
  echo"<strong>";
 echo "Total Simple since ".$date;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Simple+Timeouts'>";
  echo"<p><strong>Timeouts: ".$totalto;
  echo"<br>Total Queries: ".$totalq;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
  echo"Total Queries per Second: ".$totalqps/$divider;
  //echo"<p>";
  echo"</td>";
$totalq=0;
$totalto=0;
$totalqps=0;
$divider=0;

sleep(0.9);

echo" <td> ";
  echo"<strong>";
 echo "Total Timeouts over recorded days (each square is a recorded day)";
echo"
<img src='createlinegraphtotal.php  '> </td></tr>";
sleep(0.8);

/////////////////////////////////////////////////////////////////////////////Per device
//////////////////////////////////////////////////// 
$server = 'condn01';
for ($w=0; $w<=1; $w++)
{
$totalq=0;
$totalto=0;
$totalqps=0;
for ($i = 0; $i < $numrows; $i++)
{
$date = mssql_result($result, 0,0);
$ServerName= mssql_result($result, $i, server_name);
if ($ServerName==$server)
{
$totalq = $totalq+ mssql_result($result, $i, total_qurries);
$totalto = $totalto+ mssql_result($result, $i, timeouts);
$totalqps = $totalqps+ mssql_result($result, $i, qps);
$divider=$divider+1;

}
 }//end for
  echo"<tr><td> ";
    echo"<strong>";
 echo "Totals for ".$server." since ".$date;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Timeouts'>";
  echo"<p><strong>Timeouts: ".$totalto;
  echo"<br>Total Queries: ".$totalq;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
  echo"Total Queries per Second: ".$totalqps/$divider;
  //echo"<p>";
  echo"</td>";
  $totalq=0;
$totalto=0;
$totalqps=0;
$divider=0;

sleep(0.8);

////////////////////////////////////////////////////////////////////////REC
  for ($m = 0; $m < $numrows; $m++)
{
$ServerName= mssql_result($result, $m, server_name);

if  (mssql_result($result, $m,rec)=='y'&& $ServerName==$server)
{
$date = mssql_result($result, 0,0);
$totalq = $totalq + mssql_result($result, $m, total_qurries);
$totalto = $totalto + mssql_result($result, $m, timeouts);
$totalqps = $totalqps+ mssql_result($result, $m, qps);
$divider=$divider+1;

}
 }//end for
  echo" <td> ";
  echo"<strong>";
 echo "Total Recursive for ".$server ." since ".$date;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Recursive+Timeouts'>";
  echo"<p><strong>Timeouts: ".$totalto;
  echo"<br>Total Queries: ".$totalq;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
  echo"Total Queries per Second: ".$totalqps/$divider;
 // echo"<p>";
  echo"</td></tr>";
$totalq=0;
$totalto=0;
$totalqps=0;
$divider=0;

sleep(0.8);

//////////////////////////////////////////////////////////nonrec
  for ($n = 0; $n < $numrows; $n++)
{
$ServerName= mssql_result($result, $n, server_name);

if  (mssql_result($result, $n,rec)=='n'&&$server==$ServerName)
{
$date = mssql_result($result, 0,0);
$totalq = $totalq + mssql_result($result, $n, total_qurries);
$totalto = $totalto + mssql_result($result, $n, timeouts);
$totalqps = $totalqps+ mssql_result($result, $n, qps);
$divider=$divider+1;

}
 }//end for
  echo" <tr><td> ";
  echo"<strong>";
 echo "Total Simple for ".$server." since ".$date;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Simple+Timeouts'>";
  echo"<p><strong>Timeouts: ".$totalto;
  echo"<br>Total Queries: ".$totalq;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
  echo"Total Queries per Second: ".$totalqps/$divider;
  //echo"<p>";
  echo"</td>";
$totalq=0;
$totalto=0;
$totalqps=0;
$divider=0;
sleep(0.9);

echo" <td> ";
  echo"<strong>";
 echo "Total Timeouts over recorded days (each square is a recorded day)";
echo"
<img src='createlinegraphtotal.php?&&month=".$month."&&year=".$year."&&server=".$server."'> </td></tr>";
sleep(1.0);

$server='cocbd01';
}//end
echo"</tr></table>";
sleep(0.8);

//////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////links


echo"<p> Results by Month<p>";

$currentmonth;
for ($j = 0; $j < $numrows; $j++)
{
$date = mssql_result($result, $j,0);
$dateparts1 = explode(" ", $date);
//echo $dateparts1[0];
if ($currentmonth!=$dateparts1[0])
{
$notshownyet=true;
}
if ($dateparts1[0] == "" || $currentmonth!=$dateparts1[0] )
{
$dateparts = explode(" ", $date);
$currentmonth=$dateparts[0];
if($notshownyet=true)
{
  echo "<a href='displaymonth.php?month=".$currentmonth ."&&year=".$dateparts[2]."'>View Results for ".$dateparts[0]. " ".$dateparts[2]. "</a><br>";
  $notshownyet=false;
}
}
$rec = mssql_result($result, $j,rec);
$ServerName= mssql_result($result, $j, server_name);
//echo "<font size='+1'><a href='displaychart.php?id=".$ServerName ."&&date=".$date."&&rec=".$rec."'>View Results from ".$ServerName." ".$date."</a><br> ";
 }//end for
}
mssql_close(); //close DB connection

?>


<p>&nbsp;</p><hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>
