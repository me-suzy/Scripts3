<?
/***************************************************************************

snif 1.4
"snif is not an index file"
"simple and nice index file"
(c) Kai Blankenhorn
www.bitfolge.de
kaib@bitfolge.de


THIS IS THE REAL SNIF INDEX.PHP FILE.


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details: <http://www.gnu.org/licenses/gpl.txt>

****************************************************************************


Changelog:

v1.4	05-04-04
	easy translation system (language is selected automatically depending on the
		user's browser's language setting)
	added a German translation
	option to reorder and hide columns
	option to display "[ back ]" instead of ".."
	option to always use the full width of the screen
	special icon for the .. link
	improved directory sorting: .. now always on first position
	truncation of long file names (and an option for it)
	added number of files contained in subdirectories
	improved error messages
	fixed possible bug with hidden files
	fixed a bug with descriptions containing non-ASCII characters
	thanks to Rogier Eimeren for the comments
	
	NOTE: If you'd like to translate snif into your language, have a look at
	the $languageStrings variable at the end of the advanced settings, add
	your language and send me the PHP snippet containing your translation.

v1.3.2	01-19-04
	improved HTTP cacheability of icons, thumbnails and downloads, major speedup!
	added directory navigation in the header
	added a thumbnail size setting
	different index files in subdirectories are now possible

v1.3.1  01-14-04
	added splitting directories on multiple pages
	fixed some bugs with file links
	better support for international file names
	restored compatibility with older PHP versions (< 4.2.0, thank to Glen Solsberry)

v1.3    01-05-04
	added automatic thumbnails for images (optional, requires GDlib 2)
	added an option for case insensitive description assignment
	added XHTML 1.1 and CSS 2.0 conformance
	fixed a sorting bug
	once more fixed special characters in file names
	reduced resulting HTML size (15-20% smaller for large directories)

v1.2.9  12-20-03
	stupid date format bug fixed
	fixed download of files with special characters in their name
	code cleanup

v1.2.8  12-13-03
	settings have been split in basic and advanced settings
	added configurable server name instead of what PHP detects (thanks to Paul Munn)
	added configurable date and time format (thanks to Paul Munn)
	improved default hidden file wildcards, now also hides emacs temp files (thanks to Paul Munn)
	notices are now always suppressed
	fixed various HTML and CSS glitches (thanks to Paul Munn)
	fixed a bug which caused the sorting arrow not to be displayed (thanks to Paul Munn)
	renamed and reorganized stylesheets (thanks to Paul Munn)

v1.2.7  12-09-03
	cross site scripting bug fixed (thanks to Justin Hagstrom for reporting)
	fixed a bug with the new hidden file wildcards

v1.2.6  12-06-03
	improved external icons: you may now mix external and internal icons 
	improved directory sorting (thanks to mpember at mpember dot net dot au)
	improved default hidden files wildcards, now also hides .* (thanks to Charles Hill)
	fixed a minor bug in file type detection (thanks to Charles Hill)
	added more file extensions (thanks to Charles Hill)

v1.2.5  11-26-03
	security fix: download would allow paths above snif directory

v1.2.4  11-16-03
	added configurable separation string for description files
	added option to use external icons

v1.2.3  11-15-03
	fixed minor typos and HTML glitches

v1.2.2  11-11-03
	fixed a bug where the current path wasn't properly shown in the heading

v1.2.1  11-10-03
	fixed files without extension
	suppressed warning when io functions fail
	experimental handling of symbolic links (completely untested! Please give feedback.)

v1.2    11-04-03
	put a simple file into subdirectories to have snif handle direct requests to them
	minor bugfix

v1.1a   11-03-03
	file download for Opera fixed

v1.1    11-03-03
	download files instead of opening
	better documentation
	code cleanup

v1.0    10-19-03
	initial release



****************************************************************************
**  DESCRIPTION FILE FORMAT                                               **
****************************************************************************

Hardcore definition:

<descriptionfile>  ::= <line>*
<line>             ::= <filename><separationString><description><EOL>
<filename>         ::= <anythingExceptSeparationString>+
<separationString> ::= defined by the $separationString variable, default "\t"
<description>      ::= <anyting>+
<EOL>              ::= "\r\n" | "\n"			// OS dependent


Simple example:

.	This directory contains downloadable files for MyProgram 12.0.
myprogram_12.0.exe	Installer version of MyProgram 12.0 (recommended)
myprogram_12.0.zip	Zip file distribution of MyProgram 12.0
releasenotes.txt	Release notes for MyProgram 12.0


Please note that the room between the filename and the description is not
filled with multiple spaces, but with one single tab. It doesn't matter if
the descriptions in a file align or not, just use one tab.
If you use a description for the current directory (.) as in the first line
in the above example, it will be used as a heading in the directory listing.

Put your descriptions in a text file within the same directory as the files 
to describe. Then put the text file's name in the $useDescriptionsFrom 
variable below. It is suggested that you use the same description file name
in all subdirectories you want to list. Reason: Read the next paragraph.

To make it even easier: For my download folder at 
http://www.bitfolge.de/download, I have put the description file at
http://www.bitfolge.de/download/descript.ion
You can download it and use it as another example.

Filenames in the description file are case insensitive as of 1.2.10. This
means that myprogram.zip and MyProgram.ZIP both are regarded as the same
file. If case sensitivity matters for you, you can disable this with the
$descriptionFilenamesCaseSensitive variable in the advanced settings.


****************************************************************************
**  HANDLING SUBDIRECTORY LISTINGS                                        **
****************************************************************************

Say you've put the snif index.php into www.yourhost.com/download.
Now somebody makes a request to www.yourhost.com/download/releases. In
order to deal with this properly, you would have to copy the snif index.php
to that directory, too. But this will prevent the user to go to 
www.yourhost.com/download from www.yourhost.com/download/releases
directly by selecting the .. link.

If you have this situation, use the index.php file from the subdirectory
called "subdir" in the snif archive file. All it does is automatically 
forward the user to the parent directory and set URL parameters so that
the real snif will handle the request.

OK, that may be confusing. Again, a simple example:


/download/descript.ion                       << descriptions for /download/*.*
/download/index.php                          << this file you're reading now, >25 KB
/download/license.txt
/download/notes.txt
/download/releases/bigprogram_2.0.zip
/download/releases/descript.ion              << descriptions for /download/releases/*.*
/download/releases/index.php                 << subdir/index.php, <2 KB
/download/releases/nightly/2.1_20031103.zip
/download/releases/nightly/2.1_20031104.zip
/download/releases/nightly/index.php         << subdir/index.php, <2 KB


If a users points his browser to
  www.yourhost.com/download/releases/nightly/
  
The small index.php will forward him to
  www.yourhost.com/download/releases/?path=nightly/

And then the index file in that directory will forward him again, this time to
  www.yourhost.com/download/?path=releases/nightly/

Now we've reached the directory with the real snif (should get a copyright on
that phrase ;-)), which will take over and miraculously lists the directory
the user typed as an URL.



/***************************************************************************/
/**  SET YOUR CONFIGURATION HERE                                          **/
/***************************************************************************/



