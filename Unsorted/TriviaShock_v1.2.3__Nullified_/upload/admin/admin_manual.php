<?php
require("../script_ext.inc");
require("admin_global" . $script_ext);

show_cp_header();
show_section_info("Users Manual", "This manual provides a full explanation of how to use TriviaShock.");

?>
<table width=100%>
<tr><td>
<font size=2 face="verdana,arial,tahoma,helvetica">

<ul type=square>
<li><big><b><a href="#introduction">Introduction</a></b></big></li>
<br><br>

	<ul>
	<li><a href="#overview">Overview</a></li>
	<li><a href="#questions_and_categories">Questions and categories</a></li>
	<li><a href="#how_questions_are_given">How questions are given to the Player</a></li>
	<li><a href="#single_cat_vs_multi">Single category vs. multiple category games</a></li>
	<li><a href="#high_score_lists">High score lists</a></li>
	<li><a href="#game_modes">Game modes</a></li>
	<li><a href="#flash5">The Flash 5 Interface</a></li>
	</ul>

<br>


<li><big><b><a href="#getting_started">Getting Started</a></b></big></li>
<br><br>

	<ul>
	<li><a href="#getting_started_guidelines">Getting Started Guidelines</a>
	<li><a href="#faq">Frequently Asked Questions</a>
	</ul>

<br>

<li><big><b><a href="#control_panel">The Control Panel</a></b></big></li>
<br><br>

<ul>

<li><a href="#options"><b>Options</b></a></li>
<br><br>

	<ul>
	<li><a href="#editing_options">Editing options</a></li>
	</ul>
	<br>

<li><a href="#question_categories"><b>Question Categories</b></a></li>
<br><br>

	<ul>
	<li><a href="#creating_question_categories">Creating question categories</a></li>
	<li><a href="#browsing_question_categories">Browsing question categories</a></li>
	<li><a href="#importing_question_categories">Importing question categories</a></li>
	</ul>
	<br>

<li><a href="#questions"><b>Questions</b></a></li>
<br><br>
	<ul>
	<li><a href="#creating_questions">Creating questions</a></li>
	<li><a href="#browsing_questions">Browsing questions</a></li>
	</ul>
	<br>

<li><a href="#trivia_games"><b>Trivia Games</b></a></li>
<br><br>

	<ul>
	<li><a href="#creating_trivia_games">Creating trivia games</a></li>
	<li><a href="#browsing_trivia_games">Browsing trivia games</a></li>
	</ul>
	<br>

<li><a href="#game_sections"><b>Game Sections</b></a></li>
<br><br>

	<ul>
	<li><a href="#creating_game_sections">Creating game sections</a></li>
	<li><a href="#browsing_trivia_games">Browsing game sections</a></li>
	</ul>
	<br>

<li><a href="#game_skins"><b>Game Skins</b></a></li>
<br><br>

	<ul>
	<li><a href="#creating_game_skins">Creating game skins</a></li>
	<li><a href="#uploading_game_skins">Uploading game skins</a></li>
	<li><a href="#browsing_game_skins">Browsing game skins</a></li>
	</ul>
	<br>

<li><a href="#templates"><b>Templates</b></a></li>
<br><br>

	<ul>
	<li><a href="#browsing_templates">Browsing templates</a></li>
	<li><a href="#editing_templates">Editing templates</a></li>
	<li><a href="#headers_footers">Including your own page headers and footers</a></li>
	<li><a href="#editing_template_variables">Editing template variables</a></li>
	</ul>
	<br>

</ul>
	
</ul>

<br><br>

<a name="introduction">

<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td bgcolor="<?php echo $CP_HVARS[TABLE_BORDER_COLOR]; ?>">
<table cellpadding=5 cellspacing=<?php echo $CP_HVARS[TABLE_BORDER_SIZE];?> width=100%>
<tr><td bgcolor="<?php echo $CP_HVARS[TABLE_HEADER_COLOR];?>" align=center>
<font size=4 face="verdana,arial,tahoma,helvetica" color="<?php echo $CP_HVARS[TABLE_HEADER_TEXT_COLOR];?>">
<b>Introduction</b>
</td></tr></table>
</td></tr></table>

<a name="overview">
<h2>Overview</h2>

TriviaShock provides a complete solution for interactive trivia games/quizzes whether your goal is to create a single game
or many different games about many different subjects.

<Br><br>

Each trivia game/quiz you create has its own set of options completely independent of other games. For instance, you can set
the number of seconds a player has to answer a question, the number of questions they'll receive each game session, etc. Each
of these options only applies to the specific game you set them for (if you had 3 trivia games each could have their own
time limit per question, number of questions per session, etc.).

<br><br>

While a user plays a game, the game will keep up with the player's score. When a player answers a question correctly, 
a certain number of points (which you define) will be added to their score. You can also specify a certain number of points
to subtract from their score if they answer a question incorrectly (or run out of time on a question). The final scores of
all game sessions are saved to the database and used to determine the high score list.

<br><br><hr size=1 noshade color="#000000">

<a name="questions_and_categories">
<h2>Questions and Categories</h2>

TriviaShock, unlike other systems, is designed to make it as easy as possible to maintain multiple trivia games, while not
making it any more complicated if you only intend to create a single trivia game on your site.

<br><br>

In the past, other trivia/quiz systems typically had you create a trivia game, and then add the questions for that game
directly to the game. For many purposes this worked well, but did not allow for much (if any) re-use of questions.

<br><br>

TriviaShock adds a unique way to manage your questions. Separate from trivia games, you can create individual question
"categories" with which to organize your questions. Creating a category and adding questions to it is the first step. After
you have created the question category (or categories) you would like, you then create a trivia game and select the categories
of questions that this game will use. You can re-use categories in different games, and select any number of categories to use
in each game (one or more). Thus, you could have several specific categories containing questions about those topics, then create
several trivia games (each one using one category), and another trivia game that uses all of the categories at once (allowing
for a "general" trivia game).

<br><br>

To further explain this concept, consider the following comparison. Your goal is to create four different trivia games, one
about baseball, one about football, one about basketball, and one "general sports" game containing questions about all three
of those sports.

<br><br>

<b>The old way (using other software):</b>

<ol>
<li>Create a Baseball trivia game and add baseball questions to it</li>
<li>Create a Football trivia game and add football questions to it</li>
<li>Create a Basketball trivia game  and add basketball questions to it</li>
<li>Create a Sports trivia game and add baseball, football, and basketball questions to it.</li>
</ol>

<br><br>

The problem with this method is that when you create the fourth game, there is no way to re-use the questions from other games since
they are part of the game itself, not separate. You must add new baseball, football, and basketball questions to the
Sports trivia game.

<br><br>

<b>The new way (using TriviaShock)</b>

<br><br>

<ol>
<li>Create a Baseball category and add baseball questions to it</li>
<li>Create a Football category and add football questions to it</li>
<li>Create a Basketball category and add basketball questions to it</li>
<li>Create a Baseball trivia game and select the baseball category to use in that game</li>
<li>Create a Football trivia game and select the football category to use in that game</li>
<li>Create a Basketball trivia game and select the basketball category to use in that game</li>
<li>Create a Sports trivia game and select the baseball, football, and basketball categories to use in that game</li>
</ol>

