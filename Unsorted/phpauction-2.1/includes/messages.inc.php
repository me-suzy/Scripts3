<?php
/*
   Copyright (c), 1999, 2000 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
*/

// Error messages

$ERR		= ""; // leave this line as is
$ERR_000	= ""; // leave this line as is
$ERR_001 = "Database access error. Please contact the site administrator.";
$ERR_002 = "Name missing";
$ERR_003 = "Username missing";
$ERR_004 = "Password missing";
$ERR_005 = "Please, repeat your password";
$ERR_006 = "Passwords do not match";
$ERR_007 = "E-mail address missing";
$ERR_008 = "Please, insert a valid e-mail address";
$ERR_009 = "The username already exists in the database";
$ERR_010 = "Username too short (min 6 chars)";
$ERR_011 = "Password too short (min 6 chars)";
$ERR_012 = "Address missing";
$ERR_013 = "City missing";
$ERR_014 = "Country missing";
$ERR_015 = "ZIP code missing";
$ERR_016 = "Please, insert a valid ZIP code";
$ERR_017 = "Item's title missing";
$ERR_018 = "Item's description missing";
$ERR_019 = "Starting bid missing";
$ERR_020 = "Minimum quantity field is not correct";
$ERR_021 = "Reserve price missing";
$ERR_022 = "The reserve price you inserted is not correct";
$ERR_023 = "Choose a category for your item";
$ERR_024 = "Choose a payment method";
$ERR_025 = "User unknown";
$ERR_026 = "Password incorrect";
$ERR_027 = "Currency symbol missing";
$ERR_028 = "Please, insert a valid e-mail address";
$ERR_029 = "User data are already registered";
$ERR_030 = "Fields must be numeric and in nnnn.nn format";
$ERR_031 = "The form you are submitting is not complete";
$ERR_032 = "One or both the e-mail addresses are not correct";
$ERR_033 = "Your bid is not correct: $bid";
$ERR_034 = "Your bid must be at least: ";
$ERR_035 = "Days field must be numeric";
$ERR_036 = "The seller cannot bid in his/her own auctions";
$ERR_037 = "Search keyword cannot be empty";
$ERR_038 = "Login incorrect";
$ERR_039 = "You have already confirmed your registration.";
$ERR_040 = "";
$ERR_041 = "Please, choose a rate between 1 and 5";
$ERR_042 = "You comment is missing";
$ERR_043 = "Please enter your birthdate as mm/dd/yyyy.";
$ERR_044 = "Cookies must be enabled to login.";
$ERR_045 = "No closed auctions for this user.";
$ERR_046 = "No active auctions for this user.";

#// Added on Nov.14 2001 - Gianluca
$ERR_047 = "Required fields missing";
$ERR_048 = "Incorrect login";
$ERR_049 = "Database connection failed. Please edit your includes/passwd.inc.php
            file to set you database parameters.";
$ERR_050 = "Acceptance text missing";
$ERR_051 = "Please, insert a valid number of digits";
$ERR_052 = "Please, insert the number of news to show in the news box";
$ERR_053 = "Please, insert a valid number of news";
$ERR_054 = "Please, fill in both the password fields";
$ERR_055 = "User <I>$HTTP_POST_VARS[username]</I> already exists in the database";
$ERR_056 = "Bids increment value missing";
$ERR_057 = "Bids increment value must be numeric";
$ERR_058 = "Incorrect money format.";
$ERR_059 = "Your previous bid for this auction is higher than your current bid.<br>  In Dutch Auctions you may not place a bid where your previous bid X quantity is greater than your current bid X quantity.";


#// GIAN - Jan. 19, 2002
$ERR_060 = "The end date is minor or equal to the start date. Change the auction's duration to fix this problem.";

#// MARY - Jan. 23, 2002
$ERR_061 = "You must have placed a bid on this auction to leave feedback about this user.";

