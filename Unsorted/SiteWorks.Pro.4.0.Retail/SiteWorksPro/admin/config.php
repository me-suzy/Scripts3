<?php

	// SiteWorksPro setup script. DO NOT MODIFY THIS SCRIPT DIRECTLY. Instead,
	// run admin/setup.php to do it through the browser

    // Has the setup script been executed?
    $isSetup = 'no';
        
    // Admin App details
    $appName = "SiteWorksPro";
    $appVersion = "4.0";

    // Your sites details
    $siteName = "Your Site";
    $siteURL = "http://www.yoursite.com"; // Don't include a following slash
    $siteLogo = "images/logo.gif";
    $ezinePopup = true;
    $showUsersOnline = true;
    $showFeaturedBook = true;

    // Meta tag data
    $siteKeywords = "Enter, keywords, that, describe, your, site, here";
    $siteDescription = "Enter a short description of your site here.";

    // Contact person
    $adminName = "Your Name";
    $adminEmail = "you@yoursite.com";
        
    // Database server details
    $dbServer = "localhost"; // Can be IP or server name
    $dbUser = "admin";
    $dbPass = "password";
    $dbDatabase = "mydatabase";

    // Image bank options
    $ibMaxFileSize = 50000; // 1,000 bytes = approx. 1k

    // File types that can be uploaded
    $ibTypes = array("image/gif", "image/jpg", "image/pjpeg", "image/jpeg");

    // Number of records to show per page in admin app
    $recsPerPage = 20; // Global for admin
    $authorsPerPage = 10; // Authors to show per page on site
    $newsPerPage = 10; // News items per page on site
    $articlesPerPage = 20; // Articles per page on site

    // Forum Options
    $isVBulletinForum = false; // Set to true if running VBulletin
    $isPHPBBForum = false; // Set to true if running PHPBB
        
    $forumDBServer = "[None]";
    $forumDBUser = "[None]";
    $forumDBPass = "[None]";
    $forumDBName = "[None]";
    $forumPath = "[None]"; // Don't include a following slash
    
    $authorEmail = "Hi <<AuthorName>>,
Your login details for <<SiteName>> (<<SiteURL>>/admin) are shown below:

Username: <<Username>>
Password: <<Password>>

Regards,
<<AdminName>>
mailto:<<AdminEmail>>
<<SiteURL>>";

    // Setting the viewing options for what publishers can see and what they can do.
    // For more information on this, check the documentation
    $publisherAccess = array("sitedetails",
					        "about_us",
					        "contact_us",
					 "users",
					        "view_users",
					        "add_users",
					        "edit_users",
					        "delete_users",
					 "articles",
					        "view_articles",
					        "add_articles",
					        "edit_articles",
					        "delete_articles",
					        "activate_articles",
					 "topics",
					        "view_topics",
					        "add_topics",
					        "edit_topics",
					        "delete_topics",
					 "news",
					        "view_news",
					        "add_news",
					        "edit_news",
					        "delete_news",
					 "personalContent",
					        "edit_2cents",
					        "edit_tips",
					 "books",
					        "view_books",
					        "add_books",
					        "edit_books",
					        "delete_books",
					"images",
					        "view_images",
					        "add_images",
					        "delete_images",
					"affiliates",
					        "view_affiliates",
					        "add_affiliates",
					        "edit_affiliates",
					        "delete_affiliates",
					"newsletter",
					        "view_newsletter",
					        "delete_newsletter",
					        "export_newsletter",
					"polls",
							"view_polls",
							"add_polls",
							"edit_polls",
							"delete_polls",
							"poll_results"
					);


?>