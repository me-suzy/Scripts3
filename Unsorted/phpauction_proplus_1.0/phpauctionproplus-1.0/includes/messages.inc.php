<?#//v.1.0.0


#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


// Error messages

$ERR                = ""; // leave this line as is
$ERR_000        = ""; // leave this line as is
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
$ERR_043 = "Incorrect date format.";
$ERR_044 = "Cookies must be enabled to login.";
$ERR_045 = "No closed auctions for this user.";
$ERR_046 = "No active auctions for this user.";
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
$ERR_059 = "Your previous bid for this auction is higher than your current bid.<br>  In Dutch Auctions you may not place a bid if your previous <b>amount of bid times the quantity</b> is greater than your <b>amount of current bid times the quantity</b>.";
$ERR_060 = "The end date is less than or equal to the start date. Change the auction's duration to fix this problem.";


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
$ERR_115 = "E-mail address already used";
$ERR_116 = "No help available on that topic.";
$ERR_117 = "Incorrect date format";

#// ================================================================================
#// GIAN-- Jan. 19, 2002 -- Added for Pro version
$ERR_118 = "countries.txt file not found in the <FONT FACE=Courier>admin</FONT> directory.";
$ERR_119 = "sample error message for preview purposes.<BR>
                        MySQL said: cannot connect to server (localhost)";
$ERR_120 = " is not enough to pay the sign up fee. Please, buy more credits.";
$ERR_121 = "You don't have enough credits to pay the set up fee. Please, <A HREF=buy_credits.php TARGET=_blank>buy more credits</A> and re-submit your auction.";
$ERR_122 = "No auction found";

#// ================================================================================

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
$ERR_700 = "Incorrect date format";
$ERR_701 = "Invalid quantity (must be >0).";
$ERR_702 = "Current Bid must be greater then minimum bid.";

//ADDED Feb.14, 2002 MARY LACEY
$ERR_703 = "<br>You may not leave feedback about this user! <br>You are not a winner/seller in this  closed auction!";
$ERR_704 = "<br>You may not leave feedback about this user! <br>This auction is not closed!";
$ERR_705 = "You may only leave feedback, if you have a closed transaction with this user!";



#// GIAN-- Added on 03/07/2002 for ProPlus @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
$ERR_706 = "<I>Max. number of pictures</I> must be numeric.";
$ERR_707 = "<I>Max picture size</I> cannot be zero.";
$ERR_708 = "<I>Max picture size</I> must be numeric.";
$ERR_709 = "The picture you uploaded is too big. It cannot exceed ";
$ERR_710 = "Wrong file type. Allowd types are: GIF, PNG and JPEG";
#//Added 08.03.02 Simokas
$ERR_711 = "You cannot buy, you are the seller!";

$ERR_712 = "<B>Buy It Now</B> is not available for this auction";

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
$MSG_069 = "Auctions Duration";
$MSG_070 = "Use the checkbox Delete and the button DELETE to delete lines. Use the last line to add a new payment condition. Simplemente edita los campos de texto y pulsa Actualizar para guardar los cambios.";
$MSG_071 = "Update";
$MSG_072 = "Delete";
$MSG_073 = "Lines delete";
$MSG_074 = "Use the checkbox Delete and the button DELETE to delete lines. Simply edit the text fields and press UPDATE to save the changes.";
$MSG_075 = "Payment Methods";
$MSG_076 = "Currency Symbol";
$MSG_077 = "Edit admin e-mail address";
$MSG_078 = "Categories Table";
$MSG_079 = "Payment Methods";
$MSG_080 = "Auctions Duration";
$MSG_081 = "Countries Table";
$MSG_082 = "Categories Table";
$MSG_083 = "Countries Table";
$MSG_084 = "";
$MSG_085 = "";
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
$MSG_112 = "Auction ends";
$MSG_113 = "Auction ID";
$MSG_114 = "No picture available";
$MSG_115 = "Bid now!<BR>It's FREE";
$MSG_116 = "Current bid";
$MSG_117 = "Higher bidder";
$MSG_118 = "Ends within";
$MSG_119 = "# of bids";
$MSG_120 = "Bid increment";
$MSG_121 = "Place Your Maximum Bid Here";
$MSG_122 = "Edit, delete or add auctions durations using the form below";
$MSG_123 = "Durations table updated";
$MSG_124 = "Minimum bid";
$MSG_125 = "Seller";
$MSG_126 = " days, ";
$MSG_127 = "Starting bid";
$MSG_128 = "Bid Increments";
$MSG_129 = "ID";
$MSG_130 = "Description";
$MSG_131 = "Days";
$MSG_132 = "";
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
$MSG_154 = "You Are Bidding on:";
$MSG_155 = "Item:";
$MSG_156 = "Your bid:";
$MSG_157 = "";
$MSG_158 = "Submit my bid";
$MSG_159 = "Your bid has been registered";
$MSG_159 = "Bidder:";
$MSG_160 = "Increments table updated";
$MSG_161 = "Edit, delete or add categories using the form below.<BR>[<A HREF=\"javascript:window_open('categorieshelp.php','incre',400,300,30,30)\" CLASS=\"links\">Read more</A>]";
$MSG_162 = "";
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
$MSG_207 = "<b>Leave Feedback</b>";
$MSG_208 = "<b>View Feedback</b>";
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
$MSG_222 = "User Feedback";
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

