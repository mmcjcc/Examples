<html>
<head>
<title>Find a Server</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#666699" text="#FFFFFF" link="#CCCCCC" vlink="#CCCCCC" alink="#CCCCCC"background="bk.jpg">
<body link="#CCCCCC" vlink="#CCCCCC" alink="#CCCCCC">
    <a href="index.php"><img src="br.jpg" width="855" height="93" border="0"></a>
<p></p>
<hr>
<font size="+1"><strong>Enter the Server You Wish to Find:</strong> </font> 
<form name="form1" method="post" action="find.php">
  <p> <strong>Server Name: </strong>
<input type="text" name="ServerName">
  </p>

  <p><font size="+1">OR</font> </p>
  <p><strong>Search By IP:</strong> 
    <input type="text" name="IP_Search">
  </p>
  <p>
    <input type="submit" name="Submit" value="Submit">
  </p>
</form>
OR 
<p> Search by Type (ex. BD, PD, MF,..)</p>
<form name="form2" method="post" action="type.php">
  <p> <strong>Type: </strong>
<input type="text" name="ServerType">
  </p>

    <input type="submit" name="Submit" value="Submit">
  </p>
</form>
<p><font size="+1"><strong>Tools:</strong></font></p>
<p><a href="showall.php"><font size="+1">View All Systems in Database </font></a></p>
<p><a href="add.php"><font size="+1">Add A Server to the Database </font></a></p>
<p><a href="../TSWeb/default.htm"><font size="+1">Connect to Server via Terminal 
  Services</font></a></p>
<p><a href="updateall.php"><font size="+1">Update Patch Table with Current Server Patch Data (Could take over 1 hour)
 </font></a></p>
<p><a href="phone.php" target="_blank"><font size="+1">View Tier 2 Contact List </font></a></p>
<p><a href="http://dcrl/phone" target="_blank"><font size="+1">Additional Contacts </font></a></p>
<hr>
<p><img src="lm.jpg" width="264" height="46"> </p>
<p><font size="+3"><strong><img src="CMS.jpg" width="96" height="69"></strong></font></p>
</body>
</html>
