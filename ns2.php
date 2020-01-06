<!DOCTYPE html PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
<title>nslookup</title>
</head>
<?
$server = $HTTP_POST_VARS["server"];
$times = $HTTP_POST_VARS["times"];
$rec = $HTTP_POST_VARS["rec"];
$timeout = $HTTP_POST_VARS["timeout"];

function randlines($file, $numlines=1) {
    if (!$lines = @file($file))
        return array("couldn't read word file '$file'");

    mt_srand((double)microtime()*1000000);

    for ($i=0; $i<=($numlines-1); $i++)
        $returnvals[] = trim($lines[mt_rand(0, count($lines)-1)]);

    return $returnvals;
    }

if (!$numwords) $numwords = 1;
$bodybg        = '#FEFEAA';
$lightbg    = '#FFFFBB';
$wordfile    = 'words.txt';
//$times=3;
for($i=0; $i <= $times; $i++)
{
$url = urlencode(join(" ", randlines($wordfile, $numwords))) . ".com";
$host = gethostbyname($url);  
echo $host ."<br>";   
    echo 'nslookup running: <br>';
    if ($rec == 'y')
	{
	echo"looking for ".$url."<br>";
	system("nslookup -timeout=$timeout $url $server");
    echo"<br>";
    }
	if($rec =='n')
	{
	system("nslookup -timeout=$timeout conap01 $server");
    echo "<br>"; 
    }
	if ($rec=='both')
	{
	system("nslookup -timeout=$timeout conap01 $server");
    echo"<br>";
	echo"looking for ".$url."<br>";
	system("nslookup -timeout=$timeout $url $server");
    echo "<br>"; 
	
	} 
 }    
    
?>