#// ================================================================================
#// GIAN-- Jan. 19, 2002 -- Added for Pro version
$MSG_344 = "Time Settings";
$MSG_345 = "If you want to adjust your server time to accurately show your local time, choose the correction (+ or -) amount from your server time that you want to apply.<BR>
                        All the time functions in the program will apply the chosen adjustment.";
$MSG_346 = "Time Adjustment";
$MSG_347 = "Time settings updated";
$MSG_348 = "Batch Procedures Settings";
$MSG_349 = "STATISTICS INFORMATION";
$MSG_350 = "Registered Users";
$MSG_351 = "Active Users";
$MSG_352 = "Inactive Users";
$MSG_353 = "Active Auctions";
$MSG_354 = "Closed Auctions";
$MSG_355 = "# Bids";
$MSG_356 = "Transactions";
$MSG_357 = "Total Amount";
$MSG_358 = "since";
$MSG_359 = "RESET COUNTERS";
$MSG_360 = "Transactions";
$MSG_361 = "Users and Auction";
$MSG_362 = "Values since ";
$MSG_363 = "Dates Format";
$MSG_364 = "Fees";
$MSG_365 = "ADMIN USERS";
$MSG_366 = "REGISTERED USERS";
$MSG_367 = "New admin user";
$MSG_368 = "CATEGORIES";
$MSG_369 = "Create New Categories Tree";
$MSG_370 = "OTHER TABLES";
$MSG_371 = "Phpauction needs to periodically run cron.php to close expired auctions and
                        send noification e-mails to the seller and/or the winner.
                        The best way to run cron.php is to set up a <A HREF=\"http://www.aota.net/Script_Installation_Tips/cronhelp.php4\" TARGET=_blank>cronjob</A> if you
                        run a Unix/Linux server.<BR>
                        If for any reason you can't run a cronjob on your server, you can choose the <B>Non-batch</B> option below
                        to have cron.php run by Phpauction.";
$MSG_372 = "Run cron";
$MSG_373 = "Batch";
$MSG_374 = "Non-batch";
$MSG_375 = "According to the default in Phpauction's Settings, cron.php automatically deletes auctions older than 30 days.
                        <BR>You may change the time period below.";
$MSG_376 = "Delete auctions older than";
$MSG_377 = " days";
$MSG_378 = "Batch settings updated.";
$MSG_379 = "Choose the date format you want to use below.";
$MSG_380 = "USA format";
$MSG_381 = "Non-USA format";
$MSG_382 = "mm/dd/yyyy";
$MSG_383 = "dd/mm/yyyy";
$MSG_384 = "Date format updated.";
$MSG_385 = "SELLERS' FEES";
$MSG_386 = "AUCTIONS";
$MSG_387 = "Sellers' Setup Fee";
$MSG_388 = "Sellers' Final Value Fee";
$MSG_389 = "BUYERS' FEE";
$MSG_390 = "You can charge sellers a setup fee. It can be a fixed fee or a percentage
                    of the initial price.
                    <BR>PHPauction supports <A HREF=http://www.paypal.com TARGET=_blank>PayPal</A> payment gateway.";
