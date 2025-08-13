<?php
/************************************************************************/
/*  Program Name         : QuizShock                                    */
/*  Program Version      : 1.5.4                                        */
/*  Program Author       : Pineapple Technologies                       */
/*  Supplied by          : CyKuH [WTN]                                  */
/*  Nullified by         : CyKuH [WTN]                                  */
/*  Distribution         : via WebForum and Forums File Dumps           */
/*                  (c) WTN Team `2004                                  */
/*   Copyright (c)2002 Pineapple Technologies. All Rights Reserved.     */
/************************************************************************/

require("../script_ext.inc");
require("admin_global" . $script_ext);

show_cp_header();
show_section_info("Users Manual", "This manual provides a full explanation of how to use QuizShock.");

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
	<li><a href="#single_cat_vs_multi">Single category vs. multiple category quizzes</a></li>
	<li><a href="#high_score_lists">High score lists</a></li>
	<li><a href="#quiz_modes">Quiz modes</a></li>
	<li><a href="#flash5">The Flash Interface</a></li>
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

<li><a href="#quizzes"><b>Quizzes</b></a></li>
<br><br>

	<ul>
	<li><a href="#creating_quizzes">Creating quizzes</a></li>
	<li><a href="#browsing_quizzes">Browsing quizzes</a></li>
	</ul>
	<br>

<li><a href="#quiz_sections"><b>Quiz Sections</b></a></li>
<br><br>

	<ul>
	<li><a href="#creating_quiz_sections">Creating quiz sections</a></li>
	<li><a href="#browsing_quizzes">Browsing quiz sections</a></li>
	</ul>
	<br>

<li><a href="#flash_skins"><b>Flash Skins</b></a></li>
<br><br>

	<ul>
	<li><a href="#creating_flash_skins">Creating flash skins</a></li>
	<li><a href="#uploading_flash_skins">Uploading flash skins</a></li>
	<li><a href="#browsing_flash_skins">Browsing flash skins</a></li>
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

QuizShock provides a complete solution for interactive quizzes whether your goal is to create a single quiz
or many different quizzes about many different subjects.

<Br><br>

Each quiz you create has its own set of options completely independent of other quizzes. For instance, you can set
the number of seconds a player has to answer a question, the number of questions they'll receive each quiz session, etc. Each
of these options only applies to the specific quiz you set them for (if you had 3 quizzes each could have their own
time limit per question, number of questions per session, etc.).

<br><br>

While a user takes a quiz, the quiz will keep up with the player's score. When a player answers a question correctly, 
a certain number of points (which you define) will be added to their score. You can also specify a certain number of points
to subtract from their score if they answer a question incorrectly (or run out of time on a question). The final scores of
all quiz sessions are saved to the database and used to determine the high score list.

<br><br><hr size=1 noshade color="#000000">

<a name="questions_and_categories">
<h2>Questions and Categories</h2>

QuizShock, unlike other systems, is designed to make it as easy as possible to maintain multiple quizzes, while not
making it any more complicated if you only intend to create a single quiz on your site.

<br><br>

In the past, other quiz systems typically had you create a quiz, and then add the questions for that quiz
directly to the quiz. For many purposes this worked well, but did not allow for much (if any) re-use of questions.

<br><br>

QuizShock adds a unique way to manage your questions. Separate from quizzes, you can create individual question
"categories" with which to organize your questions. Creating a category and adding questions to it is the first step. After
you have created the question category (or categories) you would like, you then create a quiz and select the categories
of questions that this quiz will use. You can re-use categories in different quizzes, and select any number of categories to use
in each quiz (one or more). Thus, you could have several specific categories containing questions about those topics, then create
several quizzes (each one using one category), and another quiz that uses all of the categories at once (allowing
for a "general" quiz).

<br><br>

To further explain this concept, consider the following comparison. Your goal is to create four different quizzes, one
about baseball, one about football, one about basketball, and one "general sports" quiz containing questions about all three
of those sports.

<br><br>

<b>The old way (using other software):</b>

<ol>
<li>Create a Baseball quiz and add baseball questions to it</li>
<li>Create a Football quiz and add football questions to it</li>
<li>Create a Basketball quiz  and add basketball questions to it</li>
<li>Create a Sports quiz and add baseball, football, and basketball questions to it.</li>
</ol>

<br><br>

