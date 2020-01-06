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
$month = $HTTP_GET_VARS["month"];
$year = $HTTP_GET_VARS["year"];
echo"<p><strong>HISTORICAL DNS INFORMATION FOR: ".$day." ".$month." ".$year."</p></strong><p></p>";

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
$totaltomaster;
for ($i = 0; $i < $numrows; $i++)
{
$date = mssql_result($result, $i,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];
if ($month==$readingmonth&& $day==$readingday && $year==$readingyear)
 {
$ServerName= mssql_result($result, $i, server_name);
$totalq = $totalq+ mssql_result($result, $i, total_qurries);
$totalto = $totalto+ mssql_result($result, $i, timeouts);
$totaltomaster = $totalto;

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
 echo "Totals for ".$month. " ".$day;
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
  include("linemaker.php");

  
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
if ($month==$readingmonth&& $day==$readingday && $year==$readingyear)

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

 echo "Total Recursive for ".$month. " ".$day;
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
if ($month==$readingmonth&& $day==$readingday && $year==$readingyear)
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
 echo "Total Simple for ".$month." ".$day;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Simple+Timeouts'>";
  echo"<p><strong>Timeouts: ".$totalto;
  echo"<br>Total Queries: ".$totalq;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
  echo"Total Queries per Second: ".$totalqps/$divider;
  sleep(0.8);

  echo"</td>";
$totalq=0;
$totalto=0;
$totalqps=0;
$divider=0;
echo" <td> ";
  echo"<strong>";
 echo "Total Timeouts over recorded hours for ".$month." ".$day." (each square is a recorded hour)<br>";
echo"
<img src='createlinegraphday.php?day=".$day."&&month=".$month."&&year=".$year."'> </td></tr>";
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
if ($month==$readingmonth&& $day==$readingday && $year==$readingyear)
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
  echo"<tr><td> ";
    echo"<strong>";
 echo "Totals for ".$server." for ".$month." ".$day;
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
for ($b = 0; $b < $numrows; $b++)
{

$ServerName= mssql_result($result, $b, server_name);

/////////////
if  (mssql_result($result, $b,rec)=='y' && $server==$ServerName)
{
$date = mssql_result($result, $b,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];
if ($month==$readingmonth && $day==$readingday && $year==$readingyear)
 {

$totalq = $totalq + mssql_result($result, $b, total_qurries);
$totalto = $totalto + mssql_result($result, $b, timeouts);
$totalqps = $totalqps+ mssql_result($result, $b, qps);
$divider=$divider+1;

}
}
 }//end for
  echo" <td> ";
  echo"<strong>";
 echo "Total Recursive for ".$server ." for ".$month." ".$day;
  $percent = ($totalto/$totalq)*100;
  $succ = $totalq-$totalto; 
  echo"<br>";
  echo " <img src='piemaker.php?width=375&height=375&values=".$succ.",".$totalto.",&desc=Successful+Queries,Timeouts&title=Total+Recursive+Timeouts'>";
  echo"<p><strong>Timeouts: ".$totalto;
  echo"<br>Total Queries: ".$totalq;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
  echo"Total Queries per Second: ".$totalqps/$numrows;
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
$date = mssql_result($result, $n,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];
if ($month==$readingmonth&& $day==$readingday && $year==$readingyear)
 {

$totalq = $totalq + mssql_result($result, $n, total_qurries);
$totalto = $totalto + mssql_result($result, $n, timeouts);
$totalqps = $totalqps+ mssql_result($result, $n, qps);
$divider=$divider+1;

}
}
 }//end for
  echo" <tr><td> ";
  echo"<strong>";
 echo "Total Simple for ".$server." for ".$month." ".$day;
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
 echo "Total Timeouts for ".$server." over recorded hours for ".$month." ".$day." (each square is a recorded hour)";
echo"
<img src='createlinegraphday.php?day=".$day."&&month=".$month."&&year=".$year."&&server=".$server."'> </td></tr>";
sleep(1.0);

$server='cocbd01';
}//end
echo"</table>";
//////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////links


echo"<p> Results by Hour<p>";
$notshownyet=false;
$currentmonth;
$currentday;
$currenthour;
//for ($x=3; $x<=4; $x++) //to correct for hour parts, don't ask me why
//{
for ($j = 0; $j < $numrows; $j++)
{
$date = mssql_result($result, $j,0);

$dateparts1 = preg_split('/ /', $date, -1, PREG_SPLIT_NO_EMPTY);
//print_r($chars);


//$dateparts1 = explode(" ", $date);
$hour=$dateparts1[3]; //was x
$hourparts=explode(":", $hour);
//echo $hour."<br>";
//echo $hourparts[0]."<br>";
if($dateparts1[0] == $month && $dateparts1[2]==$year && $day == $dateparts1[1])
{//start if day, month, and year are correct
if ($currenthour != $hourparts[0])
 {
  $notshownyet=true;
 }
$dateparts = explode(" ", $date);
$currentday=$dateparts[1];
$hour1=$dateparts[4];
$hourparts1=explode(":", $hour);
$currenthour=$hourparts1[0];
$currenthalfd=$hourparts[1];
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
  
if($notshownyet==true&&$currenthour!='')
{
 // if($x==3)
  //{
  echo "<a href='displayhour.php?hour=".$currenthour."&&half=".$currenthalf."&&day=".$currentday."&&month=".$month ."&&year=".$dateparts[2]."&&datepart=3"."'>View Results for ".$dateparts[0]. " ".$dateparts[1]." ".$dateparts[2]." ". $currenthour." ".$currenthalf."</a><br>";
 // }
// if($x==4)
 //{
 // echo "<a href='displayhour.php?hour=".$currenthour."&&half=".$currenthalf."&&day=".$currentday."&&month=".$month ."&&year=".$dateparts[2]."'>View Results for ".$dateparts[0]. " ".$dateparts[1]." ".$dateparts[2]." ". $currenthour." ".$currenthalf."</a><br>";
// }
  $notshownyet=false;
}

$rec = mssql_result($result, $j,rec);
$ServerName= mssql_result($result, $j, server_name);
 }//end if month and year are correct
 
 }//end for
//}//end for loop to correct for hour parts
//echo"<img src='displayday2.php?day=26&&month=Jan&&year=2004'>";

}
mssql_close(); //close DB connection

?>


<p>&nbsp;</p><hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>
