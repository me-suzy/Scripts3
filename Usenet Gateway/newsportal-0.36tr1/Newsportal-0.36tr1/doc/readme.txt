Introduction

   News portal is a PHP based newsreader. It is licensed under the GNU
   Public License (see enclosed LICENSE).

Overview

   This script collection enables the access to a newsserver (by NNTP)
   from a webpage. It allows you to combine web-forums and newsgroups.
   The script is also suitable for presentation of announce newsgroups on
   web pages, without having the user notice that he is in fact accessing
   a newsserver.
   The main functionality of the script is located in the file
   newsportal.php, which contains the major part of the implemented
   php3-functions. In addition to that there are four more php-files,
   which are directly accessed by the browser:
     * index.php shows the available newsgroups of the newsserver (if you
       have added the names to groups.txt)
     * thread.php displays the article-overview of a newsgroup. The
       articles are displayed in a thread.
     * article.php displays an individual article.
     * article-raw.php displays an raw of individual article.
     * post.php posts a message into a newsgroup.
     * attachment.php shows possible attachments of articles.

   There are some more files that control the behavior of newsportal or
   contain information:
     * config.inc.php contains the configuration.
     * head.inc contains the header and the body-tag of the pages. This
       way the layout of the pages (i.e. the background) can easily be
       adjusted.
     * tail.inc contains the end of every page.
     * style.css CSS-Stylesheet for NewsPortal
     * english.lang : The English language definitions
     * *.lang : The other languages definitions
     * groups.txt list of avaiable newsgroups and it's descriptions
     * grpaccess.txt optional different access modes for newsgroups. If you
       don't use it you can simply delete it.

   Since fetching the article overview of the newsserver takes quite some
   time, newsportal caches this data in the directory spool/. Any file
   can be deleted in this directory, it will automatically be
   regenerated.

Installation

    1. download the zip archive
    2. unzip it to a directory
    3. The file config.inc.php must be edited with your settings (the most
       important variables are: $server, $port, $title, $readonly and
       $www-charset).
    4. Write the names of all groups newsportal should show into the file
       groups.txt. Behind the groupname, seperated by a blank, a
       description of the group can be added. If the description is
       missing, newsportal will try to request the description from the
       newsserver.
    5. If you want to access to post to some groups be different that default
       (see $readonly), you must add to grpaccess.txt groupname, and after a
       blank access mode: y - can send articles, n - cannot send articles.
       Each group should be in other line.
    6. The spool directory has to be created and configured to grant read
       an write access to the newsserver: chmod 777 spool
    7. Adjust the charset in config.inc.php ($www_charset). If you are in
       western europe or the USA the predefined iso-8859-15 charset is
       correct and you don't have to change it. In eastern europe
       iso-8859-2 should be the right setting, and koi8-r in russia. You
       may also want to try utf-8 for unicode, that should work
       everywhere, but not with older webbrowsers and newsreaders.

Configuration

   The following adjustments can be made in config.inc.php

Directories and files

     * $file_newsportal="newsportal.php": Name of the file containing the
       newsportal-functions.
     * $file_groups="index.php": The file which shows the list of
       available newsgroups.
     * $file_thread="thread.php": The file which shows the article-thread
       of a selected newsgroup
     * $file_article="article.php": Displays an article
     * $file_rawarticle="article-raw.php": Displays an raw of article 
     * $file_post="post.php": The file which allows you to post an
       article to a newsgroup. This file can be removed, if the system is
       set on readonly (see below).
     * $file_language="english.lang": Reference to the language
       definition file.
     * $file_footer: Optionally, the name of a file can be indicated,
       which will be attached to every article posted to a newsgroup.

Newsserver setup

     * $server: Hostname or IP of the newsserver 
     * $port: Port of the newsserver, normally 119 
     * $server_auth_user: If the newsserver requires authentication by name
       and password put your username here. Otherwise just set the variable
       to "". 
     * $server_auth_pass: Put your password here. 
     * $post_server: Optionally an extra newsserver can be indicated here
       which is used by post.php for writing articles. This is useful if
       two newsservers need to be accessed, a fast read-only server and a
       slow server to post articles. Be aware that it might take some time
       until the posted article will show up on your main newsserver
       ($server), which you use to read articles. 
     * $post_port : Port of your post-newsserver 
     * $post_server_auth_user: If the post-newsserver requires
       authentication by name and password put your username here. Otherwise
       just set the variable to "". 
     * $post_server_auth_pass: Put your post-newsserver password here. 

Grouplist layout 

     * $gl_age: if set to true number of group posts in index.php is colored
       by age of last (see $age_count), but index.php work slows.