//--
$ERR_100 = "User does not exist";
$ERR_101 = "Password incorrect";
$ERR_102 = "Username does not exist";
$ERR_103 = "You cannot rate yourself";
$ERR_104 = "All fields required";
$ERR_105 = "Username does not exist";
$ERR_106 = "<BR><BR>No user specified";
$ERR_107 = "Username is too short";
$ERR_108 = "Password is too short";
$ERR_109 = "Passwords do not match";
$ERR_110 = "E-mail address incorrect";
$ERR_111 = "Such a user already exists";
$ERR_112 = "Data missing";
$ERR_113 = "You must be at least 18 years old to register";
$ERR_114 = "No active auctions for this category"; 
$ERR_115 = "E-mail Address already used";
$ERR_116 = "No help available on that topic.";
$ERR_117 = "Incorrect date format";
//--
$ERR_600 = "Incorrect auction type";
$ERR_601 = "Quantity field not correct";
$ERR_602 = "Images must be GIF or JPG";
$ERR_603 = "The image is too large";
$ERR_604 = "This auction already exists";
$ERR_605 = "The specified ID is not valid";

$ERR_606 = "The specified auction ID is not correct"; // used in bid.php
$ERR_607 = "Your bid is below the minimum bid";
$ERR_608 = "The specified quantity is not correct";
$ERR_609 = "User does not exist";
$ERR_610 = "Fill in your username and password";
$ERR_611 = "Password incorrect";
$ERR_612 = "You cannot bid, you are the seller!";
$ERR_613 = "You cannot bid, you are the winner!";
$ERR_614 = "This auction is closed";
$ERR_615 = "You cannot bid below the minimum bid!";
$ERR_616 = "Zip code too short";
$ERR_617 = "Telephone number incorrect";
$ERR_618 = "You account has been suspended or you didn't confirm it.";
$ERR_619 = "This auction has been suspended";
$ERR_620 = "Parent category has been deleted by the Administrator.";
/* -- AUCTION MANAGEMENT -- */
$ERR_700 = "Please enter dates as mm/dd/yyyy.";
$ERR_701 = "Invalid quantity (must be >0).";
$ERR_702 = "Current Bid must be greater then minimum bid.";
/* ------------------------ */


// UI Messages

$MSG_001 = "New user registration";
$MSG_002 = "Your name";
$MSG_003 = "Username";
$MSG_004 = "Password";
$MSG_005 = "Please, repeat your password";
$MSG_006 = "Your e-mail address";
$MSG_007 = "Submit";
$MSG_008 = "Delete";
$MSG_009 = "Address";
$MSG_010 = "City";
$MSG_011 = "State/Province";
$MSG_012 = "ZIP Code";
$MSG_013 = "Telephone";
$MSG_014 = "Country";
$MSG_015 = "--Select here";
$MSG_016 = "Registration completed. Your data has been properly received.<BR>
			A confirmation e-mail has been sent to: ";
