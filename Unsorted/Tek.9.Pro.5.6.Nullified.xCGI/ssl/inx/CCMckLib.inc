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
' CCMckLib.inc
'
' This contains various subrountines and functions used by all of the
' MCK Payment scripts.  You should not have to modify them.
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

' Set the CyberCash Cash Register version number
sMCKversion = "3.3.1"


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' Debugging: Modify the variables below to enable debugging to a log file
' Be aware that performance is greatly decreased with debugging turned on
'
' Debugging is on if $debugging is not 0
' Set to 1 for API level debugging
' Set to 2 for debugging every step of the way
' You can set it higher than two and catch other levels of debugging.
' this routine uses levels 0, 1 and 2.
' At level 0. error messages will still go to the log if errorFlag is 1.
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
Dim nDebugFlag
nDebugFlag = 0

' Do you want errors logged? Default is yes. Set to 0 for no
Dim nErrorFlag
nErrorFlag = 1  

'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' CCLogError()
'   Input: sMessage(required)
'   Output: N/A
' 
' To log error messages to standard error & error log file.
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
sub CCLogError (sMessage)
   ' check for error output flag
   if (nErrorFlag = 0) then
      exit sub
   end if

   ' Write to stderr (if you can.. no guarentee here)
   ' write to debug log
   ' even if debugging is off...
   sMessage = "Error: " & sMessage
   Call CCDebug2(0, " ")
   Call CCDebug2(0, sMessage)
end sub


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' CCDebug()
'   Input: sMessage(required)
'   Output: N/A
' 
' Write to debug log.
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
sub CCDebug (sMessage)
   On Error Resume Next
   Dim fsLog, aLog, sFileName

   if (nDebugFlag > 0) then
      ' retrieve debug file name from configuration dictionary object
      sFileName = gDictConfig.Item("DEBUG_FILE")

      Set fsLog = CreateObject("Scripting.FileSystemObject")
      Set aLog = fsLog.OpenTextFile(sFileName, 8, TRUE)

      if (Err.Number <> 0) then
         Err.Clear()
      else
         aLog.WriteLine (sMessage)
         aLog.Close
      end if
   end if
end sub


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' CCDebug2()
'   Input: nLevel(required), sMessage(required)
'   Output: N/A
' 
' Write to debug log for a given level. If the _cc_debug_flag is numerically
' lower than the level presented, the message will not be log.
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
sub CCDebug2 (nLevel, sMessage)
   'On Error Resume Next
   Dim fsLog, aLog 
   Dim sFileName, nDebugLevel

   ' retrieve debug file and debug level from configuration dictionary object
   sFileName = gDictConfig.Item("DEBUG_FILE")
   nDebugLevel = gDictConfig.Item("DEBUG_LEVEL")

   if (IsNumeric(nLevel) and IsNumeric(nDebugLevel) and _
      CInt(nLevel) <= CInt(nDebugLevel)) then
      Set fsLog = CreateObject("Scripting.FileSystemObject")
      Set aLog = fsLog.OpenTextFile(sFileName, 8, TRUE)

      if (Err.Number <> 0) then
         Err.Clear()
      else
         aLog.WriteLine (sMessage)
         aLog.Close
      end if
   end if
end sub


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' CCDebugNVlist()
'   Input: nLevel(required), DictToken(required)
'   Output: N/A
' 
' Write to debug log for a given level. If the _cc_debug_flag is numerically
' lower than the level presented, the message will not be log.
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
sub CCDebugNVlist (nLevel, DictToken)
   Dim sTmp, arDictKey, cCount, sDebugMsg

   arDictKey = DictToken.keys
   for cCount = 0 to DictToken.Count -1
      sTmp = arDictKey(cCount)
      sDebugMsg = sTmp & " => " & DictToken.Item(sTmp)       
      Call CCDebug2(nLevel, sDebugMsg) 
   Next