The problem with this method is that when you create the fourth quiz, there is no way to re-use the questions from other quizzes since
they are part of the quiz itself, not separate. You must add new baseball, football, and basketball questions to the
Sports quiz.

<br><br>

<b>The new way (using QuizShock)</b>

<br><br>

<ol>
<li>Create a Baseball category and add baseball questions to it</li>
<li>Create a Football category and add football questions to it</li>
<li>Create a Basketball category and add basketball questions to it</li>
<li>Create a Baseball quiz and select the baseball category to use in that quiz</li>
<li>Create a Football quiz and select the football category to use in that quiz</li>
<li>Create a Basketball quiz and select the basketball category to use in that quiz</li>
<li>Create a Sports quiz and select the baseball, football, and basketball categories to use in that quiz</li>
</ol>

<br><br>

Now, don't be fooled by there being more steps - this system is actually much quicker. When the three sports categories were
created, all we had to do was create a quiz for each one and select the respective category of questions. But the real example
of the QuizShock system comes in when the Sports quiz is created. Unlike the old method, where new questions would
have to be added to the sports quiz, all we had to was select the three sports categories we already created for the other
quizzes, and the quiz automatically selects questions (randomly) from all three of those categories! In addition, as time goes on if 
we add additional questions to a category such as the baseball category, not only will these questions be available to the baseball
quiz but to the Sports quiz as well - since they both use the same category.

<br><br>

In addition to the benefits described above, the question category system would theoretically allow you to use the same questions
in more than one quiz. Since each quiz has its own options (time limit per question, number of questions, points,
etc.), you could create, for example, two different quizzes using the baseball category. Perhaps one could be an easy quiz, giving
only 5 questions and 30 seconds to answer each question. The second could be a quiz for experts, giving 10 questions, only 10
seconds to answer each question, and huge point penalties for answering a question incorrectly.

<br><br>

Finally, this system allows for easy portability of questions. Since question categories are created separately from quizzes, this
allows them to be exported and downloaded right out of your database. You can use this feature to back up your questions or
even trade question categories with other users. A feature on the control panel allows you to easily import new question categories
into the database, and from there you can use them in your quizzes as you would with any other question category.

<br><br><hr size=1 noshade color="#000000">

<a name="how_questions_are_given">
<h2>How Questions are given to the Player</h2>

QuizShock features a unique system of randomizing questions given to the player. With many older systems, the quiz/quiz
consisted of only the set of questions you added to it. With QuizShock, you can select categories with as many questions in them
as you like, and then specify the number of questions to give to the player each session. For example, let us say you create
a "Sports" quiz using three different question categories, each containing 100 questions (300 total). Next, you set the questions
per session to 10. When the quiz is taken, the player will be given 10 questions randomly selected from the 300 total available in
the quiz. There could be many different combinations, allowing the player to enjoy the quiz again and again.

<br><br>

The questions are chosen completely at random, regardless of what category they are in. For example, in the Sports quiz, the
first question given could be from the baseball, the second from basketball, the third from baseball again, the fourth from football,
and so on. It is completely randomized, the questions from each category used by the quiz are treated as one large pool of questions
which the system chooses from.

<br><br><hr size=1 noshade color="#000000">

<a name="single_cat_vs_multi">
<h2>Single Category Vs. Multiple Category quizzes</h2>

Because QuizShock separates the quizzes from the actual questions, the system must make a distinction between quizzes you
create that contain only one category (such as the baseball, football, and basketball example quizzes), and quizzes that contain
multiple categories (the sports quiz, which contains baseball, football, and basketball question categories).


	<blockquote>
	<b>Single Category quizzes</b>
	
		<blockquote>
		If a quiz contains only one question category, the system assumes that the question category you used in that
		quiz covered the same topic as the quiz itself (i.e. If you created  Baseball quiz and included a baseball
		question category). Nothing special happens in this case, the player is simply given the questions as usual
		during the quiz.
		</blockquote>

	<b>Multiple Category quizzes</b>

		<blockquote>	
		If a quiz contains more than one question category, such as the sports trivia example which contained three,
		it is considered a multiple category quiz. Since the questions are randomized, the first question given to the
		player could be from the baseball category, the second from the basketball category, the third from the
		baseball category again, the fourth from football, etc. Because the quiz will jump around from category to
		category, in order to avoid confusing the player, the quiz will automatically tell the player what category
		the next question they will receive is from. This way, they will have an idea of what kind of question is coming
		up next. In addition, you avoid the need to phrase your questions in such a way that each one re-iterates
		what the question is about. Example, you would not have to word each baseball question like
		"In Baseball, ....", because when the quiz was taken it would automatically say "Category: Baseball" before and 
		during each baseball question given to the player.
		</blockquote>
	
	</blockquote>
	