$MSG_017 = "Item title";
$MSG_018 = "Item description<BR>(HTML allowed)";
$MSG_019 = "URL of picture<BR>(optional)";
$MSG_020 = "Auction starts with";
$MSG_021 = "Reserve price";
$MSG_022 = "Duration";
$MSG_023 = "Country";
$MSG_024 = "ZIP Code";
$MSG_025 = "Shipping conditions";
$MSG_026 = "Payment methods";
$MSG_027 = "Choose a category";
$MSG_028 = "Sell an item";
$MSG_029 = "No";
$MSG_030 = "Yes";
$MSG_031 = "Buyers pays shipping expenses";
$MSG_032 = "Seller pays shipping expenses";
$MSG_033 = "Will ship internationally";
$MSG_034 = "Preview auction";
$MSG_035 = "Reset form";
$MSG_036 = "Submit my data";
$MSG_037 = "No image available";
$MSG_038 = "Buyers pays shipping expenses";
$MSG_039 = "No reserve price";
$MSG_040 = "Submit auction";
$MSG_041 = "Item category";
$MSG_042 = "Item description";
$MSG_043 = "Will NOT ship internationally";
$MSG_044 = "Fill in your username and password and submit the form.";
$MSG_045 = "Users management";
$MSG_046 = "You can still <A HREF='sell.php?mode=recall&SESSION_ID=$SESSION_ID'> make changes</A> to your auction";
$MSG_047 = "Username";
$MSG_048 = "Password";
$MSG_049 = "If you are not registered, ";
$MSG_050 = "(min 6 chars)";
$MSG_051 = "Main page";
$MSG_052 = "Login";
$MSG_053 = "Edit admin e-mail";
$MSG_054 = "Submit new e-mail";
$MSG_055 = "Edit the admin e-mail address below";
$MSG_056 = "E-mail address updated";
$MSG_057 = "Edit the currency symbol below";
$MSG_058 = "Submit new currency";
$MSG_059 = "E-mail address updated";
$MSG_060 = "Currency symbol updated";
$MSG_061 = "INSTALLATION";
$MSG_062 = "ADMINISTRATION";
$MSG_063 = "CONFIGURATION";
$MSG_064 = "Step 1. - Create MySQL database";
$MSG_065 = "Step2. - Create necessary tables";
$MSG_066 = "Step 3. - Populate tables";
$MSG_067 = "Auctions management";
$MSG_068 = "Reset form";
$MSG_069 = "Edit auctions duration";
$MSG_070 = "Use the checkbox Delete and the button DELETE to delete lines. Use the last line to add a new payment condition. Simplemente edita los campos de texto y pulsa Actualizar para guardar los cambios.";
$MSG_071 = "Update";
$MSG_072 = "Delete";
$MSG_073 = "Lines delete";
$MSG_074 = "Use the checkbox Delete and the button DELETE to delete lines. Simply edit the text fields and press UPDATE to save the changes.";
$MSG_075 = "Edit payment methods";
$MSG_076 = "Edit currency symbol";
$MSG_077 = "Edit admin e-mail address";
$MSG_078 = "Edit categories table";
$MSG_079 = "Payment methods table";
$MSG_080 = "Auctions duration table";
$MSG_081 = "Countries table";
$MSG_082 = "Edit categories table";
$MSG_083 = "Edit countries table";
$MSG_084 = "Para iniciar o actualizar la tabla de categorías y subcategorías, 
				edita el fichero CATEGORIES.TXT que encontrarás en la distribución, 
				y sigue las instrucciones que encontrarás al principio de éste.
				<BR>Una vez editado el fichero, pulsa el botón inferior de \"Procesar los cambios\"  
				para actualizar la base de datos. 
				Las tablas existentes serán borradas, sin que se compruebe si algunos de los valores actuales
				están siendo usados. La mejor filosofía es dejar las categorías y subcategorías que fueron definidas
				al principio y añadir de nuevas sólo si son necesarias.";
$MSG_085 = "Para iniciar o actualizar la tabla de categorías y subcategorías, 
				editas el fichero COUNTRIES.TXT que encontrarás en la distribución, 
				y sigue las instrucciones que encontrarás al principio de éste.
				<BR>Una vez editado el fichero, pulsa el botón inferior de \"Procesar los cambios\"  
				para actualizar la base de datos. 
				Las tablas existentes serán borradas, sin que se compruebe si algunos de los valores actuales
				están siendo usados.";
$MSG_086 = "Categories table updated";
$MSG_087 = "Description";
$MSG_088 = "Delete";
$MSG_089 = "Process changes";
$MSG_090 = "Countries table updated";
$MSG_091 = "Change language";
$MSG_092 = "Edit, delete or add payments methods using the form below";
$MSG_093 = "Payments method table updated";
$MSG_094 = "Edit, delete or add countries using the form below";
$MSG_095 = "Countries table updated";
$MSG_096 = "Actual language";
$MSG_097 = "Days";
$MSG_098 = "Registration confirmation";
$MSG_099 = "New auction confirmation";
$MSG_100 = "Your auction has been properly received.<BR>A confirmation e-mail has been sent to your e-mail address.<BR>";
$MSG_101 = "Auction URL: ";
$MSG_102 = " Go! ";
$MSG_103 = "Search ";
$MSG_104 = "Browse ";
$MSG_105 = "View history";
$MSG_106 = "Send this auction to a friend";
$MSG_107 = "User's e-mail";
$MSG_108 = "View picture";
$MSG_109 = "Item description";
$MSG_110 = "Country";
$MSG_111 = "Auction started";
$MSG_112 = "Auction ended";
$MSG_113 = "Auction ID";
$MSG_114 = "No picture available";
$MSG_115 = "Bid now!<BR>It's FREE";
$MSG_116 = "Current bid";
$MSG_117 = "Higher bidder";
$MSG_118 = "Ends within";
$MSG_119 = "# of bids";
$MSG_120 = "Bid increment";
$MSG_121 = "Place Your Bid Here";
$MSG_122 = "Edit, delete or add auctions durations using the form below";
$MSG_123 = "Durations table updated";
$MSG_124 = "Minimum bid";
$MSG_125 = "Seller";
$MSG_126 = " days, ";
$MSG_127 = "Starting bid";
$MSG_128 = "Edit bid increments";
$MSG_129 = "ID";
$MSG_130 = "Description";
$MSG_131 = "Days";
$MSG_132 = "Los incrementos de las ofertas dependen del valor actual de la oferta más alta.
				Lee <A HREF=\"./inchelp.php\">este documento</A> como ayuda. Usa esta tabla para definir los Incrementos de tu subasta.
				Usa la casilla Borrar y el botón de Borrar para eliminar líneas. 
				Use la última línea para añadir líneas. Simplemente edite los campos de texto
				y use Actualizar para guardar los cambios.";