/**************  BASIC SETTINGS  *******************************************/
/* These settings configure the most basic functions of snif. You should   */
/* be able to understand them quickly.                                     */
/***************************************************************************/

/**
 * Specify which files should be hidden in the file listing using
 * unix/DOS wildcards (? and *).
 * This is case insensitive. This script, the current directory and the
 * description file will automatically be hidden.
 **/
$hiddenFilesWildcards = Array("*.php", "*~", "source");

/**
 * Show sub directories and let the user change to them.
 * It will be impossible to go above the directory this script is in.
 **/
$allowSubDirs = true;

/**
 * Allow the users to download .php files. This will expose the full contents
 * of the downloaded files (including any password used in it). Be careful
 * with this!
 * This only makes sense if you don't hide all .php files.
 **/
$allowPHPDownloads = false;


/**
 * Automatically generate and display thumbnails for image files. This
 * feature requires GDlib 2.0+.
 **/
$useAutoThumbnails = true;



/**************  ADVANCED SETTINGS  ****************************************/
/* Usually you won't need to change these, but you may have a look if you  */
/* want snif to do something you think it can't. Maybe there's a setting   */
/* which lets you do it.                                                   */
/***************************************************************************/

/**
 * Set the server name to be reported on generated pages. Use this only if
 * your server reports the wrong name if $_SERVER['HTTP_HOST'] (which is 
 * the default) is used.
 **/
$snifServer = $_SERVER['HTTP_HOST'];
//$snifServer = 'www.yourdomain.com';

/**
 * Set the date and time format used for file modified dates. For the syntax
 * of this string, please refer to http://www.php.net/manual/en/function.date.php
 * DEPRECATED, please use languageStrings instead.
 * @deprecated
 **/
 
$snifDateFormat = 'd-m-y';

/**
 * Specify which files should be hidden in the file listing using
 * regular expressions. Do not use expression limiters or modifiers.
 * These patterns will be merged with $hiddenFilesWildcards.
 **/
$hiddenFilesRegex = Array();
 
/**
 * Description file, leave blank for no descriptions.
 **/
$useDescriptionsFrom = "descript.ion";

/**
 * Define the string that should be used to separate file names and
 * descriptions in the description files. Defaults to "\t" (tab).
 **/
$separationString = "\t";

/**
 * Use external images instead of built-in ones. If you set this to
 * true, you should specify every value in the $externalIcons array below.
 * If you don't, internal images will be used instead.
 **/
$useExternalImages = true;

/**
 * State the filenames for external file icons. Only used if
 * $useExternalImages == true. Paths are relative to the directory of snif.
 * Icon size should be 16x16 pixels, except where noted otherwise.
 * Use an empty string to use the internally stored image for that icon.
 **/
$externalIcons = Array (
	"archive"	=> "",
	"binary"	=> "",
	"dirup"   => "",
	"folder"	=> "",
	"html"		=> "",
	"image"		=> "",
	"text"		=> "",
	"unknown"	=> "",
	"download"	=> "",   // 7x16 pixels
	"asc"		=> "",       // 5x3 pixels
	"desc"		=> ""      // 5x3 pixels
);

/**
 * Filenames in description files are case insensitive. If a file in a
 * directory is called MyProgram.ZIP, adding a description line for 
 * myprogram.zip will be used for this file.
 * If you set this to true, filenames in description files and directories
 * must be exactly the same.
 **/
$descriptionFilenamesCaseSensitive = false;

/**
 * If a directory contains more than this number of files, display it on
 * multiple pages. Useful for very large directories. $usePaging sets the
 * number of files displayed per page. Set to 0 to disable multiple pages.
 **/
$usePaging = 0;

/**
 * Make links to directories in a file listing point directly to that
 * directory. Defaults to false. Set this to true if you want to display
 * individual index files for each directory. If you want to display a
 * subdirectory with snif, copy the subdir/index.php from the snif archive
 * to that directory.
 **/
$directDirectoryLinks = false;

/**
 * Sets the height of thumbnails. Images bigger than this value will be
 * downsized to this height. Smaller images will stay unchanged. Defaults
 * to 50.
 **/
$thumbnailHeight = 50;

/**
 * Use "back" instead of ".." to go up in directories. The "back" string
 * is italicized to distiguish it from directories.
 **/
$useBackForDirUp = true;

/**
 * Determines which columns to display and in which order.
 * To hide a column, delete it from this array. To rearrange columns,
 * change their order in this array.
 * Default value is
 * $displayColumns = Array("name", "type", "size", "date", "description");
 * These are also the only column names possible.
 **/
$displayColumns = Array(
	"name",
	"type",
	"size",
	"date",
	"description"
);

/**
 * Sets the listing to always occupy the whole width of the screen instead of
 * only the necessary space.
 **/