end sub


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' ValidateOrderId()
'   Input: orderID(required)
'   Output: sMessage
' 
' This routine tests an order id for the correct format that the Cash 
' Register expects. 
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function ValidateOrderId(sOrderID)
   Dim nOrderLen

   nOrderLen = Len(sOrderID)
   if ((nOrderLen > 32) or _
       (nOrderLen <= 0)) then
      ' Wrong size
      ValidateOrderId = nE_ERROR
      exit function
   end if

   if (Instr(1, sOrderID, ":") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, "<") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, ">") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, "=") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, "+") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, "@") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, Chr(34)) <> 0) then  ' invalid character "
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, "%") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, "=") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, "&") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, "$") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if
   if (Instr(1, sOrderID, "*") <> 0) then
      ValidateOrderId = nE_ERROR
      exit function
   end if

   ValidateOrderId = nE_NoErr
end function



'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' genURLencodedForm()
'   Input: DictMsg(required)
'   Output: sMessage
' 
' genURLencodedForm takes a list elements 0,2,4,... are fieldnames
' elements 1,3,5,... are values.
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function genURLencodedForm (DictMsg)
   Dim sMessage, sName, sValue, nBlind
   Dim cCount, sDebugMsg, arDictKey

   Call CCDebug2(5, " ")
   Call CCDebug2(5, "== Entering genURLencodedForm ==")
   
   'Step through the list

   ' Get the keys
   arDictKey = DictMsg.keys 

  'loop the array of keys
   for cCount = 0 to DictMsg.Count -1 
      sName = arDictKey(cCount)
      sValue = DictMsg.item(sName)

      ' Do we need to blind the value? Yes for any card number or account number
      nBlind = 0

      if (Instr(sName, "cpi.") <> 0 _
           	Or Instr(sName, "CPI") <> 0 _
            Or Instr(sName, "card-") <> 0 _
            Or Instr(sName, "check-") <> 0) then
         nBlind = 1
         sDebugMsg = "Raw (Encoded) name-value pair: " & sName & " -> ****"
      else
         sDebugMsg = "Raw (Encoded) name-value pair: " & sName & " -> " & sValue
      end if
      Call CCDebug2(5, sDebugMsg)

      ' urlencode name and value
      sName = Server.URLEncode(sName)
      sValue = Server.URLEncode(sValue)
 
      if (nBlind = 1) then
         sDebugMsg = sName & " -> ****"
      else
         sDebugMsg = sName & " -> " & sValue
      end if
      Call CCDebug2(5, sDebugMsg)

      ' add the now-urlencoded name=value pair to message
      sMessage = sMessage & sName & "=" & sValue

      ' add an ampersand as a name=value pair delimiter unless
      ' we have reached the end.
      if (cCount < DictMsg.Count-1) then
        sMessage = sMessage & "&"
      end if
   Next

   ' everything is url encoded, so send the message back
   sDebugMsg = "Message is now: " & sMessage
   Call CCDebug2(5, "== Exiting genURLencodedForm ==")
   genURLencodedForm = sMessage
end function


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' URLDecodeForm()
'   Input: sEncoded(required)
'   Output: DictDecode
' 
' URL decode the message
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
sub URLDecodeForm (sEncoded, DictDecode)
   Dim cCount, sDebugMsg, nBlind
   Dim sName, sValue, arPair, arTmp, nHexPos

   ' Store name=value pair in array
   arPair = split(sEncoded, Chr(38))

   Call CCDebug2(5, " ")
   Call CCDebug2(5, "== Entering URLDecodeForm ==")

   ' Split into name and value
   For cCount = 0 to UBound(arPair)
      arTmp = Split(arPair(cCount), "=")
      sName = arTmp(0)
      sValue = arTmp(1)

      ' Do we need to blind the value? Yes for any card number or account number
      nBlind = 0

      if (Instr(sName, "cpi.") <> 0 _ 
          Or Instr(sName, "CPI") <> 0 _
          Or Instr(sName, "card-") <> 0 _
          Or Instr(sName, "check-") <> 0) then
         nBlind = 1
	 sDebugMsg = "Raw name-value pair: " & sName & " -> ****"
      else
	 sDebugMsg = "Raw name-value pair: " & sName & " -> " & sValue
      end if
      Call CCDebug2(5, sDebugMsg)

      ' replace '+' with space
      sName = replace (sName, "+", " ")
      sValue = replace (sValue, "+", " ")

      ' If there is a hex char, convert it to ascii
      nHexPos = Instr(1, sName, "%") 
      if (nHexPos <> 0) then
         sName = ConvertHex(sName)
      end if

      ' It there is a hex char, convert it to ascii
      nHexPos = Instr(1, sValue, "%") 
      if (nHexPos <> 0) then
         sValue = ConvertHex(sValue)
      end if

      if (nBlind = 1) then
         sDebugMsg = "Decoded name-value pair: " & sName & " -> ****"
      else
         sDebugMsg = "Decoded name-value pair: " & sName & " -> " & sValue
      end if
      Call CCDebug2(5, sDebugMsg)

      DictDecode.Add sName, sValue
   Next

   Call CCDebug2(5, "== Exiting URLDecodeForm ==")
