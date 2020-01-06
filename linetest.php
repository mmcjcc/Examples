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


$day='27';
$month = 'Jan';
$year = '2004';

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
$l->SetTitleColor(255, 255, 255);
$l->SetTitle("Timeouts for this day");

$numrows = mssql_num_rows($result);
$firstrun=true;
for ($x = 3; $x <= 4; $x++)
{
for ($j = 0; $j < $numrows; $j++)
{
$date = mssql_result($result, $j,0);
$dateparts1 = explode(" ", $date);
if($dateparts1[0] == $month && $dateparts1[2]==$year && $day == $dateparts1[1])
{//start if day, month, and year are correct
$dateparts = explode(" ", $date);
$currentday=$dateparts[1];
$hour1=$dateparts[$x];
$hourparts1=explode(":", $hour1);
$currenthour=$hourparts1[0];
$currenthalfd=$hourparts[1];
$currenthalfp = strstr($currenthalfd, 'P');
$currenthalfa = strstr($currenthalfd, 'A');
 
if ($currenthour == $lasthour && $currenthour !='')
{
$totalto = $totalto + mssql_result($result, $j, timeouts);
//echo "Ran Part 1 Timeout's == ".$totalto."<br>";
if ($j==$numrows-1)
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($lasthour, array($totalto));
  $lasthour=$currenthour;
  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);

}
}
if ($currenthour != $lasthour && $currenthour !='' && $firstrun!=true)
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($lasthour, array($totalto));
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



//$l->AddValue($currenthour, array($lasthour));
}
 }//end for
}//end if month and year are correct



//$l->AddValue("Hour 1", array(2));

$l->SetSeriesLabels(Array("Timeouts"));

$l->spit("jpg");


?>
