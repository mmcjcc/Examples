<%@LANGUAGE="VBSCRIPT" CODEPAGE="1252"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
'****************************************************************
'Microsoft SQL Server 2000
'Visual Basic file generated for DTS Package
'File Name: C:\Documents and Settings\c1l0\Desktop\New Package.bas
'Package Name: dump
'Package Description: DTS package description
'Generated Date: 12/18/2003
'Generated Time: 6:03:56 PM
'****************************************************************
<script language="vb">
Option Explicit
Public goPackageOld As New DTS.Package
Public goPackage As DTS.Package2
Private Sub Main()
	set goPackage = goPackageOld

	goPackage.Name = "dump"
	goPackage.Description = "DTS package description"
	goPackage.WriteCompletionStatusToNTEventLog = False
	goPackage.FailOnError = False
	goPackage.PackagePriorityClass = 2
	goPackage.MaxConcurrentSteps = 4
	goPackage.LineageOptions = 0
	goPackage.UseTransaction = True
	goPackage.TransactionIsolationLevel = 4096
	goPackage.AutoCommitTransaction = True
	goPackage.RepositoryMetadataOptions = 0
	goPackage.UseOLEDBServiceComponents = True
	goPackage.LogToSQLServer = False
	goPackage.LogServerFlags = 0
	goPackage.FailPackageOnLogFailure = False
	goPackage.ExplicitGlobalVariables = False
	goPackage.PackageType = 0
	

Dim oConnProperty As DTS.OleDBProperty

'---------------------------------------------------------------------------
' create package connection information
'---------------------------------------------------------------------------

Dim oConnection as DTS.Connection2

'------------- a new connection defined below.
'For security purposes, the password is never scripted

Set oConnection = goPackage.Connections.New("SQLOLEDB")

	oConnection.ConnectionProperties("Persist Security Info") = True
	oConnection.ConnectionProperties("User ID") = "sa"
	oConnection.ConnectionProperties("Initial Catalog") = "jasontest"
	oConnection.ConnectionProperties("Data Source") = "LBZDS01"
	oConnection.ConnectionProperties("Application Name") = "DTS  Import/Export Wizard"
	
	oConnection.Name = "Connection 1"
	oConnection.ID = 1
	oConnection.Reusable = True
	oConnection.ConnectImmediate = False
	oConnection.DataSource = "LBZDS01"
	oConnection.UserID = "sa"
	oConnection.ConnectionTimeout = 60
	oConnection.Catalog = "jasontest"
	oConnection.UseTrustedConnection = False
	oConnection.UseDSL = False
	
	'If you have a password for this connection, please uncomment and add your password below.
	'oConnection.Password = "<put the password here>"

goPackage.Connections.Add oConnection
Set oConnection = Nothing

'------------- a new connection defined below.
'For security purposes, the password is never scripted

Set oConnection = goPackage.Connections.New("Microsoft.Jet.OLEDB.4.0")

	oConnection.ConnectionProperties("Data Source") = "C:\Servers\dump.xls"
	oConnection.ConnectionProperties("Extended Properties") = "Excel 8.0;HDR=YES;"
	
	oConnection.Name = "Connection 2"
	oConnection.ID = 2
	oConnection.Reusable = True
	oConnection.ConnectImmediate = False
	oConnection.DataSource = "C:\Servers\dump.xls"
	oConnection.ConnectionTimeout = 60
	oConnection.UseTrustedConnection = False
	oConnection.UseDSL = False
	
	'If you have a password for this connection, please uncomment and add your password below.
	'oConnection.Password = "<put the password here>"

goPackage.Connections.Add oConnection
Set oConnection = Nothing

'---------------------------------------------------------------------------
' create package steps information
'---------------------------------------------------------------------------

Dim oStep as DTS.Step2
Dim oPrecConstraint as DTS.PrecedenceConstraint

'------------- a new step defined below