end sub


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' ConvertHex()
'   Input: sHexString(required)
'   Output: sUnhexString 
' 
' Convert Hex value to ascii value
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function ConvertHex(sHexString)
   ' Create a DictHex
   Dim DictHex
   Set DictHex = CreateObject("Scripting.Dictionary")

   Dim cCount1, sTmpChar1, sTmpChar2, sTmpChar3, sTmpString
   Dim sUnhexChar, sUnhexString

   For cCount = 1 to 255
     ' key is hex 
     ' value is decimal
     DictHex.Add Hex(cCount), CStr(cCount)
   Next

   ' Now convert the hex value
   for cCount = 0 to Len(sHexString) - 1 
      sTmpChar1 = Mid(sHexString, cCount+1, 1) 

      if (sTmpChar1 = "%" and _
          sCount < Len(sHexString) - 2) then 'skip situation like ccc% or ccc%l
         sTmpChar2 = Mid(sHexString, cCount+2, 1)
         if (Not isNumeric(sTmpChar2)) then
            sTmpChar2 = UCase(sTmpChar2)
         end if
         sTmpChar3 = Mid(sHexString, cCount+3, 1)
         if (Not isNumeric(sTmpChar3)) then
            sTmpChar3 = UCase(sTmpChar3)
         end if
         sTmpString = sTmpChar2 & sTmpChar3
         cCount = cCount + 2
         sUnhexChar = Chr(DictHex(sTmpString))
         sUnhexString = sUnhexString + Chr(DictHex.Item(sTmpString))
      else
         sUnhexString = sUnhexString + sTmpChar1
      end if
   next

   ConvertHex = sUnhexString
end function


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' GetQuery()
'   Input: N/A 
'   Output: DictResult 
' 
' Retrieve HTTP "GET" or "POST" message 
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
sub GetQuery (DictResult)
   Dim sRequestString, sDebugMsg

   Call CCDebug(" ")
   Call CCDebug("== Entering GetQuery ==")
   sDebugMsg = "REQUEST_METHOD: " & Request.ServerVariables("REQUEST_METHOD")
   Call CCDebug(sDebugMsg)

   if (Request.ServerVariables("REQUEST_METHOD") = "GET") then
      sRequestString = Request.QueryString

      ' We do not log raw query info because
      ' it may contain sensitive data:  like credit card or checking
      ' account numbers.  If you need to see that kind of thing,
      ' uncomment this line and set the debug level as needed
      ' Call CCDebug2(6, sRequestString)
   elseif (Request.ServerVariables("REQUEST_METHOD") = "POST") then
      sRequestString = Request.Form

      ' We do not log raw query info because
      ' it may contain sensitive data:  like credit card or checking
      ' account numbers.  If you need to see that kind of thing,
      ' uncomment this line and set the debug level as needed
      ' Call CCDebug2(6, sRequestString)

      sDebugMsg = "Content-Length = " _
                & Request.ServerVariables("CONTENT_LENGTH")
      Call CCDebug(sDebugMsg)
   end if

   Call URLDecodeForm(sRequestString, DictResult)      

   if (nDebugFlag > 0) then
      Dim arDictKey, cCount

      ' Get the keys
      arDictKey = DictResult.keys 

      Call CCDebug(" ")

      'loop the array of keys
      for cCount = 0 to DictResult.Count -1 
          if (Instr(arDictKey(cCount), "cpi.") <> 0 _
              Or Instr(arDictKey(cCount), "CPI") <> 0 _
              Or Instr(arDictKey(cCount), "card-") <> 0 _
              Or Instr(arDictKey(cCount), "check-") <> 0) then
             sDebugMsg = arDictKey(cCount) & " = [[****]]"
          else
             sDebugMsg = arDictKey(cCount) & " = [[" _
                       & DictResult.Item(arDictKey(cCount)) & "]]"
          end if
	  Call CCDebug (sDebugMsg)
      next
   end if
   Call CCDebug("== Exiting GetQuery ==")
