<%
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
'
'                             COPYRIGHT NOTICE
'
'   The contents of this file is protected under the United States
'   copyright laws. Its use or disclosure in whole or in
'   part is governed by the terms of the MCK License which accompanied
'   the file or was made available at the time the file was downloaded. Use or
'   disclosure of this file in whole or in part shall be deemed acceptance of 
'   the terms of the MCK License.
' 
'  (c) Copyright 2000 by CyberCash, Inc. All rights reserved.

' Written by Vivien Chen
'
' These routines are intended to be used exclusively with the 
' CyberCash secure payment system.
'
' CCMckDirectLib.inc
' 
' Do not redistribute without written permission from CyberCash Inc.
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' Variable naming standard:
'    gName - global variable
'    sName - string
'    nName - number
'    cName - count of Names, used in a looping
'    fName - boolean
'    arName - array
'    DictName - dictionary object
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' SendCC2_1Server
'   Input: sOperation(required), DictArgs (required)
'   Output: DictResult
'
' This takes the old sendmserver format and generates a message to the
' wrapper CR2.1 API interpreter
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

sub SendCC2_1Server (sOperation, DictArgs, DictResult)
   Dim sPaymentURL, sDebugMsg, arDictKey, cCount

   ' DON'T TOUCH THIS! This is the CR script that will process your payment.
   sPaymentURL = gDictConfig.Item("CCPS_HOST") & "cr21api.cgi" _
               & "/" & sOperation

   sDebugMsg = "Url is " & sPaymentURL
   Call CCDebug2(7, sDebugMsg)

   Dim SockObj
   set SockObj = Server.CreateObject("CyberCashMCK.socket.1")

   Dim Args
   set Args = Server.CreateObject("CyberCashMCK.MessageBlock")
   Args.ReadDictionary(DictArgs)

   Dim Result
   set Result = SockObj.SendCCServer(sPaymentURL, sConfigLoc, Args)

   set DictResult = Server.CreateObject("Scripting.Dictionary")
   Result.WriteDictionary(DictResult)

end sub


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' doDirectPayment()
'   Input: sPaymentURL(required), DictPayment (required)
'   Output: DictPOP, DictToken 
' 
' To sned and receive message to/from wrapper 
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
sub doDirectPayment(sPaymentURL, DictPayment, DictPOP, DictToken)
   On Error Resume Next
   Dim sOrderId, sResult, sPOP, sTmp, arDictKey, cCount
   Dim sDebugMsg, sName

   Dim Args
   Set Args = CreateObject("CyberCashMCK.MessageBlock")
   Dim Result
   Dim DictMo
   Set DictMo = CreateObject("Scripting.Dictionary")
   Dim DictCpi
   Set DictCpi = CreateObject("Scripting.Dictionary")
   Dim DictMf
   Set DictMf = CreateObject("Scripting.Dictionary")


   ' For templating after the payment completes
   arDictKey = DictPayment.keys
   for cCount = 0 to DictPayment.Count - 1
      sName = arDictKey(cCount)
      DictToken.Add sName, DictPayment.item(sName)
   Next

   ' Build the arguments then call SendCCServer
   sOrderId = DictPayment.item("mo.order-id")

   ' Buld blocks 
   Call BuildBlock("mo", DictPayment, DictMo)
   Call BuildBlock("cpi", DictPayment, DictCpi)
   Call BuildBlock("mf", DictPayment, DictMf)

   Args.Add "MO", genURLencodedForm(DictMo)
   DictToken.Add "MO", DictArgs.Item("MO")

   Args.Add "CPI", genURLencodedForm(DictCpi)
   DictToken.Add "CPI", DictArgs.Item("CPI")

   ' No reason to send MF... response comes back here!
   ' Args will be used for templating, so make sure *everything* is there.

   DictToken.Add "MF", genURLencodedForm(DictMf)

   Dim SockObj
   set SockObj = Server.CreateObject("CyberCashMCK.socket.1")
   set Result = SockObj.SendCCServer(sPaymentURL, sConfigLoc, Args)

   ' Result will have at least:
   '       'MStatus'
   '       'MErrLoc'
   '       'MErrMsg'
   ' since SendCCServer will generate these for any message that
   ' does not naturally return them
   '
   ' Any "normal" return will contain a POP block as well.
   '  Load that into the tolken list
   '  *AND* if the POP is not present, generate an error POP
    
   sPOP = Result.Lookup("POP")
   if (sPOP <> "") then
      DictToken.Add "POP", sPOP
      Call URLdecodeForm(sPOP, DictPOP) 
   else
      ' Build a false POP from the status info, etc.
      DictPOP.Add "pop.status", Result.Lookup("MStatus")
      DictPOP.Add "pop.error-code", MCKGetErrorMessage(nE_No_POP) 

      ' Build an error message
      sTmp = "Location: " & Result.Lookup("MErrLoc") _
           & "; Message: " & Result.Lookup("MErrMsg")
      DictPOP.Add "pop.error-message", sTmp

      sTmp = GetCurrentTimeString() & ".000"
      DictPOP.Add "pop.sale-date", sTmp

      ' These are needed for logging ...
      DictPOP.Add "pop.order-id", sOrderId 

      sTmp = genURLencodedForm(DictPOP)
      DictToken.Add "POP", sTmp 
   end if

   DictToken.Add "#error-code#", DictPOP.item("pop.error-code")
   DictToken.Add "#error-message#", DictPOP.item("pop.error-message")
   DictToken.Add "status", DictPOP.item("pop.status") 

   ' Also need to build a failureAnchor
   if (DictPayment.Exists("mo.failure-url")) then
      sTmp = "<a href=" & Chr(34) & DictPayment.Item("mo.failure-url") _
           & Chr(34) & "> Additional Information </a> "
      DictToken.Add "#failAnchor#", sTmp 
   else
      DictToken.Add "#failAnchor#", ""
   end if

   ' Load everything in the POP into the templating dictionary 
   ' and into result dictionary 
   arDictKey = DictPOP.keys
   for cCount = 0 to DictPOP.Count -1
      sTmp = arDictKey(cCount)
      DictToken.Add sTmp, DictPOP.item(sTmp)
      Result.Add sTmp, DictPOP.item(sTmp)
   Next

   ' And build the Manual Notification parameters
   ' Use only the POP and MF... Nothing outside blocks.
   sTmp = "<input type=hidden name=" & Chr(34) & "POP" & Chr(34) _
        & "value=" & Chr(34) & Result.Lookup("POP") & Chr(34) & ">" & CRTN
   sTmp = sTmp & "<input type=hidden name=" & Chr(34) & "MF" & Chr(34) _
        & "value=" & Chr(34) & DictToken.Item("MF") & Chr(34) & ">" & CRTN
   DictToken.Add "#MANUAL_NOTIFICATION#", sTmp

   'Caution... this data may contain card info...
   'dump this only if you must have it, then clear the debug log
   'after you've used it
   'Call CCDebug2(7, "")
   'Call CCDebug2(7, "Token List is: ")
   'Call CCDebugNVlist(7, DictToken)
   'Call CCDebug2(7, "End of Token List")
end sub

%>
