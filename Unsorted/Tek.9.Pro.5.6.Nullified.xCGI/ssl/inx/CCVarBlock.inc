<%
' Here is the place you put global variables.

CRTN = Chr(10) & Chr(13)

' Create a dictionary object for configuration parameters
Dim gDictConfig
Set gDictConfig = CreateObject("Scripting.Dictionary")

' the body of html page that will be displayed on browser
Dim sHTMLPage


''''''''''''''''''
' Error messages ' 
''''''''''''''''''
Dim nE_NoErr, nCC_ERRBASE, nE_ERROR, nE_No_Post, nE_No_Cont_Len, nE_BadCont_Len
Dim nE_Null_Msg, nE_No_Method, nE_No_Get, nE_BadMethod, nE_Browser, nE_No_Amount
Dim nE_Bad_Amount, nE_Big_Amount, nE_Neg_Amount, nE_Bad_Currency, nE_Reject_Currency
Dim nE_Bad_DefCurrency, nE_No_Orderid, nE_Bad_Orderid, nE_No_Payload, nE_Bad_Payload
Dim nE_No_SSL, nE_Bad_Url, nE_Not_HTTP, nE_Failed_HTTP, nE_No_CashReg, nE_No_Message
Dim nE_No_Config, nE_Null_Config, nE_Bad_Config, nE_Bad_Timeout, nE_GoAway, nE_TimedOut
Dim nE_No_POP, nE_POP_Signature, nE_MF_Signature, nE_Cash_Register, nE_MO_Signature
Dim nE_Payment_Failed, nE_CPI_Signature, nE_No_Memory, nE_Stat_Failed, nE_No_Template
Dim nE_Read_Template, nE_No_OrderLog, nE_Order_NotFound, nE_Order_NotSaved, nE_Null_Result
Dim nE_Bad_Params, nE_No_NotifLog, nE_No_Pay_Page, nE_Fail_Notif, nE_No_File, nE_No_Receipt

' Load error constants
nE_NoErr = 0
nCC_ERRBASE = -300

'Generic unknown error
nE_ERROR = nCC_ERRBASE

' Errors in the request for CGI service
nE_No_Post      = nCC_ERRBASE - 1
nE_No_Cont_Len  = nCC_ERRBASE - 2
nE_BadCont_Len = nCC_ERRBASE - 3
nE_Null_Msg    = nCC_ERRBASE - 4
nE_No_Method   = nCC_ERRBASE - 5
nE_No_Get       = nCC_ERRBASE - 6
nE_BadMethod   = nCC_ERRBASE - 7

'narrow use: indicates incompatible browser
nE_Browser    = nCC_ERRBASE - 8

'Errors in the content of the request
nE_No_Amount   = nCC_ERRBASE - 10
nE_Bad_Amount  = nCC_ERRBASE - 11
nE_Big_Amount  = nCC_ERRBASE - 12
nE_Neg_Amount  = nCC_ERRBASE - 13
nE_Bad_Currency = nCC_ERRBASE - 14
nE_Reject_Currency = nCC_ERRBASE - 15
nE_Bad_DefCurrency = nCC_ERRBASE - 16

nE_No_Orderid  = nCC_ERRBASE - 17
nE_Bad_Orderid  = nCC_ERRBASE - 18

nE_No_Payload  = nCC_ERRBASE - 19
nE_Bad_Payload  = nCC_ERRBASE - 20 

'Protocol errors
nE_No_SSL      = nCC_ERRBASE - 30
nE_Bad_Url     = nCC_ERRBASE - 31
nE_Not_HTTP    = nCC_ERRBASE - 32
nE_Failed_HTTP = nCC_ERRBASE - 33
nE_No_CashReg  = nCC_ERRBASE - 34
nE_No_Message  = nCC_ERRBASE - 35

'Configuration problems
nE_No_Config      = nCC_ERRBASE - 50
nE_Null_Config    = nCC_ERRBASE - 51
nE_Bad_Config     = nCC_ERRBASE - 52
nE_Bad_Timeout    = nCC_ERRBASE - 53

'Cash Register response problems
nE_GoAway      = nCC_ERRBASE - 60
nE_TimedOut    = nCC_ERRBASE - 61
nE_No_POP       = nCC_ERRBASE - 62
nE_POP_Signature  = nCC_ERRBASE - 63
nE_MF_Signature  = nCC_ERRBASE - 64
nE_Cash_Register = nCC_ERRBASE - 65
nE_MO_Signature = nCC_ERRBASE - 66
nE_Payment_Failed = nCC_ERRBASE - 67
nE_CPI_Signature = nCC_ERRBASE - 68

'system problems -- probably not relevant to PERL
' But for the sake of keeping in synch with other code
nE_No_Memory            = nCC_ERRBASE - 70
nE_Stat_Failed  = nCC_ERRBASE - 71

