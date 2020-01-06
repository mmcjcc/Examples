
</html>
<%@ Language="VBScript" %>
<% Option Explicit %>
<%
If Request.Form("frmHost") = "" Then
    ' Set Initial Value to Local IP Address
    strIP = Request.ServerVariables("REMOTE_ADDR")
Else
    strIP = Request.Form("frmHost") 
End If
%>
<html>
<head>
    <title>Jay's ASP Reverse DNS Lookup [v 1.0]</title>
</head>
<body bgcolor="#FFFFFF">

<form Method="POST" Name="frmRDNS">
    <label for="frmHost"><u>Host:</u></label>
    <input type="text" name="frmHost" ID="frmHost"
        value="<%= strIP  %>">
    <input type="button" name="btnSubmit" ID="btnSubmit"
        value="Lookup" onClick="document.frmRDNS.submit()">
</form>

<font face="arial" size="2" color="#003366">
<%
rMethod = uCase(Request.ServerVariables("REQUEST_METHOD"))
If rMethod = "POST" Then
    ' Lookup Host
    strReturn = nsLookup(strIP)
    If strReturn <> "" Then
        Response.Write strReturn
    Else
        ' A Lame Host is any Valid Host that DNS Cannot Resolve
        ' See InterNic for Details
        Response.Write "<b>Lame Host - Could Not Resolve DNS For " _
            & strIP & "</b><br>"
    End If
End If

Function NSlookup(strHost)
    'Create Shell Object
    Set oShell = Server.CreateObject("Wscript.Shell")
    'Run NSLookup via Command Prompt
    'Dump Results into a temp text file
    oShell.Run "%ComSpec% /c nslookup " & strHost _
        & "> C:\" & strHost & ".txt", 0, True

    'Open the temp Text File and Read out the Data
    Set oFS = Server.CreateObject("Scripting.FileSystemObject")
    Set oTF = oFS.OpenTextFile("C:\" & strHost & ".txt")

    tempData = Null
    Data = Null
    i = 0
    Do While Not oTF.AtEndOfStream
        Data = Trim(oTF.Readline)
            If i > 2 Then ' Don't want to display local DNS Info.
                tempData = tempData & Data & "<BR>"
            End If
        i = (i + 1)
    Loop

    'Close it
    oTF.Close
    'Delete It
    oFS.DeleteFile "C:\" & strHost & ".txt"

    Set oFS = Nothing
    nsLookup = tempData
End Function
%>
</font>

</body>
</html>