Nothing special has to be done when creating the quiz to distinguish it between a single and a multiple category quiz.
If you select more than one question category, the software will automatically know it is a multiple category quiz, and
the name of the following question category will be shown before and during each question.

<br><br><hr size=1 noshade color="#000000">

<a name="high_score_lists">
<h2>High Score Lists</h2>

Each quiz also has its own high scores list. This list shows the top x number of players, ranked in order by their score 
(from greatest to least). When you create a quiz you will be given an option to define how many scores to show on the high 
scores list (i.e. 10 would keep a top 10 list for that quiz). Since all final scores are saved to the database at the end of quiz 
sessions regardless of whether or not it is a high score, you can change the number of high scores to show at any time and the list 
will automatically change.

<br><br><hr size=1 noshade color="#000000">

<a name="game_modes">
<h2>quiz Modes</h2>

In order to make maintenance of quizzes simple and efficient, each quiz has three different modes that it can be in.
You can change the mode that a quiz is in via the quiz browser on the control panel. The modes are as follows:

	<blockquote>
	
	<b>Online</b>
	
		<blockquote>
		The quiz is online and can be taken. <b>No changes can be made to the quiz options or any of the questions in any
		of the categories used by the quiz, as this could have strange effects on quizzes already in progress.</b>
		</blockquote>
		
	
	<b>Offline</b>
	
		<blockquote>
		The quiz is not online, and no quiz sessions can be started. All quizzes start in this mode when first created.
		It is important to remember that if a quiz is online, and you change the mode to offline while people are in the middle of taking, their quiz
		sessions will immediately be interrupted and their scores will not count towards the high score list. This could
		frustrate people taking the quiz, so it is not recommended that you change a quiz from online to offline while
		people are taking.
		</blockquote>
		
	
	<b>Maintenance</b>
	
		<blockquote>
		This mode is designed to make it easy to make changes to your quiz without worrying about interrupting
		quiz sessions. On busy sites where there are people taking often, there may not be a convenient time for you to set
		the quiz to offline without interrupting players' quiz sessions. By setting a quiz to maintenance mode, you will stop any
		players from starting new quiz sessions, however, any quiz sessions that were already in progress can finish normally.
		This way, within several minutes (or however
		long a quiz session takes), there will be no users taking. Once all quiz sessions are finished, you will be able to make changes to the quiz options and/or the questions
		used by the quiz. To allow people to start quizzes again, you should set the mode back to online once you are finished making
		changes.
		</blockquote>
	
	</blockquote>

<br><br>

<br><br><hr size=1 noshade color="#000000">

<a name="flash5">
<h2>The Flash Interface</h2>

All quizzes created with QuizShock are taken through a fully-animated Macromedia Flash interface with sound.
This interface is fully customizable through a <a href="#game_skins">skins</a> system. Several pre-made skins come with
QuizShock, and if you have Macromedia Flash you can create your own flash skins.

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

With all of the features in QuizShock, many new administrators wonder where to get started 
creating their quizzes. Listed below are guidelines in order to start creating quizzes.  
The steps should be done in the order they are given. For a more detailed explanation of each step, click 
"More Information" under that step.

<ol>

<li>Edit Options</li>

	<blockquote>
	The very first thing you should do is set up the program options. This is where you will enter the
	name and address of your site and the address of your QuizShock installation. If you only set one
	option, set the <b>Quiz Site URL</b>, as this is required for the quiz frontend to function properly.

	<br><br>
	<a href="#editing_options">More Information</a>
	</blockquote>

<li>Create Category</li>

	<blockquote>
	Before you can create any quizzes, you must create some questions. Before you can create questions,
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

<li>Create quiz</li>

	<blockquote>
	Now that you have created the question categories you want, you can set up a quiz (or more
	than one, if you wish).

	<br><br>
	<a href="#creating_trivia_games">More Information</a>
	</blockquote>