$tableWidth100Percent = false;

/**
 * Specifies how long file and directory names are to be truncated. Defaults
 * to 30, set to 0 to turn off truncation.
 **/
$truncateLength = 30;




/***************************************************************************/
/**  TRANSLATIONS                                                         **/
/***************************************************************************/

$languageStrings = Array(
	"en" => Array(
		// only serves as the default language, no translations needed
		"Index of" => "",
		"name" => "",
		"type" => "",
		"size" => "",
		"date" => "",
		"description" => "",
		"DATEFORMAT" => "d-m-y", // special string, sets the format of the date (see http://www.php.net/manual/en/function.date.php)
		"folder" => "",
		"archive" => "",
		"image" => "",
		"text" => "",
		"html" => "",
		"unknown" => "",
		"valid" => "",
		"binary" => "",
		"dirup" => "",
		"download" => "",
		"asc" => "",
		"desc" => "",
		"[ back ]" => "",
		"1 item" => "",
		"%d items" => "",
		"%s is not a subdirectory of the current directory." => "",
		"File not found: %s" => "",
		"Illegal characters detected in URL, ignoring." => "",
		"Illegal path specified, ignoring." => ""
	),
	"de" => Array(
		"Index of" => "Inhalt von",
		"name" => "Name",
		"type" => "Typ",
		"size" => "Gr&ouml;&szlig;e",
		"date" => "Datum",
		"DATEFORMAT" => "d.m.y", // special string, sets the format of the date (see http://www.php.net/manual/en/function.date.php)
		"description" => "Beschreibung",
		"folder" => "Verzeichnis",
		"archive" => "Archiv",
		"image" => "Bild",
		"text" => "Text-Datei",
		"html" => "HTML-Datei",
		"unknown" => "unbekannt",
		"valid" => "g&uuml;ltiges",
		"binary" => "Bin&auml;rdatei",
		"dirup" => "Aufw&auml;rts",
		"download" => "Download",
		"asc" => "aufsteigend",
		"desc" => "absteigend",
		"[ back ]" => "[ aufw&auml;rts ]",
		"1 item" => "1 Eintrag",
		"%d items" => "%d Eintr&auml;ge",
		"%s is not a subdirectory of the current directory." => "%s is kein Unterverzeichnis des momentanen Verzeichnisses.",
		"File not found: %s" => "Die Datei '%s' wurde konnte nicht gefunden werden.",
		"Illegal characters detected in URL, ignoring." => "Ung&uuml;ltige Zeichen in der URL wurden ignoriert.",
		"Illegal path specified, ignoring." => "Ein ung&uuml;ltiger Pfad in einem Parameter wurde bereinigt."
	)
);




/***************************************************************************/
/**  REAL CODE STARTS HERE, NO NEED TO CHANGE ANYTHING                    **/
/***************************************************************************/


/***************************************************************************/
/**  TRANSLATION                                                          **/
/***************************************************************************/

function translate($string) {
	GLOBAL $languageStrings;
	static $requestLanguage;
	
	if ($requestLanguage=="") {
		$validLanguages = array_keys($languageStrings);
		if ($requestLanguage == "") {
			$acceptLanguages = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
			for ($i=0; $i<count($acceptLanguages) AND $requestLanguage==""; $i++) {
				$al = substr($acceptLanguages[$i],0,2);
				if (in_Array($al,$validLanguages)) {
					$requestLanguage = $al;
				}
			}
			if ($requestLanguage=="") {
				$requestLanguage = $validLanguages[0];
			}
		}
	}
	
	$stringTranslated = $languageStrings[$requestLanguage][$string];
	if ($stringTranslated!="") {
		return $stringTranslated;
	} else {
		return $string;
	}
}


/***************************************************************************/
/**  INITIALIZATION                                                       **/
/***************************************************************************/

// make sure all the notices don't come up in some configurations
error_reporting (E_ALL ^ E_NOTICE);

$displayError = Array();

// safify all GET variables
foreach($_GET AS $key => $value) {
	$_GET[$key] = strip_tags($value);
	if ($_GET[$key] != $value) {
		$displayError[] = translate("Illegal characters detected in URL, ignoring.");
	}
}

// first of all, security: prevent any unauthorized paths
// if sub directories are forbidden, ignore any path setting
if (!$allowSubDirs) {
	$path = "";
} else {
	$path = $_GET["path"];
	
	// ignore any potentially malicious paths
	$path = safeDirectory($path);
}

// default sorting is by name
if ($_GET["sort"]=="") 
	$_GET["sort"] = "name";

// default order is ascending
if ($_GET["order"]=="") {
	$_GET["order"] = "asc";
} else {
	$_GET["order"] = strtolower($_GET["order"]);
}

// hide descriptions column if no description file is specified
if ($useDescriptionsFrom=="") {
	$index = array_search("description", $displayColumns);
	if ($index!==false && $index!==null) {
		unset($displayColumns[$index]);
	}
}
	
// add files used by snif to hidden file list
if ($useDescriptionsFrom!="") {
	$hiddenFilesWildcards[] = $useDescriptionsFrom;
}
$hiddenFilesWildcards[] = ".";
$hiddenFilesWildcards[] = basename($_SERVER["PHP_SELF"]);

// build hidden files regular expression
for ($i=0;$i<count($hiddenFilesWildcards);$i++) {
	$translate = Array(
		"." => "\\.",
		"*" => ".*",
		"?" => ".?",
		"+" => "\\+",
		"[" => "\\[",
		"]" => "\\]",
		"(" => "\\(",
		")" => "\\)",
		"{" => "\\{",
		"}" => "\\}",
		"^" => "\\^",
		"\$" => "\\\$",
		"\\" => "\\\\",
	);
	$hiddenFilesRegex[] = "^".strtr($hiddenFilesWildcards[$i],$translate)."$";
}
// hide .*
$hiddenFilesRegex[] = "^\\.[^.].*$";
$hiddenFilesWholeRegex = "/".join("|",$hiddenFilesRegex)."/i";



