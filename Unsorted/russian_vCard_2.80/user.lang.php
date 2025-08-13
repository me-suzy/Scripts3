<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.8                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
$CharSet ='windows-1251'; // Language WIN Char Set code for users pages
$htmldir ='ltr'; // ltr = Ñëåâà - íàïðàâî , rtl = Ñïðàâà - íàëåâî (ýòî äëÿ àðàáîâ)
				/* text direction attribute is used to declare the direction 
		    that the text should run, either left to right (default) or right to left */

// Ñîçäàíèå, ïðåäïðîñìîòð è âèä ñòðàíèö
$MsgImage ='Èçîáðàæåíèå';
$MsgYourTitle ='Çàãîëîâîê îòêðûòêè';
$MsgMessage ='Ñîîáùåíèå';
$MsgMessageHere ='- Âàøå ñîîáùåíèå ïîÿâèòñÿ çäåñü -'; // Sample message that will apper if postcard use a template
$MsgFont ='Øðèôò';
$MsgNoFontFace ='Íå óêàçûâàòü';
$MsgFontSizeSmall ='Ìàëåíüêèé';
$MsgFontSizeMedium ='Ñðåäíèé';
$MsgFontSizeLarge ='Áîëüøîé';
$MsgFontSizeXLarge ='Î÷åíü áîëüøîé';
$MsgFontColorBlack ='×åðíûé';
$MsgFontColorWhite ='Áåëûé';
$MsgSignature ='Ïîäïèñü';
$MsgRecpName ='Èìÿ ïîëó÷àòåëÿ';
$MsgRecpEmail ='Å-mail ïîëó÷àòåëÿ';
$MsgAddRecp ='Äîáàâèòü ïîëó÷àòåëåé';
$MsgTotalRecp ='Âñåãî ïîëó÷àòåëåé';
$MsgPlay ='ÏÐÎÑËÓØÀÒÜ';
$MsgYourName ='Âàøå èìÿ';
$MsgYourEmail ='Âàø email';
$MsgChoosePoem ='Âûáîð ñòèõîòâîðåíèÿ';
$MsgView ='Ïðîñìîòð';
$MsgChooseLayout ='Âûáîð øàáëîíà îòêðûòêè';
$MsgChooseDate ='Óêàçàòü äàòó äîñòàâêè?';
$MsgChooseDateImmediate ='Íåìåäëåííî';
$MsgDateFormat ='Óêàæèòå äàòó â ôîðìàòå DD/MM/YYYY, êîãäà îòêðûòêà äîëæíà áûòü äîñòàâëåíà.';
$MsgChooseStamp ='Âûáîð ìàðêè';
$MsgPostColor ='Öâåò ôîíà îòêðûòêè';
$MsgPageBackground ='Ðèñóíîê';
$MsgNone ='Íèêàêîé';
$MsgMusic ='Ìóçûêà';
$MsgPreviewButton ='Ïîñìîòðåòü ïåðåä îòïðàâêîé';
$MsgNotify ='Óâåäîìèòü ïî email, êîãäà ïîëó÷àòåëü ïðî÷èòàåò ýòó îòêðûòêó.';
$MsgYes  ='Äà';
$MsgNo  ='Íåò';
$MsgNoFlash ='Âàì íåîáõîäèì Flash ïëååð äëÿ ïðîñìîòðà Flash âåðñèé îòêðûòîê.';
$MsgClickHereToGet ='Íàæìèòå çäåñü, ÷òîáû ïîëó÷èòü åãî!';
$MsgHelp ='Ñïðàâêà!';
$MsgCloseWindow ='Çàêðûòü îêíî';
$MsgPrintable ='Âåðñèÿ äëÿ ïå÷àòè';

$MsgCreateCard ='Ñîçäàòü îòêðûòêó';


$MsgDateFormatDMY ='Äåíü - Ìåñÿö - Ãîä';
$MsgDateFormatMDY ='Ìåñÿö - Äåíü - Ãîä';