Set oStep = goPackage.Steps.New

	oStep.Name = "Create Table server3 Step"
	oStep.Description = "Create Table server3 Step"
	oStep.ExecutionStatus = 1
	oStep.TaskName = "Create Table server3 Task"
	oStep.CommitSuccess = False
	oStep.RollbackFailure = False
	oStep.ScriptLanguage = "VBScript"
	oStep.AddGlobalVariables = True
	oStep.RelativePriority = 3
	oStep.CloseConnection = False
	oStep.ExecuteInMainThread = False
	oStep.IsPackageDSORowset = False
	oStep.JoinTransactionIfPresent = False
	oStep.DisableStep = False
	oStep.FailPackageOnError = False
	
goPackage.Steps.Add oStep
Set oStep = Nothing

'------------- a new step defined below

Set oStep = goPackage.Steps.New

	oStep.Name = "Copy Data from server3 to server3 Step"
	oStep.Description = "Copy Data from server3 to server3 Step"
	oStep.ExecutionStatus = 1
	oStep.TaskName = "Copy Data from server3 to server3 Task"
	oStep.CommitSuccess = False
	oStep.RollbackFailure = False
	oStep.ScriptLanguage = "VBScript"
	oStep.AddGlobalVariables = True
	oStep.RelativePriority = 3
	oStep.CloseConnection = False
	oStep.ExecuteInMainThread = True
	oStep.IsPackageDSORowset = False
	oStep.JoinTransactionIfPresent = False
	oStep.DisableStep = False
	oStep.FailPackageOnError = False
	
goPackage.Steps.Add oStep
Set oStep = Nothing

'------------- a precedence constraint for steps defined below

Set oStep = goPackage.Steps("Copy Data from server3 to server3 Step")
Set oPrecConstraint = oStep.PrecedenceConstraints.New("Create Table server3 Step")
	oPrecConstraint.StepName = "Create Table server3 Step"
	oPrecConstraint.PrecedenceBasis = 0
	oPrecConstraint.Value = 4
	
oStep.precedenceConstraints.Add oPrecConstraint
Set oPrecConstraint = Nothing

'---------------------------------------------------------------------------
' create package tasks information
'---------------------------------------------------------------------------

'------------- call Task_Sub1 for task Create Table server3 Task (Create Table server3 Task)
Call Task_Sub1( goPackage	)

'------------- call Task_Sub2 for task Copy Data from server3 to server3 Task (Copy Data from server3 to server3 Task)
Call Task_Sub2( goPackage	)

'---------------------------------------------------------------------------
' Save or execute package
'---------------------------------------------------------------------------

'goPackage.SaveToSQLServer "(local)", "sa", ""
goPackage.Execute
goPackage.Uninitialize
'to save a package instead of executing it, comment out the executing package line above and uncomment the saving package line
set goPackage = Nothing

set goPackageOld = Nothing

End Sub


'------------- define Task_Sub1 for task Create Table server3 Task (Create Table server3 Task)
Public Sub Task_Sub1(ByVal goPackage As Object)

Dim oTask As DTS.Task
Dim oLookup As DTS.Lookup

Dim oCustomTask1 As DTS.ExecuteSQLTask2
Set oTask = goPackage.Tasks.New("DTSExecuteSQLTask")
Set oCustomTask1 = oTask.CustomTask

	oCustomTask1.Name = "Create Table server3 Task"
	oCustomTask1.Description = "Create Table server3 Task"
	oCustomTask1.SQLStatement = "CREATE TABLE `server3` (" & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Server_Name1` VarChar (50) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`IP` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Rack` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Domain` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Backup_Server` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Backup_Job` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Server_Location` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Asset_Tag` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Installation_Date` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Status` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`OS` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Manufacturer` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Model` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Serial` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Function` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Type` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`SLA` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Class` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Responsible_Admin` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Application_Owner` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Owner_Contact` VarChar (255) , " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & "`Notes` VarChar (255) " & vbCrLf
	oCustomTask1.SQLStatement = oCustomTask1.SQLStatement & ")"
	oCustomTask1.ConnectionID = 2
	oCustomTask1.CommandTimeout = 0
	oCustomTask1.OutputAsRecordset = False
	
goPackage.Tasks.Add oTask
Set oCustomTask1 = Nothing
Set oTask = Nothing

End Sub

'------------- define Task_Sub2 for task Copy Data from server3 to server3 Task (Copy Data from server3 to server3 Task)
Public Sub Task_Sub2(ByVal goPackage As Object)