end sub


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' BuildSignature()
'   Input: sBlockName(required), sSecret(required), DictPayment(required) 
'   Output: sSignString 
' 
' Extract a block from input and generate the sign for it:
' - sort fields in a block by alphabetical order on the field key.
' - add secret to front and back
' - apply MD5hash.
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function BuildSignature (sBlockName, sSecret, DictPayment)
   Dim sHashString, sSignString, arDictKey
   Dim SignObj, cOut, cIn, tmp, sDebugMsg

   Dim DictResult
   Set DictResult = CreateObject("Scripting.Dictionary")

   ' Build a name value pair list from DictPayment dictionary, 
   Call BuildBlock(sBlockName, DictPayment, DictResult)

   ' Get the keys
   arDictKey = DictResult.keys 

   ' sort dictionary by key
   for cOut=0 to DictResult.Count - 2
      for cIn = cOut+1 to DictResult.Count-1 
         if (StrComp(arDictKey(cOut), arDictKey(cIn)) > 0) then
            tmp = arDictKey(cIn)
            arDictKey(cIn) = arDictKey(cOut)
            arDictKey(cOut) = tmp
         end if
      next 
   next

   ' Loop the array of keys and build string for hash
   ' When we translate a message block to a string, we don't want
   ' to lose the tags or the delimiters between names and values.
   ' To do this, we serialize a block as follows:
   '       <name1><space><value1><CR>...<CR><nameN><space><valueN> "
   for cCount = 0 to DictResult.Count -1 
      if (sHashString <> "") then
         sHashString = sHashString & Chr(13)
      end if
      sHashString = sHashString & arDictKey(cCount) & " " _
                  & DictResult.Item(arDictKey(cCount))
   next
  
   ' Call GetHash() in the MessageBlock object to generate MD5 hash 
   set SignObj = Server.CreateObject("CyberCashMCK.MessageBlock")
   sSignString = SignObj.GetHash(sHashString, sSecret)

   BuildSignature = sSignString
end function


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' BuildBlock()
'   Input: sBlockName(optional), DictPayment(required)
'   Output: DictResult 
' 
' Build a name value pair list by extracting all items with "sBlockName" prefix.
'
' If sBlockName = "*",  generate a signature based on the entire block.
' If the sBlockName is "" (null), generate a signature on fields
' with names that do *not* have a prefix. (e.g. do not contain the dot char)   
'
' Note: '.' is a reserved character
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
sub BuildBlock(sBlockName, DictPayment, DictResult)
   Dim arDictKey, sPrefixString, cCount

   if (sBlockName = "*") then
      ' The block is ALL of it

      ' Get the keys
      arDictKey = DictPayment.Keys 

      'loop the array of keys
      for cCount = 0 to DictPayment.Count -1 
         DictResult.Add arDictKey(cCount), DictPayment.Item(arDictKey(cCount))
      next
   elseif (sBlockName = NULL) then
      ' unprefixed elements
      
      ' Get the keys
      arDictKey = DictPayment.Keys 

      'loop the array of keys
      for cCount = 0 to DictPayment.Count -1
         ' Treat ".nnn" as unprefixed label 
         if (InStr(1, arDictKey(cCount), ".") <= 1) then
            DictResult.Add arDictKey(cCount), _
                           DictPayment.Item(arDictKey(cCount))
         end if
      next
    else
       sPrefixString = sBlockName & "."

       ' Get the keys
       arDictKey = DictPayment.Keys 

       'loop the array of keys
       for cCount = 0 to DictPayment.Count -1 
          if (InStr(1, arDictKey(cCount), sPrefixString) <> 0) then ' found it
             DictResult.Add arDictKey(cCount), _
                            DictPayment.Item(arDictKey(cCount))
          end if
       next
    end if