// Error Messages
$MsgActiveJS ='Ïîæàëóéñòà àêòèâèðóéòå javascript!';
$MsgErrorMessage ='Âû äîëæíû íàïèñàòü ñîîáùåíèå äëÿ Âàøåé îòêðûòêè.';
$MsgErrorRecpName ='Âû äîëæíû óêàçàòü èìÿ ïîëó÷àòåëÿ.';
$MsgErrorRecpEmail ='âû äîëæíû óêàçàòü e-mail àäðåñ ïîëó÷àòåëÿ.';
$MsgErrorRecpEmail2 ='<B>Å-mail àäðåñ</B> ïîëó÷àòåëÿ îøèáî÷íûé.';
$MsgErrorSenderName ='Âû äîëæíû óêàçàòü Âàøå èìÿ.';
$MsgErrorSenderEmail ='âû äîëæíû óêàçàòü Âàø e-mail àäðåñ.';
$MsgErrorSenderEmail2 ='Âàø <B>e-mail àäðåñ</B> îøèáî÷íûé.';
$MsgErrorNotFoundTxt ='Èçâèíèòå, íåò îòêðûòêè, êîòîðàÿ ñîîòâåòñòâîâàëà áû óêàçàííîìó íîìåðó. Âû ìîãëè îøèáèòüñÿ, óêàçàâ ID îòêðûòêè èëè Âàøà îòêðûòêà ìîæåò áûòü ñëèøêîì äàâíî îòïðàâëåíà è óæå óäàëåíà èç ñèñòåìû.';
$MsgErrorNoCardsEvents ='Èçâèíèòå, íåò îòêðûòîê äëÿ ýòîãî ñîáûòèÿ â áàçå äàííûõ.';
$MsgErrorInvalidePageNumber ='Âû óêàçàëè íåâåðíûé íîìåð ñòðàíèöû.';
$MsgErrorNoCardsinDB ='Îãîð÷åíû, íåò îòêðûòêè â áàçå äàííûõ.';

$MsgInvalidePageNumber ='Âû óêàçàëè íåâåðíûé íîìåð ñòðàíèöû';

$MsgBackEditButton ='Âåðíóòüñÿ ê ðåäàêòèðîâàíèþ';
$MsgSendButton ='Îòïðàâèòü îòêðûòêó!';

$MsgSendTo ='Îòïðàâèòü îòêðûòêó äëÿ ';
$MsgClickHere ='íàæìèòå çäåñü';
$MsgAvoidDuplicat ='íàæìèòå îäèí ðàç, ÷òîáû èçáåæàòü äóáëèðîâàíèÿ!';

// Info Windows
$MsgWinvCode ='vCode';
$MsgWinTextCode ='Text Code';
$MsgSomeText ='some text';
$MsgWinEmoticons ='Ñìàéëèêè';
$MsgWinEmoticonsNote ='Âñå çíàêè uppercased(?) (O è P)!';
$MsgWinEmoticonsNoteFotter ='<B>If</B> you do NOT want the graphic to appear, but still want to use the original emoticons you will have to exclude the nose.';
$MsgWinBackground ='Ôîíîâîå èçîáðàæåíèå';
$MsgWinStamp ='Èçîáðàæåíèå ìàðêè';
$MsgWinColors ='Öâåòà';
$MsgWinMusic ='Ìóçûêà';
$MsgWinMusicNote ='Âûáîð äåéñòâèÿ.';
$MsgWinMusicNote2 ='Íåîáõîäèìî íåñêîëüêî ñåêóíä, ÷òîáû çàãðóçèòü çâóê íà Âàø êîìïüþòåð';
$MsgWinPoem ='Ñòèõîòâîðåíèå';
$MsgWinPoemNote ='âûáîð ñòèõîòâîðåíèÿ.';
$MsgWinNotify ='Õîòèòå Âû ïîëó÷èòü óâåäîìëåíèå ïî email, ïîñëå òîãî, êàê îòêðûòêà áóäåò ïðîñìîòðåíà ïîëó÷àòåëåì?';
$MsgWinNotifyTitle ='Óâåäîìëåíèå ïî e-mail';
$MsgWinFonts ='Øðèôòû';
$MsgWinFontsNote ='Åñëè Âû õîòèòå èñïîëüçîâàòü ýòî äåéñòâèå, <FONT COLOR=red>ïîæàëóéñòà ïîìíèòå</FONT>, ÷òî íå ó âñåõ ëþäåé ìîãóò áûòü óñòàíîâëåíû ýòè øðèôòû íà êîìïüþòåðå. Åñëè ýòèõ øðèôòîâ íåò, òî ïîëó÷àòåëè óâèäÿò îòêðûòêó ñî øðèôòîì, êîòîðûé óñòàíîâëåí ó íèõ â ñèñòåìå, îáû÷íî ýòî Times, Arial èëè Helvetica.'; 
$MsgWinName ='Èìÿ';
$MsgWinSample ='Ïðèìåð';
$MsgWinSampleString ='abcdefghijklmnopqrstuvwxyz';

