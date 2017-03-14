Option Explicit
Sub SheetSplit()
    '
    'Creates an individual workbook for each worksheet in the active workbook.
    '
    Dim wbDest As Workbook
    Dim wbSource As Workbook
    Dim sht As Object
    Dim tempSheet As Worksheet
    Dim strSavePath As String
    Dim sname As String
    Dim relativePath As String
    Dim couplername As String
    Dim printedArea As String
    Dim dirpath As String
    Dim innerdirpath As String
    Dim areaRange As Range

    'Dim oCht As Chart
    
    Dim i  As Integer
    Dim intCount As Integer
    Dim objPic As Shape
    Dim objChart As Chart
    
    
    Set wbSource = ActiveWorkbook
    With wbSource
            .Sheets.Add(After:=.Sheets(.Sheets.Count)).Name = "tempSheet"
    End With
    For Each sht In wbSource.Sheets
    
    If sht.Name <> "tempSheet" Then
        sht.Copy
        Set wbDest = ActiveWorkbook
        couplername = sht.Range("A1")
        printedArea = sht.PageSetup.PrintArea
        'MsgBox printedArea
        sname = sht.Range("A1") & ".xls"
        dirpath = wbSource.Path & "\" & "temp" & "\"
        If Len(Dir(dirpath, vbDirectory)) = 0 Then
           MkDir dirpath
        End If
        innerdirpath = wbSource.Path & "\" & "temp" & "\" & couplername & "\"
        If Len(Dir(innerdirpath, vbDirectory)) = 0 Then
           MkDir innerdirpath
        End If
        relativePath = wbSource.Path & "\" & "temp" & "\" & couplername & "\" & couplername & "_wiring" 'use path of wbSource
        
        Application.DisplayAlerts = False
        ActiveWorkbook.CheckCompatibility = False
        ActiveWorkbook.SaveAs Filename:=relativePath, FileFormat:=xlExcel8
        Application.DisplayAlerts = True
        
        
        sht.ExportAsFixedFormat Type:=xlTypePDF, _
                        Filename:=wbSource.Path & "\" & "temp" & "\" & couplername & "\" & couplername & "_wiring" & ".pdf", _
                        IgnorePrintAreas:=False, _
                        Quality:=xlQualityStandard, _
                        OpenAfterPublish:=False
        
        'copy the range as an image
        Call sht.Range(printedArea).CopyPicture(xlScreen, xlPicture)
        
        'remove all previous shapes in sheet
        intCount = Sheets("tempSheet").Shapes.Count
        For i = 1 To intCount
            Sheets("tempSheet").Shapes.Item(1).Delete
        Next i
        'create an empty chart in sheet
        Sheets("tempSheet").Shapes.AddChart
        'activate sheet2
        Sheets("tempSheet").Activate
        'select the shape in sheet
        Sheets("tempSheet").Shapes.Item(1).Select
        Set objChart = ActiveChart
        'paste the range into the chart
        
        Sheets("tempSheet").Shapes.Item(1).Line.Visible = msoFalse
        Sheets("tempSheet").Shapes.Item(1).Width = sht.Range(printedArea).Width
        Sheets("tempSheet").Shapes.Item(1).Height = sht.Range(printedArea).Height
        objChart.Paste
        'save the chart as a png
        objChart.Export Filename:=wbSource.Path & "\" & "temp" & "\" & couplername & "\" & couplername & "_wiring" & ".png", Filtername:="PNG"
        
 
        
        wbDest.Close False 'close the newly saved workbook without saving (we already saved)
        

    End If
    Next sht
    Application.DisplayAlerts = False
    Sheets("tempSheet").Delete
    Application.DisplayAlerts = True
    'MsgBox "Done!"
 
End Sub