/***************************************************************************/
/**  REQUEST HANDLING                                                     **/
/***************************************************************************/

// handle image requests
if ($_GET["getimage"]!="") {
	$imagesEncoded = Array(
		"archive"  => "R0lGODlhEAAQAJECAAAAAP///////wAAACH5BAEAAAIALAAAAAAQABAAAAI3lA+pxxgfUhNKPRAbhimu2kXiRUGeFwIlN47qdlnuarokbG46nV937UO9gDMHsMLAcSYU0GJSAAA7",
		"asc"      => "R0lGODlhBQADAIABAN3d3f///yH5BAEAAAEALAAAAAAFAAMAAAIFTGAHuF0AOw==",
		"binary"   => "R0lGODlhEAAQAJECAAAAAP///////wAAACH5BAEAAAIALAAAAAAQABAAAAI0lICZxgYBY0DNyfhAfROrxoVQBo5mpzFih5bsFLoX5iLYWK6xyur5ubPAbhPZrKhSKCmCAgA7",
		"desc"     => "R0lGODlhBQADAIABAN3d3f///yH5BAEAAAEALAAAAAAFAAMAAAIFhB0XC1sAOw==",
		"dirup"    => "R0lGODlhEAAQAJECAAAAAP///////wAAACH5BAEAAAIALAAAAAAQABAAAAIulI+JwKAJggzuiThl2wbnT3WZN4oaA1bYRobXCLpkq5nnVr9xqe85C2xYhkRFAQA7",
		"folder"   => "R0lGODlhEAAQAJECAAAAAP///////wAAACH5BAEAAAIALAAAAAAQABAAAAIplI+JwKAJggzuiThl2wbnT3UgWHmjJp5Tqa5py7bhJc/mWW46Z/V+UgAAOw==",
		"html"     => "R0lGODlhEAAQAKIHABsb/2ho/4CA/0BA/zY2/wAAAP///////yH5BAEAAAcALAAAAAAQABAAAANEeFfcrVAVQ6thUdo6S57b9UBgSHmkyUWlMAzCmlKxAZ9s5Q5AjWqGwIAS8OVsNYJxJgDwXrHfQoVLEa7Y6+Wokjq+owQAOw==",
		"image"    => "R0lGODlhEAAQAKIEAK6urmRkZAAAAP///////wAAAAAAAAAAACH5BAEAAAQALAAAAAAQABAAAANCSCTcrVCJQetgUdo6RZ7b9UBgSHnkAKwscEZTy74pG9zuBavA7dOanu+H0gyGxN0RGdClKEjgwvKTlkzFhWOLISQAADs=",
		"text"     => "R0lGODlhEAAQAJECAAAAAP///////wAAACH5BAEAAAIALAAAAAAQABAAAAI0lICZxgYBY0DNyfhAfXcuxnWQBnoKMjXZ6qUlFroWLJHzGNtHnat87cOhRkGRbGc8npakAgA7",
		"download" => "R0lGODlhBwAQAIABAAAAAP///yH5BAEAAAEALAAAAAAHABAAAAISjI+pywb6UkQzgHsPls3h2gUFADs=",
		"blank"    => "R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==",
		"unknown"  => "R0lGODlhEAAQAJECAAAAAP///////wAAACH5BAEAAAIALAAAAAAQABAAAAI1lICZxgYBY0DNyfhAfXcuxnkI1nCjB2lgappld6qWdE4vFtprR+4sffv1ZjwdkSc7KJYUQQEAOw=="
	);
	$imageDataEnc = $imagesEncoded[$_GET["getimage"]];
	if ($imageDataEnc) {
		$maxAge = 31536000; // one year
		doConditionalGet($_GET["getimage"], gmmktime(1,0,0,1,1,2004));
		$imageDataRaw = base64_decode($imageDataEnc);
		Header("Content-Type: image/gif");
		Header("Content-Length: ".strlen($imageDataRaw));
		Header("Cache-Control: public, max-age=$maxAge, must-revalidate");
		Header("Expires: ".createHTTPDate(time()+$maxAge));
		echo $imageDataRaw;
	}
	
	die();
}

// handle thumbnail creation
if ($_GET["thumbnail"]!="") {
	GLOBAL $thumbnailHeight;
	
	$file = safeDirectory($_GET["thumbnail"]);
	doConditionalGet($_GET["thumbnail"],filemtime($file));
	
	$extension = strtolower(substr(strrchr($file, "."), 1));
	switch ($extension) {
		case "gif":		$src = imagecreatefromgif($file); break;
		case "jpg":		// fall through
		case "jpeg":	$src = imagecreatefromjpeg($file); break;
		case "png":		$src = imagecreatefrompng($file); break;
	}
	$srcWidth = imagesx($src);
	$srcHeight = imagesy($src);
	$srcAspectRatio = $srcWidth / $srcHeight;
	
	if ($srcHeight<=$thumbnailHeight) {
		$thumb = $src;
	} else {
		$thumbHeight = $thumbnailHeight;
		$thumbWidth = $thumbHeight * $srcAspectRatio;
		if (function_exists('imagecreatetruecolor')) {
			$thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
		} else {
			$thumb = imagecreate($thumbWidth, $thumbHeight);
		} 
		imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $srcWidth, $srcHeight);
	}
	
	$maxAge = 3600; // one hour
	Header("Content-Type: image/jpeg");
	Header("Cache-Control: public, max-age=$maxAge, must-revalidate");
	Header("Expires: ".createHTTPDate(time()+$maxAge));
	imagejpeg($thumb);
	die();
}

