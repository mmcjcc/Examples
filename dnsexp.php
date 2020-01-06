<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?php
include("DNS.php");
$res = new Net_DNS_Resolver();
$res->debug = 0;
//$res->recurse = 1;
$res->nameservers = Array('158.73.19.3');
$answer = $res->search("conap01.hcfa.gov");
echo "<BR><HR><BR>";
print_r($answer);
?>
<body>

</body>
</html>