// Message in confirmation page
$MsgSendAnotherCard ='Îòïðàâèòü äðóãóþ îòêðûòêó';

// Top X gallery
$MsgTop ='Ëó÷øèå';

// Category Browser Pages
$MsgNext ='Ñëåäóþùàÿ';
$MsgPrevious ='Ïðåäûäóùàÿ';
$MsgBackCatMain ='Âåðíóòüñÿ íà ãëàâíóþ ñòðàíèöó êàòåãîðèé';
$MsgPageOf ='èç'; // page xx OF yy
$MsgPage ='Ñòðàíèöà'; // PAGE xx of yy

$MsgCategories ='Êàòåãîðèè';
$MsgCategory ='Êàòåãîðèÿ';
$MsgPostcards ='Îòêðûòêè';
$MsgCards ='Îòêðûòêè';

// Back Link Messages
$MsgBack ='Íàçàä';
$MsgBackButton ='Íà ïðåäûäóùóþ ñòðàíèöó';
$MsgBacktoSection ='Ê ïðåäûäóùåé ñåêöèè';

// Links
$MsgHome ='Ãëàâíàÿ ñòðàíèöà';
$MsgGoTo ='Ïåðåéòè';

// File Upload
$MsgUploadYourOwnFileTitle ='Èñïîëüçîâàòü âàøå èçîáðàæåíèå';
$MsgUploadYourOwnFileInfo ='Ñîçäàòü îòêðûòêó, èñïîëüçóÿ Âàøå èçîáðàæåíèå';
$MsgErrorFileExtension ='Íåâåðíîå ðàñøèðåíèå ôàéëà. Ðàñøèðåíèå ìîæåò áûòü .gif, .jpeg, .jpg èëè .swf!';
$MsgFileBiggerThan ='Ðàçìåð ôàéëà áîëüøå, ÷åì'; // File size is bigger than XX Kilobytes
$MsgFileMaxSizeAllowed ='Ìàêñèìàëüíûé ðàçìåð ôàéëà'; // The max size of file is XX Kilobytes
$MsgFileAllowed ='Âû ìîæåòå çàãðóçèòü Âàøå èçîáðàæåíèå (.gif, .jpg) èëè flash-àíèìàöèþ (.swf) äëÿ ñîçäàíèÿ îòêðûòêè. Âûáåðèòå ôàéë è íàæìèòå íà êíîïêó.';
$MsgFileUploadNotAllowed ='Ñèñòåìà çàãðóçêè ôàéëîâ âûêëþ÷åíà íà ýòîì ñåðâåðå! Èçâèíèòå';
$MsgFileSend ='Îòïðàâèòü ôàéë!';
$MsgFileSelect ='Âûáðàòü Âàø ôàéë';
$MsgFileUseFile ='Ñîçäàòü îòêðûòêó';

$MsgCalendarMonth ='Ìåñÿö';
$MsgCalendarDayBegin ='Ïåðâûé äåíü';
$MsgCalendarDayEnd ='Ïîñëåäíèé äåíü';
$MsgCalendarEventName ='Íàçâàíèå ñîáûòèÿ';
$MsgCalendar ='Êàëåíäàðü';
$MsgMonthNames = array('ßíâàðü', 'Ôåâðàëü', 'Ìàðò', 'Àïðåëü', 'Ìàé', 'Èþíü', 'Èþëü', 'Àâãóñò', 'Ñåíòÿáðü', 'Îêòÿáðü', 'Íîÿáðü', 'Äåêàáðü');

/* ######################## added version 1.2 ######################## */
$MsgOptionsHelp ='Íàñòðîéêè è ñïðàâêà!!';
$MsgTopCardInCat ='Ëó÷øèå îòêðûòêè â êàòåãîðèè';
$MsgCopyWant ='Âû õîòèòå ïîëó÷èòü êîïèþ îòêðûòêè?';
$MsgHome ='Ãëàâíàÿ';