Dim oTask As DTS.Task
Dim oLookup As DTS.Lookup

Dim oCustomTask2 As DTS.DataPumpTask2
Set oTask = goPackage.Tasks.New("DTSDataPumpTask")
Set oCustomTask2 = oTask.CustomTask

	oCustomTask2.Name = "Copy Data from server3 to server3 Task"
	oCustomTask2.Description = "Copy Data from server3 to server3 Task"
	oCustomTask2.SourceConnectionID = 1
	oCustomTask2.SourceSQLStatement = "select [Server_Name1],[IP],[Rack],[Domain],[Backup_Server],[Backup_Job],[Server_Location],[Asset_Tag],[Installation_Date],[Status],[OS],[Manufacturer],[Model],[Serial],[Function],[Type],[SLA],[Class],[Responsible_Admin],[Application_Owner],[Owner_Contact]"
	oCustomTask2.SourceSQLStatement = oCustomTask2.SourceSQLStatement & ",[Notes] from [jasontest].[dbo].[server3]"
	oCustomTask2.DestinationConnectionID = 2
	oCustomTask2.DestinationObjectName = "server3"
	oCustomTask2.ProgressRowCount = 1000
	oCustomTask2.MaximumErrorCount = 0
	oCustomTask2.FetchBufferSize = 1
	oCustomTask2.UseFastLoad = True
	oCustomTask2.InsertCommitSize = 0
	oCustomTask2.ExceptionFileColumnDelimiter = "|"
	oCustomTask2.ExceptionFileRowDelimiter = vbCrLf
	oCustomTask2.AllowIdentityInserts = False
	oCustomTask2.FirstRow = 0
	oCustomTask2.LastRow = 0
	oCustomTask2.FastLoadOptions = 2
	oCustomTask2.ExceptionFileOptions = 1
	oCustomTask2.DataPumpOptions = 0
	
Call oCustomTask2_Trans_Sub1( oCustomTask2	)
		
		
goPackage.Tasks.Add oTask
Set oCustomTask2 = Nothing
Set oTask = Nothing

End Sub

