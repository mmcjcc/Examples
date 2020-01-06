<!DOCTYPE html PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
<title>nslookup</title>
</head>
<body>

<body bgcolor="#666699" text="#FFFFFF" link="#CCCCCC" vlink="#CCCCCC" alink="#CCCCCC"background="bk.jpg">
<a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a><p></p>
<hr>
<?php
echo"<strong>Conducting Requested test.... </strong><p>";

?>

 <?php
 //Get current time 
    $mtime = microtime(); 
//Split seconds and microseconds 
    $mtime = explode(" ",$mtime); 
//Create one value for start time 
    $mtime = $mtime[1] + $mtime[0]; 
//Write start time into a variable 
    $tstart = $mtime; 

$server = $HTTP_POST_VARS["server"];
$times = $HTTP_POST_VARS["times"];
$rec = $HTTP_POST_VARS["rec"];
$timeout = $HTTP_POST_VARS["timeout"];
$name = $HTTP_POST_VARS["name"];
$ver = $HTTP_POST_VARS["ver"];

function randlines($file, $numlines=1) {
    if (!$lines = @file($file))
        return array("couldn't read word file '$file'");

    mt_srand((double)microtime()*1000000);

    for ($i=0; $i<=($numlines-1); $i++)
        $returnvals[] = trim($lines[mt_rand(0, count($lines)-1)]);

    return $returnvals;
    }

if (!$numwords) $numwords = 1;

$wordfile    = 'words.txt';
$localw    = 'word.txt';
$timeouts=0;
//$times=3;
for($j=1; $j <= $times; $j++)
{
if ($name =='')
{
$url = urlencode(join(" ", randlines($wordfile, $numwords))) . ".com";
$local = urlencode(join(" ", randlines($localw, $numwords)));
}
else 
{
$url=$name;
$local=$name;
}
if ($ver == 'y')
	{
  echo '<strong>nslookup running:</strong> <br>';
  }
    if ($rec == 'y')
	{
	if ($ver == 'y')
	{
	echo"looking for ".$url."<br>";
	}
	//echo exec("nslookup -timeout=$timeout $url $server");
	$output = exec("nslookup -timeout=$timeout $url $server 2>&1", $array, $returnvar);
   	$size = count($array);
    $encounteredtimeout=false;
	for($i=0; $i <= $size; $i++)
    {
	if ($ver == 'y'){
     echo $array[$i]." ";} 
   if (preg_match("/timeout/", $array[$i]))
     {
	  if($encounteredtimeout==false)
	  {
	  $timeouts=$timeouts + 1;
      $encounteredtimeout=true;
	  }
	  }
     }
	 if ($ver == 'y')
	 {
	 echo"<br>";
	 }
	} //end if rec=y
	
////////////////////////////////////////////////////////////////////////////////////////
	if($rec =='n')
	{
	if ($ver == 'y'){
	echo "looking for ".$local. "<br>";
	}
	$output = exec("nslookup -timeout=$timeout $local $server 2>&1", $array, $returnvar);
   	$size = count($array);
    
   $encounteredtimeout=false;
	for($i=0; $i <= $size; $i++)
    {
	if ($ver == 'y'){
     echo $array[$i]." ";} 
   if (preg_match("/timeout/", $array[$i]))
     {
	  if($encounteredtimeout==false)
	  {
	  $timeouts=$timeouts + 1;
      $encounteredtimeout=true;
	  }
	  }
     }
	 if ($ver == 'y')
	 {
	 echo"<br>";
	 }
    }
//////////////////////////////////////////////////////////////////////////////////////////////
	if ($rec=='both')
	{
	if ($ver == 'y'){
	echo "looking for ".$local. "<br>";
	}
	$output = exec("nslookup -timeout=$timeout $local $server", $array, $returnvar);
   	$size = count($array);
    $encounteredtimeout=false;
	for($i=0; $i <= $size; $i++)
    {
	if ($ver == 'y'){
     echo $array[$i]." ";} 
   if (preg_match("/timeout/", $array[$i]))
     {
	  if($encounteredtimeout==false)
	  {
	  $timeouts=$timeouts + 1;
      $encounteredtimeout=true;
	  }
	  }
     }
	 if ($ver == 'y')
	 {
	 echo"<br>";
	echo"looking for ".$url."<br>";
	}
	$output = exec("nslookup -timeout=$timeout $url $server 2>&1", $array, $returnvar);
	//echo $returnvar;
   	$size = count($array);
   $encounteredtimeout=false;
	for($i=0; $i <= $size; $i++)
    {
	if ($ver == 'y'){
     echo $array[$i]." ";} 
   if (preg_match("/timeout/", $array[$i]))
     {
	  if($encounteredtimeout==false)
	  {
	  $timeouts=$timeouts + 1;
      $encounteredtimeout=true;
	  }
	  }
     }
	 if ($ver == 'y'){
	 echo"<br>";}
	
	} 
  }
  if ($rec=='both')
  {
   $times=$times*2;
  }
  $percent = ($timeouts/$times)*100;
  $succ = $times-$timeouts; 
  echo"<br>";
  echo " <img src='piemaker.php?width=400&height=300&values=".$succ.",".$timeouts.",&desc=Successful+Queries,Timeouts&title=Timeouts'>";
  echo"<p><strong>Timeouts: ".$timeouts;
  echo"<br>Total Queries: ".$times;
  echo"<br>Percent of queries which timed out: ". $percent."%<br>"; 
//Get current time as we did at start 
    $mtime = microtime(); 
    $mtime = explode(" ",$mtime); 
    $mtime = $mtime[1] + $mtime[0]; 
//Store end time in a variable 
    $tend = $mtime; 
//Calculate the difference 
    $totaltime = ($tend - $tstart); 
//Output result 
$qps = $times/$totaltime;
echo" Approx. Queries processed per second: ".$qps;
    echo "<p><strong> TEST COMPLETE "; 
  printf ("Test was completed in %f seconds ", $totaltime); 

?>
</body>
</html>