<li>Browse quizzes</li>

	<blockquote>
	When a new quiz is created, it is automatically set to <b>offline</b> mode by default. Before
	it can be taken, it must be set to <b>online</b> mode. This is done via the quiz browser. Find your
	quiz and change the mode to online.

	<br><br>
	<a href="#browsing_trivia_games">More Information</a>
	</blockquote>

<li>Test your quiz</li>

	<blockquote>
	Now that your quiz is online, you can test it by going to your main trivia site. Click "Trivia Site Index"
	on the left, or "Go to your trivia home page" at the bottom of the control panel. Select your quiz and
	then click "take Now" to test it. Note: If you are logged in as the administrator when you take, your score
	will not count towards the high scores list for that quiz.
	</blockquote>

</ol>

<br><br><hr size=1 noshade color="#000000">

<a name="faq">

<h2>Frequently Asked Questions / Troubleshooting</h2>

<h4>I tested my quiz, but I don't see my score on the high scores list. Why not?</h4>

If you are logged in as the administrator when you take your quiz, the score will never be counted towards
the high score list. The reason is obvious - you have access to the answers and counting your score would not
be fair to other users.

<h4>I created a quiz but why can't I see it on my site?</h4>

The quiz must be in <b>online</b> mode to show up on your site. After you create your quiz, make sure
you go to the quiz browser (by select "Browse quizzes" on the left), and set your quiz to online. Otherwise,
it cannot be taken.

<h4>I tried to take my quiz but it says "loading" and never loads. What's wrong?</h4>