$MSG_133 = "Bid increments table";
$MSG_134 = "Current bid";
$MSG_135 = "Edit, delete or add increments using the form below.<BR>
            Be careful, there's no control over the table's values congruence. 
            You must take care to check it yourself. The only data check performed is over the fields content (must be numeric) but the relation between them is not checked.<br>
            [<A HREF=\"javascript:window_open('incrementshelp.php','incre',400,500,30,30)\" CLASS=\"links\">Read more</A>]";
$MSG_136 = "and";
$MSG_137 = "Increment";
$MSG_138 = "Back to the auction";
$MSG_139 = "Send this auction to a friend";
$MSG_140 = "Your friend's name";
$MSG_141 = "Your friend's e-mail";
$MSG_142 = "Your name";
$MSG_143 = "Your e-mail";
$MSG_144 = "Add a comment";
$MSG_145 = "Send to your friend";
$MSG_146 = "This auction has been sent to ";
$MSG_147 = "Send to another friend";
$MSG_148 = "Help";
$MSG_149 = "You can contact this user using the form below.";
$MSG_150 = "Send request";
$MSG_151 = " The e-mail you requested is ";
$MSG_152 = "Confirm your bid";
$MSG_153 = "To bid you must be registered."; 
$MSG_154 = "YOUR BID:";
$MSG_155 = "Item:";
$MSG_156 = "Your bid:";
$MSG_157 = "Si aprietas en el botón inferior, estás de acuerdo en comprar este producto
				según las condiciones y el precio descrito por el vendedor.<BR>
				Los gastos de transporte, si los hubiere, son aquellos especificados en la descripción del producto.<BR>
				Aún puedes rectificar tu oferta pulsando el botón de Volver Atrás y realizando una nueva oferta
				desde la página de subastas.
				Para confirmar y hacer efectiva tu oferta, escribe tu Nombre de usuario y tu contraseña y pulsa el botón de 
				HACER MI OFERTA situado más abajo.";
$MSG_158 = "Submit my bid";
$MSG_159 = "Your bid has been registered";
$MSG_159 = "Bidder:";
$MSG_160 = "Increments table updated";
$MSG_161 = "Edit, delete or add categories using the form below.<BR>[<A HREF=\"javascript:window_open('categorieshelp.php','incre',400,300,30,30)\" CLASS=\"links\">Read more</A>]";
$MSG_162 = "Tu oferta ha sido aceptada. Gracias por ofertar.<BR>
		    La <A HREF=\"./item.php?id=$id\">página de la subasta</A> ahora ya refleja tu oferta.";
$MSG_163 = "Register!";
$MSG_164 = "Help";
$MSG_165 = "Category: ";
$MSG_166 = "Home";
$MSG_167 = "Picture";
$MSG_168 = "Auction";
$MSG_169 = "Actual bid";
$MSG_170 = "Bids #";
$MSG_171 = "Ends in";
$MSG_172 = "No active auctions in this category";
$MSG_173 = "Search result: ";
$MSG_174 = "Bid";
$MSG_175 = "Date and hour";
$MSG_176 = "Bidder";				
$MSG_177 = "Categories index";				
$MSG_178 = "Contact the bidder";				
$MSG_179 = "To get another user's e-mail address, just fill in your username and password.";
$MSG_180 = " is:";
$MSG_181 = "User's login";
$MSG_182 = "Edit your personal data";
$MSG_183 = "Your data has been updated";
$MSG_184 = "Categories table has been updated.";
$MSG_185 = "Help";
$MSG_186 = "<A HREF=\"javascript:history.back()\">Back</A>";
$MSG_187 = "Your username";
$MSG_188 = "Your password";
$MSG_189 = "Your e-mail";
$MSG_190 = "Your item's category";
$MSG_191 = "Payment methods";
$MSG_192 = "Shipping conditions";
$MSG_193 = "Auction's duration";
$MSG_194 = "Starting bid";
$MSG_195 = "Picture's URL";
$MSG_196 = "Item's description";
$MSG_197 = "Auction's title";
$MSG_198 = "No items found";
$MSG_199 = "Search";
$MSG_200 = "User: ";
$MSG_201 = "new user";
$MSG_202 = "Users's data";
$MSG_203 = "Active auctions";
$MSG_204 = "Closed auctions";
$MSG_205 = "User's control panel";
$MSG_206 = "User's profile";
$MSG_207 = "Leave your comment";
$MSG_208 = "View comments";
$MSG_209 = "Registered user since: ";
$MSG_210 = "Contact with ";
$MSG_211 = "";
$MSG_212 = "Auctions:";
$MSG_213 = "View active auctions";
$MSG_214 = "View closed auctions";
$MSG_215 = "Forgot your password?";
$MSG_216 = "If you lost your password, please fill in your username below.";
$MSG_217 = "A new password has been sent to your e-mail address.";
$MSG_218 = "View user's profile";
$MSG_219 = "Active auctions: ";
$MSG_220 = "Closed auctions: ";
$MSG_221 = "User login";
$MSG_222 = "User rating";
$MSG_223 = "Leave your comment";
$MSG_224 = "Choose a rate between 1 and 5";
$MSG_225 = "Thanks for leaving your comment";
$MSG_226 = "Your rating ";
$MSG_227 = "Your comment ";
$MSG_228 = "Valued by ";
$MSG_229 = "Newest feedbacks:";
$MSG_230 = "View all feedbacks";
$MSG_231 = "REGISTERED USERS";
$MSG_232 = "AUCTIONS";
$MSG_233 = "More";
$MSG_234 = "Back";
$MSG_235 = "Register now";
$MSG_236 = "Sell an item";
$MSG_237 = "Bid";
$MSG_238 = "Search";
$MSG_239 = "Auctions";
$MSG_240 = "From";
$MSG_241 = "To";
$MSG_242 = "Increment";
$MSG_243 = "If you want to change your password, please fill in the two fields below. Otherwise leave them blank.";
$MSG_244 = "Edit data";
$MSG_245 = "Logout";
$MSG_246 = "Logged in";
$MSG_247 = "User: ";
$MSG_248 = "Confirm your registration";
$MSG_249 = "Confirm";
$MSG_250 = "Refuse";
$MSG_251 = "---- Select here";
$MSG_252 = "Birthdate";
$MSG_253 = "(mm/dd/yyyy)";
$MSG_254 = "Suggest a new category";
$MSG_255 = "Auction's ID";
$MSG_256 = "Or select the image you want to upload (optional)";
$MSG_257 = "Auction's type";
$MSG_258 = "Items quantity";
$MSG_259 = "Login";
$MSG_260 = "Copyright 2000-2002, PHPAUCTION.ORG";
$MSG_261 = "Auction type";
$MSG_262 = "Your suggestion";
$MSG_263 = "Register now!";
$MSG_264 = "You still can ";
$MSG_265 = "make changes";
$MSG_266 = " to this auction";
$MSG_267 = "If you reached this page, you or someone claiming to be you, signed up at this site.
				<br>To confirm your registration simply press the <B>Confirm</B> button below.
				<BR>If you didn't want to register and want to delete your data from our database, use the <B>Refuse</B> button.";
$MSG_268 = "Password";
$MSG_269 = "Your bid has been registered";
$MSG_270 = "Back";
$MSG_271 = "Your bid has been processed";
$MSG_272 = "Auction:";
$MSG_273 = "To bid you must be registered.";
$MSG_274 = "Home";
$MSG_275 = "Go!";
$MSG_276 = "Categories";
$MSG_277 = "More";
$MSG_278 = "Last created auctions";
$MSG_279 = "Higher bids";
$MSG_280 = "Ending soon!";
$MSG_281 = "Help Column";
$MSG_282 = "News";
$MSG_283 = "minimum";
$MSG_284 = "Quantity";
$MSG_285 = "Go back";
$MSG_286 = " and specify a valid bid.";
$MSG_287 = "Category";
$MSG_288 = "Search keyword(s) cannot be empty";
$MSG_289 = "Total pages:";
$MSG_290 = "Total items:";
$MSG_291 = "items per page shown";
$MSG_292 = "All categories";
$MSG_293 = "NICK";
$MSG_294 = "NAME";
$MSG_295 = "COUNTRY";
$MSG_296 = "E-MAIL";
$MSG_297 = "ACTION";
$MSG_298 = "Edit";
$MSG_299 = "Delete";
$MSG_300 = "Suspend";
$MSG_301 = "users found in the database";
$MSG_302 = "Name";
$MSG_303 = "E-mail";
$MSG_304 = "Delete user";
$MSG_305 = "Suspend user";
$MSG_306 = "Reactivate user";
$MSG_307 = "Are you sure you want to delete this user?";
$MSG_308 = "Are you sure you want to suspend this user?";
$MSG_309 = "Are you sure you want to reactivate this user?";
$MSG_310 = "Reactivate";
$MSG_311 = "auctions found in the database";
$MSG_312 = "TITLE";
$MSG_313 = "USER";
$MSG_314 = "DATE";
$MSG_315 = "DURATION";
$MSG_316 = "CATEGORY";
$MSG_317 = "DESCRIPTION";
$MSG_318 = "CURRENT BID";
$MSG_319 = "QUANTITY";
$MSG_320 = "RESERVE PRICE";
$MSG_321 = "Suspend auction";
$MSG_322 = "Reactivate auction";
$MSG_323 = "Are you sure you want to suspend this auction?";
$MSG_324 = "Are you sure you want to reactivate this auction?";
$MSG_325 = "Delete auction";
$MSG_326 = "Are you sure you want to delete this auction?";
$MSG_327 = "MINIMUM BID";
/*
 * Some more category fields.
 */
 
$MSG_328 = "Color";
$MSG_329 = "Image Location";
$MSG_330 = "Thank you for confirming your registration!<BR>The registration process completed and you can now participate in our site's activities.<BR>";
$MSG_331 = "Your registration has been deleted permanently from our database.";
$MSG_332 = "Subject";
$MSG_333 = "Message";
$MSG_334 = "Contact with";
$MSG_335 = "Contact from ";
$MSG_336 = "regarding your auction ";
$MSG_337 = "Your message has been sent to ";
$MSG_338 = "Delete news";
$MSG_339 = "Are you sure you want to delete this news?";
$MSG_340 = "News list";
$MSG_341 = "View all news";
$MSG_342 = " News";
$MSG_343 = "Edit news";



$MSG_500 = "More";
$MSG_501 = "Home";
$MSG_502 = "Number of feedbacks";
$MSG_503 = "Feedback";
$MSG_504 = "Comment";
$MSG_505 = "Back to user's profile";
$MSG_506 = "Feedback sent on: ";
$MSG_507 = "Hide history";
$MSG_508 = "[user e-mail]";
$MSG_509 = "User's data";
$MSG_510 = "Your data has been updated";
$MSG_511 = "Edit user";
$MSG_512 = "Edit auction";
$MSG_513 = "Suggest a category";
$MSG_514 = "No bidder reached the reserve price";
$MSG_515 = "Reserve price has been reached";
$MSG_516 = "News management";
$MSG_517 = " news found in the database";
$MSG_518 = "Add new";
$MSG_519 = "Title";
$MSG_520 = "Content";
$MSG_521 = "Activate";
$MSG_522 = "Date";


#// ADDED Nov.14 2001 - Gianluca
$MSG_523 = "Note: Cookies must be enabled to login.";
$MSG_524 = "SETTINGS";
$MSG_525 = "Manage admin users";
$MSG_526 = "General settings";
$MSG_527 = "Site name";
$MSG_528 = "Site URL";
$MSG_529 = "Edit the settings below according to your phpauction installation.";
$MSG_530 = "Save changes";
$MSG_531 = "Your logo";
$MSG_532 = "Display login box?";
$MSG_533 = "Display news box?";
$MSG_534 = "Show acceptance text?";
$MSG_535 = "Your site's name will appear in the e-mail messages phpauction sends to users";
$MSG_536 = "This must be the complete URL (starting with <B>http://</B>) of your phpauction installations.<BR>
			Be sure to include the ending slash.";
$MSG_537 = "Select <B>Yes</B> if you want the users login box to be displayed in the Home page. Otherwise select <B>No</B>";
$MSG_538 = "Select <B>Yes</B> if you want the news box to be displayed in the Home page. Otherwise select <B>No</B>";
$MSG_539 = "Selecting the <B>Yes</B> option below will make phpauction display the text you fill in the text box below in the users registration page just before the submit buttom.<BR>
			This is typically used to display some legal notes users accept submitting the registration form.";
$MSG_540 = "Admin e-mail";
$MSG_541 = "The admin e-mail address is used to send automatic e-mail messages";
$MSG_542 = "General settings updated";
$MSG_543 = "Admin home";
$MSG_544 = "Money format";
$MSG_545 = "US style: 1,250.00";
$MSG_546 = "European style: 1.250,00";
$MSG_547 = "Set to zero or leave blank if you don't want decimal digits in your money representation";
$MSG_548 = "Decimal digits";
$MSG_549 = "Symbol position";
$MSG_550 = "Before the amount (i.e. USD 200)";
$MSG_551 = "After the amount (i.e. 200 USD)";
$MSG_552 = "Currency symbol";
$MSG_553 = "Currency settings updated";
$MSG_554 = "Number of news you want to show";
$MSG_555 = "Fonts and colors";
$MSG_556 = "Current logo";
$MSG_557 = "Username";
$MSG_558 = "Created";
$MSG_559 = "Last login";
$MSG_560 = "Status";
$MSG_561 = "DELETE";
$MSG_562 = "Edit admin user";
$MSG_563 = "If you want to change the user's password use the two fields below. To mantain the current password leave them blank.";
$MSG_564 = "Repeat password";
$MSG_565 = "User is";
$MSG_566 = "active";
$MSG_567 = "not active";
$MSG_568 = "New admin user";
$MSG_569 = "Insert user";
$MSG_570 = "Never logged in";
$MSG_571 = "Standard font";
$MSG_572 = "Error font";
$MSG_573 = "Small font";
$MSG_574 = "Footer font";
$MSG_575 = "Title font";
$MSG_576 = "These are the font settings for your error messages";
$MSG_577 = "This is the standard font used to display all the site's text";
$MSG_578 = "Face";
$MSG_579 = "Size";
$MSG_580 = "Color";
$MSG_581 = "Bold";
$MSG_582 = "Italic";
$MSG_583 = "The <B>Small font</B> format is used to display minor text like date in the header of the pages";
$MSG_584 = "Font format for the text area in the footer of the pages";
$MSG_585 = "This is the font used in the titles of pages";
$MSG_586 = "Border color";
$MSG_587 = "This is the color of the footer, top navigation bar, & border of the external table";
$MSG_588 = "Navigation font";
$MSG_589 = "This is the font format of the navigation links in the header of the pages";
$MSG_590 = "Header background color";
$MSG_591 = "Tables header color";
$MSG_592 = "Logged in as: ";
$MSG_593 = "Fonts and colors settings updated";
$MSG_594 = "<BR>
			<FONTs COLOR=RED><B>Note:</B> for this utility to work, the numbers format MUST follow the USA style notation.<BR>
		    Your <A HREF=currency.php>currency settings</A> will be ignored here.";
$MSG_595 = "Links color";
$MSG_596 = "Visited links color";
$MSG_597 = "Activate banners management?";
$MSG_598 = "<A HREF=http://sourceforge.net/projects/phpadsnew/ target=_BLANK>phpAdsNew</a> has been integrated into phpauction.  You must choose below to activate.<BR>
			PhpAdsNew is a powerful banners management system.<BR>
			468x60 banners are placed by default in the header of each page.";
$MSG_599 = "Banners management";
$MSG_600 = "Banners settings updated";
$MSG_601 = "Access PhpAdsNew administration back-end.";
$MSG_602 = "Upload a new logo (max. 50 Kbytes)";
$MSG_603 = "Activate Newsletter?";
$MSG_604 = "If you activate this option, users will be able to subscribe your newsletter from the registration page.<BR>
			The \"Newsletter management\" will let you send e-mail messages to the subscribed users";

#//
$MSG_605 = "Message Body";
$MSG_606 = "Subject";
$MSG_607 = "Newsletter Submission";
$MSG_608 = "Would you like to receive our Newsletter?";
$MSG_609 = "Check NO to unsubscribe to our Newsletter";
$MSG_610 = "<b>If you want to change your password, please fill in the two fields below, otherwise leave them blank.</b>";
$MSG_611 = "<b>This item has been viewed</b>";
$MSG_612 = "<b>times</b>";
$MSG_613 = "Bids increment";
$MSG_614 = "Use the built-in proportional increments table";
$MSG_615 = "Use your custom fixed increment";
$MSG_616 = "Increment";
$MSG_617 = "<B>*NOTE*  If you want to change you password use the two fields below.<BR>Otherwise leave them blank.</B>";
$MSG_618 = "Your auctions";
$MSG_619 = "Open Auctions";
$MSG_620 = "Your bids";
$MSG_621 = "Edit your personal profile";
$MSG_622 = "Your control panel";
$MSG_623 = "Closed Auctions";
$MSG_624 = "Auction's title";
$MSG_625 = "Started";
$MSG_626 = "Ends";
$MSG_627 = "N. Bids";
$MSG_628 = "Max. Bid";
$MSG_629 = "Ended";
$MSG_630 = "Re-list";
$MSG_631 = "Process selected auctions";
$MSG_632 = "Edit auction";
#// ADDED Dec.25, 2001 - Mary
$MSG_633 = "This is the color of the table headers in the main page";
$MSG_634 = "The main header, columns and auction rows, will be this color";

$MSG_635 = "To change your item's picture use the fields below.";
$MSG_636 = "Current picture";
$MSG_637 = "Back to auctions list";
$MSG_638 = "Bids You Have Placed";
$MSG_639 = "Your Bid";

#// ADDED Dec.25, 2001 - Mary
$MSG_640 = "<b>*Note*<b> If Dutch Auction you may not set a reserve price, nor may you set a custom increment amount.";

#// Gianluca Jan. 9, 2002
$MSG_641 = "Dutch auction";
$MSG_642 = "Standard auction";
$MSG_643 = "\n\nThe winning bid amount is:";

#// GIAN - Jan. 19, 2002
$MSG_644 = "To populate the categories tree from scratch, you must first edit
			categories.txt following the instructions contained in <A HREF=\"../docs/CATEGORIES\">docs/CATEGORIES</A>
			and run <A HREF=\"populate_categories.php\">populate_categories.php</A>";

#// Mary - Jan. 25, 2002
$MSG_645 = "Post a question for Seller";
$MSG_646 = "You must be logged in to ask questions to the seller";
$MSG_647 = "Ask";
$MSG_648 = "	Reply to questions";
$MSG_649 = "Answer:";
$MSG_650 = "Question:";
$MSG_651 = "	Ask something to";
$MSG_652 = "Back to Top";
$MSG_653 = "Nickname:";
$MSG_654 = "Date:";




$MSG_900 = "Auction type";
$MSG_901 = "Number of items";
$MSG_902 = "Quantity";
$MSG_903 = "Items quantity";
$MSG_904 = "This auction is closed";
$MSG_905 = "Check out this auction";
$MSG_906 = "Your bid is no longer the winner";
$MSG_907 = "- Winner Information";
$MSG_908 = "- No Winner";
$MSG_909 = "Auction closed - you win!";
$MSG_910 = "No auctions exist for this user.";
$MSG_911 = "closed";
$MSG_912 = "Help Management";
$MSG_913 = "topics found in the database";
$MSG_914 = "Topic";
$MSG_915 = "Text";
$MSG_916 = "Edit help topics";
$MSG_917 = "Add help topic";
$MSG_918 = "Other Help Topics:";
$MSG_919 = "General Help";
?>