$MsgSearch_noresults ='Âàø ïîèñê íå äàë ðåçóëüòàòîâ. Ïîïðîáóéòå èñïîëüçîâàòü äðóãèå êëþ÷åâûå ñëîâà.';
$MsgSearch_returned ='Ïî âàøåìó çàïðîñó íàéäåíî'; // Your search returned XX results
$MsgSearch_results ='ðåçóëüòàò(îâ)'; // Your search returned XX results
$MsgSearch_relevance ='Ëèñòèíã â ïîðÿäêå ðåëåâàíòíîñòè';
$MsgSearch_button ='Ïîèñê îòêðûòêè';

// address book
$MsgABook_tit_generaltitle ='Ìîÿ àäðåñíàÿ êíèãà';
$MsgABook_tit_login ='Ìîÿ àäðåñíàÿ êíèãà: Àâòîðèçàöèÿ';
$MsgABook_tit_editprofile ='Ðåäàêòèðîâàòü ëè÷íóþ àíêåòó';
$MsgABook_tit_forgotpword ='Çàáûëè ïàðîëü?';
$MsgABook_tit_createabook ='Ñîçäàòü àäðåñíóþ êíèãó';
$MsgABook_tit_addrecord ='Äîáàâèòü E-mail àäðåñ';
$MsgABook_tit_editrecord ='ðåäàêòèðîâàòü E-mail àäðåñ';
$MsgABook_tit_deleterecord ='Óäàëèòü E-mail àäðåñ?';
$MsgABook_tit_updaterecord ='Îáíîâèòü E-mail àäðåñ';
$MsgABook_tit_help ='Ìîÿ àäðåñíàÿ êíèãà: Ñïðàâêà';
$MsgABook_tit_error ='Îøèáêà!';
$MsgABook_tit_cleancookie ='Cookies óäàëåíû!';
$MsgABook_email ='E-mail àäðåñ';
$MsgABook_realname ='Ðåàëüíîå èìÿ';
$MsgABook_name ='Èìÿ';
$MsgABook_password ='Ïàðîëü';
$MsgABook_username ='Èìÿ ïîëüçîâàòåëÿ';

$MsgABook_error ='Îäíà èëè áîëåå îáëàñòåé ôîðìû ïóñòûå.<BR><BR> Ïîæàëóéñòà âåðíèòåñü íàçàä è çàïîëíèòå íåîáõîäèìûå îáëàñòè ôîðìû ïåðåä ïðîäîëæåíèåì.';
$MsgABook_error_username ='Ýòî èìÿ ïîëüçîâàòåëÿ óæå èñïîëüçóåòñÿ.<br><br>Ïîæàëóéñòà âåðíèòåñü íàçàä è óêàæèòå äðóãîå èìÿ ïîëüçîâàòåëÿ.';
$MsgABook_error_invalidlogin ='Îøèáî÷íîå èìÿ ïîëüçîâàòåëÿ èëè ïàðîëü.';
$MsgABook_error_emailformate ='Îøèáî÷íûé ôîðìàò e-mail àäðåñà.<br><br>Ïîæàëóéñòà âåðíèòåñü íàçàä è ïðîâåðòå e-mail àäðåñ.';
$MsgABook_error_invalidloginnote='Âû ñîâåðøèëè îøèáêó. Âåðíèòåñü íàçàä, ÷òîáû èñïðàèòü åå è ïîïûòàòüñÿ ñíîâà. Íàæìèòå <b>New User</b> äëÿ ñîçäàíèÿ íîâîé àäðåñíîé êíèãè.';
$MsgABook_helppassword ='Ñïðàâêà! Âîñòñòàíîâëåíèå çàáûòîãî ïàðîëÿ!';
$MsgABook_cleancookie ='Óäàëèòü äàííûå îá èìåíè ïîëüçîâàòåëÿ/ïàðîëå ñ ýòîãî êîìïüþòåðà!';
$MsgABook_cleancookie_note ='Äàííûå îá èìåíè ïîëüçîâàòåëÿ è ïàðîëå óäàëåíû ñ âàøåãî êîìïüþòåðà!';
$MsgABook_pwdremeber ='Çàïîìíèòü ìîè èìÿ ïîëüçîâàòåëÿ è ïàðîëü';
$MsgABook_forgotpword_note ='Ââåäèòå Âàøå èìÿ ïîëüçîâàòåëÿ è íàæìèòå êíîïêó <b>Îòïðàâèòü</>, ÷òîáû ïîëó÷èòü ïàðîëü íà e-mail àäðåñ, êîòîðûé Âû óêàçàëè â Âàøåé àíêåòå.  Íàæìèòå <b>Îòìåíà</b> äëÿ âîçâðàòà íà ñòðàíèöó ââîäà èìåíè ïîëüçîâàòåëÿ è ïàðîëÿ.';
$MsgABook_forgotpword_note2 ='Ââåäèòå èìÿ ïîëüçîâàòåëÿ è ïàðîëü äëÿ âõîäà â Âàøó àäðåñíóþ êíèãó. Åñëè Âû íîâûé ïîëüçîâàòåëü è íå èìååòå àäðåñíîé êíèãè, íàæìèòå <b>New User</b> äëÿ ñîçäàíèÿ íîâîé àäðåñíîé êíèãè íà íàøåì ñåðâåðå.';
$MsgABook_create_note ='Privacy Policy: The information you enter below is stored on our web server and only will be used for your private use to insert the infos into postcards you send from our site.';
$MsgABook_profile_note ='Âûïîëíèòå ëþáûå èçìåíåíèÿ, ïîòîì íàæìèòå <B>Ñîõðàíèòü</B> äëÿ îáíîâëåíèÿ èíôîðìàöèè â âàøåé àíêåòå.';
$MsgABook_topnote ='Äëÿ âûáîðà íåñêîëüêèõ ïóíêòîâ óäåðæèâàéòå íàæàòîé \'Ctrl\' ïðè êëèêå';
$MsgABook_bottonnote ='Ïðèìå÷àíèå: Ïîìíèòå, ÷òî âûõîä èç àäðåñíîé êíèãè, ïîñëå çàâåðøåíèÿ ðàáîòû, íåîáõîäèì äëÿ çàùèòû Âàøåé ïåðñîíàëüíîé èíôîðìàöèè.';
$MsgABook_note1 ='Âàøà àäðåñíàÿ êíèãà çàêðûòà. Âû ìîæåòå çàïèñàòü èíôîðìàöèþ â àäðåñíóþ êíèãó òîëüêî åñëè îíà îòêðûòà. Âàøà àäðåñíàÿ êíèãà ñåé÷àñ çàêðûòà.';