' Miscellaneous problems
nE_No_Template    = nCC_ERRBASE - 90    ' Can't open the template
nE_Read_Template  = nCC_ERRBASE - 91  ' Can't read the template
nE_No_OrderLog    = nCC_ERRBASE - 92  ' Can't open the order log
nE_Order_NotFound = nCC_ERRBASE - 93  ' Can't find the order
nE_Order_NotSaved = nCC_ERRBASE - 94  ' Can't record the order
nE_Null_Result    = nCC_ERRBASE - 95  ' No result record passed in
nE_Bad_Params     = nCC_ERRBASE - 96  ' Improper argument to function

nE_No_Pay_Page    = nCC_ERRBASE - 98  ' Can't gen payment page
nE_Fail_Notif     = nCC_ERRBASE - 99  ' Notification failed
nE_No_File        = nCC_ERRBASE - 100  ' Can't open file
nE_No_Receipt     = nCC_ERRBASE - 101  ' Can't open receipt template

' Create a dictionary object for error messages
Dim gDictError
Set gDictError = CreateObject("Scripting.Dictionary")

gDictError.Add nE_NoErr       , "OK"
gDictError.Add nE_ERROR       , "Unspecified Error"
gDictError.Add nE_No_Post      , "Expecting HTTP POST message"
gDictError.Add nE_No_Cont_Len  , "No Content-length"
gDictError.Add nE_BadCont_Len , "Bad Content-length"
gDictError.Add nE_Null_Msg    , "POST message body empty"
gDictError.Add nE_No_Method   , "No HTTP request method specified"
gDictError.Add nE_No_Get       , "Expecting HTTP GET request"
gDictError.Add nE_BadMethod   , "Unexpected HTTP request method"

gDictError.Add nE_Browser    , "Unsupported browser"

gDictError.Add nE_No_Amount   , "No price/amount specified"
gDictError.Add nE_Bad_Amount  , "Invalid price/amount specified"
gDictError.Add nE_Big_Amount  , "Price/amount specified is too large"
gDictError.Add nE_Neg_Amount  , "Price/amount specified is negative or zero"
gDictError.Add nE_Bad_Currency , "Currency specified is invalid"
gDictError.Add nE_Reject_Currency , "Currency specified is not accepted"
gDictError.Add nE_Bad_DefCurrency , "Default currency specified is invalid"

gDictError.Add nE_No_Orderid  , "No Order Id specified"
gDictError.Add nE_Bad_Orderid  , "Invalid Order Id specified"

gDictError.Add nE_No_SSL      , "SSL is unsupported for direct connect"
gDictError.Add nE_Bad_Url     , "URL is malformed"
gDictError.Add nE_Not_HTTP    , "Invalid HTTP response"
gDictError.Add nE_Failed_HTTP , "HTTP request failed"
gDictError.Add nE_No_CashReg  , "missing Cash Register Host or Port"
gDictError.Add nE_No_Message  , "missing message for CCSocketSend"

gDictError.Add nE_No_Config      , "Cannot open MCK Configuration file"
gDictError.Add nE_Null_Config    , "MCK Configuration is not initialized"
gDictError.Add nE_Bad_Config     , "MCK Configuration file has syntax errors"
gDictError.Add nE_Bad_Timeout    , "Invalid timeout value (<= 0)"
gDictError.Add nE_GoAway      , "Server rejected request"
gDictError.Add nE_TimedOut    , "Server request timed out"
gDictError.Add nE_No_POP       , "No POP record returned for payment request"
gDictError.Add nE_POP_Signature  , "Proof-of-purchase (POP) signature error"
gDictError.Add nE_MF_Signature  , "Merchant data (MF) signature error"
gDictError.Add nE_Cash_Register , "Problem at the Cash Register"
gDictError.Add nE_MO_Signature  , "Merchant order (MO) signature error"
gDictError.Add nE_Payment_Failed , "Payment Failed"
gDictError.Add nE_CPI_Signature  , "Customer payment information (CPI) signature error"

gDictError.Add nE_No_Memory    , "Out of memory"
gDictError.Add nE_Stat_Failed  , "stat() on file failed"

gDictError.Add nE_No_Template    , "Cannot open template"
gDictError.Add nE_Read_Template  , "Failure while reading template"

gDictError.Add nE_No_OrderLog    , "Cannot open the Order Log"
gDictError.Add nE_Order_NotFound , "Order not found"
gDictError.Add nE_Order_NotSaved , "Order not saved"
gDictError.Add nE_Null_Result    , "Unexpected Null result"
gDictError.Add nE_Bad_Params     , "Invalid parameters for function call"


gDictError.Add nE_No_Pay_Page  , "Failed to generate a Payment Page"
gDictError.Add nE_Fail_Notif   , "Unable to Log this transaction"
gDictError.Add nE_No_File      , "Unable to open file"
gDictError.Add nE_No_Receipt   , "Payment successful failed to generate receipt"
%>
