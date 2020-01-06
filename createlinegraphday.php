<?php


/*

The Line Graph generator by Ashish Kasturia (http://www.123ashish.com)
Copyright (C) 2003 Ashish Kasturia (ashish at 123ashish.com)


The Line Graph generator is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, 
USA.

*/

$day=$HTTP_GET_VARS["day"];
$month = $HTTP_GET_VARS["month"];
$year = $HTTP_GET_VARS["year"];
$server = $HTTP_GET_VARS["server"];

include("linemaker.php");
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

$l = new Line();
//$l->SetBGJPEGImage("../catherine.jpg");
$l->SetTitleColor(0, 0, 152);
$l->SetTitle("Timeouts");
$l->SetBGColor(255, 255, 255);
$l->SetAxesColor(207, 207, 207);
$numrows = mssql_num_rows($result);
$firstrun=true;
//for ($x = 3; $x <= 4; $x++)
//{
for ($j = 0; $j < $numrows; $j++)
{
$date = mssql_result($result, $j,0);
$dateparts1 = explode(" ", $date);
$ServerName= mssql_result($result, $j, server_name);
if($server !='')
{
if($ServerName==$server && $dateparts1[0] == $month && $dateparts1[2]==$year && $day == $dateparts1[1])
{//start if day, month, and year are correct
$currenthalfp = '';
$currenthalfa='';
$dateparts = explode(" ", $date);
$currentday=$dateparts[1];
$dateparts = preg_split('/ /', $date, -1, PREG_SPLIT_NO_EMPTY);

$hour1=$dateparts[3];
$hourparts1=explode(":", $hour1);
$currenthour=$hourparts1[0];
$currenthalfd=$hourparts1[1];
$currenthalfp = strstr($currenthalfd, 'P');
$currenthalfa = strstr($currenthalfd, 'A');
if ($currenthalfp=='')
{
 $currenthalf='AM';
 }
 if ($currenthalfa=='')
{
 $currenthalf='PM' ;
 }
//echo  $currenthalf;
if ($currenthour == $lasthour && $currenthour !='')
{
$totalto = $totalto + mssql_result($result, $j, timeouts);
//echo "Ran Part 1 Timeout's == ".$totalto."<br>";
if ($j==$numrows-1)
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($lasthour." ".$currenthalf, array($totalto));
  $lasthour=$currenthour;
  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);

}
 }


if ($currenthour != $lasthour && $currenthour !='' && $firstrun!=true)
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($lasthour." ".$currenthalf, array($totalto));
  $lasthour=$currenthour;
  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);

}
if ($firstrun == true)
{
$lasthour=$currenthour;
$totalto = $totalto + mssql_result($result, $j, timeouts);
$firstrun=false;
//echo "Ran first run <br>";
}
 //read ahead a day
if($j !=$numrows-1) //A bunch of stuff to deal with the last hour of a day, what a pain!!!!!
{
  $nextserver = mssql_result($result, $j+1,server_name); //read ahead
  $date2 = mssql_result($result, $j+1,0);
  $dateparts2 = explode(" ", $date2);
  $currentday2=$dateparts2[1];
  $dateparts2 = preg_split('/ /', $date2, -1, PREG_SPLIT_NO_EMPTY);
  $hour2=$dateparts2[3];
  $hourparts2=explode(":", $hour2);
  $currenthour2=$hourparts2[0];
  if($j+3 <=$numrows-1)
  {
  $date3 = mssql_result($result, $j+3,0);
  $dateparts3 = explode(" ", $date3);
 
  }
  else 
  {
     $anotherfuckedupcondition=true;
	 
  }
  //echo $currentday . " ".$currentday2."<br>";
 if($currentday != $currentday2||($server != $nextserver&& $dateparts3[1]!=$currentday)||($anotherfuckedupcondition==true&&$server != $nextserver))
  { //echo"Match found";
   $l->AddValue($lasthour." ".$currenthalf, array($totalto));

  }
}//end if  
}//end if

}//end if

if($server=='')//////////////////////////////if server is not defined
{ //start if server is not defined
if($dateparts1[0] == $month && $dateparts1[2]==$year && $day == $dateparts1[1])
{//start if day, month, and year are correct
$dateparts = explode(" ", $date);
$currentday=$dateparts[1];
$dateparts = preg_split('/ /', $date, -1, PREG_SPLIT_NO_EMPTY);

$hour1=$dateparts[3];
$hourparts1=explode(":", $hour1);
$currenthour=$hourparts1[0];
$currenthalfd=$hourparts[1];
$currenthalfp = strstr($currenthalfd, 'P');
$currenthalfa = strstr($currenthalfd, 'A');
 if ($currenthalfp=='')
 {
 $currenthalf='AM';
 }
 if ($currenthalfa=='')
 {
 $currenthalf='PM' ;
 }
 
if ($currenthour == $lasthour && $currenthour !='')
{
$totalto = $totalto + mssql_result($result, $j, timeouts);
//echo "Ran Part 1 Timeout's == ".$totalto."<br>";
if ($j==$numrows-1)
 {
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($lasthour." ".$currenthalf, array($totalto));
  $lasthour=$currenthour;
  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);

 }
}
if ($currenthour != $lasthour && $currenthour !='' && $firstrun!=true)
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($lasthour." ".$currenthalf, array($totalto));
  $lasthour=$currenthour;
  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);

}
if ($firstrun == true)
{
$lasthour=$currenthour;
$totalto = $totalto + mssql_result($result, $j, timeouts);
$firstrun=false;
//echo "Ran first run <br>";
}
if($j !=$numrows-1) //A bunch of stuff to deal with the last hour of a day, what a pain!!!!!
{
  $nextserver = mssql_result($result, $j+1,server_name); //read ahead
  $date2 = mssql_result($result, $j+1,0);
  $dateparts2 = explode(" ", $date2);
  $currentday2=$dateparts2[1];
  $dateparts2 = preg_split('/ /', $date2, -1, PREG_SPLIT_NO_EMPTY);
  $hour2=$dateparts2[3];
  $hourparts2=explode(":", $hour2);
  $currenthour2=$hourparts2[0];
 // $date3 = mssql_result($result, $j+3,0);
  //$dateparts3 = explode(" ", $date3);
  //echo $currentday . " ".$currentday2."<br>";
 if($currentday != $currentday2)
  { //echo"Match found";
   $l->AddValue($lasthour." ".$currenthalf, array($totalto));

  }
}//end if  for last hout

}//end if last day correct

}//end if server correnct


// }//end for
}//end hour correction


$l->SetSeriesLabels(Array("Timeouts"));

$l->spit("png");


?>
