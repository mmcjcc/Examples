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

$server = $HTTP_GET_VARS["server"];
$month = $HTTP_GET_VARS["month"];
$year = $HTTP_GET_VARS["year"];

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

for ($j = 0; $j < $numrows; $j++)
{
$date = mssql_result($result, $j,0);
$dateparts1 = explode(" ", $date);
$dateparts = explode(" ", $date);
$currentday=$dateparts[1];

$ServerName= mssql_result($result, $j, server_name);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////If Server is defined
if($server !='')
{
if($ServerName==$server && $dateparts[0]==$month && $dateparts[2] ==$year)
{//start if day, month, and year are correct

// echo $currentday . " ".$lastday ."<br>";
if ($currentday == $lastday)
{
$totalto = $totalto + mssql_result($result, $j, timeouts);
  $lastmonth=$dateparts[0];

//echo "Ran Part 1, Timeout's == ".$totalto."<br>";
//if ($j==$numrows-1)
//{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
//  $l->AddValue($dateparts[0]. " ".$lastday, array($totalto));
 // $lastday=$currentday;
 // $totalto=0;
 // $totalto = $totalto + mssql_result($result, $j, timeouts);
  //  $lastmonth=$dateparts[0];

//echo "Ran Part 2 Timeout's == ".$totalto."<br>";

//}
}
if ($currentday != $lastday && $currentday !='' && $firstrun!=true)
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($dateparts[0]. " ".$lastday, array($totalto));
  $lastday=$currentday;
  //echo "Ran Part 3 Timeout's == ".$totalto."<br>";
  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);
    $lastmonth=$dateparts[0];


}
if ($firstrun == true)
{
$lastday=$currentday;
$totalto = $totalto + mssql_result($result, $j, timeouts);
$firstrun=false;
 $lastmonth=$dateparts[0];

//echo "Ran first run <br>";
}

}//end if
if ($j==$numrows-1) //To account for last day of month.  
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($lastmonth. " ".$lastday, array($totalto));
  $lastday=$currentday;
  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);
    $lastmonth=$dateparts[0];

//echo "Ran Part 2 Timeout's == ".$totalto."<br>";

}

}//end if

///////////////////////////////////////////////////////////////////////////////////////////Server is not defined
if($server=='')
{ 

if($server==''&& $dateparts[0]==$month && $dateparts[2] ==$year)
{//start if day, month, and year are correct
$dateparts = explode(" ", $date);
$currentday=$dateparts[1];
 
if ($currentday == $lastday )
{
$totalto = $totalto + mssql_result($result, $j, timeouts);
$lastmonth=$dateparts[0];
//echo "Ran Part 1 Timeout's == ".$totalto."<br>";
/*if ($j==$numrows-1)
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($dateparts[0]. " ".$lastday, array($totalto));
  $lastday=$currentday;
  $lastmonth=$dateparts[0];
  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);

} */
}
if ($currentday != $lastday && $currentday !='' && $firstrun!=true)
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($dateparts[0]. " ".$lastday, array($totalto));
  $lastday=$currentday;
  $lastmonth=$dateparts[0];

  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);

}
if ($firstrun == true)
{
$lastday=$currentday;
$totalto = $totalto + mssql_result($result, $j, timeouts);
$firstrun=false;
$lastmonth=$dateparts[0];
//echo "Ran first run <br>";
}


}//end if
if ($j==$numrows-1) //Takes care of the last day of the month blahhhh I hate dates.
{
  //echo $totalto. "  Currenthour:" .$lasthour ."<br>";
  $l->AddValue($lastmonth. " ".$lastday, array($totalto));
  $lastday=$currentday;
  $totalto=0;
  $totalto = $totalto + mssql_result($result, $j, timeouts);

}
}//end if


 }//end for



$l->SetSeriesLabels(Array("Timeouts/Day"));

$l->spit("png");


?>
