<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php

 function systemuptime ($ServerName, $CommunityString)
 {
   $sysup = snmpget($ServerName, $CommunityString, "system.sysUpTime.0");
   return $sysup;
}



?>
</body>
</html>