$MsgABook_help_add ='Äîáàâëåíèå íîâîãî e-mail àäðåñà: Åñëè Âû õîòèòå äîáàâèòü íîâûé email àäðåñ â Âàøó àäðåñíóþ êíèãó, íàæìèòå çäåñü.';
$MsgABook_help_edit ='Ðåäàêòèðîâàíèå e-mail àäðåñà: Âûáåðèòå òîëüêî îäíó çàïèñü íà îñíîâíîé ñòðàíèöå è íàæìèòå êíîïêó <b>Èçìåíèòü</b>.';
$MsgABook_help_delete ='Óäàëåíèå e-mail àäðåñà: Âûáåðèòå çàïèñü, êîòîðóþ õîòèòå óäàëèòü è íàæìèòå <b>Óäàëèòü</b>.';
$MsgABook_help_help ='Ñòðàíèöà ïîìîùè: Âû óæå çäåñü :)';
$MsgABook_help_logout ='Âûõîä èç àäðåñíîé êíèãè áëîêèðóåò ïîëó÷åíèå äîñòóïà ïîñòîðîííèõ ê Âàøåé ïåðñîíàëüíîé èíôîðìàöèè.';
$MsgABook_help_close ='Çàêðûòü îêíî Âàøåé àäðåñíîé êíèãè.';
$MsgABook_help_insert ='Âñòàâèòü âûáðàííûå e-mail àäðåñà èç àäðåñíîé êíèãè.';
$MsgABook_help_profile ='Îáíîâëåíèå Âàøåé àíêåòû äëÿ àäðåñíîé êíèãè.';

$MsgReferFriend ='Ðåêîìåíäîâàòü ýòîò ñàéò äðóãó';
$MsgReferFriend_friendname ='Èìÿ äðóãà';
$MsgReferFriend_friendemail ='Å-mail äðóãà';
$MsgReferFriend_thanks ='Ñïàñèáî Âàì';
$MsgReferFriend_end ='Ñïàñèáî, ÷òî ïîðåêîìåíäîâàëè ýòîò ñàéò';
$MsgReferFriend_custommessage ='Äîáàâèòü è÷íîå ñîîáùåíèå';
$MsgReferFriend_error ='Îäíî èëè áîëåå ïîëåé ôîðìû áûëè îñòàâëåíû ïóñòûìè.<BR><BR> Ïîæàëóéñòà óêàæèòå âñþ íåîáõîäèìóþ èíôîðìàöèþ.';
$MsgReferFriend_error_emailformate ='Îøèáî÷íûé ôîðìàò e-mail àäðåñà.<br><br>Ïîæàëóéñòà âåðíèòåñü íàçàä è ïðîâåðüòå e-mail àäðåñ.';