// handle download requests
if ($_GET["download"]!="") {
	$filename = safeDirectory($path.$_GET["download"]);
	if (
		!file_exists($filename)
		OR fileIsHidden($filename)
		OR (substr(strtolower($filename), -4)==".php" AND !$allowPHPDownloads)) {
		
		Header("HTTP/1.0 404 Not Found");
		$displayError[] = sprintf(translate("File not found: %s"), $filename);
	} else {
		doConditionalGet($filename, filemtime($filename));
		Header("Content-Length: ".filesize($filename));
		Header("Content-Type: application/x-download");
		Header("Content-Disposition: attachment; filename=".$_GET["download"]);
		readfile($filename);
		die();
	}
}



/***************************************************************************/
/**  FUNCTIONS                                                            **/
/***************************************************************************/

// create a HTTP conform date
function createHTTPDate($time) {
	return gmdate("D, d M Y H:i:s", $time)." GMT";
}


// this function is from http://simon.incutio.com/archive/2003/04/23/conditionalGet
function doConditionalGet($file, $timestamp) {
	$last_modified = createHTTPDate($timestamp);
	$etag = '"'.md5($file.$last_modified).'"';
	// Send the headers
	Header("Last-Modified: $last_modified");
	Header("ETag: $etag");
	// See if the client has provided the required headers
	$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
		stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
		false;
	$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
		stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : 
		false;
	if (!$if_modified_since && !$if_none_match) {
		return;
	}
	// At least one of the headers is there - check them
	if ($if_none_match && $if_none_match != $etag) {
		return; // etag is there but doesn't match
	}
	if ($if_modified_since && $if_modified_since != $last_modified) {
		return; // if-modified-since is there but doesn't match
	}
	// Nothing has changed since their last request - serve a 304 and exit
	Header('HTTP/1.0 304 Not Modified');
	die();
}


function safeDirectory($path) {
	GLOBAL $displayError;
	$result = $path;
	if (strpos($path,"..")!==false)
		$result = "";
	if (substr($path,0,1)=="/") {
		$result = "";
	}
	if ($result!=$path) {
		$displayError[] = translate("Illegal path specified, ignoring.");
	}
	return $result;
}


/**
 * Formats a file's size nicely (750 B, 3.4 KB etc.)
 **/
function niceSize($size) {
	define("SIZESTEP", 1024.0);
	static $sizeUnits = Array("&nbsp;B","KB","MB","GB","TB");
	
	if ($size==="")
		return "";
	
	$unitIndex = 0;
	while ($size>SIZESTEP) {
		$size = $size / SIZESTEP;
		$unitIndex++;
	}
	
	if ($unitIndex==0) {
		return number_format($size, 0)."&nbsp;".$sizeUnits[$unitIndex];
	} else {
		return number_format($size, 1, ".", ",")."&nbsp;".$sizeUnits[$unitIndex];
	}
}

/**
 * Compare two strings or numbers. Return values as strcmp().
 **/
function myCompare($arrA, $arrB, $caseSensitive=false) {
	$a = $arrA[$_GET["sort"]];
	$b = $arrB[$_GET["sort"]];
	
	// sort .. first
	if ($arrA["isBack"]) return -1;
	if ($arrB["isBack"]) return 1;
	// sort directories above everything else
	if ($arrA["isDirectory"]!=$arrB["isDirectory"]) {
		$result = $arrB["isDirectory"]-$arrA["isDirectory"];
	} else if ($arrA["isDirectory"] && $arrB["isDirectory"] && ($_GET["sort"]=="type" || $_GET["sort"]=="size")) {
		$result = 0;
	} else {
		if (is_string($a) OR is_string($b)) {
			if (!$caseSensitive) {
				$a = strtoupper($a);
				$b = strtoupper($b);
			}
			$result = strcoll($a,$b);
		} else {
			$result = $a-$b;
		}
	}
	
	if (strtolower($_GET["order"])=="desc") {
		return -$result;
	} else {
		return $result;
	}
}


/**
 * URLEncodes some characters in a string. PHP's urlencode and rawurlencode
 * produce very unsatisfying results for special and reserverd characters in
 * filenames.
 **/
function myEncode($string) {
	// % must be the first, as it is the escape character
	$from = Array("%"," ","#","&");
	$to = Array("%25","%20","%23","%26");
	return str_replace($from, $to, $string);
}


/**
 * Build a URL using new sorting settings.
 **/
function getNewSortURL($newSort) {
	GLOBAL $path;
	$base = $_SERVER["PHP_SELF"];
	$url = $base."?sort=$newSort";
	if ($newSort==$_GET["sort"]) {
		if ($_GET["order"]=="asc" OR $_GET["order"]=="") {
			$url.= "&amp;order=desc";
		}
	}
	if ($path!="") {
		$url.= "&amp;path=$path";
	}
	return $url;
}

/**
 * Determine a file's file type based on its extension.
 **/
function getFileType($fileInfo) {
	// put any additional extensions in here
	$extension = $fileInfo["type"];
	static $fileTypes = Array(
		"html"		=> Array("html","htm"),
		"image"		=> Array("gif","jpg","jpeg","png","tif","tiff","bmp","art"),
		"text"		=> Array("asp","c","cfg","cpp","css","csv","conf","cue","diz","h","inf","ini","java","js","log","nfo","php","phps","pl","rdf","rss","rtf","sql","txt","vbs","xml"),
		"binary"	=> Array("asf","au","avi","bin","class","divx","doc","exe","mov","mpg","mpeg","mp3","ogg","ogm","pdf","ppt","ps","rm","swf","wmf","wmv","xls"),
		"archive"	=> Array("ace","arc","bz2","cab","gz","lha","jar","rar","sit","tar","tbz2","tgz","z","zip","zoo")
	);
	static $extensions = null;

	if ($extensions==null) {
		$extensions = Array();
		foreach($fileTypes AS $keyType => $value) {
			foreach($value AS $ext) $extensions[$ext] = $keyType;
		}
	}

	if ($fileInfo["isDirectory"]) {
		if ($fileInfo["isBack"]) {
			return "dirup";
		} else {
			return "folder";
		}
	}
	
	$type = $extensions[strtolower($extension)];
	if ($type=="") {
		return "unknown";
	} else {
		return $type;
	}
}