Thread Layout

     * $treestyle: The appearance of the message tree:
          + 0: Simple listing of the articles
          + 1: Easy listing of the articles, with some more HTML tags
          + 2: Simple listing in a table
          + 3: Threaded with HTML-tags (UL, li)
          + 4: Threaded with text characters
          + 5: Threaded with graphical images
          + 6: Threaded with text characters and table
          + 7: Threaded with graphical images table
     * $thread_showDate,
     * $thread_showSubject,
     * $thread_showAuthor:
          + true: the date / the subject / the author are displayed in
            the thread
          + false: output is suppressed.
     * $thread_showAuthorlink: Set true to add a mailto-link to the email
       address of the author, false else
     * $thread_maxSubject: Maximum number of characters of the subject
       displayed
     * $maxarticles: This number indicates the maximum amount of overview
       data of a newsgroup newsportal tries to get from the newsserver.
       "0" means no limitation. $maxarticles also indicates the amount of
       articles to be stored in the spoolfiles. A lower value means less
       work for newsportal
     * $maxarticles_extra: The problem with $maxarticles is that all
       article data must be completely requested again by the new server,
       if the indicated value is exceeded. $maxarticles_extra can be set
       to prevent this. The article-spool will only be restructured if
       $maxarticles + $maxarticles_extra articles are present, whereby
       $maxarticles many article data are requested. Only if an exact
       given number of articles should be displayed on the web page, the
       value of this variable schould be set "0".
     * $age_count: Number of different age levels for the coloured
       marking of articles
     * $age_time[n]: maximal age of an article in seconds, so that the
       article gets marked with the colour
     * $age_color[n]: n is a natural number > = 1 and all numbers from 1
       to n must be assigned, gaps are not permitted.
     * $age_color[n]: The colour in which the article is marked
     * $thread_sort_order: The sort sequence for the articles:
          + 0: No assortment, articles are displayed in the order in
            which they are polled from the server. This is nearly like
            ascending assortment.
          + 1: ascending assortment, the oldest articles to the top.
          + -1: descending assortment, the newest articles to the top.
     * $thread_sort_type: kind of sorting:
          + "thread": order by thread, newest thread on top/bottom
          + "article": order by article, newest article on top/bottom
          [TRanx comment: When You set to "thread" on top/bottom is that
           thread, which contain the newest article from all articles, if
           "article" on top/bottom is that thread, which first article is
           newest]
     * $articles_per_page: If this value is not 0, the maximum amount of
       articles is given, which are to be displayed on one page at the
       same time. The thread will be split into individual pages.
     * $startpage: In connection with $$articles_per_page the variable
       indicates, which page is to be displayed first:
          + "first": The page with the newest articles
          + "last": the page with the oldest articles

   The specification should be co-ordinated with $thread_sorting. "first"
   for 0 and 1, and "last" for -1.

Frame support

   There is no frame support in this version, except this:
   
     * $frame_externallink: Target frame for external links within
       articles.

Article layout

     * $article_show["Subject"],
     * $article_show["From"],
     * $article_show["From_link"]: Set true to add a mailto-link to the
       email address of the author, false else
     * $article_show["From_rewrite"]: regular expression that converts
       the email-adress (to make it invisible to spam-harvesters for
       example)
     * $article_show["Newsgroups"],
     * $article_show["Organization"],
     * $article_show["Date"],
     * $article_show["Message-ID"],
     * $article_show["User-Agent"],
     * $article_show["References"]: "true" displays the respective header
       line in article.php, by "false" it is suppressed.
     * $article_showthread: true, if under every article the subthread of
       that article should be showed, false if not.
     * $article_graphicquotes: true, if use graphical representation of
       quotes instead of a > character

Attachments

     * $attachment_show: true, if you want attachments to be shown
     * $attachment_delete_alternative: if true, non-text attachments
       inside a multipart/alternative block will be deleted, if there is
       a text-only alternative.
     * $attachment_uudecode: if true, newsportal tries to decode
       uuencoded attachments.

