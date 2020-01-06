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
<?php
$day=$HTTP_GET_VARS["day"];
$hour=$HTTP_GET_VARS["hour"];
$half=$HTTP_GET_VARS["half"];
$month = $HTTP_GET_VARS["month"];
$year = $HTTP_GET_VARS["year"];
$datepart= $HTTP_GET_VARS["datepart"];

echo"<p><strong>HISTORICAL DNS INFORMATION FOR: ".$day." ".$month." ".$year." ". $hour. " ".$half."</p></strong><p></p>";

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

$totalq=0;
$totalto=0;
$totalqps=0;
for ($i = 0; $i < $numrows; $i++)
{
$date = mssql_result($result, $i,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];
$dateparts1 = preg_split('/ /', $date, -1, PREG_SPLIT_NO_EMPTY);
//printf $dateparts1;
$readinghour=$dateparts1[3];
//printf $readinghour ."<br>";
$hourparts1=explode(":", $readinghour);
$currenthour=$hourparts1[0];
//echo $currenthour ."<br>";
$currenthalfd=$hourparts1[1];
$currenthalfp = strstr($currenthalfd, 'P');
$currenthalfa = strstr($currenthalfd, 'A');
if ($currenthalfp=='')
{
 $currenthalf=$currenthalfa ;
 }
if ($currenthalfa=='')
{
 $currenthalf=$currenthalfp ;
 }
if ($month==$readingmonth && $day==$readingday && $year==$readingyear && $currenthour==$hour && $currenthalf==$half)
 {
$ServerName= mssql_result($result, $i, server_name);
$totalq = $totalq+ mssql_result($result, $i, total_qurries);
$totalto = $totalto+ mssql_result($result, $i, timeouts);
$totalqps = $totalqps+ mssql_result($result, $i, qps);
$divider=$divider+1;
 }
 }//end for
 echo"<p><font size='+1'>Information:
   <table width='80%' border='1' >
  <font size='+1'>
  <tr>
   
    <td> ";
 echo"<strong>";
 echo "Totals for ".$month. " ".$day." ".$hour.$half;
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
if  (mssql_result($result, $m,rec)=='y')
{
$date = mssql_result($result, $m,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];
$dateparts1 = preg_split('/ /', $date, -1, PREG_SPLIT_NO_EMPTY);
$readinghour=$dateparts1[3];

$hourparts1=explode(":", $readinghour);
$currenthour=$hourparts1[0];
$currenthalfd=$hourparts1[1];
$currenthalfp = strstr($currenthalfd, 'P');
$currenthalfa = strstr($currenthalfd, 'A');
if ($currenthalfp=='')
{
 $currenthalf=$currenthalfa ;
 }
if ($currenthalfa=='')
{
 $currenthalf=$currenthalfp ;
 }
if ($month==$readingmonth && $day==$readingday && $year==$readingyear && $currenthour==$hour && $currenthalf==$half)

 {
$ServerName= mssql_result($result, $m, server_name);
$totalq = $totalq+ mssql_result($result, $m, total_qurries);
$totalto = $totalto+ mssql_result($result, $m, timeouts);
$totalqps = $totalqps+ mssql_result($result, $m, qps);
$divider=$divider+1;

 }
}
 }//end for
 echo" <td> ";
 echo"<strong>";

 echo "Total Recursive for ".$month. " ".$day." ".$hour.$half;
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
$date = mssql_result($result, $n,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];

$dateparts1 = preg_split('/ /', $date, -1, PREG_SPLIT_NO_EMPTY);
$readinghour=$dateparts1[3];


$hourparts1=explode(":", $readinghour);
$currenthour=$hourparts1[0];
$currenthalfd=$hourparts1[1];
$currenthalfp = strstr($currenthalfd, 'P');
$currenthalfa = strstr($currenthalfd, 'A');
if ($currenthalfp=='')
{
 $currenthalf=$currenthalfa ;
 }
if ($currenthalfa=='')
{
 $currenthalf=$currenthalfp ;
 }
if ($month==$readingmonth && $day==$readingday && $year==$readingyear && $currenthour==$hour && $currenthalf==$half)
 {
$ServerName= mssql_result($result, $n, server_name);
$totalq = $totalq+ mssql_result($result, $n, total_qurries);
$totalto = $totalto+ mssql_result($result, $n, timeouts);
$totalqps = $totalqps+ mssql_result($result, $n, qps);
$divider=$divider+1;

 }
}
 }//end for
  echo"<tr> <td> ";
  echo"<strong>";
 echo "Total Simple for ".$month." ".$day." ".$hour.$half;
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
$date = mssql_result($result, $i,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];
$dateparts1 = preg_split('/ /', $date, -1, PREG_SPLIT_NO_EMPTY);
$readinghour=$dateparts1[3];