$MsgNewsletter_join ='Âíåñòè ìîé àäðåñ â ñïèñîê àäðåñîâ ñëóæáû âèðòóàëüíûõ îòêðûòîê';

$Msg_error_emptyfield ='ïîëå ïóñòîå';

$Msg_label_username ='Èìÿ ïîëüçîâàòåëÿ';
$Msg_label_password ='Ïàðîëü';
$Msg_label_realname ='Ðåàëüíîå èìÿ';
$Msg_label_email ='E-mail àäðåñ';
$Msg_label_addressbook ='Àäðåñíàÿ êíèãà';

$Msg_label_add ='Äîáàâèòü';
$Msg_label_close ='Çàêðûòü';
$Msg_label_delete ='Óäàëèòü';
$Msg_label_done ='Ãîòîâî';
$Msg_label_edit ='Ðåäàêòèðîâàòü';
$Msg_label_finish ='Çàâåðøåíî';
$Msg_label_help ='Ñïðàâêà';
$Msg_label_login ='Âîéòè';
$Msg_label_logout ='Âûéòè';
$Msg_label_open ='Îòêðûòü';
$Msg_label_update ='Îáíîâèòü';
$Msg_label_samplee ='Ïðèìåð';
$Msg_label_image ='Èçîáðàæåíèå';
$Msg_label_view ='Âèä';

/* ######################## added version 1.3 ######################## */
$MsgSubcategory ='Ñóáêàòåãîðèÿ';
$MsgRandomCards ='Ñëó÷àéíàÿ îòêðûòêà';

/* ######################## added version 1.6 ######################## */
// updated!!!!
$MsgABook_bottonnote2 ='<font color=red><b>Âíèìàíèå:</b> ×òîáû óêàçàòü íåñêîëüêî ïîëó÷àòåëåé îòêðûòêè, èñïîëüçóéòå êëàâèøè SHIFT/CTRL</font>.';

/* ######################## added version 2.0 ######################## */
$Msg_rate ='ðåéòèíã îòêðûòêè';
$Msg_button_rate ='ðåéòèíãîâàòü!';

/* ######################## added version 2.2 ######################## */
$MsgABook_password2 ='Ïîäòâåðæäåíèå ïàðîëÿ';
$MsgABook_error2 ='Ïàðîëü íå ïîäòâåðæäåí. Âåðíèòåñü íàçàä äëÿ èñïðàâëåíèÿ îøèáêè.';

/* ######################## added version 2.3 ######################## */
$MsgABook_helppage ='<p><b>×òî òàêîå - Ìîÿ àäðåñíàÿ êíèãà? </b></p><p>Ìîÿ àäðåñíàÿ êíèãà - ýòî ñðåäñòâî, êîòîðîå ïðåäíàçíà÷åíî, ÷òîáû ñäåëàòü ëåã÷å äëÿ Âàñ ïðîöåññ ñîçäàíèÿ è îòïðàâêè îòêðûòîê. Â íåé Âû ìîæåòå ñîõðàíèòü èìåíà, e-mail àäðåñà Âàøèõ äðóçåé è çíàêîìûõ. Âû ìîæåòå áûñòðî óêàçûâàòü àäðåñà äëÿ Âàøèõ îòêðûòîê. Àäðåñíàÿ êíèãà ïðîñòà â èñïîëüçîâàíèè è ñïîñîáíà âêëþ÷èòü â ñåáÿ ìíîãî ïîëåçíûõ õàðàêòåðèñòèê ïî îáëåã÷åíèþ ïðîöåññà ñîçäàíèÿ îòêðûòêè. </p><p><b>Êàê ìíå äîáàâèòü èìåíà è e-mail àäðåñà â îòêðûòêó, èñïîëüçóÿ ìîþ àäðåñíóþ êíèãó? </b></p><p>First select the number of recipients you want use and then go to your List, simply select on the name and then click \'Insert emails into card\'. The name and email address of your recipient will be added to your card. If you want select multiple contacts, jsut holding down \'Ctrl\' while clicking the names. These names will be added to your card if there is the correct number recipients fields. </p>';


?>