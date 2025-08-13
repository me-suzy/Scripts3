~~~~~~~~~~~~~~~~~~~~
SilveriCE TGP Script
~~~~~~~~~~~~~~~~~~~~

[[Contents]]
>Background Info
>Copyright
>Contact Information
>PRO vs. Free
>Installing
>Configuring
>Advanced Configuring
>Making it work with your page
>Using the script


Background Info:

I wanted to build a simple, effective, quick and more to the point, CHEAP TGP script.
So here it is! SilveriCE TGP includes

:PHP frontend, MySQL backend - will 'clip' onto your existing webpage 
:Fully featured submission form - name, address, email, category, etc 
:Fully automatted checking of URL and Email validity 
:Exceptionally easy review abilities - the administator is presented a list of all submitted
 url, each with their information and date, and accept, reject and edit options. once a site is 
 accepted or rejected, it dissapears from this list - allowing you to choose how many sites you wish to accept.
 Once revision is complete, the webmaster finalises the selection, and the entries go live. 
:Fully featured blacklist - allows both domain and email banning 
:The ability to accept sites, then send them live. This is a unique concept; you can choose how
 many sites you want displayed, and only publish that number of accepted sites per day. You don't
 have to refuse good galleries. 


[[CopyRight]]
This script is copyright Simon Yorkston 2002, all right reserved et al.
What this means is this:
	: you may use the script as long as	
	: you NEVER edit parts of the script you have been instructed not too
	: you attempt to bypass / remove advertisments that comes as part
	  of the user agreement to use the script on a free usage basis
	: you NEVER redistribute the script for profit

[[Contact Information]]
If you need to contact me for WHATEVER reason:
quantum-x@ice.org
http://quantum-x.ice.org/tgp
icq: 52779009
MSN: inversereality@hotmail.com

[[Pro vs. Free]]
This script is released in two flavours, free and pro.
The differences are as follows, and are quite significant.

FREE> : Free to use. 
	: Free support
	: Advertisments on the submit page
	: No ability to edit the submitted posts
	: No free upgrades

PRO	:  $100
	: NO advertisments
	: Free support and instillation if necessary
	: Ability to edit sumitted pages
	: FREE upgrades
	: The ability to REQUEST features, and have them made
	: The warm fuzzy feeling of supporting someone who 
	  is really trying to make a difference and break
	  the market by putting a REASONABLE price on a script


Setup Information
-----------------


[[Installing]]
I have made a php script that will self-install this script.
There are two versions - setup.php and setup2.php

If you have access to create DATABASES, then use setup.php
If you DO NOT have access to create databases, then speak to your
administrator, and get them to add a database called 'silverice'; then run setup2.php

[[Configuring]]
The script will automatically be able to run and function. If you don't want to configure
anything, you don't have to.
If you do want to configure settings, load auth.php, and follow the instructions in the commented code.

You have the ability to edit and change all the messages that the script shows.

NOTE: Php will NOT allow you to use " [double quotes] in your code, unless you 'escape' them-
that is, you put a \ in front of them.
IE: <font color="#000000">"Hi there", said Jon</font>
     would have to become <font color=\"#000000\">\"Hi there\", said Jon</font>
It is fairly simple to do it manually use even better, use a search and replace function.

If you forget, the script WILL spew errors at you.


[[Making it work with your page]]
This is incredibly easy. If you have an existing page, where you want the URLs to appear, just copy 
and paste the entire contents from list.php.
Make sure the page has a .php extension.

[[Configuring output / Trade scripts]]
You can configure what the script outputs - ie, it's urls.
by default, it displays xx of Category.
If you open up list.php, you can change this, and also add trade script support.
You will find the lines

    $publish = $url;
    $url_format = "<a href=\"$publish\" target=\"_blank\">$pics of $category</a>";

To append the url that will be opened, you have to change the $publish variable.
IE: if i wanted all my traffic to leave through 'out.php&s=60' i would have to do the following:
    $publish = "out.php&s=60".$url;
Normal urls rules apply.

To change the description- you have the choice of the following information to include:
    $url
    $email
    $name
    $pics
    $category

For instance, to change the output text to "Name's category - xx pics"
I would have to modify $url_format to
    $url_format = "<a href=\"$publish\" target=\"_blank\">$name's $category - $pics pics</a>";

[[Using the script]]
Again, incredibly easy. 
Send your users to where you have your submit.php.
They will enter their information, and it will be stored.

When you wish to administrate, simply go to admin.php.
From here, you can view the submissions, and accept or decline them appropriately.

Once you have completed this, to make the accepted URLs 'live', just choose the publish option.