end sub


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' BuildPaymentFormArgs()
'   Input: DictMo(required),  DictMf(optional)
'   Output: sArgsString
' 
' Build #MANUAL_ARGS# for payment page templates 
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function BuildPaymentFormArgs(DictMo, DictMf)
   Dim sArgsString, sBodyString

   sBodyString = genURLencodedForm(DictMo)
   sArgsString = sArgsString & "<input type=hidden name=" & Chr(34) & "MO" _
               & Chr(34) & "value=" & Chr(34) & sBodyString & Chr(34) & ">" _
               & CRTN

   sBodyString = genURLencodedForm(DictMf)
   sArgsString = sArgsString & "<input type=hidden name=" & Chr(34) & "MF" _
               & Chr(34) & "value=" & Chr(34) & sBodyString & Chr(34) & ">" _
               & CRTN

   BuildPaymentFormArgs = sArgsString
end function


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' BuildAppletArgs()
'   Input: DictMoCCW(required), DictMf(optional)
'   Output: sArgsString
' 
' Build #APPLET_ARGS# for payment page template 
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function BuildAppletArgs(DictMoCCW, DictMf)
   Dim sArgsString, sBodyString

   sBodyString = genURLencodedForm(DictMoCCW)
   sArgsString = sArgsString & "<param name=" & Chr(34) & "MO" _
               & Chr(34) & "value=" & Chr(34) & sBodyString & Chr(34) & ">" _
               & CRTN

   sBodyString = genURLencodedForm(DictMf)
   sArgsString = sArgsString & "<param name=" & Chr(34) & "MF" _
               & Chr(34) & "value=" & Chr(34) & sBodyString & Chr(34) & ">" _
               & CRTN
   BuildAppletArgs = sArgsString
end function

'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' GeneratePaymentPage()
'   Input: sPaymentTemplate(required), DictTemp(required), DictPayment(required)
'   Output: nStatus 
' 
' Replace tokens in the payment page templates
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function GeneratePaymentPage(sPaymentTemplate, DictTemp, DictPayment)
   Dim nStatus

   Dim DictMo
   Set DictMo = CreateObject("Scripting.Dictionary")
   Dim DictMf
   Set DictMf = CreateObject("Scripting.Dictionary")

   ' Build blocks
   Call BuildBlock("mo", DictPayment, DictMo)
   Call BuildBlock("mf", DictPayment, DictMf)

   ' Substitute tokens in the Payment template
   DictTemp.Add "#MANUAL_ARGS#", BuildPaymentFormArgs _
                                 (DictMo, DictMf)

   nStatus = FormatTemplate(sPaymentTemplate, DictTemp)

   GeneratePaymentPage = nStatus 
end function


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' Browser()
'   Input: N/A
'   Output: DictResult 
' 
' Find the consumer browser type
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
sub Browser (DictResult)
   Dim sUserAgent, nPlatformPos, nWindowsPos, nWinPos, nMacPos, nLoc, nBrowser
   Dim nSlash, nSpace, nLbound, nBytes
 
   sUserAgent = Request.ServerVariables("HTTP_USER_AGENT")
   ' ignore case
   sUserAgent = LCase(sUserAgent)
   sUserAgent = Trim(sUserAgent)

   ' Set up platform
   nPlatformPos = Instr(1, sUserAgent, "(")
   if (nPlatformPos > 0) then
      nWindowsPos = Instr(1, sUserAgent, "windows") 
      nWinPos = Instr(1, sUserAgent, "win")
      nMacPos = Instr(1, sUserAgent, "mac")
   end if

   if (nWinPos <> 0) then
      if (nWindowsPos <> 0) then
         nLoc = nWindowsPos + Len("windows")
      else
         nLoc = nWinPos + Len("win")
      end if

      if (Instr(nLoc, sUserAgent, "32") <> 0 or _
          Instr(nLoc, sUserAgent, "95") <> 0 or _
          Instr(nLoc, sUserAgent, "nt") <> 0) then 
         DictResult.Add "platform", "win32"
      else
         DictResult.Add "platform", "win"
      end if
   elseif (nMacPos <> 0) then
      DictResult.Add "platform", "mac"
   else
      DictResult.Add "platform", "other"       
   end if

   ' Set up browser type and version
   nBrowser = Instr(1, sUserAgent, "msie")
   if (nBrowser > 0) then
      nSemiColon = Instr(nBrowser, sUserAgent, ";")
      nLbound = nBrowser+Len("msie ")
      nBytes = nSemiColon - nLbound 
      DictResult.Add "version", Mid(sUserAgent, nLbound, nBytes) 
      DictResult.Add "type", "msie"
   else
      ' Only MSIE will lie to you and tell you it is Mozilla!
      ' every one else does: browerKind/version
      nSlash = Instr(1, sUserAgent, "/")
      nSpace = Instr(1, sUserAgent, " ")
      DictResult.Add "version", Mid(sUserAgent, nSlash+1 , nSpace - nSlash - 1)
      DictResult.Add "type", Left(sUserAgent, nSlash-1)
   end if