<br><br>

Now, don't be fooled by there being more steps - this system is actually much quicker. When the three sports categories were
created, all we had to do was create a trivia game for each one and select the respective category of questions. But the real example
of the TriviaShock system comes in when the Sports trivia game is created. Unlike the old method, where new questions would
have to be added to the sports trivia game, all we had to was select the three sports categories we already created for the other
games, and the game automatically selects questions (randomly) from all three of those categories! In addition, as time goes on if 
we add additional questions to a category such as the baseball category, not only will these questions be available to the baseball
trivia game but to the Sports trivia game as well - since they both use the same category.

<br><br>

In addition to the benefits described above, the question category system would theoretically allow you to use the same questions
in more than one trivia game. Since each trivia game has its own options (time limit per question, number of questions, points,
etc.), you could create, for example, two different games using the baseball category. Perhaps one could be an easy game, giving
only 5 questions and 30 seconds to answer each question. The second could be a game for experts, giving 10 questions, only 10
seconds to answer each question, and huge point penalties for answering a question incorrectly.

<br><br>

Finally, this system allows for easy portability of questions. Since question categories are created separately from games, this
allows them to be exported and downloaded right out of your database. You can use this feature to back up your questions or
even trade question categories with other users. A feature on the control panel allows you to easily import new question categories
into the database, and from there you can use them in your trivia games as you would with any other question category.

<br><br><hr size=1 noshade color="#000000">

<a name="how_questions_are_given">
<h2>How Questions are given to the Player</h2>

TriviaShock features a unique system of randomizing questions given to the player. With many older systems, the trivia game/quiz
consisted of only the set of questions you added to it. With TriviaShock, you can select categories with as many questions in them
as you like, and then specify the number of questions to give to the player each session. For example, let us say you create
a "Sports" trivia game using three different question categories, each containing 100 questions (300 total). Next, you set the questions
per session to 10. When the game is played, the player will be given 10 questions randomly selected from the 300 total available in
the game. There could be many different combinations, allowing the player to enjoy the game again and again.

<br><br>

The questions are chosen completely at random, regardless of what category they are in. For example, in the Sports Trivia game, the
first question given could be from the baseball, the second from basketball, the third from baseball again, the fourth from football,
and so on. It is completely randomized, the questions from each category used by the game are treated as one large pool of questions
which the system chooses from.

<br><br><hr size=1 noshade color="#000000">

<a name="single_cat_vs_multi">
<h2>Single Category Vs. Multiple Category Games</h2>

Because TriviaShock separates the games from the actual questions, the system must make a distinction between trivia games you
create that contain only one category (such as the baseball, football, and basketball example games), and games that contain
multiple categories (the sports trivia game, which contains baseball, football, and basketball question categories).


	<blockquote>
	<b>Single Category Games</b>
	
		<blockquote>
		If a game contains only one question category, the system assumes that the question category you used in that
		game covered the same topic as the game itself (i.e. If you created  Baseball game and included a baseball
		question category). Nothing special happens in this case, the player is simply given the questions as usual
		during the game.
		</blockquote>

	<b>Multiple Category Games</b>

		<blockquote>	
		If a game contains more than one question category, such as the sports trivia example which contained three,
		it is considered a multiple category game. Since the questions are randomized, the first question given to the
		player could be from the baseball category, the second from the basketball category, the third from the
		baseball category again, the fourth from football, etc. Because the game will jump around from category to
		category, in order to avoid confusing the player, the game will automatically tell the player what category
		the next question they will receive is from. This way, they will have an idea of what kind of question is coming
		up next. In addition, you avoid the need to phrase your questions in such a way that each one re-iterates
		what the question is about. Example, you would not have to word each baseball question like
		"In Baseball, ....", because when the game was played it would automatically say "Category: Baseball" before and 
		during each baseball question given to the player.
		</blockquote>
	
	</blockquote>
	
Nothing special has to be done when creating the trivia game to distinguish it between a single and a multiple category game.
If you select more than one question category, the software will automatically know it is a multiple category game, and
the name of the following question category will be shown before and during each question.

<br><br><hr size=1 noshade color="#000000">

<a name="high_score_lists">
<h2>High Score Lists</h2>

Each trivia game also has its own high scores list. This list shows the top x number of players, ranked in order by their score 
(from greatest to least). When you create a trivia game you will be given an option to define how many scores to show on the high 
scores list (i.e. 10 would keep a top 10 list for that game). Since all final scores are saved to the database at the end of game 
sessions regardless of whether or not it is a high score, you can change the number of high scores to show at any time and the list 
will automatically change.

<br><br><hr size=1 noshade color="#000000">

<a name="game_modes">
<h2>Game Modes</h2>

In order to make maintenance of trivia games simple and efficient, each game has three different modes that it can be in.
You can change the mode that a game is in via the game browser on the control panel. The modes are as follows:

	<blockquote>
	
	<b>Online</b>
	
		<blockquote>
		The game is online and can be played. <b>No changes can be made to the game options or any of the questions in any
		of the categories used by the game, as this could have strange effects on games already in progress.</b>
		</blockquote>
		
	
	<b>Offline</b>
	
		<blockquote>
		The game is not online, and no game sessions can be started. All games start in this mode when first created.
		It is important to remember that if a game is online, and you change the mode to offline while people are in the middle of playing, their game
		sessions will immediately be interrupted and their scores will not count towards the high score list. This could
		frustrate people playing the game, so it is not recommended that you change a game from online to offline while
		people are playing.
		</blockquote>
		
	
	<b>Maintenance</b>
	
		<blockquote>
		This mode is designed to make it easy to make changes to your game without worrying about interrupting
		game sessions. On busy sites where there are people playing often, there may not be a convenient time for you to set
		the game to offline without interrupting players' game sessions. By setting a game to maintenance mode, you will stop any
		players from starting new game sessions, however, any game sessions that were already in progress can finish normally.
		This way, within several minutes (or however
		long a game session takes), there will be no users playing. Once all game sessions are finished, you will be able to make changes to the game options and/or the questions
		used by the game. To allow people to start games again, you should set the mode back to online once you are finished making
		changes.
		</blockquote>
	
	</blockquote>

<br><br>

<br><br><hr size=1 noshade color="#000000">

<a name="flash5">
<h2>The Flash 5 Interface</h2>

All trivia games created with TriviaShock are played through a fully-animated Macromedia Flash 5 interface with sound.
This interface is fully customizable through a <a href="#game_skins">skins</a> system. Several pre-made skins come with
TriviaShock, and if you have Macromedia Flash 5 you can create your own game skins.

<br><br>

<a name="getting_started">

<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td bgcolor="<?php echo $CP_HVARS[TABLE_BORDER_COLOR]; ?>">
<table cellpadding=5 cellspacing=<?php echo $CP_HVARS[TABLE_BORDER_SIZE];?> width=100%>
<tr><td bgcolor="<?php echo $CP_HVARS[TABLE_HEADER_COLOR];?>" align=center>
<font size=4 face="verdana,arial,tahoma,helvetica" color="<?php echo $CP_HVARS[TABLE_HEADER_TEXT_COLOR];?>">
<b>Getting Started</b>
</td></tr></table>
</td></tr></table>

