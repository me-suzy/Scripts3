<?
$LANG = ARRAY() ;		// MUST initialize, DO NOT CHANGE
/*******************************************************************
* Language: Italian
* Contributed by: Renato Cretella
*	Name: Renato Cretella <cretella@hostcontact.com>
*	Company: Live Contact
*	URL: http://www.LiveContact.net
* Revised by: Giacomo Pelagatti
*	Name: Giacomo Pelagatti <giacomo@parmagrocery.com>
*	Company: ParmaGrocery.com
*	URL: http://www.ParmaGrocery.com
*
* ------------------------------------------------------------------
* DO NOT REMOVE COMMENTS.  JUST MODIFY THE ACTUAL TEXT.
* DO NOT REMOVE THE orig TEXT!  THEY ARE THERE FOR REFERENCE.
* ------------------------------------------------------------------
* NOTE: If you are going to submit a language pack, please keep
* everything basic and do not mention any company name, personal name,
* copyrighted name, or such.  Keep all translations generic, simple
* and catered to all audiences and companies.  Basically, your
* translated language pack should be ready to use without being touched.
*******************************************************************/



/***************** ONLY MAKE THE CHANGE BELOW ******************/


/*** branded string located troughout 99% of the pages */
//orig: PHP <i>Live!</i> &copy; 2001 OSI Codes, New York, New York
$LANG['DEFAULT_BRANDING'] = "Powered by <a href='http://www.PHPLiveSupport.com/' target='new'>PHP <i>Live!</i></a>" ;

/*** chat page titles */
//orig: Request Live! Support Request
//was: "Richiesta di Supporto": not consistent with "assistenza" being used later on for "support".
$LANG['TITLE_SUPPORTREQUEST'] = "Richiesta di assistenza" ;
//orig: You are now speaking with a Live! Support
//was: "Sei ora in connessione con un operatore": "in collegamento" is a more adequate term for mediated human interaction.
$LANG['TITLE_SUPPORCHAT'] = "Sei ora in collegamento con un operatore" ;
//orig: Please leave a message
//was: "Lascia un messaggio": missing polite invitation ("Please").
$LANG['TITLE_LEAVEMESSAGE'] = "Per favore lascia un messaggio" ;
//orig: Printer Friendly View
//was: "Versione Stampabile": inconsistent use of first capital letter.
$LANG['TITLE_PRINTVIEW'] = "Versione stampabile" ;

$LANG['WORD_BACK'] = "Indietro" ;
$LANG['WORD_CLOSE'] = "Chiudi" ;
$LANG['WORD_NAME'] = "Nome" ;
$LANG['WORD_EMAIL'] = "Email" ;
$LANG['WORD_SUBJECT'] = "Oggetto" ;
$LANG['WORD_MESSAGE'] = "Messaggio" ;
$LANG['WORD_SEND'] = "Invia" ;
//was: "Azienfa": wrong spelling.
$LANG['WORD_COMPANY'] = "Azienda" ;
$LANG['WORD_DEPARTMENT'] = "Reparto" ;
$LANG['WORD_DAY'] = "Giorno" ;

//orig: Welcome to our Live! support help desk.
$LANG['CHAT_REQUEST_TITLE'] = "Benvenuto sul nostro sistema di assistenza on line." ;
//orig: Please select the department you would like to reach.
//was: "Seleziona il Reparto che vuoi contattare.": inconsistent use of first capital letter.
$LANG['CHAT_REQUEST_SELECTDEPT'] = "Seleziona il reparto che vuoi contattare." ;
//orig: Input your Screen Name used during this session:
//was: "Inserisci il tuo nome:": inexhaustive and misleading.
$LANG['CHAT_REQUEST_INPUTSCREENNAME'] = "Inserisci il nome da usare durante questa sessione:" ;
//orig: Welcome %%user%%! Please hold while we contact a representative. If a representative does not respond in few seconds, then he/she is not available at this time.
//was: "Benvenuto %%user%%! Pazienta un attimo, stiamo contattando un operatore disponibile. Se nessun operatore risponde in pochi minuti significa che in questo momento non è disponibile.": a colloquial expression ("Pazienta un attimo") was used in place of a more standard formal prompt ("Please hold"); other minor changes.
$LANG['CHAT_GREETING'] = "Benvenuto %%user%%! Attendi prego, stiamo contattando un operatore. Se nessun operatore risponde entro pochi secondi significa che al momento non è disponibile." ;
//orig: Printer Friendly
$LANG['CHAT_PRINTER_FRIENDLY'] = "Versione stampabile" ;
//orig: Your party has left this session.
//was: "Il tuo interlocutore ha lasciato la chat.": not consistent with "sessione" being used for "session" earlier on; "abbandonato" is a slightly more proper translation for "left" in this context.
$LANG['CHAT_PARTYLEFTSESSION'] = "Il tuo interlocutore ha abbandonato la sessione." ;
//orig: You are now speaking with
//was: "Sei ora in chat con ": not consistent with the $LANG['TITLE_SUPPORCHAT'] message.
$LANG['CHAT_REQUEST_NOWSPEAKINGWITH'] = "Sei ora in collegamento con " ;
//orig: Department is currently unavailable. Please leave a message.
//was: "Reparto non disponibile. Lascia un messaggio.": not exhaustive and quite impolite.
$LANG['MESSAGE_BOX_MESSAGE'] = "Il reparto non è al momento disponibile. Per favore lascia un messaggio." ;
//orig: Thank you.  Your message has been sent to:
//was: "Grazie, il tuo messaggio è stato inviato a:": incorrect punctuation.
$LANG['MESSAGE_BOX_SENT'] = "Grazie. Il tuo messaggio è stato inviato a:" ;
//orig: Items with (*) must be filled.
$LANG['MESSAGE_BOX_JS_A_ALLFIELDSSUP'] = "I campi con (*) devono essere compilati." ;
//orig: Email is invalid format (example: someone@somewhere.com)
$LANG['MESSAGE_BOX_JS_A_INVALIDEMAIL'] = "Il email è disposizione non valida (esempio: someone@somewhere.com)" ;
//orig: Department not available.  Please leave a message.
//was: "Reparto non disponibile, lascia un messaggio.": (see $LANG['MESSAGE_BOX_MESSAGE']).
$LANG['CHAT_DEPT_NOTAVAIL'] = "Reparto non disponibile. Per favore lascia un messaggio." ;
//orig: Session did not create.  Request did not send.
$LANG['CHAT_NOTCREATE'] = "Sessione non creata. Richiesta non inoltrata." ;

//orig: Please hold while being transferred to
$LANG['CHAT_CALL_TRANSFER'] = "Tenere prego mentre essendo trasferendo a" ;
//orig: What is your question?
$LANG['CHAT_REQUEST_QUESTION'] = "Che cosa è la vostra domanda?" ;
//orig: A copy of the chat transcript has been sent to:
$LANG['CHAT_TRANSCRIPT_SENT'] = "A copy of the chat transcript has been sent to:" ;
?>