end sub


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' FormatTemplate()
'   Input: sTemplateLoc(required), DictToken(required) 
'   Output: nE_NoErr or nE_ERROR 
' 
'  Do NOT alter this function.  It is here only because it must call
'  the customized function
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function FormatTemplate(sTemplateLoc, DictToken)
   On Error Resume Next

   ' Open the template file
   Dim fs, a, arDictKey, sTemplateString, cCount, sDebugMsg
   Set fs = CreateObject("Scripting.FileSystemObject")
   Set a = fs.OpenTextFile(sTemplateLoc, 1, FALSE)

   if (Err.Number = 0) then
      sTemplateString = a.ReadAll
      a.Close()
   end if

   if (Err.Number <> 0) then
      sDebugMsg = MCKGetErrorMessage(nE_No_Template) & " - " & sTemplateLoc 
      sTemplateFailure = Replace(sTemplateFailure, "#MESSAGE#", sDebugMsg)
      sHTMLPage = sTemplateFailure
      Call CCLogError(sDebugMsg)
      Err.Clear()
      FormatTemplate = nE_ERROR
      exit function
   end if

   ' Get the keys
   arDictKey = DictToken.keys


   'loop the array of keys, and substitute valid tokens
   for cCount = 0 to DictToken.Count - 1
      if (InStr(1, sTemplateString, arDictKey(cCount)) <> 0) then
         sTemplateString = Replace(sTemplateString, arDictKey(cCount), _ 
                                   DictToken.Item(arDictKey(cCount))) 
      end if 
   next

   ' Done with building the body of Payment html page
   sHTMLPage = sTemplateString

   FormatTemplate = nE_NoErr
end function


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' StringTemplate()
'   Input: sTemplateLoc(required), DictToken(required) 
'   Output: sTemplateString, nE_NoErr or nE_ERROR 
' 
' read a template and build a string from it, replacing
' tokens as you go...
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function StringTemplate (sTemplateLoc, DictToken, sTemplateString)
   On Error Resume Next

   ' Open the template file
   Dim fs, a, arDictKey, cCount, sDebugMsg
   Set fs = CreateObject("Scripting.FileSystemObject")
   Set a = fs.OpenTextFile(sTemplateLoc, 1, FALSE)

   if (Err.Number = 0) then
      sTemplateString = a.ReadAll
      a.Close()
   end if

   if (Err.Number <> 0) then
      sDebugMsg = MCKGetErrorMessage(nE_No_Template) & " - " & sTemplateLoc 
      sTemplateFailure = Replace(sTemplateFailure, "#MESSAGE#", sDebugMsg)
      sHTMLPage = sTemplateFailure
      Call CCLogError(sDebugMsg)
      Err.Clear()
      StringTemplate = nE_ERROR
      exit function
   end if

   ' Get the keys
   arDictKey = DictToken.keys

   'loop the array of keys, and substitute valid tokens
   for cCount = 0 to DictToken.Count - 1
      if (InStr(1, sTemplateString, arDictKey(cCount)) <> 0) then
         sTemplateString = Replace(sTemplateString, arDictKey(cCount), DictToken.Item(arDictKey(cCount))) 
      end if 
   next
  
   StringTemplate = nE_NoErr
end function