function getIcon($fileType) {
	GLOBAL $useExternalImages, $externalIcons;
	if ($useExternalImages && $externalIcons[$fileType]!="") {
		return $externalIcons[$fileType];
	} else {
		return $_SERVER["PHP_SELF"]."?getimage=$fileType";
	}
}

// checks if a file is hidden from view
function fileIsHidden($filename) {
	GLOBAL $hiddenFilesWholeRegex;
	return preg_match($hiddenFilesWholeRegex,$filename);
}

/**
 * Gets a file's description from the description array.
 **/
function getDescription($filename) {
	GLOBAL $descriptions, $descriptionFilenamesCaseSensitive;
	
	if (!$descriptionFilenamesCaseSensitive) {
		$filename = strtolower($filename);
	}
	return $descriptions[$filename];
}

function getPageLink($startNumber, $linkText, $linkTitle="") {
	GLOBAL $snifServer, $path;
	$url = "http://".$snifServer.$_SERVER["PHP_SELF"]."?path=".$path."&sort=".$_GET["sort"]."&order=".$_GET["order"]."&start=".$startNumber;
	if ($linkTitle!="") {
		$titleAttribute = " title=\"$linkTitle\"";
	} else {
		$titleAttribute = "";
	}
	return "<a href=\"$url\"$titleAttribute>$linkText</a>&nbsp;";
}

function getPagingHeader() {
	GLOBAL $pageStart, $usePaging, $pagingNumberOfPages, $pagingActualPage, $pageNumber, $files;
	static $displayPages = Array();
	if (count($displayPages)==0) {
		$displayPages[] = 0;
		for ($i=$pagingActualPage-1; $i<$pagingActualPage+3; $i++) {
			if ($i>=0 && $i<$pagingNumberOfPages) {
				$displayPages[] = $i;
			}
		}
		$displayPages[] = $pagingNumberOfPages-1;
		$displayPages = array_unique($displayPages);
	}
	
	$header = "pages&nbsp;&nbsp;";
	if ($pageStart>0) {
		$header.= getPageLink($pageStart-$usePaging, "&laquo;", "previous");
	}
	if ($pageStart+$usePaging<count($files)) {
		$header.= getPageLink($pageStart+$usePaging, "&raquo;", "next");
	}
	foreach($displayPages as $i => $pageNumber) {
		if ($pageNumber-$displayPages[$i-1] > 1) {
			$header.= ".. ";
		}
		if ($pageNumber!=$pagingActualPage) {
			$header.= getPageLink($pageNumber*$usePaging, $pageNumber+1);
		} else {
			$header.= "<span class=\"snWhite\">".($pageNumber+1)."&nbsp;</span>";
		}
	}
	
	return $header;
}

function getPathLink($directory) {
	GLOBAL $directDirectoryLinks;
	if ($directDirectoryLinks) {
		return $directory."/";
	} else {
		return $_SERVER["PHP_SELF"]."?path=".urlEncode($directory)."/";
	}
}

/**
 * Truncates a string to a certain length at the most sensible point.
 * First, if there's a '.' character near the end of the string, the string is truncated after this character.
 * If there is no '.', the string is truncated after the last ' ' character.
 * If the string is truncated, " ..." is appended.
 * If the string is already shorter than $length, it is returned unchanged.
 * 
 * @static
 * @param string    string A string to be truncated.
 * @param int        length the maximum length the string should be truncated to
 * @return string    the truncated string
 */
function iTrunc($string, $length) {
	if ($length==0) {
		return $string;
	}
	if (strlen($string)<=$length) {
		return $string;
	}
	
	$pos = strrpos($string,".");
	if ($pos>=$length-4) {
		$string = substr($string,0,$length-4);
		$pos = strrpos($string,".");
	}
	if ($pos>=$length*0.4) {
		return substr($string,0,$pos+1)."...";
	}
	
	$pos = strrpos($string," ");
	if ($pos>=$length-4) {
		$string = substr($string,0,$length-4);
		$pos = strrpos($string," ");
	}
	if ($pos>=$length*0.4) {
		return substr($string,0,$pos)."...";
	}
	
	return substr($string,0,$length-4)."...";
}


function getDirSize($dirname) {
	$dir = dir($dirname);
	$fileCount = 0;
	while ($filename = $dir->read()) {
		if (!fileIsHidden($dirname."/".$filename)) 
			$fileCount++;
	}
	return $fileCount-2; // . and .. do not count
}


/***************************************************************************/
/**  LIST BUILDING                                                        **/
/***************************************************************************/

// change directory
// must be done before description file is parsed
if ($path!="") {
	if (!@chdir($path)) {
		$displayError[] = sprintf(translate("%s is not a subdirectory of the current directory."), $path);
		$path = "";
	}
} 
$dir = dir(".");

// parsing description file
$descriptions = Array();
if ($useDescriptionsFrom!="") {
	$descriptionsFile = @file($useDescriptionsFrom);
	if ($descriptionsFile!==false) {
		for ($i=0;$i<count($descriptionsFile);$i++) {
			$d = explode($separationString,$descriptionsFile[$i]);
			if (!$descriptionFilenamesCaseSensitive) {
				$d[0] = strtolower($d[0]);
			}
			$descriptions[$d[0]] = htmlentities(join($separationString, array_slice($d, 1)));
		}
	}
}