Public Sub oCustomTask2_Trans_Sub1(ByVal oCustomTask2 As Object)

	Dim oTransformation As DTS.Transformation2
	Dim oTransProps as DTS.Properties
	Dim oColumn As DTS.Column
	Set oTransformation = oCustomTask2.Transformations.New("DTS.DataPumpTransformCopy")
		oTransformation.Name = "DirectCopyXform"
		oTransformation.TransformFlags = 63
		oTransformation.ForceSourceBlobsBuffered = 0
		oTransformation.ForceBlobsInMemory = False
		oTransformation.InMemoryBlobSize = 1048576
		oTransformation.TransformPhases = 4
		
		Set oColumn = oTransformation.SourceColumns.New("Server_Name1" , 1)
			oColumn.Name = "Server_Name1"
			oColumn.Ordinal = 1
			oColumn.Flags = 8
			oColumn.Size = 50
			oColumn.DataType = 129
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = False
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("IP" , 2)
			oColumn.Name = "IP"
			oColumn.Ordinal = 2
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Rack" , 3)
			oColumn.Name = "Rack"
			oColumn.Ordinal = 3
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Domain" , 4)
			oColumn.Name = "Domain"
			oColumn.Ordinal = 4
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Backup_Server" , 5)
			oColumn.Name = "Backup_Server"
			oColumn.Ordinal = 5
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Backup_Job" , 6)
			oColumn.Name = "Backup_Job"
			oColumn.Ordinal = 6
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Server_Location" , 7)
			oColumn.Name = "Server_Location"
			oColumn.Ordinal = 7
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Asset_Tag" , 8)
			oColumn.Name = "Asset_Tag"
			oColumn.Ordinal = 8
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Installation_Date" , 9)
			oColumn.Name = "Installation_Date"
			oColumn.Ordinal = 9
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Status" , 10)
			oColumn.Name = "Status"
			oColumn.Ordinal = 10
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("OS" , 11)
			oColumn.Name = "OS"
			oColumn.Ordinal = 11
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Manufacturer" , 12)
			oColumn.Name = "Manufacturer"
			oColumn.Ordinal = 12
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Model" , 13)
			oColumn.Name = "Model"
			oColumn.Ordinal = 13
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Serial" , 14)
			oColumn.Name = "Serial"
			oColumn.Ordinal = 14
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Function" , 15)
			oColumn.Name = "Function"
			oColumn.Ordinal = 15
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Type" , 16)
			oColumn.Name = "Type"
			oColumn.Ordinal = 16
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("SLA" , 17)
			oColumn.Name = "SLA"
			oColumn.Ordinal = 17
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Class" , 18)
			oColumn.Name = "Class"
			oColumn.Ordinal = 18
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Responsible_Admin" , 19)
			oColumn.Name = "Responsible_Admin"
			oColumn.Ordinal = 19
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Application_Owner" , 20)
			oColumn.Name = "Application_Owner"
			oColumn.Ordinal = 20
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Owner_Contact" , 21)
			oColumn.Name = "Owner_Contact"
			oColumn.Ordinal = 21
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.SourceColumns.New("Notes" , 22)
			oColumn.Name = "Notes"
			oColumn.Ordinal = 22
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.SourceColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Server_Name1" , 1)
			oColumn.Name = "Server_Name1"
			oColumn.Ordinal = 1
			oColumn.Flags = 8
			oColumn.Size = 50
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = False
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("IP" , 2)
			oColumn.Name = "IP"
			oColumn.Ordinal = 2
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Rack" , 3)
			oColumn.Name = "Rack"
			oColumn.Ordinal = 3
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Domain" , 4)
			oColumn.Name = "Domain"
			oColumn.Ordinal = 4
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Backup_Server" , 5)
			oColumn.Name = "Backup_Server"
			oColumn.Ordinal = 5
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Backup_Job" , 6)
			oColumn.Name = "Backup_Job"
			oColumn.Ordinal = 6
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Server_Location" , 7)
			oColumn.Name = "Server_Location"
			oColumn.Ordinal = 7
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Asset_Tag" , 8)
			oColumn.Name = "Asset_Tag"
			oColumn.Ordinal = 8
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Installation_Date" , 9)
			oColumn.Name = "Installation_Date"
			oColumn.Ordinal = 9
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Status" , 10)
			oColumn.Name = "Status"
			oColumn.Ordinal = 10
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("OS" , 11)
			oColumn.Name = "OS"
			oColumn.Ordinal = 11
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Manufacturer" , 12)
			oColumn.Name = "Manufacturer"
			oColumn.Ordinal = 12
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Model" , 13)
			oColumn.Name = "Model"
			oColumn.Ordinal = 13
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Serial" , 14)
			oColumn.Name = "Serial"
			oColumn.Ordinal = 14
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Function" , 15)
			oColumn.Name = "Function"
			oColumn.Ordinal = 15
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Type" , 16)
			oColumn.Name = "Type"
			oColumn.Ordinal = 16
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("SLA" , 17)
			oColumn.Name = "SLA"
			oColumn.Ordinal = 17
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Class" , 18)
			oColumn.Name = "Class"
			oColumn.Ordinal = 18
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Responsible_Admin" , 19)
			oColumn.Name = "Responsible_Admin"
			oColumn.Ordinal = 19
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Application_Owner" , 20)
			oColumn.Name = "Application_Owner"
			oColumn.Ordinal = 20
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Owner_Contact" , 21)
			oColumn.Name = "Owner_Contact"
			oColumn.Ordinal = 21
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

		Set oColumn = oTransformation.DestinationColumns.New("Notes" , 22)
			oColumn.Name = "Notes"
			oColumn.Ordinal = 22
			oColumn.Flags = 104
			oColumn.Size = 255
			oColumn.DataType = 130
			oColumn.Precision = 0
			oColumn.NumericScale = 0
			oColumn.Nullable = True
			
		oTransformation.DestinationColumns.Add oColumn
		Set oColumn = Nothing

	Set oTransProps = oTransformation.TransformServerProperties

		
	Set oTransProps = Nothing

	oCustomTask2.Transformations.Add oTransformation
	Set oTransformation = Nothing

End Sub
</script>

</body>
</html>
