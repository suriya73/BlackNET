Imports System.Threading
Imports System.IO
Imports System.Runtime.InteropServices

Public Class Form1
    Public MTX As String = "RunOneWatchDog"
    Public MT As Mutex = Nothing
    Public LO As Object = New FileInfo(Application.ExecutablePath)

    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Me.Visible = False
        Me.Opacity = 0
        Me.ShowInTaskbar = False
        Me.Hide()
        Dim WatchDog As New ProcessWatcher
        WatchDog.StartWatcher()

        Try
            For Each x In Process.GetProcesses
                Try
                    If CompDir(New FileInfo(x.MainModule.FileName), LO) Then
                        If x.Id > Process.GetCurrentProcess.Id Then
                            End
                        End If
                    End If
                Catch ex As Exception
                End Try
            Next
        Catch ex As Exception
        End Try
        Try
            Mutex.OpenExisting(MTX)
            End
        Catch ex As Exception
        End Try
        Try
            MT = New Mutex(True, MTX)
        Catch ex As Exception
            End
        End Try
    End Sub
    Private Function CompDir(ByVal F1 As IO.FileInfo, ByVal F2 As IO.FileInfo) As Boolean ' Compare 2 path
        If F1.Name.ToLower <> F2.Name.ToLower Then Return False
        Dim D1 = F1.Directory
        Dim D2 = F2.Directory
re:
        If D1.Name.ToLower = D2.Name.ToLower = False Then Return False
        D1 = D1.Parent
        D2 = D2.Parent
        If D1 Is Nothing And D2 Is Nothing Then Return True
        If D1 Is Nothing Then Return False
        If D2 Is Nothing Then Return False
        GoTo re
    End Function
End Class