<a name="getting_started_guidelines">

<h2>Getting Started Guidelines</h2>

With all of the features in TriviaShock, many new administrators wonder where to get started 
creating their trivia games. Listed below are guidelines in order to start creating trivia games.  
The steps should be done in the order they are given. For a more detailed explanation of each step, click 
"More Information" under that step.

<ol>

<li>Edit Options</li>

	<blockquote>
	The very first thing you should do is set up the program options. This is where you will enter the
	name and address of your site and the address of your TriviaShock installation. If you only set one
	option, set the <b>Trivia Site URL</b>, as this is required for the game frontend to function properly.

	<br><br>
	<a href="#editing_options">More Information</a>
	</blockquote>

<li>Create Category</li>

	<blockquote>
	Before you can create any trivia games, you must create some questions. Before you can create questions,
	you must create a category to place them in.

	<br><br>
	<a href="#creating_question_categories">More Information</a>
	</blockquote>

<li>Create Questions</li>

	<blockquote>
	Once you have the category (or categories) you want, begin adding questions to them.

	<br><br>
	<a href="#creating_questions">More Information</a>
	</blockquote>

<li>Create Game</li>

	<blockquote>
	Now that you have created the question categories you want, you can set up a trivia game (or more
	than one, if you wish).

	<br><br>
	<a href="#creating_trivia_games">More Information</a>
	</blockquote>

<li>Browse Games</li>

	<blockquote>
	When a new trivia game is created, it is automatically set to <b>offline</b> mode by default. Before
	it can be played, it must be set to <b>online</b> mode. This is done via the game browser. Find your
	game and change the mode to online.

	<br><br>
	<a href="#browsing_trivia_games">More Information</a>
	</blockquote>

<li>Test your Game</li>

	<blockquote>
	Now that your game is online, you can test it by going to your main trivia site. Click "Trivia Site Index"
	on the left, or "Go to your trivia home page" at the bottom of the control panel. Select your game and
	then click "Play Now" to test it. Note: If you are logged in as the administrator when you play, your score
	will not count towards the high scores list for that game.
	</blockquote>

</ol>

<br><br><hr size=1 noshade color="#000000">

<a name="faq">

<h2>Frequently Asked Questions / Troubleshooting</h2>

<h4>I tested my game, but I don't see my score on the high scores list. Why not?</h4>

If you are logged in as the administrator when you play your game, the score will never be counted towards
the high score list. The reason is obvious - you have access to the answers and counting your score would not
be fair to other users.

<h4>I created a game but why can't I see it on my site?</h4>

The game must be in <b>online</b> mode to show up on your site. After you create your game, make sure
you go to the game browser (by select "Browse Games" on the left), and set your game to online. Otherwise,
it cannot be played.

<h4>I tried to play my game but it says "loading" and never loads. What's wrong?</h4>

