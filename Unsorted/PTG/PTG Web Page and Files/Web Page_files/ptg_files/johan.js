//////////////////////////////////////////////////////////////////////
//                                                                  //
//                     ULTRA LIST BUILDER (tm)                      //
//                  List Building & Mining System                   //
//                                                                  //
//                     Anthony Stillwell, 2002                      //
//                       All rights reserved                        //
//                                                                  //
//           For support and latest product information             //
//              visit http://www.ultralistbuilder.com               //
//                                                                  //
//     Selling or distributing this software in whole or            //
//     in part or of any modification of this software without      //
//     written permission is expressly forbidden. Permission        //
//     to modify the script for personal use on the domain for      //
//     which this script is licensed is granted to the purchaser.   //
//     In all cases this full header and copyright information      //
//     must remain fully intact. Any and All violators will be      //
//     PROSECUTED to the fullest extent of the law.                 //
//                                                                  //
//////////////////////////////////////////////////////////////////////
//                                                                  //
//                          Reprint Rights                          //
//                                                                  //
//             Reprint Rights For "ULB" are avaiable here           //
//                  http://www.ultralistbuilder.com                 //
//                                                                  //
//////////////////////////////////////////////////////////////////////

// Set usecookie to 1 so that the popup only loads once per session.
// Set it to 0 if you want it to popup every single time the page loads.

var usecookie = 1;

var msg = ///////////////////////////////// Modify Only the Next Line /////////////////////////////////

"Why should you be left out, sitting on the sidelines watching, \n while so many others make incredible, huge amounts of money on the Internet\? \n \n  Now you don\'t have to.... \n   \n  *** Special Charter Invitation ***  \n  For a limited time only, I\'m giving away FREE subscriptions \n  to my Super Fast Profit E-Cash Bulletin ( \$197 Value)  \n  This Bulletin is solely and unabashedly and unapologetically  \n   devoted to one thing and one thing only\:  \n ******************************************************************** \n  Making as much money as is humanly possible, as quickly \n  as possible, as easily as possible via super-savvy Internet \n marketing in your business, in any business. \n******************************************************************** \n\n ** Click OK to discover the source for tested and proven \"fail-safe, profit-certain\" marketing secrets. **\n  Ready to move up to the Big League and be a real player!  \n ";

              ///////////////////////////////// Stop Editing Here /////////////////////////////////

// Enter the subscribe address for your newsletter or autoresponder
var email = "ecash@goldbar.net";

// Enter the subject for your email
var subject = "New subscriber!";

function getCookieVal (offset)
{
    var endstr = document.cookie.indexOf (";", offset);
    if (endstr == -1)
        endstr = document.cookie.length;
    return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie (name)
{
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen)
    {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg)
            return getCookieVal (j);
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
    }
    return null;
}

function CallOnLoad()
{
    if (GetCookie("johan") == "yes" && usecookie)
        return;
    if (confirm(msg))
    {
        loc = "mailto:" + email;
        if (subject)
            loc = loc + "?subject=" + escape(subject);
        window.location = loc;
    }
    document.cookie = "johan=yes";
}
// ULTRA LIST BUILDER