Safety settings

     * $send_poster_host: true means that a header-line named
       "X-HTTP-Posting-Host:" will be attached to every posted article,
       set to the hostname of the user who wrote the article.
     * $readonly: if set to true, the newsportal is read-only. The file
       post.php can be safely removed.
     * $testgroup: if set to true newsportal checks if a group is listed
       in groups.txt when accessed through thread.php. Otherwise a group
       could be seen simply by entering the right URL, although the group
       is not displayed in the group list.
     * $validate_email: Sets how newsportal checks an email address in
       post.php for syntax:
          + 0: no examination. Not recommended, since the newsserver will
            give an error message, if the address is not syntactically
            correct.
          + 1: Checks the address on syntactic correctness.
          + 2: Additionally a MX or A record is checked for the
            domain-name of the e-mail address. Newsportal performs a
            hostname lookup.
     * $block_xnoarchive: if set true, don't show articles that have
       "X-No-Archive: yes" in the header. I don't think this makes sense,
       because newsportal is not an archive. But some people wanted such
       a "feature".
     * $rawview: if set to true user can display an raw message

General setting

     * $title: The value of this variable is put in the title-header of
       the generated webpages.
     * $organization: Name of your organization. Put after the
       "Organization:"-header when posting articles.
     * $setcookies: Permits the user to save his name and his
       email-address as cookies in his browser.
     * $compress_spoolfiles: Sets whether the spool files should be
       compressed or not. This is recommended under normal conditions,
       since the size of the spoolfiles shrinks approximately to about
       15% of the original size. Be aware that some PHP-Versions do not
       support compressing
     * $cache_articles: true if you want articles to be cached. This
       feature is experimental, but is recommended if you have groups
       with a lot of attachments. This feature makes problems if articles
       were canceled, because newsportal doesn't notice this.
     * $www_charset: Charset of your websites. For example iso-8859-15
       for central europe, iso-8859-2 for east europe, utf-8 for unicode
       or koi8-r for russia
     * $iconv_enable: Enable automatic conversion of charsets. So, for
       example, if your charset is iso-8859-1, you can also read utf-8
       encoded postings and vice versa
     * $fqdn: Full Qualified Domain Name. It's used for generating
       Message-ID of posting articles (part after @)
     * $show_xface: if true uses included xface.php to decode X-Face:
       header. This option is experimental for now and don't work
       properly. You must have CompFace installed

Group specific config

   If you have single groups or complete hierarchies where you want to
   set some alternative options, you have to edit this section.
   You can create additional config-files, where you can overwrite the
   default settings of your config.inc.php.
   The variable $group_config binds that additional config files to
   groups.
   It is array, where it's keys are regular expressions that match group
   names, and it's values are names of config-files that are loaded over
   the normal config. The first key that matches wins, so put the more
   specific expressions on top.
   Example:
$group_config=array(
        '^de\.alt\.fan\.aldi$' => "aldi-fan.inc.php",
        '^de\.alt\.' => "de-alternative.inc.php",
        '\.comp\.' => 'computer.inc.php'
);

   First it looks after the single group de.alt.fan.aldi, second the
   complete de.alt.ALL hierarchie, and then every group that contains
   comp.
   The additional config files that were referenced here are like the
   config.inc.php, but contains normally only a few options.
   For example, if you want to have de.alt.fan.aldi readonly, then create
   the file aldi-fan.inc.php with the following contents:
<?
$readonly=false;
?>

   Warning: index.php ignores this file. If you want to have different
   groups on different newsservers, you have to replace this file by an
   static html-file. There will be a better solution for this in later
   versions of NewsPortal.

Safety notes

   This script was originally (and actually still) only meant for access
   to local newsgroups. If you use it with UseNet newsgroups, following
   problems could show up:
     * Articles could be posted anonymously (i.e. spamming), see
       $send_poster_host
     * There are lists with so-called "open" newsservers in the internet.
       Mostly "open" doesn't mean for this server that everyone is
       allowed to use this server. Normally it means that the operator of
       the server forgot to protect his server adequatly. So before using
       an "open" newsserver, you should make sure that the operator
       permits the use of his server for newsportal.
     * Posting articles anonymously is not accepted in most UseNet?
       groups. Before you give writing access to a newsgroup, you should
       ask the users in the newsgroup if they have no objections. Do not
       give public write access on UseNet? newsgroups, if you do not know
       exactly, what you are doing!
     * Do not use the cancel-features of newsportal, if you don't know
       what you are doing! Cancel means removing articles worldwide on
       every newsserver!

   The author reserves the right not to be responsible for the
   topicality, correctness, completeness or quality of the program
   provided. Liability claims regarding damage caused by the program
   provided, will therefore be rejected.
   In other words: Use this program at your own risk!

Compatibility

   Newsportal should work with every phpserver with php4 support and
   every newsserver. Webserver and newsserver do not need to run on the
   same machine.

Contact
  Author:
   Florian Amrhein
   email: florian.amrhein@gmx.de
   
  Changes:
   DJ TRanx
   email: tranx@djcentral.com
   WWW: http://newsportal.glt.pl
