
<?php
$day='26';
$month = 'Jan';
$year = '2004';

include("linemaker.php");
$l = new Line();

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
exit;
}

else 
 {

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$l = new Line();
$l->SetTitleColor(255, 255, 255);
$l->SetTitle("Timeouts over recorded period");

for ($j = 0; $j < $numrows; $j++)
{
$date = mssql_result($result, $j,0);
$dateparts1 = explode(" ", $date);
$hour=$dateparts1[4];
$hourparts=explode(":", $hour);

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
  
if($notshownyet==true)
{

for ($i = 0; $i < $numrows; $i++)
{
$date = mssql_result($result, $i,0);
$dateparts = explode(" ", $date);
$readingmonth=$dateparts[0];
$readingday=$dateparts[1];
$readingyear=$dateparts[2];
$readinghour=$dateparts[4];
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
$totalq = $totalq+ mssql_result($result, $i, total_qurries);
$totalto = $totalto+ mssql_result($result, $i, timeouts);
$l->AddValue($currenthour, $totalto);

 }
 }//end for

////////////////
  $notshownyet=false;
}

}//end if month and year are correct
 }//end for

$l->SetSeriesLabels(Array("Timeouts"));
$l->spit("jpg");

///////////////////////////////////////////////////////////////////////////////

}
mssql_close(); //close DB connection

?>