$MSG_391 = "Activate Setup Fee?";
$MSG_392 = "Fee value";
$MSG_393 = "% of the initial price";
$MSG_394 = "Sellers setup fee settings updated";
$MSG_395 = "You can charge sellers a Final Value Fee. It can be a fixed fee or a percentage
                    of the final price.
                    <BR>PHPauction supports <A HREF=http://www.paypal.com TARGET=_blank>PayPal</A> payment gateway.";
$MSG_396 = "Activate Final Value Fee?";
$MSG_397 = "Seller Final Value Fee settings updated";
$MSG_398 = "PayPal E-mail Address";
$MSG_399 = "If you are going to set up the options to charge sellers and/or buyers, please provide your <A HREF=http://www.paypal.com/ TARGET=_blank>PayPal</A>.
                        <BR>[<A HREF=\"javascript:window_open('paypalhelp.php','incre',400,500,30,30)\">Read more</A>]";
$MSG_400 = "E-mail address";
$MSG_401 = "PayPal e-mail address updated.";
$MSG_402 = "Buyers Final Value Fee";
$MSG_403 = "You can charge buyers a Final Value Fee. It can be a fixed fee or a percentage
                    of the final price.
                    <BR>PHPauction supports <A HREF=http://www.paypal.com TARGET=_blank>PayPal</A> payment gateway.";
$MSG_404 = "Signup Fee settings updated";
$MSG_405 = "If you edited categories.txt click on the <FONT FACE=Courier>Start&nbsp;&gt;&gt;</FONT> button below";
$MSG_406 = "Start&nbsp;&gt;&gt;";
$MSG_407 = "A new categories tree has been created according to the content of your <A HREF=categories.txt>categories.txt</A> file.<BR><BR>
			You can make changes to your directories tree accessing the <A HREF=categories.php>Categories Administration Module</A>";
$MSG_408 = "Are you sure you want to reset the counters?";
$MSG_409 = "Error Handling";
$MSG_410 = "Fatal errors that occur during Phpauction's execution (typically MySQL errors) will redirect users to an error page.
                        You can customize the error message you want to appear in the error page below.<BR>
                        NOTE: HTML tags are allowed.";
$MSG_411 = "Error Text";
$MSG_412 = "Error E-mail Address";
$MSG_413 = "Error Handling settings updated.";
$MSG_414 = "Preview Error Page";
$MSG_415 = "Error";
$MSG_416 = "Please report the above error message to:";
$MSG_417 = "Sign up Fee";
$MSG_418 = "You can charge users a Sign Up Fee. It is a fixed amount expressed in the currency you selected in the <A HREF=\"currency.php\">Currency Settings</A> page.
                    <BR>Phpauction supports <A HREF=http://www.paypal.com TARGET=_blank>PayPal</A> payment gateway.";
$MSG_419 = "Activate Sign Up Fee?";
$MSG_420 = "Thanks for registering at&nbsp;";
$MSG_421 = "To complete the sign-up process you should now pay the registration fee of&nbsp;";
$MSG_422 = "Please, click on the <B>Buy Credits!</B> button below to add credits to your credit account and confirm your registration.<BR>
                        You will be automatically charged the sign up fee.";
$MSG_423 = "Thanks for setting up an auction at&nbsp;";
$MSG_424 = "To make your auction visible to all the users and accept bids, you should now pay the set up fee of&nbsp;";
$MSG_425 = "<B>NOTE: You will be charged a ";
$MSG_426 = " % of the starting price of your auction</B>";
$MSG_427 = "<B>NOTE: You will be charged a fixed fee of";
$MSG_428 = " to set up your auction.";
$MSG_429 = "There were no bids or reserve price was not met";
$MSG_430 = "Your Credits Account";
$MSG_431 = "Buy Credits";
$MSG_432 = "Date";
$MSG_433 = "Withdrawals";
$MSG_434 = "Deposits";
$MSG_435 = "Total Balance";
$MSG_436 = "Currency:&nbsp;";
$MSG_437 = "Account Balance: ";
$MSG_438 = "To add credits to your account please, fill in the desired amount below and click on the <B>Buy Credits</B> button.";
$MSG_440 = "Amount";
$MSG_441 = "Buy Credits";
$MSG_442 = "If you are sure you want to add&nbsp;";
$MSG_443 = "&nbsp;to your account, click on the Paypal logo below.";
$MSG_444 = "Your payment has been successfully processed.<BR>
                        Your <A HREF=\"credits_account.php\">credit account</A> has been updated to include the amount you just submitted.";
$MSG_445 = "You cancelled your Payment. You have not been charged and no credits have been added to <A HREF=\"credits_account.php\">your account</A>";
$MSG_446 = "Credits purchased";
$MSG_447 = "Buy Credits!";
$MSG_448 = "If you reached this page, you or someone claiming to be you, signed up at this site.
                        <br>To confirm your registration, you must first pay the sign up fee.
                        <BR>Please click on the <B>Buy Credits!</B> button below to add credits to your account.";
$MSG_449 = "Sign Up Fee";
$MSG_450 = "Auction Setup Fee";
$MSG_451 = "Your account has been charged&nbsp;";
$MSG_452 = "Auction Final Value Fee";
$MSG_453 = "Winners Contact Info";
$MSG_454 = "Sellers Contact Info";
$MSG_455 = "Winner's Nick";
$MSG_456 = "Winner's E-mail";
$MSG_457 = "Winner's Bid";
$MSG_458 = "Auction:&nbsp;";
$MSG_459 = "Seller's Nick";
$MSG_460 = "Seller's E-mail";
$MSG_461 = "Your Bid";
$MSG_462 = "Bidfind";
$MSG_463 = "<A HREF=http://www.bidfind.com TARGET=_blank>Bidfind</A> is a search directory of auction items.
                    Bidfind agent needs to find a megalist file to";
$MSG_464 = "Advanced Search";
$MSG_465 = "Advanced Search";
$MSG_466 = "Search!";
$MSG_467 = "(mm/dd/yyyy)";
$MSG_468 = "You can choose bwtween two ways to charge fees to your users:<OL>
			<LI><B>Pay:</B> users will have to pay to your Paypal account everytime a fee is              required
			<LI><B>Prepay:</B> users will have to prepay (buy credits) before they access the services you decided to charge for
			</OL>";
$MSG_469 = "Current setting is:";
$MSG_470 = "% of the final price";

#// ================================================================================
#// Added by Simokas
#// ================================================================================

$MSG_471 = "Auction Watch";
$MSG_472 = "Item Watch";
$MSG_473 = "Enter keyword(s)";
$MSG_474 = "Keyword(s) updated";
$MSG_475 = "Item Watch";

#//Added by Mary 02-16-02
$MSG_476 = "RATING";
$MSG_477 = "<br>You do not have any feedback at this time.<br>";
$MSG_478 = "View Feedback";
//=======
$MSG_479 = "Switch to ";
$MSG_480 = "To complete the sign up process, please proceed to pay the sign fee of ";
$MSG_481 = " by clicking on the Paypal button below. ";
$MSG_482 = "Pay the sign up fee";

$MSG_483 = "Your Account";
$MSG_484 = "Your auction has been properly received.<BR> 
    	    To activate it, please proceed to pay the ";
$MSG_485 = " fee by clicking on the Paypal logo below.";

$MSG_486 = " Setup fee for auction: ";
$MSG_487 = "Amount";
$MSG_488 = "Auction Setup Fee Confirmation";
$MSG_489 = "Your fee is still due for this auction";
$MSG_490 = "Seller's fee is still due for this auction";
$MSG_491 = "Buyer's's fee is still due for this auction";
$MSG_492 = "PAY FEE";
$MSG_493 = "Fee amount: ";
$MSG_494 = "Please proceed to pay the fee of ";
$MSG_495 = "Auction Final Value Fee for ";
#// Added by Simokas 08.03.02
$MSG_496 = "Buy Now";
$MSG_497 = "Buy Now Price";
$MSG_498 = "Item purchased successfully<br>";

$MSG_500 = "More";
$MSG_501 = "Home";
$MSG_502 = "Number of feedbacks";
$MSG_503 = "Feedback";
$MSG_504 = "COMMENT";
$MSG_505 = "Back to user's profile";
$MSG_506 = "sent feedback on: ";
$MSG_507 = "Hide history";
$MSG_508 = "[user e-mail]";
$MSG_509 = "User's data";
$MSG_510 = "Your data has been updated";
$MSG_511 = "Edit user";
$MSG_512 = "Edit auction";
$MSG_513 = "Suggest a category";
$MSG_514 = "Reserve price has not been met";
$MSG_515 = "Reserve price has been reached";
$MSG_516 = "News Management";
$MSG_517 = " news found in the database";
$MSG_518 = "Add new";
$MSG_519 = "Title";
$MSG_520 = "Content";
$MSG_521 = "Activate";
$MSG_522 = "Date";
$MSG_523 = "Note: Cookies must be enabled to login.";
$MSG_524 = "SETTINGS";
$MSG_525 = "Admin users management";
$MSG_526 = "General Settings";
$MSG_527 = "Site name";
$MSG_528 = "Site URL";
$MSG_529 = "Edit the settings below according to your Phpauction PRO's installation.";
$MSG_530 = "Save changes";
$MSG_531 = "Your logo";
$MSG_532 = "Display login box?";
$MSG_533 = "Display news box?";
$MSG_534 = "Show acceptance text?";
$MSG_535 = "Your site's name will appear in the e-mail messages Phpauction PRO sends to users";
$MSG_536 = "This must be the complete URL (starting with <B>http://</B>) of your Phpauction PRO's installations.<BR>
                        Be sure to include the ending slash.";
$MSG_537 = "Select <B>Yes</B> if you want the users login box to be displayed in the Home page. Otherwise select <B>No</B>";
$MSG_538 = "Select <B>Yes</B> if you want the news box to be displayed in the Home page. Otherwise select <B>No</B>";
$MSG_539 = "Selecting the <B>Yes</B> option below will make Phpauction PRO display the text you fill in the text box below in the users registration page just before the submit buttom.<BR>
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
$MSG_552 = "Currency Symbol";
$MSG_553 = "Currency settings updated";
$MSG_554 = "Number of news you want to show";
$MSG_555 = "Fonts and Colors";
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
$MSG_577 = "This is the standard font used to display all the site's text.<BR>
                        If you want to have a different colors set, edit includes/fontcolor.inc.php";
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
$MSG_599 = "Banners Management";
$MSG_600 = "Banners settings updated";
$MSG_601 = "Access PhpAdsNew administration back-end.";
$MSG_602 = "Upload a new logo (max. 50 Kbytes)";
$MSG_603 = "Activate Newsletter?";
$MSG_604 = "If you activate this option, users will be able to subscribe your newsletter from the registration page.<BR>
                        The \"Newsletter management\" will let you send e-mail messages to the subscribed users";
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
$MSG_633 = "This is the color of the table headers in the main page";
$MSG_634 = "The main header, columns and auction rows, will be this color";
$MSG_635 = "To change your item's picture use the fields below.";
$MSG_636 = "Current picture";
$MSG_637 = "Back to auctions list";
$MSG_638 = "Bids You Have Placed";
$MSG_639 = "Your Bid";
$MSG_640 = "<b>*Note*<b> If Dutch Auction you may not set a reserve price, nor may you set a custom increment amount.";
$MSG_641 = "Dutch auction";
$MSG_642 = "Standard auction";
$MSG_643 = "\n\nThe winning bid amount is:";
$MSG_644 = "To populate the categories tree from scratch, you must first edit
                        <A HREF=categories.txt>categories.txt</A> you find in the \"admin\" directory following the instructions contained in <A HREF=\"../docs/CATEGORIES\">docs/CATEGORIES</A>.";

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

#// GIAN march 2, 2002
$MSG_655 = "Updates and Upgrades";
$MSG_656 = "Welcome to Phpauction Pro Updates and Upgrades management system.
			<BR>This modules will connect over the internet to phpauction's upgrades database
			and check your package installation to suggest you updates and upgrades.
			<BR><BR>
			We suggest to run this tool periodically to be sure to mantain your Phpauction Pro
			installation up to date.";
$MSG_657 = "Check updates database";
$MSG_658 = "No new files or upgrades found.";
$MSG_659 = "Please, select below the file you want to upgrade or install.";
$MSG_660 = "The script is not present in your Phpauction Pro installation.";
$MSG_661 = "An older version is present in your Phpauction Pro installation.";
$MSG_662 = "Process selected files";


#// GIAN-- 03/07/2002 addec for Pro Plus
$MSG_663 = "Picture Gallery";
$MSG_664 = "If you activate this option, sellers will be able to upload additional pictures 
            up to the maximum you specifiy (see below).<BR>
            Remember you can set up a fee for the additional pictures sellers upload: 
            see the <A HREF=admin.php?S=fees>fees section</A>.";
$MSG_665 = "Activate Picture Gallery?";
$MSG_666 = "Max. Number of pictures";
$MSG_667 = "Picture Gallery settings updated";
$MSG_668 = "Picture Gallery fee";
$MSG_669 = "You can charge sellers for the additional pictures they will upload or leave this service free.";
$MSG_670 = "Activate Picture Gallery fee?";
$MSG_671 = "Max. pictures size";
$MSG_672 = "Kbytes";
$MSG_673 = "You can upload up to ";
$MSG_674 = "pictures.";
$MSG_675 = "You will be charged ";
$MSG_676 = "for each picture you upload.";
$MSG_677 = "Upload Pictures";
$MSG_678 = "Close";
$MSG_679 = "Please, follow the steps below.";
$MSG_680 = "Select the file to upload";
$MSG_681 = "Upload file";
$MSG_682 = "Repeat steps 1. and 2. for each picture. When finished click on the <I>Create Gallery</I> button below.";
$MSG_683 = "&gt;&gt;&gt; Create Gallery &lt;&lt;&lt;";
$MSG_684 = "Filename";
$MSG_685 = "Size (bytes)";
$MSG_686 = "Delete";
$MSG_687 = "Uploaded Files";
$MSG_688 = "You already uploaded ";
$MSG_689 = " files";
$MSG_690 = "SetUp Fee";
$MSG_691 = "Picture Gallery Fee";
$MSG_692 = "Edit Picture Gallery";
$MSG_693 = "&gt;&gt;&gt; Update Gallery &lt;&lt;&lt;";
$MSG_694 = "View Picture Gallery";
$MSG_695 = "Repeat steps 1. and 2. for each picture. When finished click on the <I>Update Gallery</I> button below.";
$MSG_696 = "Pictures Gallery Fee: ";
$MSG_697 = "You can delete and add pictures to your gallery below.
            <BR>Your gallery can contain up to ";
$MSG_698 = "pictures (number of pictures of your original gallery)";
#// MBL-- 03/10/2002 added for Pro Plus Proxy Bidding
$MSG_699 = "Your bid of ";
$MSG_700 = " has been entered. "; 
$MSG_701 = " Your bid was not enough to make you the highest bidder!<br>Would you like to bid again?";
$MSG_702 = " Auto Bidding?";


#// GIAN-- 03/11/2002 For Bulk Upload +++++++++++++++++++++++
$MSG_703 = "Bulk Auctions Upload";
$MSG_704 = "Using this feature, you will be able to upload multiple items .<BR>
						<BR>Once uploaded, the auctions will be created, but not listed(live).  You will be able to review the items from  <A HREF=yourauctions.php>Your Auctions</A> section. After you reviewing your items, you must list them one by one to go live.
						<BR><BR>
						Auctions' data must be stored in a text file with fields separated by TABs following
						<A HREF=\"Javascript:window_open('bulkschema.php','bulkschema',400,500,20,20)\">this schema</A>.
						<BR><BR>You will also need to know the following numeric codes to be able to populate your file with the correct information.";
$MSG_705 = "Bulk Upload File Schema";
$MSG_706 = "Below is the list of fields you can upload for your auctions, along with their format and/or values .<BR>
            <BR>In your file, each auction is represented by a line and the fields must be separated by a TAB character.";
$MSG_707 = "Field";
$MSG_708 = "Value";
$MSG_709 = "A text string up to 255 characters long";
$MSG_710 = "Item Description";
$MSG_711 = "A description text up to 65535 characters long";
$MSG_712 = "The category or subcategory code. Read more <a href=bulkschema.php?title=cat>here</a>.";
$MSG_713 = "Category";
$MSG_714 = "Starting bid";
$MSG_715 = "The amount of the starting bid for your auction.";
$MSG_716 = "Reserve price";
$MSG_717 = "The amount of the reserve price. If you do not want to set a reserve price for your auction set this field to zero.";
$MSG_718 = "Auction Type";
$MSG_719 = "1 means <B>Standard Auction</B><BR>2 means <B>Dutch Auction</B>";
$MSG_720 = "Duration";
$MSG_721 = "Is the duration of your auction.";
$MSG_722 = "Bids increment";
$MSG_723 = "If you want to set a custom increment, put its value here.<BR>If you want your auction to follow the site's increment system, set this field to zero.";
$MSG_724 = "Location";
$MSG_725 = "The location of the item you are going to sell";
$MSG_726 = "Location ZIP";
$MSG_727 = "The ZIP code of the location where the item is you are going to sell";
$MSG_728 = "Shipping Expenses";
$MSG_729 = "1 means <B>Buyers pays shipping expenses</B><BR>2 means <B>Seller pays shipping expenses</B>";
$MSG_730 = "Shipping";
$MSG_731 = "Whether you are going to ship internationally.<BR>
            1 means <B>yes</B><BR>2 means <B>no</B>";
$MSG_732 = "Quantity";
$MSG_733 = "How many items you are going to sell (this is usually 1 for standard auctions, a quantinty value for dutch auctions)";
$MSG_734 = "<A HREF=\"Javascript:window_open('bulkschema.php','bulkschema',400,500,20,20)\">Categories</A>";
$MSG_735 = "<A HREF=\"Javascript:window_open('bulkschema.php','bulkschema',400,500,20,20)\">Categories</A>";
$MSG_734 = "<A HREF=\"Javascript:window_open('bulkschema.php','bulkschema',400,500,20,20)\">Categories</A>";
$MSG_737 = "Uploaded successfully!<br>Now go to <A HREF=yourauctions.php>Your Auctions</a> to edit the uploaded auctions.";
$MSG_738 = "Minimum Price";
$MSG_739 = "The price at which bidding starts";
$MSG_740 = "To get correct category or subcategory id, select all categories from Browse menu. Find wanted category, if you hold your mouse cursor
	over it you can see the id=number. The current number is the (sub)category id, which you need to input into the bulk upload text file.";
$MSG_741 = "Uploaded auctions";

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
$MSG_916 = "Help Topics Management";
$MSG_917 = "Add help topic";
$MSG_918 = "Other Help Topics:";
$MSG_919 = "General Help";

#// Added by Simokas 10.03.2002
$MSG_920 = "Activate Buy Now?";
$MSG_921 = "If you activate this option, users will be able to buy item from the auction right away, if there are
	no bids placed for this item. This option must first be enabled by seller who opens the auction.";
$MSG_922 = "Send e-mail to the seller";

$MSG_1000 = "Search Title";
$MSG_1001 = "Search Title <B>and</B> Description &nbsp; <I>To Find More Items!</I>";
$MSG_1002 = "Search in Categories";
$MSG_1003 = "Price Range";
$MSG_1004 = "Between &nbsp;&nbsp;&nbsp;";
$MSG_1005 = "  and ";
$MSG_1006 = "Payment Choice";
$MSG_1007 = "Seller";
$MSG_1008 = "Located In";
$MSG_1009 = "Ending Within";
$MSG_1010 = "Today";
$MSG_1011 = "Tomorrow";
$MSG_1012 = "in 3 Days";
$MSG_1013 = "in 5 Days";
$MSG_1014 = "Sort by";
$MSG_1015 = "Items Ending First";
$MSG_1016 = "Newly Listed Items First";
$MSG_1017 = "Lowest Prices First";
$MSG_1018 = "Highest Prices First";
$MSG_1019 = "Auction Type";
$MSG_1020 = "Dutch Auction";
$MSG_1021 = "Standard Auction";
#// Mary added on March 12, 2002 for thanks,php and cancel.php
$MSG_1022 = "Your payment completed.  If you should have any problems or questions, please contact ";
$MSG_1023 = "Support";
$MSG_1024 = "Thank you for your business!";
$MSG_1025 = "Your transaction did not complete. You should not incur any charges. If you did not want to cancel your payment, or if you have received this page in error, please contact ";
$MSG_1026 = "Support";
$MSG_1027 = "Thank you for your business!";
?>