There could be several things causing this, but the #1 reason is not setting your <b>Quiz Site URL</b> in
the options (you can set the options by clicking "Edit Options" on the left of the control panel). Make sure
you set <b>Quiz Site URL</b> to the correct URL where you have installed QuizShock (i.e. if your site
is example.com and you installed QuizShock in /quiz, the <b>Quiz Site URL</b> would be <i>http://example.com/quiz</i>).
Remember not to have a trailing slash (/) in the URL.

<br><br>

If your options are set correctly and the frontend still never loads, there could be a problem with your flash skins
directory. Ensure that the directory /swf/skins exists as a subdirectory from where you installed QuizShock. Also
ensure that the skin file you selected for that particular quiz is in that directory.

<h4>I tried to take my quiz and all I got was a blank screen. Why?</h4>

Two things could be causing this. First, ensure that you have Macromedia Flash installed - it is required to take.
It can be downloaded <a href="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">here</a>.
Second, there could be a problem with the Flash frontend file. Make sure that the directory /swf exists as a subdirectory
where you installed QuizShock, and that the file <b>triviashock.swf</b> exists in that directory.

<h4>How do I customize the Flash interface?</h4>

The Flash interface for QuizShock is controlled through a <a href="#game_skins">skins</a> system. Changing the
graphics, animations, and sounds is as easy as loading up the flash skin source code into Macromedia Flash and editing
the symbols. Each time you create a quiz, you can specify which flash skin you would like to use for that
quiz. <a href="#flash_skins">Click here to learn more</a>.

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
		This is the name of your web site. It is <i>not</i> the name of your quiz, which you will set below.
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
		
	<b><big>Quiz Site URL</big></b><br>
	
		<blockquote>
		The URL to where you have this software installed. For example, if you installed it to /quiz on your site,
		the url would be something like http://www.example.com/quiz/
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

	<b><big>System Online</big></b><br>
	
		<blockquote>
		Whether or not the system is online. If you set this to No, all users will be <i>locked out</i>. However,
		if you are logged in as the administrator you will still be able to browse the site.
		</blockquote>
		
	<b><big>Maximum Users</big></b><br>
	
		<blockquote>
		This is the maximum number of people who can take quizzes at any given time. Any additional players will not
		be allowed to start quiz sessions.
		</blockquote>

	<b><big>Interrupt Active quiz Sessions?</big></b><br>
	
		<blockquote>
		This option specifies whether or not quiz sessions that are in progress when you set trivia to offline will be
		interrupted and terminated. If you set this option to "yes", and set the System Online option to "no" (turning off
		the system), any players who were currently taking will be shown a message that says their quiz session
		has been terminated and their quiz will end. If you set this option to "no" and set System Online to "no", no one
		will be able to start new quizzes but those players already in quiz sessions will be able to finish them normally.
		</blockquote>
	
	<b><big>Offline Message</big></b><br>
	
		<blockquote>
		This is the message that will be displayed to your users if you set System Online to "no". It should explain
		that the system is offline and possibly the reason it is offline.
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

A question category is just a group of related questions. It doesn't really Do anything, your users won't be able to "take"
a question category. It is only a way for you to organize your questions.

<br><br><hr size=1 noshade color="#000000">

<a name="creating_question_categories">
<h2>Creating Question Categories</h2>

Creating a question category is the very first thing you will need to do, because all questions must be part of a question
category. After you create a question category you can then add questions to it, and finally create a quiz that uses
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
		
		Please note that if a category is currently being used by a quiz that is in online mode or currently has
		ongoing quiz sessions, you will not be able to delete the category.
		
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
		back up categories of questions or exchange them with friends and other users of the QuizShock software.
		
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
other users of the QuizShock software.

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
				will not show up when the question is given in a quiz.
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
				in a quiz (meaning what you may enter as the last answer could be the first answer when the question is given
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
		have set the quiz to show them the correct answer if they get it wrong.
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
<h1>Quizzes</h1>

These are the quizzes that your users will be able to participate in. Question categories are not actually playable
quizzes, they are just groups of related questions.

<br><br><hr size=1 noshade color="#000000">

<a name="creating_quizzes">
<h2>Creating Quizzes</h2>

The way in which your question categories relate to your quizzes is that your quizzes will include the questions from whichever 
categories you specify. 

<br><br>

To create a quiz, simply fill out the form shown, which contains the fields described below:

	<blockquote>
	
	<b><big>Quiz Name</big></b><br>
	
		<blockquote>
		A short name for the quiz.
		</blockquote>

	<br>
			
	<b><big>quiz Description</big></b><br>
	
		<blockquote>
		A short description for the quiz. It may describe what type of questions are contained in the quiz
		and anything else notable about the quiz.
		</blockquote>
		
	<br>

	<b><big>flash skin</big></b><br>
	
		<blockquote>
		This is the flash skin to use with this quiz. flash skins are Flash swf files which the main Flash frontend
		loads up to customize the look of the interface. The skins contain all of the graphics and animations which
		will be displayed. Changing the look of the frontend is as easy as changing the skin the quiz uses.
		
		The skins shown on the drop down menu are a list of swf (Flash) files from the <b>/swf/skins</b> directory.
		</blockquote>
		
	<br>
		
	<b><big>Question Categories</big></b><br>
	
		<blockquote>
		
		This option allows you to choose which questions you want included in the quiz. Selecting categories for the
		quiz doesn't divide it into categories, it just gives the quiz a pool of questions to choose from. This also does
		not determine how many questions long the quiz will be (you specify that in the next option), it simply tells the
		quiz which questions to Choose from. A quiz can have one or more question categories.
		
		<br><br>
		
		For example, you may have created a category of questions called "Baseball". To create a quiz just containing
		questions about baseball, check the box to the left of category "Baseball".
		
		<br><br>
		
		In another example, let us say that you have created 4 different categories of questions - Baseball, Basketball, Football,
		and Hockey. Your goal would be to create a general sports quiz, containing questions from all 4 categories.
		This time, you would simply select all 4 of those categories. Each time the quiz is taken, the questions given to the
		player will be selected randomly from the total questions contained in all of those categories combined. Since it is
		randomized, the first question given could be from Baseball, and maybe the second from Football, then Basketball, then
		Baseball again, etc. It would be different every time.
		
		</blockquote>
		
	<br>
		
	<b><big>Questions Per Session</big></b><br>
	
		<blockquote>
		This defines the number of questions a player will receive each time the quiz is taken. This number of questions will
		be randomly selected from the question categories you chose. By having more questions available in the categories than
		you set this number to, their quiz would most likely contain different questions every time, thus making the
		quiz more interesting. For example, if you created the sports quiz used in the example above, and each of
		the four categories had a total of 100 questions, that would equal a total of 400 questions the quiz would have
		to choose from. If you set Questions Per Session to 10, each time the quiz was taken, 10 questions would be randomly
		selected from the 400 available to the quiz, which would create many different possibilities!
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
		This does not determine which scores are saved to the database, as all quiz scores are saved. This means that you could
		change this number at any time and the high score list would change appropriately.
		</blockquote>
		
	<br>

	<b><big>High Score Period</big></b><br>
	
		<blockquote>
		The number of days to keep high scores before resetting the list. For example, if you set this to 7, the high score list
		for the quiz will be reset every 7 days. The time which you create the quiz will be considered to be the last time
		the scores were reset, so using the previous example, the high scores would be reset 7 days after the quiz was created
		and again each 7 days after that.
		</blockquote>
		
	<br>
		
	<b><big>Show Correct Answer</big></b><br>
	
		<blockquote>
		This option allows you to specify whether or not to reveal the correct answer to a question after a player answers it
		incorrectly. If you set this to Yes, they will still have the question counted incorrectly (and the incorrect question
		penalty will apply), but the correct answer will be shown to them. This allows for your users to learn more by taking
		the quiz.
		
		
		If set to no, the correct answer will Not be shown to them if they answer incorrectly. This would keep your questions
		"fresh" for longer as players would not learn the answers as quickly, and this would offer more replay in many cases.
		</blockquote>
		
	</blockquote>
		
Once you have filled in all required fields, click "Create New Quiz" to create the quiz. If there are any errors in the form,
they will be shown at the top of the page and you will be able to correct them.

<br><br>

Note: When you create a quiz, it starts in offline mode. This means that it cannot yet be taken. You can change the mode of
the quiz via the quiz browser.

<br><br><hr size=1 noshade color="#000000">

<a name="browsing_quizzes">
<h2>Browsing Quizzes/Sections</h2>

This tool shows a list of quizzes/sections in the database. To view them, select "Browse quizzes" from the list of options
on the left of the control panel.

<br><br>

Each blue bar shown (if any) represents a quiz section, not a quiz. There will be two links next to the name of
the quiz section:

<br><br>

<blockquote>

	<b><big>Edit</big></b><br>

		<blockquote>
		Edit the name and/or description of the quiz section.
		</blockquote>

	<br>

	<b><big>Delete</big></b><br>

		<blockquote>
		Deletes the quiz section.
		</blockquote>

</blockquote>


<br><br>

After those two links, you should see a text input field "Order". This field specifies the order the quiz sections will
appear in. The quiz sections (if you have more than one) will be displayed in ascending order by what you enter in this
field. For example, if you place a "1" in one quiz section, and a "2", in the other, the first quiz section will be
displayed above the second one on your main page. To update the orders, click "Go" next to "Update Orders" at the bottom
of the page.

<br><br>

Each gray box represents a quiz. Each quiz is part of the quiz section it is listed under. quizzes not
listed under a quiz section are not part of any quiz section.

<br><br>

Next to each quiz is a text input field called "Order". It works in the same way as the quiz section order fields,
allowing you to specify what order you want the quizzes to appear in when shown on your main page.

<br><br>

You will see four links to the right of the quiz name. They are as follows:

	<blockquote>
		
	<b><big>Edit</big></b><br>
	
		<blockquote>
		This option allows you to edit any options related to the quiz. If you select it you will be shown the same form
		you used to first create the quiz, except your previously set options will already be filled in.
		
		<br><br>

		Note: You cannot edit a quiz while it is in online mode or if it is in maintenance mode but quizzes are still in progress:
		To learn more about quiz modes, <a href="#quiz_modes">click here</a>.
		</blockquote>
		
	<br>
	
	<b><big>Delete</big></b><br>
	
		<blockquote>	
		Allows you to delete the quiz. Note that this DOES NOT delete the categories used by it, only the quiz itself.
		This removes everything else related to the quiz, such as quiz logs and the high score list.

		<br><br>

		Note: You cannot delete a quiz while it is in online mode or if it is in maintenance mode but quizzes are still in progress.
		To learn more about quiz modes, <a href="#quiz_modes">click here</a>.
		</blockquote>
		
	<br>
	
	<b><big>Reset Scores</big></b><br>
	
		<blockquote>	
		This option allows you to reset the high score list. It does not delete the quiz logs, it only disincludes all previous
		quiz scores from the current top 10.
		</blockquote>
		
	<br>

	<b><big>Reset quiz Logs</big></b><br>
	
		<blockquote>
		This completely deletes all previous quiz logs (records of quiz sessions). Since
		the quiz logs also include the scores for each quiz taken, this actually resets the high score list at the same time.
		This will set the number of takes to 0 as well.
		
		<br><br>
		
		Note: You cannot edit a quiz while it is in online mode or if it is in maintenance mode but quizzes are still in progress.
		To learn more about quiz modes, <a href="#quiz_modes">click here</a>.

		</blockquote>

	<br>

	<b><big>Set Quiz Mode To:</big></b><br>

		<blockquote>
		This option allows you to change the mode of the quiz, either to online, offline, or maintenance mode.
		To learn more about how quiz modes work, <a href="#quiz_modes">click here</a>
		</blockquote>

	</blockquote>
	

	


<br><br><hr size=1 noshade color="#000000">

<a name="quiz_sections">
<h1>Quiz Sections</h1>

Quiz sections allow you to organize your quizzes on the front page of your site. Rather than simply listing all
of your quizzes on one long list, you can specify quiz sections and place your quizzes under them. For example, if
your site contains quizzes for sports, movies, and tv, you might make a section for each and place the respective
quizzes under those sections. quiz sections are not to be confused with question categories - the latter are used to organize
and group your questions together (for easy re-use).

<br><br><hr size=1 noshade color="#000000">

<a name="creating_quiz_sections">
<h2>Creating quiz sections</h2>

To create a new quiz section, select "Create quiz section" from the list of options on the left of the control panel.
Specify a unique name for the quiz section. Optionally, you may specify a description for the quiz section. Click
"Create quiz section" to finish.

<br><br><hr size=1 noshade color="#000000">

<a name="flash_skins">
<h1>flash skins</h1>

The Flash quiz interface for QuizShock (which all quizzes are taken through) is fully customizable via a "skins"
system. All of the coding and logic for the interface is separated into a file which you never need to edit. Whenever a
quiz is taken, the graphics, sounds, and animations are loaded from a separate .SWF (Flash) file called a "skin".

<br><br>

Creating or customizing a skin is done by loading up the skin source code (.FLA file) into Macromedia Flash 
(The skin source code at the QuizShock Include in WTN Team Release). 
From there you can control all of the graphics, animations, and sounds. It is up to you how much you want to customize the 
skin - you could change the color scheme to match your site's colors, add artwork/sounds related to a quiz, or even
completely change the layout and animations. When you are done, publish the file (export it as a .swf file), and upload the 
file using the <a href="#uploading_game_skins">upload flash skin</a> tool on the control panel, or FTP the file to your
<b>/swf/skins</b> directory.

<br><br>

When you create a quiz, simply select the skin you want that quiz to use from the drop down menu. Each trivia
quiz you create can use its own skin.

<br><br><hr size=1 noshade color="#000000">

<a name="creating_flash_skins">
<h2>Creating flash skins</h2>

The game skin .FLA source code, and for more resources on creating game skins Include in WTN Team Release

<br><br><hr size=1 noshade color="#000000">

<a name="uploading_flash_skins">
<h2>Uploading flash skins</h2>

To upload a new flash skin to your site, click "Upload flash skin" from the list of options on the left of the
control panel.

<br><br>

Select the flash skin file (.swf file) from your computer by pressing the browse/choose file button. Next,
click upload skin to upload it to your site.

<br><br>

Note: You can also upload a flash skin by FTP'ing into your site and placing the flash skin (.swf) file in your
<b>/swf/skins</b> directory.

<br><br><hr size=1 noshade color="#000000">

<a name="browsing_flash_skins">
<h2>Browsing flash skins</h2>

To view samples of the flash skins you have installed, click "Browse flash skins" from the list of options on
the left of the control panel.

<br><br>

Select the flash skin file to view from the drop down menu, and click "View flash skin". A sample of the flash skin
(shown at half size) will be displayed with sample data placed in it. This is not an actual quiz, just a sample of
what the flash skin would look like when used for a quiz.

<br><br><hr size=1 noshade color="#000000">

<a name="templates">
<h1>Templates</h1>

QuizShock allows you to easily customize the look and feel of your site via a template system. Every web page generated by the software
(except the ones on the control panel) is controlled by a "template". Templates are simply
HTML pages with "variables" placed in locations where information changes. Each time a page is generated (say, for example, the front
page which lists all quizzes), a fresh template is loaded, and variables (such as the name of the site, the list of quizzes,
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
available quizzes must be displayed. The sub-template <b>GAME_LISTING</b> represents the HTML code displayed for one quiz. This code
will be repeated for each quiz you have set up, and then placed within the main template <b>TRIVIA_MAIN_PAGE</b> where
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

There may be cases where you would like to include your own HTML above and below the site generated by QuizShock, perhaps to
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

This would include the HTML file <b>my_site_header.html</b> from one directory higher than where you installed QuizShock.

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