// build a two dimensional array containing the files in the chosen directory and their meta data
$files = Array();
while($entry = $dir->read()) {
	// if the filename matches one of the hidden files wildcards, skip the file
	if (fileIsHidden($entry))
		continue;
		
	// if the file is a directory and if directories are forbidden, skip it
	if (!$allowSubDirs AND is_dir($entry))
		continue;
	
	$f = Array();

	$f["name"] = $entry;
	$f["isDownloadable"] = (substr(strtolower($entry), -4)!=".php") || $allowPHPDownloads;
	$f["isDirectory"] = is_dir($entry);
	$fDate = @filemtime($entry);
	$f["date"] = $fDate;
	$f["fullDate"] = date("r", $fDate);
	$f["shortDate"] = date(translate("DATEFORMAT"), $fDate);
	$f["description"] = getDescription($entry);
	if ($f["isDirectory"]) {
		$f["type"] = "&lt;DIR&gt;";
		$f["size"] = "";
		$f["niceSize"] = "";
		
		// building the link
		if ($entry=="..") {
			// strip the last directory from the path
			$pathArr = explode("/",$path);
			$link = implode("/",array_slice($pathArr,0,count($pathArr)-2));
			
			// if there is no path set, don't add it to the link
			if ($link=="") {
				// we're already in $baseDir, so skip the file
				if ($path=="")
					continue;
				$f["link"] = $_SERVER["PHP_SELF"];
			} else {
				$link.= "/";
				$f["link"] = $_SERVER["PHP_SELF"]."?path=".urlEncode($link);
			}
			$f["isBack"] = true;
			if ($useBackForDirUp) {
				$f["displayName"] = translate("[ back ]");
			}
		} else {
			$filesInDir = getDirSize($entry);
			if ($filesInDir==1) {
				$f["niceSize"] = translate("1 item");
			} else {
				$f["niceSize"] = sprintf(translate("%d items"),$filesInDir);
			}
			$f["link"] = getPathLink($path.$entry);
		}
	} else {
		if (is_link($entry)) {
			$linkTarget = readlink($entry);
			$pi = pathinfo($linkTarget);
			$scriptDir = dirname($_SERVER["SCRIPT_FILENAME"]);
			if (strpos($pi["dirname"], $scriptDir)===0) {
				$f["type"] = "&lt;LINK&gt;";
				// links have no date, so take the target's date
				$f["date"] = filemtime($linkTarget);
				$f["link"] = $path.urlencode(substr($linkTarget, strlen($scriptDir)+1));
			} else {
				// link target is outside of script directory, so skip it
				continue;
			}
		} else {
			$fSize = @filesize($entry);
			$f["size"] = $fSize;
			$f["fullSize"] = number_format($fSize,0,".",",");
			$f["niceSize"] = nicesize($fSize);
			$pi = pathinfo($entry);
			$f["type"] = $pi["extension"];
			$f["link"] = myEncode($path.$entry);
		}
	}
	if (!$f["isBack"]) {
		$f["displayName"] = htmlentities(iTrunc($f["name"], $truncateLength));
	}
	$f["filetype"] = getFileType($f);
	$f["icon"] = getIcon($f["filetype"]);
	if ($useAutoThumbnails && $f["filetype"]=="image") {
		$f["thumbnail"] = "<img src=\"".$PHP_SELF."?thumbnail=".$path.$f["name"]."\"/>";
	}

	$files[] = $f;
}

usort($files, "myCompare");


$pagingInEffect = $usePaging>0 && count($files)>$usePaging;
if ($usePaging>0) {
	$pageStart = $_GET["start"];
	if ($pageStart=="" || $pageStart<0 || $pageStart>count($files))
		$pageStart = 0;
	$pagingActualPage = floor($pageStart / $usePaging);
	$pagingNumberOfPages = ceil(count($files) / $usePaging);
} else {
	$pageStart = 0;
	$usePaging = count($files);
}
$pageEnd = min(count($files),$pageStart+$usePaging);



/***************************************************************************/
/**  HTML OUTPUT                                                          **/
/***************************************************************************/

$columns = count($displayColumns);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?echo translate("Index of"); echo htmlentities(dirname($_SERVER["PHP_SELF"])."/".$path);?></title>
		<style type="text/css">
			.snif * {
				font-family: Tahoma, Sans-Serif;
				font-size: 10pt;
			}
			.snif img {
				border:none;
			}
			.snif a, a.snif {
				text-decoration: none;
			}
			.snif a:hover, a.snif:hover {
				text-decoration: underline;
			}
			body.snif {
				background: #ffffff;
			}
			table.snif {
				border: 1px solid #444444;
				<?
				if ($tableWidth100Percent) {
					echo "width:100%;";
				}
				?>
			}
			table.snif td {
				padding-left: 10px;
				padding-right: 10px;
			}
			td.snifDir {
				font-weight: bold;
				color: #ffffff;
				background-color: #000000;
				padding-top: 3px;
				padding-bottom: 3px;
			}
			td.snifDir a {
				color:white;
			}
			tr.snifHeading, td.snifHeading, td.snifHeading a {
				font-weight: bold;
				color: #dddddd;
				background-color: #444444;
				padding-top: 3px;
				padding-bottom: 3px;
			}
			tr.snF td {
				color: #444444;
				padding-top: 2px;
				padding-bottom: 2px;
				vertical-align: top;
				padding-left: 10px;
				padding-right: 10px;
				white-space:nowrap;
			}
			tr.snF td a {
				color: #000000;
			}
			tr.snF td a:hover, a.snif:hover {
				background-color: #bbbbee;
			}
			tr.snifEven {
				background-color: #eeeeee;
			}
			tr.snifOdd {
				background-color: #dddddd;
			}
			.snifCopyright * {
				color: #bbbbbb;
				font-size: 8pt;
			}
			.snifSmaller {
				font-weight: normal;
				font-size: 8pt;
			}
			.snWhite {
				color: white;
			}
			.snW {
				white-space:normal;
			}
		</style>
	</head>