There could be several things causing this, but the #1 reason is not setting your <b>Trivia Site URL</b> in
the options (you can set the options by clicking "Edit Options" on the left of the control panel). Make sure
you set <b>Trivia Site URL</b> to the correct URL where you have installed TriviaShock (i.e. if your site
is example.com and you installed TriviaShock in /trivia, the <b>Trivia Site URL</b> would be <i>http://example.com/trivia</i>).
Remember not to have a trailing slash (/) in the URL.

<br><br>

If your options are set correctly and the frontend still never loads, there could be a problem with your game skins
directory. Ensure that the directory /swf/skins exists as a subdirectory from where you installed TriviaShock. Also
ensure that the skin file you selected for that particular trivia game is in that directory.

<h4>I tried to play my game and all I got was a blank screen. Why?</h4>

Two things could be causing this. First, ensure that you have Macromedia Flash 5 installed - it is required to play.
It can be downloaded <a href="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">here</a>.
Second, there could be a problem with the Flash frontend file. Make sure that the directory /swf exists as a subdirectory
where you installed TriviaShock, and that the file <b>triviashock.swf</b> exists in that directory.

<h4>How do I customize the Flash 5 interface?</h4>

The Flash 5 interface for TriviaShock is controlled through a <a href="#game_skins">skins</a> system. Changing the
graphics, animations, and sounds is as easy as loading up the game skin source code into Macromedia Flash 5 and editing
the symbols. Each time you create a trivia game, you can specify which game skin you would like that game to use for that
game. <a href="#game_skins">Click here to learn more</a>.

<br><br>

<a name="control_panel">

<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td bgcolor="<?php echo $CP_HVARS[TABLE_BORDER_COLOR]; ?>">
<table cellpadding=5 cellspacing=<?php echo $CP_HVARS[TABLE_BORDER_SIZE];?> width=100%>
<tr><td bgcolor="<?php echo $CP_HVARS[TABLE_HEADER_COLOR];?>" align=center>
<font size=4 face="verdana,arial,tahoma,helvetica" color="<?php echo $CP_HVARS[TABLE_HEADER_TEXT_COLOR];?>">
<b>The Control Panel</b>
</td></tr></table>
</td></tr></table>

<a name="options">
<h1>Options</h1>

<a name="editing_options">
<h2>Editing Options</h2>

The program options can be modified using the edit options script on the control panel. The options control the general configuration
of the software. They are as follows:

	<blockquote>


	<b><big>Home Site Name</big></b><br>
	
		<blockquote>
		This is the name of your web site. It is <i>not</i> the name of your trivia game, which you will set below.
		</blockquote>
		
	<b><big>Home Site URL</big></b><br>
	
		<blockquote>
		This is the web address to your main web site. (i.e. http://www.example.com)
		</blockquote>
		
	<b><big>Trivia Site Name</big></b><br>
	
		<blockquote>
		The name of the trivia section on your web site. For example, if your Home Site Name is "Sports Central", you
		may call your trivia site "Sports Central Trivia", or something similar.
		</blockquote>
		
	<b><big>Trivia Site URL</big></b><br>
	
		<blockquote>
		The URL to where you have this software installed. For example, if you installed it to /trivia on your site,
		the url would be something like http://www.example.com/trivia/
		</blockquote>
		
	<b><big>Contact Link</big></b><br>
	
		<blockquote>
		This is a link (placed at the bottom of each page) for your visitors to contact the webmaster. If you have a 
		web form to fill out to contact the webmaster, you can place the URL of that form here
		(i.e. http://www.example.com/contact_us.html). If you do not have a web form from which they could contact
		you and would simply like to directly link to the webmaster's email address, use this format:
		<b>mailto:webmaster@example.com</b>. The "mailto:" part tells the web browser to open up the user's email
		client with a blank email to the address "webmaster@example.com".
		</blockquote>

	<b><big>Default Time Offset</big></b><br>

		<blockquote>
		The default timezone to use for displaying times and dates on the site (when the user is not logged
		in). Also pre-selects this timezone when a new user is registering.
		</blockquote>

	<b><big>Trivia Online</big></b><br>
	
		<blockquote>
		Whether or not the trivia system is online. If you set this to No, all users will be <i>locked out</i>. However,
		if you are logged in as the administrator you will still be able to browse the site.
		</blockquote>
		
	<b><big>Maximum Users</big></b><br>
	
		<blockquote>
		This is the maximum number of people who can play trivia games at any given time. Any additional players will not
		be allowed to start trivia game sessions.
		</blockquote>

	<b><big>Interrupt Active Game Sessions?</big></b><br>
	
		<blockquote>
		This option specifies whether or not game sessions that are in progress when you set trivia to offline will be
		interrupted and terminated. If you set this option to "yes", and set the Trivia Online option to "no" (turning off
		the trivia system), any players who were currently playing will be shown a message that says their game session
		has been terminated and their game will end. If you set this option to "no" and set Trivia Online to "no", no one
		will be able to start new games but those players already in game sessions will be able to finish them normally.
		</blockquote>
	
	<b><big>Offline Message</big></b><br>
	
		<blockquote>
		This is the message that will be displayed to your users if you set Trivia Online to "no". It should explain
		that the trivia system is offline and possibly the reason it is offline.
		</blockquote>

	<b><big>Disallowed Usernames</big></b><br>
	
		<blockquote>
		This is a list of usernames (each separated by a space) that you do not want users to be able to register under.
		These are not case sensitive. For example, if you list "admin", no user will be able to sign up with the name
		"admin", or "ADMIN", or "aDmIn", etc.
		</blockquote>
		
	<b><big>Disallowed Email Addresses</big></b><br>
	
		<blockquote>
		This is a list of email addresses that you do not allow users to register under (each separated by a space).
		For example, if you list user@example.com, no users will be able to register using that email address.
		</blockquote>
	
	<b><big>Use Email Activation System?</big></b><br>
	
		<blockquote>
		If this option is set to "yes", new registering users will first be sent an email (to the address they signed up
		under), containing a link that will activate their account. Their account will not be active or valid until
		they have clicked this link. This helps prevent users from making multiple accounts very quickly as they will
		need a valid email address for each account. If you set this options to "no", this feature will not be used and
		new accounts are active right when the user signs up.
		</blockquote>
		
	<b><big>Email From</big></b><br>
	
		<blockquote>
		This is the email address that all emails sent from the software will appear to be from. Emails sent from the
		software include the activation email link sent to new users sign ups and the "forgot info" email that includes
		their username and password. This must be in the form of an email address but the address does not necessarily
		have to exist. You may want to have it from a fake user at your domain such as <b>noreply@example.com</b> so that
		users will not be able to reply and send email back to you when these automated emails are sent.
		</blockquote>

	<b><big>Enable GZIP Page Compression?</big></b><br>
	
		<blockquote>
		If this option is set to "yes", all HTML pages outputted by this software will automatically be GZIP compressed.
		This could decrease the size of the pages 5 to 1 in some cases and save bandwidth. GZIP compressed pages are 
		supported by almost all modern web browsers, and the feature is automatically disabled if the browser does not
		support it (thus, even users with very old browsers will be able to view the site, the page just won't be compressed when
		it is sent). This feature requires that PHP 4.0.4 and the ZLIB extension are installed.
		</blockquote>

	<b><big>Send No-cache Headers?</big></b><br>
	
		<blockquote>
		Whether or not to send no-cache headers to the web browser with each web page. If this is enabled, your visitors'
		web browsers will not cache (store a copy of) each web page, and will reload the page each time the user visits
		it. This is useful for pages that update often, since the user will get the latest page each time and not an old,
		cached version which was saved on their computer previously. This, however, could increase bandwidth usage since the
		page must be sent to the user each time it is requested.
		</blockquote>
						
	<b><big>Date Format</big></b><br>
	
		<blockquote>
		The format of the dates shown on the site. This format is identical to the one used by the PHP date() function.
		To learn more, check out the PHP manual entry for the date() function:<br><br>
		
		<a href="http://www.php.net/manual/en/function.date.php" target="_blank">http://www.php.net/manual/en/function.date.php</a>
		</blockquote>
		
	<b><big>Time Format</big></b><br>
	
		<blockquote>
		The format of the times shown on the site. Uses the date() function as mentioned above, but only place time
		related options here.
		</blockquote>

	</blockquote>
	
To change any of these options, simply fill out the form given and click "Save Changes" at the bottom. Remember that each time
you go to edit options, your previously saved options will already be filled in.

<br><br><hr size=1 noshade color="#000000">

<a name="question_categories">
<h1>Question Categories</h1>

A question category is just a group of related questions. It doesn't really Do anything, your users won't be able to "play"
a question category. It is only a way for you to organize your questions.

<br><br><hr size=1 noshade color="#000000">

<a name="creating_question_categories">
<h2>Creating Question Categories</h2>

Creating a question category is the very first thing you will need to do, because all questions must be part of a question
category. After you create a question category you can then add questions to it, and finally create a trivia game that uses
that group of questions.

<br><br>

To create a new question category, select "Create Category" from the list of options on the left. You will 
be presented with a form that has two options. They are as follows:

	<blockquote>
	
	<b><big>Category Name</big></b><br>
	
		<blockquote>
		A unique name for this category. It should describe the questions it contains (i.e. "Baseball" could be
		the name for a category containing baseball related questions). No two categories can have the same name.
		</blockquote>
	<br>
	
	<b><big>Category Description</big></b><br>
	
		<blockquote>
		A short description of the category and the type of questions it contains.
		</blockquote>

	<br>
	
	</blockquote>
	
Once you have filled in these two fields, click "Create Category" at the bottom.

<br><br><hr size=1 noshade color="#000000">

<a name="browsing_question_categories">
<h2>Browsing Question Categories</h2>

The category browser allows you to view categories in the database. It will show a list of the categories as well as their
descriptions and the number of questions currently in that category.

<br><br>

At the top will be several options on how to display the categories in the database. The first dropdown menu allows
you to specify how many categories you want to see per page. The second specifies how to sort the categories, and the
third specifies what order to put them in (ascending or descending, which means least to greatest / alphabetical or
greatest to least / reverse alphabetical). If there are more categories in the database that can be shown on one page,
you will be given links to see the next page of categories near the bottom of the page.

<br><br>

Next to the category name, you will see five links:

<br><br>

	<blockquote>
	
	<b><big>Edit</big></b><br>
	
		<blockquote>
		This allows you to edit the category (change it's name and/or description).
		</blockquote>
		
	<br>
	
	<b><big>Delete</big></b><br>
	
		<blockquote>
		Delete the category and all questions contained in it. If you want to save the questions in a category
		but delete the category itself, you can move the questions to another category using the question browser.
		
		Please note that if a category is currently being used by a game that is in online mode or currently has
		ongoing game sessions, you will not be able to delete the category.
		
		</blockquote>
	
	<br>
	
	<b><big>Add Questions</big></b><br>
	
		<blockquote>
		This option will take you directly to the question editor to add questions to the category.
		</blockquote>
	
	<br>
	
	<b><big>Browse Questions</big></b>
	
		<blockquote>
		Takes you directly to the question browser to view the questions contained in the category.
		</blockquote>

	<br>
			
	<b><big>Export to File</big></b><br>
	
		<blockquote>
		This dumps the entire category along with its questions into a file for you to download. This is useful to
		back up categories of questions or exchange them with friends and other users of the TriviaShock software.
		
		<br><br>
		
		You will be sent a question category file with the name of the file being the name of the category with
		a <b>.tsc</b> extension. (example: baseball.tsc). <b>Note: This could take a few moments if the category
		you are exporting is very large, please be patient. Do not click the reload button on your browser as
		this will only cause it to start over.</b>
		
		<br><br>
		
		You can load question category files into the database using the import category tool on the control panel.
		</blockquote>
		
	</blockquote>
		
<br><br><hr size=1 noshade color="#000000">

<a name="importing_question_categories">		
<h2>Importing Question Categories</h2>

This tool is used to import your question category files (<b>.tsc</b> files) into the database. Question category files
are created using the "Export to File" tool on the category browser. These files contain the category information as well
as every question in that category. They allow for an easy way to back up categories of questions or even share them with
other users of the TriviaShock software.

<br><br>

To load a question category into the database, select "Import Category" from the options on the left of the control panel. You will be taken to a 
form which will allow you to upload the file. To the right of the first blank, there should be a button called "Browse" or
"Choose File" (depending on what web browser you use). Click this button to bring up a file selection window. Select the
question category file from your hard drive (it should end with <b>.tsc</b>). You should see the name of the file in the blank. Now, click
"Import Question Category" to load it into the database. Remember that the larger a question category is, the longer this could
take. Very large categories could take as much as a few minutes to load into the database. If there is already a category in
your database with the same name as the one you are loading into it, the software will automatically rename it (for example if
you already have a "Baseball" category and you try to import a category with the same name, the new category will be renamed
to Baseball_1).

<br><br><hr size=1 noshade color="#000000">

<a name="questions">
<h1>Questions</h1>

<a name="creating_questions">
<h2>Creating Questions</h2>

The question editor is a tool designed to make adding and editing questions as fast and easy as possible.

<br><br>

To add a question to the database, select "Add Question" from the list of options.

<br><br>

You will be shown a form with 3 options. The first is the category to place this question in, which can be selected
from the drop down list shown.

<br><br>

The next two options give you the ability to create 4 different types of questions. The first drop down menu allows you to select the
question type. "Custom" is simply a question with a blank set of answer choices. "All of the Above" automatically creates a question
with that as the last answer choice, and same for "None of the Above". The second drop down menu allows you to select how many answer
choices the question will have (which you can always change later).

<br><br>

The second option will create a "True/False" question for you. Since True/False questions have exactly 2 answers, you need not select the
number of answers, simply click the "Go" button to the right.

<br><br>

Keep in mind that there is nothing "special" about "all of the above", "none of the above", or "true/false" questions as compared to
custom questions. For example, you could create a custom question with 5 answer choices and enter "All of the Above" as the last answer 
choice manually. Likewise, you could create a custom question with 2 answers and enter "True" and "False" manually.
<br><br>

Once you have selected the question type you would like to create, you will be presented with a new form which is the question editor.
There are 5 different fields to be filled out:

	<blockquote>
	
	<b><big>Category</big></b><br>
	
		<blockquote>		
		The category the question will reside in. This can be changed at any time.
		</blockquote>
		
	<br>
	
	<b><big>Question</big></b><br>
	
		<blockquote>
		The question that will be given to the player. Example: "What is the capital of Iowa?"
		</blockquote>	
	
	<br>
	
	<b><big>Answer Choices</big></b><br>
	
		<blockquote>
		This form allows you to edit the answer choices that will be given for this
		question. There are 5 different options/fields for each answer choice. Each row represents an answer choice.

		
	
			<blockquote>
			
			<b><big>Answer Number</big></b><br>
			
				<blockquote>
				This is not a field but a number given to each answer choice for convenience. These numbers
				will not show up when the question is given in a game.
				</blockquote>
				
			<br>
			
			<b><big>Correct</big></b><br>
			
				<blockquote>
				This field is a radio button between all of the answer choices (meaning only one of them can
				be selected). Whichever answer choice has the radio button checked is considered the correct answer
				choice for the question. Any other answer choice is considered incorrect.
				</blockquote>
				
			<b><big>Answer</big></b><br>
			
				<blockquote>
				The actual text of the answer choice shown to the player.
				</blockquote>
				
			<br>
			
			<b><big>Order</big></b><br>
			
				<blockquote>
				This is a special field that determines the order the answers will appear in.
				
				<br><br>
				
				By default, the answer choices are given in random order when the question is shown
				in a game (meaning what you may enter as the last answer could be the first answer when the question is given
				to a player. The
				order is randomized). However, there are certain types of questions which require answers to be
				in certain locations every time (for example, in an "All of the Above" question, you would want
				the "All of the Above" answer choice to always appear at the bottom, and only the other answer
				choices to appear in random order).
				
				<br><br>
				
				Just place the number of the position you want the answer choice to appear in for answers you
				want to appear in a fixed location. Example: for a question with 5 answer choices, to have an
				answer always appear as the 5th answer choice given, place a 5 next to it under order. Any answer
				choices which have their order field left blank will appear in random order.
				</blockquote>
				
			<br>
			
			<b><big>Delete</big></b><br>
			
				<blockquote>
				You may decide you do not want a certain answer choice or that you want less answer choices for
				a question. To delete an answer choice, check the box under Delete for that answer. You can select
				any number of answer choices to delete, once you are ready, click "Del" at the bottom of the
				Delete column. Also note that the script will remember everything previously entered into the form
				and deleting answer choices <i>will not</i> erase your form.
				</blockquote>
		
			<br>
			
			</blockquote>
			
		Because questions can have anywhere from 2 to 9 answer choices, if your question currently has less than 9 answer
		choices you will see an option below the answer choices that says "Add [x] new answers [Go]". To add more answer
		choices (which will be added to the bottom of the list), select the number to add from the first drop down menu,
		then click "Go". Note: You will NOT lose anything you previously typed into the form, it will remember all of your settings
		when the new answer choices are added.
		
		</blockquote>
	<br>
			
	<b><big>Answer Notes</big></b><br>
	
		<blockquote>
		This field is for entering any notes about the correct answer that you wish to show to the player once the
		question is answered. For example, you may want to explain why it is the correct answer or just give them background
		information about it. 
		
		<br><br>
		
		The information you enter here will only be shown to the player if the question is answered correctly or if you
		have set the trivia game to show them the correct answer if they get it wrong.
		</blockquote>
		
	</blockquote>
	
Once you have filled out each field, click "Add Question" at the bottom to save the question to the database. If there are any
problems with your form, they will be displayed at the top and you will be given a chance to correct them.

<br><br><hr size=1 noshade color="#000000">

<a name="browsing_questions">
<h2>Browsing Questions</h2>

The question browser allows you to view questions that are in the database. Select "Browse Questions" from at the top to browse 
questions in the database.

<br><br>

At the top you will be given a set of options on how to display the questions. The first row, which should say "Show [x] questions sorted by
[x] in [x] order [Go]" allows you to specify which questions to display, in what order, and how many per page. The first drop down is how
many questions to view on each page (if you have many questions in the database it could take multiple pages to show them all), the second is
what field to sort them in (Date created is when you created the question, Category is the name of the category the question belongs to,
difficulty level is the difficulty level of the question). The third drop down determines the order (ascending or descending, which means
least to greatest / alphabetical order or greatest to least / reverse alphabetical order).

<br><br>

The second row allows you to select which categories you would like to view questions from. Selecting "All Categories" (which is the default)
shows questions in all categories. Selecting any one category will only display questions from that category.

<br><br>

The third row allows you to search for a question by the question itself. It accepts wildcards (represented by <b>*</b>), which will match
any characters. Example: <b>*capital*</b> would match the question "What is the capital of Iowa?" as well as the question "What is the capital of
Texas?".

<br><br>

If you change any of the options, simply click one of the three "Go" buttons to refresh the list of questions.

<br><br>

Next is the list of questions. There are four columns. The first one is a checkbox which, when clicked, will check the checkboxes next to all
questions shown (the usage of the checkboxes is explained below). The next column is the question itself, followed by the name of the
category it belongs to.

<br><br>

To edit a question, click the question text to be taken directly to the question editor, where you will be able to make any changes using the
same form you used to create the question.

<br><br>

At the bottom are three operations you can perform on questions you have selected (questions for which you checked the checkbox in the far
left column). Clicking "Go" next to the first option will delete the questions you have selected. The next option allows you to move selected
questions from one category to another - just select the category that you would like to move the questions to and click "Go".

<br><br><hr size=1 noshade color="#000000">

<a name="trivia_games">
<h1>Trivia Games</h1>

Trivia Games are the actual, playable games that your users will be able to participate in. Question categories are not actually playable
games, they are just groups of related questions. To create a game for your users to play, you must create a trivia game.

<br><br><hr size=1 noshade color="#000000">

<a name="creating_trivia_games">
<h2>Creating Trivia Games</h2>

The way in which your question categories relate to your trivia games is that your trivia games will include the questions from whichever 
categories you specify. 

<br><br>

To create a trivia game, simply fill out the form shown, which contains the fields described below:

	<blockquote>
	
	<b><big>Game Name </big></b><br>
	
		<blockquote>
		A short name for the trivia game. For example, a trivia game about Sports could be called "Sports Trivia"
		</blockquote>

	<br>
			
	<b><big>Game Description</big></b><br>
	
		<blockquote>
		A short description for the trivia game. It may describe what type of questions are contained in the game
		and anything else notable about the game.
		</blockquote>
		
	<br>

	<b><big>Game Skin</big></b><br>
	
		<blockquote>
		This is the game skin to use with this game. Game skins are Flash swf files which the main Flash frontend
		loads up to customize the look of the interface. The skins contain all of the graphics and animations which
		will be displayed. Changing the look of the frontend is as easy as changing the skin the game uses.
		
		The skins shown on the drop down menu are a list of swf (Flash) files from the <b>/swf/skins</b> directory.
		</blockquote>
		
	<br>
		
	<b><big>Question Categories</big></b><br>
	
		<blockquote>
		
		This option allows you to choose which questions you want included in the game. Selecting categories for the
		game doesn't divide it into categories, it just gives the game a pool of questions to choose from. This also does
		not determine how many questions long the game will be (you specify that in the next option), it simply tells the
		game which questions to Choose from. A game can have one or more question categories.
		
		<br><br>
		
		For example, you may have created a category of questions called "Baseball". To create a trivia game just containing
		questions about baseball, check the box to the left of category "Baseball".
		
		<br><br>
		
		In another example, let us say that you have created 4 different categories of questions - Baseball, Basketball, Football,
		and Hockey. Your goal would be to create a general sports trivia game, containing questions from all 4 categories.
		This time, you would simply select all 4 of those categories. Each time the game is played, the questions given to the
		player will be selected randomly from the total questions contained in all of those categories combined. Since it is
		randomized, the first question given could be from Baseball, and maybe the second from Football, then Basketball, then
		Baseball again, etc. It would be different every time.
		
		</blockquote>
		
	<br>
		
	<b><big>Questions Per Session</big></b><br>
	
		<blockquote>
		This defines the number of questions a player will receive each time the game is played. This number of questions will
		be randomly selected from the question categories you chose. By having more questions available in the categories than
		you set this number to, their game would most likely contain different questions every time, thus making the
		game more interesting. For example, if you created the sports trivia game used in the example above, and each of
		the four categories had a total of 100 questions, that would equal a total of 400 questions the game would have
		to choose from. If you set Questions Per Session to 10, each time the game was played, 10 questions would be randomly
		selected from the 400 available to the game, which would create many different possibilities!
		</blockquote>
	
	<br>
	
	<b><big>Question Time Limit</big></b><br>
	
		<blockquote>
		The number of seconds a player has to answer each question. When a question is given, a countdown from this number to
		0 will be shown. Once the timer hits 0, the question is automatically counted as incorrect.
		</blockquote>
	
	<br>
	
	<b><big>Correct Points</big></b><br>
	
		<blockquote>
		The number of points added to a player's score if a question is answered correctly.
		</blockquote>
	
	<br>
		
	<b><big>Incorrect Question Penalty</big></b><br>
		
		<blockquote>
		The number of points to subtract from a player's score if a question is answered incorrectly. If a player runs out
		of time on a question, it is also counted as incorrect and this same point penalty will apply.
		</blockquote>
	
	<br>
	
	<b><big>Number of High Scores</big></b><br>
	
		<blockquote>
		The number of high scores to show on the high score list. Example: setting this to 10 will show a "Top 10 High Scores" list.
		This does not determine which scores are saved to the database, as all game scores are saved. This means that you could
		change this number at any time and the high score list would change appropriately.
		</blockquote>
		
	<br>

	<b><big>High Score Period</big></b><br>
	
		<blockquote>
		The number of days to keep high scores before resetting the list. For example, if you set this to 7, the high score list
		for the game will be reset every 7 days. The time which you create the game will be considered to be the last time
		the scores were reset, so using the previous example, the high scores would be reset 7 days after the game was created
		and again each 7 days after that.
		</blockquote>
		
	<br>
		
	<b><big>Show Correct Answer</big></b><br>
	
		<blockquote>
		This option allows you to specify whether or not to reveal the correct answer to a question after a player answers it
		incorrectly. If you set this to Yes, they will still have the question counted incorrectly (and the incorrect question
		penalty will apply), but the correct answer will be shown to them. This allows for your users to learn more by playing
		the game.
		
		
		If set to no, the correct answer will Not be shown to them if they answer incorrectly. This would keep your questions
		"fresh" for longer as players would not learn the answers as quickly, and this would offer more replay in many cases.
		</blockquote>
		
	</blockquote>
		
Once you have filled in all required fields, click "Create New Game" to create the trivia game. If there are any errors in the form,
they will be shown at the top of the page and you will be able to correct them.

<br><br>

Note: When you create a trivia game, it starts in offline mode. This means that it cannot yet be played. You can change the mode of
the game via the game browser.

<br><br><hr size=1 noshade color="#000000">

<a name="browsing_trivia_games">
<h2>Browsing Trivia Games/Sections</h2>

This tool shows a list of games/sections in the database. To view them, select "Browse Games" from the list of options
on the left of the control panel.

<br><br>

Each blue bar shown (if any) represents a game section, not a trivia game. There will be two links next to the name of
the game section:

<br><br>

<blockquote>

	<b><big>Edit</big></b><br>

		<blockquote>
		Edit the name and/or description of the game section.
		</blockquote>

	<br>

	<b><big>Delete</big></b><br>

		<blockquote>
		Deletes the game section.
		</blockquote>

</blockquote>


<br><br>

After those two links, you should see a text input field "Order". This field specifies the order the game sections will
appear in. The game sections (if you have more than one) will be displayed in ascending order by what you enter in this
field. For example, if you place a "1" in one game section, and a "2", in the other, the first game section will be
displayed above the second one on your main page. To update the orders, click "Go" next to "Update Orders" at the bottom
of the page.

<br><br>

Each gray box represents a trivia game. Each trivia game is part of the game section it is listed under. Trivia games not
listed under a game section are not part of any game section.

<br><br>

Next to each trivia game is a text input field called "Order". It works in the same way as the game section order fields,
allowing you to specify what order you want the games to appear in when shown on your main page.

<br><br>

You will see four links to the right of the game name. They are as follows:

	<blockquote>
		
	<b><big>Edit</big></b><br>
	
		<blockquote>
		This option allows you to edit any options related to the game. If you select it you will be shown the same form
		you used to first create the game, except your previously set options will already be filled in.
		
		<br><br>

		Note: You cannot edit a game while it is in online mode or if it is in maintenance mode but games are still in progress:
		To learn more about game modes, <a href="#game_modes">click here</a>.
		</blockquote>
		
	<br>
	
	<b><big>Delete</big></b><br>
	
		<blockquote>	
		Allows you to delete the game. Note that this DOES NOT delete the categories used by it, only the game itself.
		This removes everything else related to the game, such as game logs and the high score list.

		<br><br>

		Note: You cannot delete a game while it is in online mode or if it is in maintenance mode but games are still in progress.
		To learn more about game modes, <a href="#game_modes">click here</a>.
		</blockquote>
		
	<br>
	
	<b><big>Reset Scores</big></b><br>
	
		<blockquote>	
		This option allows you to reset the high score list. It does not delete the game logs, it only disincludes all previous
		game scores from the current top 10.
		</blockquote>
		
	<br>

	<b><big>Reset Game Logs</big></b><br>
	
		<blockquote>
		This completely deletes all previous game logs (records of game sessions). Since
		the game logs also include the scores for each game played, this actually resets the high score list at the same time.
		This will set the number of plays to 0 as well.
		
		<br><br>
		
		Note: You cannot edit a game while it is in online mode or if it is in maintenance mode but games are still in progress.
		To learn more about game modes, <a href="#game_modes">click here</a>.

		</blockquote>

	<br>

	<b><big>Set Game Mode To:</big></b><br>

		<blockquote>
		This option allows you to change the mode of the game, either to online, offline, or maintenance mode.
		To learn more about how game modes work, <a href="#game_modes">click here</a>
		</blockquote>

	</blockquote>
	

	


<br><br><hr size=1 noshade color="#000000">

<a name="game_sections">
<h1>Game Sections</h1>

Game sections allow you to organize your trivia games on the front page of your site. Rather than simply listing all
of your trivia games on one long list, you can specify game sections and place your games under them. For example, if
your site contains trivia games for sports, movies, and tv, you might make a section for each and place the respective
games under those sections. Game sections are not to be confused with question categories - the latter are used to organize
and group your questions together (for easy re-use).

<br><br><hr size=1 noshade color="#000000">

<a name="creating_game_sections">
<h2>Creating Game Sections</h2>

To create a new game section, select "Create Game Section" from the list of options on the left of the control panel.
Specify a unique name for the game section. Optionally, you may specify a description for the game section. Click
"Create Game Section" to finish.

<br><br><hr size=1 noshade color="#000000">

<a name="game_skins">
<h1>Game Skins</h1>

The Flash 5 game interface for TriviaShock (which all trivia games are played through) is fully customizable via a "skins"
system. All of the coding and logic for the interface is separated into a file which you never need to edit. Whenever a
game is played, the graphics, sounds, and animations are loaded from a separate .SWF (Flash) file called a "skin".

<br><br>

Creating or customizing a skin is done by loading up the skin source code (.FLA file) into Macromedia Flash 5 
(The skin source code at the TriviaShock Include in WTN Team Release). 
From there you can control all of the graphics, animations, and sounds. It is up to you how much you want to customize the 
skin - you could change the color scheme to match your site's colors, add artwork/sounds related to a trivia game, or even
completely change the layout and animations. When you are done, publish the file (export it as a .swf file), and upload the 
file using the <a href="#uploading_game_skins">upload game skin</a> tool on the control panel, or FTP the file to your
<b>/swf/skins</b> directory.

<br><br>

When you create a trivia game, simply select the skin you want that game to use from the drop down menu. Each trivia
game you create can use its own skin.

<br><br><hr size=1 noshade color="#000000">

<a name="creating_game_skins">
<h2>Creating Game Skins</h2>

The game skin .FLA source code, and for more resources on creating game skins Include in WTN Team Release

<br><br><hr size=1 noshade color="#000000">

<a name="uploading_game_skins">
<h2>Uploading Game Skins</h2>

To upload a new game skin to your site, click "Upload Game Skin" from the list of options on the left of the
control panel.

<br><br>

Select the game skin file (.swf file) from your computer by pressing the browse/choose file button. Next,
click upload skin to upload it to your site.

<br><br>

Note: You can also upload a game skin by FTP'ing into your site and placing the game skin (.swf) file in your
<b>/swf/skins</b> directory.

<br><br><hr size=1 noshade color="#000000">

<a name="browsing_game_skins">
<h2>Browsing Game Skins</h2>

To view samples of the game skins you have installed, click "Browse Game Skins" from the list of options on
the left of the control panel.

<br><br>

Select the game skin file to view from the drop down menu, and click "View Game Skin". A sample of the game skin
(shown at half size) will be displayed with sample data placed in it. This is not an actual game, just a sample of
what the game skin would look like when used for a trivia game.

<br><br><hr size=1 noshade color="#000000">

<a name="templates">
<h1>Templates</h1>

TriviaShock allows you to easily customize the look and feel of your site via a template system. Every web page generated by the software
(except the ones on the control panel) is controlled by a "template". Templates are simply
HTML pages with "variables" placed in locations where information changes. Each time a page is generated (say, for example, the front
page which lists all trivia games), a fresh template is loaded, and variables (such as the name of the site, the list of trivia games,
and other things that can change) are placed in the appropriate location of the template. The final result is the web page that
is sent to the browser.

<br><br>

Variables are marked in the templates as capitalized text enclosed in curly braces - <b>{ }</b>. Variables are given useful names in order to
make it apparent what information is going to go there. Here is an example of what a template could look like:

<blockquote>
<pre>
&lt;font size=3>&lt;b>{TRIVIA_SITE_NAME}&lt;/b>&lt;/font>

&lt;br>&lt;br>

{CURRENT_TIME}
</pre>
</blockquote>

The variables, {TRIVIA_SITE_NAME} and {CURRENT_TIME} are automatically replaced by the script with what data is to go in them when
a user visits the page. For example, <b>{TRIVIA_SITE_NAME}</b> will be replaced with the name of your trivia site as you set it in
the options. <b>{CURRENT_TIME}</b> will be replaced with whatever the current time of day is.

<br><br>

Since in most cases you will want all of this information placed in the templates, you would leave all of the variables in the templates
when editing them.

<br><br>

The ability to customize the look of your
site comes from the ability to change any of the HTML in the template, which will immediately change how things look. For example,
perhaps you may want the name of your trivia site displayed in big blue text. You would simply edit the appropriate template and
use this code:

<blockquote>
<pre>
&lt;font size=5 color="blue">{TRIVIA_SITE_NAME}&lt;/font>
</pre>
</blockquote>

Let us say for example your trivia site name is "Example.com Trivia Challenge". When a user visits the page, the script will replace
<b>{TRIVIA_SITE_NAME}</b> with <b>Example.com Trivia Challenge</b> in the template. This is what the web browser will see:

<blockquote>
<pre>
&lt;font size=5 color="blue">Example.com Trivia Challenge&lt;/font>
</pre>
</blockquote>

<br><br>

Throughout the templates you will also find variables called <b>template variables</b>. These variables contain basic values for
things like colors, table sizes, border sizes, fonts, etc. By changing style variables, you can quickly change the color scheme
or basic look of the site without even having to edit the templates themselves. Template variables look just like regular variables
in the templates:

<blockquote>
<pre>
&lt;table width="{MAIN_TABLE_SIZE}">
</pre>
</blockquote>

<br>

Since this variable would be substituted in every time the size of the main table was set (in each template), all you would have to do
to instantly change the size of the main table on all pages is change the value of <b>{MAIN_TABLE_SIZE}</b>.

<br><br>

Template variables can be edited via the <A href="#editing_template_variables">template variable editor</a>.

<br><br><hr size=1 noshade color="#000000">

<a name="browsing_templates">
<h2>Browsing Templates</h2>

This script allows you to view the templates used by the software, which determine how the trivia site will look. 
Select "Browse Templates" under the template options to bring up the template editor.

<br><br>

You will be shown a threaded list of templates.  Each template name is listed in large bold text, with a description of the template's
usage in small text under it.

<br><br>

Template names which are indented are sub-templates which make up a small part of the template of the level above them. In most cases
this is used to have repeating portions of some HTML within a template. For example, for the <b>TRIVIA_MAIN_PAGE</b> template, a list of
available trivia games must be displayed. The sub-template <b>GAME_LISTING</b> represents the HTML code displayed for one game. This code
will be repeated for each trivia game you have set up, and then placed within the main template <b>TRIVIA_MAIN_PAGE</b> where
the text <b>{GAME_LIST}</b> appears.

<br><br>

Next to each template you will find two options:

	<blockquote>
		
	<b><big>Edit</big></b><br>
	
		<blockquote>
		Takes you directly to the template editor to change the contents of that template.
		</blockquote>
		
	<br>
	
	<b><big>Restore Default</big></b><br>
	
		<blockquote>
		Restores the template to its default "out-of-the-box" setting. Of course this is only useful if you have
		changed the contents of the template from what they originally were.
		</blockquote>
		
	</blockquote>
	

<br><br><hr size=1 noshade color="#000000">

<a name="editing_templates">
<h2>Editing Templates</h2>

The template editor is the tool used to edit the contents of a template. To get to the template editor, simply click "Edit" next
to whichever template you wish to edit on the <a href="#browsing_templates">template browser</a>.

You will be shown a form with three fields:

	<blockquote>
	
	<b><big>Template Name</big></b><br>
	
		<blockquote>
		The name of the template you are currently editing. This cannot be changed.
		</blockquote>
		
	<br>
		
	<b><big>Description</big></b><br>
	
		<blockquote>
		A description of the template you are currently editing and how it is used.
		</blockquote>
		
	<br>
	
	<b><big>Template</big></b>
	
		<blockquote>
		This is a text box which allows you to edit the contents of the template. When you first load up the template editor,
		the previous contents of the template should already be filled into this text box.
		</blockquote>
		
	</blockquote>
	
Once you have made your changes to the template, click "Save Changes" at the bottom to save your changes to the database. This will
immediately affect any web pages that use this template.

<br><br><hr size=1 noshade color="#000000">

<a name="headers_footers">
<h2>Including your own Headers and Footers</h2>

There may be cases where you would like to include your own HTML above and below the site generated by TriviaShock, perhaps to
keep the look of the rest of your web site. Maybe you have a logo and some navigation buttons that you would like to appear
above your trivia site. This can easily be done by inserting the appropriate PHP code into the HEADER and FOOTER templates.
<br><br>

These two templates are automatically PHP parsed, allowing you to place PHP code in them to be executed. The PHP code must be
between <b>&lt;?php</b> and <b>?></b> tags. Example:<br><br>

<b>&lt;?php echo "Hello"; ?></b>

<br><br>

This would write the text "Hello" wherever you placed that block of code.<br><br>

Including your own headers and footers is as simple as calling the PHP require() function in this format. Simply specify the
server path to the HTML (or PHP) file you would like to include. Example:<br><br>

<b>&lt;?php require("../my_site_header.html"); ?></b>

<br><br>

This would include the HTML file <b>my_site_header.html</b> from one directory higher than where you installed TriviaShock.

<br><br><hr size=1 noshade color="#000000">

<a name="editing_template_variables">
<h2>Editing Template Variables</h2>

Template variables are variables that control the basic look and feel of the templates (simple variables such as colors, fonts,
table sizes, border colors, etc.). They are placed throughout the templates and can be changed using the template variable editor.
For most simple changes to the look of the site (colors, fonts, etc.), editing the template variables is all you will need to do
as they are used in every template.


Editing of the template variables is fairly straightforward. To edit them, select "Edit Template Variables"
under the template options. A list of the template variables will be shown with the name and description on the left
and a field to enter the data on the right. The name of each template variables corresponds to how they will appear in
the templates. For example, the template variable TABLE_CELL1_COLOR is referenced in the templates as:

<br><br>

<b>{TABLE_CELL1_COLOR}</b>

<br><br>

So, wherever you see that variable in a template is where the value you enter will be placed.

To save your changes to the template variables, click the "Save Changes" button at the bottom of the list. To restore the default
values (the values set when you first install the program), click "Restore Defaults".


<br><br><br><br><br><br><br><br><br><br>

</td></tr></table>

<?php show_cp_footer(); ?>