'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' InitConfig()
'   Input: N/A
'   Output: nE_NoErr or nE_ERROR
'
' Read configuration file
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
Function InitConfig()
   On Error Resume Next
   Dim fs, a
   Dim sBuf, nStringPos, sName, sValue, sDebugMsg

   ' Open the merchant configuration file for reading
   Set fs = CreateObject("Scripting.FileSystemObject")
   Set a = fs.OpenTextFile(sConfigLoc, 1, FALSE)

   ' Read configuration file line by line, disregard comment and blank line,
   ' save name and value to the global dictionary object.
   if (Err.Number = 0) then
      gDictConfig.RemoveAll()
      Do While a.AtEndOfStream <> True
         sBuf = a.ReadLine
         sBuf = Trim(sBuf)

         if (sBuf <> "") then
            if (Left(sBuf, 1) <> "#") then
               ' get name and value of the parameter and trim leading and
               ' trailing spaces
               nStringPos = Instr(1, sBuf, "=")
               sName = Left(sBuf, CInt(nStringPos-1))
               sValue = Right(sBuf, Len(sBuf) - nStringPos)
               sName = Trim(sName)
               sValue = Trim(sValue)

               gDictConfig.Add sName, sValue
            end if
         end if
      Loop

      a.Close()
   end if

   if (Err.Number <> 0 and Err.Number <> 457) then
      sDebugMsg = MCKGetErrorMessage(nE_No_Config) &  " or " _
                & MCKGetErrorMessage(nE_Null_Config) & " - " & sConfigLoc
      sTemplateFailure = Replace(sTemplateFailure, "#MESSAGE#", sDebugMsg)
      sHTMLPage = sTemplateFailure
      Err.Clear()
      InitConfig = nE_ERROR
      exit function
   end if

   InitConfig = nE_NoErr
End Function


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' MCKGetErrorMessage()
'   Input: nErrorCode 
'   Output: sErrorMessage 
'
' Mapp error message 
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function MCKGetErrorMessage(nErrorCode)
    if (gDictError.Exists(nErrorCode)) then
       MCKGetErrorMessage = gDictError.Item(nErrorCode) 
       exit function
    else
       MCKGetErrorMessage = "Unknown Error" 
       exit function
    end if  
end function


'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' CCSetTimeout
'   Input: nNewTimeout
'   Output: nOldTimeout, previous timeout value, -1 if error
'            (allows you to backup ...)
'
'  This lets you reset the timeout in the configuration
'   Timout is the number of seconds the Direct Connection socket will wait
'   for a response from the CyberCash Cash Register before giving it up
'   as a lost cause.
'
'  Your configuration file may specify a default timeout.
'
'  If your configuration does not specify a timeout,
'  the default value will be set to 90 seconds.
'
'  The only time this is inadequate is when waiting for the status of a
'  big batch. The processor may take more than 90 seconds to provide the
'  status. If the batch size is more than 30, it is saver to increase
'  this time out.
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function CCSetTimeout(nNewTimeout)
   Dim nOldTimeout

   if (nNewTimeout <= 0) then
      CCSetTimeout = nE_Bad_Timeout
      exit function
   end if

   gDictConfig.remove("TIMEOUT")
   gDictConfig.Add "TIMEOUT", nNewTimeout

   CCSetTimeout = nOldTimeout
end function

'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' GetCurrentTimeString()
'   Output: sTime 
'
' Format the current time to YYMMDDHHMMSS 
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function GetCurrentTimeString()
   Dim sYear, sMonth, sDay, sHour, sMinute, sSecond, sTime

   sTime = Year(Now())

   if (Month(Now()) < 10) then
      sTime = sTime & 0 & Month(Now()) 
   else
      sTime = sTime & Month(Now())      
   end if 

   if (Day(Now()) < 10) then
      sTime = sTime & 0 & Day(Now())
   else
      sTime = sTime & Day(Now())
   end if 

   if (Hour(Now()) < 10) then
      sTime = sTime & 0 & Hour(Now())
   else
      sTime = sTime & Hour(Now())
   end if 

   if (Minute(Now()) < 10) then
      sTime = sTime & 0 & Minute(Now())
   else
      sTime = sTime & Minute(Now())
   end if 

   if (Second(Now()) < 10) then
      sTime = sTime & 0 & Second(Now())
   else
      sTime = sTime & Second(Now())
   end if 

   GetCurrentTimeString = sTime
end function
%>