<body class="snif">
<?
if (count($displayError)>0) {
	foreach($displayError AS $error) {
		echo "<b style=\"color:red\">$error</b><br/>";
	}
	echo "<br/>";
}
?>
<table cellpadding="0" cellspacing="0" class="snif">
	<tr>
		<td class="snifDir" colspan="<?echo count($displayColumns)?>">
						<?
						$baseDirname = $snifServer.htmlentities(dirname($_SERVER["PHP_SELF"]));
						$pathToSnif = explode("/",$baseDirname);
						echo "http://".join("/",array_slice($pathToSnif, 0, -1))."/";
						echo "<a href=\"".dirname($_SERVER["PHP_SELF"])."/\">".join("/",array_slice($pathToSnif, -1))."</a>";
						$pathArr = explode("/",$path);
						for ($i=0; $i<count($pathArr)-1; $i++) {
							$dirLink = getPathLink(join("/",array_slice($pathArr, 0, $i+1)));
							echo "/<a href=\"$dirLink\">".$pathArr[$i]."</a>";
						}
						?><br/>
						<span class="snifSmaller"><?echo $descriptions["."];?></span>
		</td>
	</tr>
	<?
	if ($pagingInEffect) {
	?>
	<tr class="snifHeading">
		<td class="snifHeading" colspan="<?echo count($displayColumns)?>">
			<?
			echo getPagingHeader();
			?>
		</td>
	</tr>
<?
	}
?>
	<tr class="snifHeading">
		<?
		foreach($displayColumns AS $column) {
			switch ($column) {
				case "name":
					?>
					<td class="snifHeading">
						<img src="<?echo $PHP_SELF?>?getimage=blank" alt="" width="30" height="16" style="vertical-align:middle;"/><a href="<?echo getNewSortURL("name");?>"><?echo translate("name");?></a>
						<?
						$sort = $_GET["sort"];
						if ($sort=="name")
							echo "<img src=\"".getIcon($_GET["order"])."\" width=\"5\" height=\"3\" style=\"vertical-align:middle;\" alt=\"".translate($_GET["order"])."\"/>";
						?>
					</td>
					<?
					break;
				case "type":
					?>
					<td class="snifHeading">
						<a href="<?echo getNewSortURL("type");?>"><?echo translate("type");?></a>
						<?
						if ($sort=="type")
							echo "<img src=\"".getIcon($_GET["order"])."\" width=\"5\" height=\"3\" style=\"vertical-align:middle;\" alt=\"".translate($_GET["order"])."\"/>";
						?>
					</td>
					<?
					break;
				case "size":
					?>
					<td class="snifHeading" align="right">
						<?
						if ($sort=="size")
							echo "<img src=\"".getIcon($_GET["order"])."\" width=\"5\" height=\"3\" style=\"vertical-align:middle;\" alt=\"".translate($_GET["order"])."\"/>";
						?>
						<a href="<?echo getNewSortURL("size");?>"><?echo translate("size");?></a>
					</td>
					<?
					break;
				case "date":
					?>
					<td class="snifHeading">
						<a href="<?echo getNewSortURL("date");?>"><?echo translate("date");?></a>
						<?
						if ($sort=="date")
							echo "<img src=\"".getIcon($_GET["order"])."\" width=\"5\" height=\"3\" style=\"vertical-align:20%;\" alt=\"".translate($_GET["order"])."\"/>";
						?>
					</td>
					<?
					break;
				case "description":
					?>
					<td class="snifHeading"><?echo translate("description");?></td>
					<?
					break;
			}
		}
		?>
	</tr>
	<?
	for ($i=$pageStart;$i<$pageEnd;$i++) {
	?>
	<tr class="snF <?echo ($i%2==0) ? "snifEven" : "snifOdd"?>">
		<?
		foreach($displayColumns AS $column) {
			switch ($column) {
				case "name":
					echo "<td>";
					if ($files[$i]["isDirectory"] OR !$files[$i]["isDownloadable"]) {
					?><img src="<?echo $PHP_SELF?>?getimage=blank" alt="" width="7" height="16" style="vertical-align:middle;"/>
					<?
					} else {
					?><a href="<?echo $PHP_SELF?>?path=<?echo rawurlencode($path)?>&amp;download=<?echo rawurlencode($files[$i]["name"]);?>"><img src="<?echo getIcon("download")?>" alt="<?echo translate("download");?>" title="<?echo translate("download");?>" width="7" height="16" style="vertical-align:middle;"/></a>
					<?
					}
					?><a href="<?echo $files[$i]["link"];?>" title="<?echo htmlentities($files[$i]["name"]);?>"><img src="<?echo $files[$i]["icon"]?>" alt="" title="<?echo translate($files[$i]["filetype"])?>" width="16" height="16" style="vertical-align:middle;"/>&nbsp;<?echo $files[$i]["displayName"];?>&nbsp;</a>
					<?
					echo "</td>";
					break;
				
				case "type":
					echo "<td>";
					echo $files[$i]["type"];
					echo "</td>";
					break;
				
				case "size":
					echo "<td align=\"right\">";
					echo "	<span title=\"".$files[$i]["fullSize"]." Bytes\">".$files[$i]["niceSize"]."</span>";
					echo "</td>";
					break;
				
				case "date":
					echo "<td>";
					echo "<span title=\"".$files[$i]["fullDate"]."\">".$files[$i]["shortDate"]."</span>";
					echo "</td>";
					break;
				
				case "description":
					?><td class="snW"><?echo $files[$i]["description"];?>
					<?
					if ($files[$i]["filetype"]=="image") {
						?>
						<img src="<?echo $PHP_SELF?>?thumbnail=<?echo $path.$files[$i]["name"]?>" alt=""/>
						<?
					}
					?></td><?
					break;
			}
		}
		?>
	</tr><?
	}
	if ($pagingInEffect) {
	?>
	<tr class="snifHeading">
		<td class="snifHeading" colspan="<?echo $columns?>">
			<?
			echo getPagingHeader();
			?>
		</td>
	</tr>
<?
	}
?>
</table>
<div class="snifCopyright">
<br/>
<a href="http://www.bitfolge.de/snif">
snif 1.4
&copy; 2003-04 Kai Blankenhorn</a><br/>
<a href="http://validator.w3.org/check/referer"><?echo translate("valid");?> XHTML 1.1</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer"><?echo translate("valid");?> CSS 2</a>
</div>
</body>
</html>