$hourparts1=explode(":", $readinghour);
$currenthour=$hourparts1[0];
$currenthalfd=$hourparts1[1];
$currenthalfp = strstr($currenthalfd, 'P');
$currenthalfa = strstr($currenthalfd, 'A');
if ($currenthalfp=='')
{
 $currenthalf=$currenthalfa ;
 }
if ($currenthalfa=='')
{
 $currenthalf=$currenthalfp ;
 }
if ($month==$readingmonth && $day==$readingday && $year==$readingyear && $currenthour==$hour && $currenthalf==$half)
 {

$ServerName= mssql_result($result, $i, server_name);
if ($ServerName==$server)
{
$totalq = $totalq+ mssql_result($result, $i, total_qurries);
$totalto = $totalto+ mssql_result($result, $i, timeouts);
$totalqps = $totalqps+ mssql_result($result, $i, qps);
$divider=$divider+1;

}
sleep(0.8);

}
 }//end for
  echo"<td> ";
    echo"<strong>";
 echo "Totals for ".$server." for ".$month." ".$day." ".$hour.$half;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Timeouts'>";
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

////////////////////////////////////////////////////////////////////////REC
  for ($b = 0; $b < $numrows; $b++)
{
$ServerName= mssql_result($result, $b, server_name);

if  (mssql_result($result, $b,rec)=='y'&& $ServerName==$server)
{
$date = mssql_result($result, $b,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];
$dateparts1 = preg_split('/ /', $date, -1, PREG_SPLIT_NO_EMPTY);
$readinghour=$dateparts1[3];

$hourparts1=explode(":", $readinghour);
$currenthour=$hourparts1[0];
$currenthalfd=$hourparts1[1];
$currenthalfp = strstr($currenthalfd, 'P');
$currenthalfa = strstr($currenthalfd, 'A');
if ($currenthalfp=='')
{
 $currenthalf=$currenthalfa ;
 }
if ($currenthalfa=='')
{
 $currenthalf=$currenthalfp ;
 }
if ($month==$readingmonth && $day==$readingday && $year==$readingyear && $currenthour==$hour && $currenthalf==$half)
 {

$totalq = $totalq + mssql_result($result, $b, total_qurries);
$totalto = $totalto + mssql_result($result, $b, timeouts);
$totalqps = $totalqps+ mssql_result($result, $b, qps);
$divider=$divider+1;

}
}
 }//end for
  echo"<tr> <td> ";
  echo"<strong>";
 echo "Total Recursive for ".$server ." for ".$month." ".$day." ".$hour.$half;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Recursive+Timeouts'>";
  echo"<p><strong>Timeouts: ".$totalto;
  echo"<br>Total Queries: ".$totalq;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
  echo"Total Queries per Second: ".$totalqps/$numrows;
 // echo"<p>";
  echo"</td>";
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
$date = mssql_result($result, $n,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];
$dateparts1 = preg_split('/ /', $date, -1, PREG_SPLIT_NO_EMPTY);
$readinghour=$dateparts1[3];

$hourparts1=explode(":", $readinghour);
$currenthour=$hourparts1[0];
$currenthalfd=$hourparts1[1];
$currenthalfp = strstr($currenthalfd, 'P');
$currenthalfa = strstr($currenthalfd, 'A');
if ($currenthalfp=='')
{
 $currenthalf=$currenthalfa ;
 }
if ($currenthalfa=='')
{
 $currenthalf=$currenthalfp ;
 }
if ($month==$readingmonth && $day==$readingday && $year==$readingyear && $currenthour==$hour && $currenthalf==$half)
 {

$totalq = $totalq + mssql_result($result, $n, total_qurries);
$totalto = $totalto + mssql_result($result, $n, timeouts);
$totalqps = $totalqps+ mssql_result($result, $n, qps);
$divider=$divider+1;

}
}
 }//end for
  echo" <td> ";
  echo"<strong>";
  echo "Total Simple for ".$server." for ".$month." ".$day ." ".$hour.$half;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Simple+Timeouts'>";
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

$server='cocbd01';
}//end
echo"</table>";
//////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////links



}
mssql_close(); //close DB connection

?>


<p>&nbsp;</p><hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>
