<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	// The purpose of this file is to run any updates and global
	// checks.  For instance, we will put auto upgrade checks in here

	// border theme, must be located at your pics/frames/<color> directory
	// available: black, blue, tan, green (others will result in NO color)
	$FRAME = "black" ;

	// crypt key is used to generate an encoded mysql password
	$CRYPTKEY = "PHP Live! Powered (www.osicodes.com)" ;

	// when a call is transferred, let's give a future time of 2 minutes so
	// the visitor does not see a "Party has left" message.  it is giving
	// the operator 2 minutes to pickup or reject.
	$TRANSFER_BUFFER = 120 ;	// seconds

	// take it minus 15 because the admin window refreshes every
	// 5 seconds.  we use 15 to give it some buffer times of when
	// admin opens up multiple windows... thus, there could be up to
	// 15 seconds during someone request chat to admin and he/she is not there.
	$admin_idle_value = 15 ;
	$admin_idle = time() - $admin_idle_value ;

	// chat_timeout: seconds before chat session says "Party has left chat" message.
	// the chat session is refreshed every 2.5 seconds... and also, there is an exit
	// button.  so keep this value high (default 15) so it doesn't give a false
	// message if visitor or yourself launches app or connection breaks for short time.
	// we have noticed 6 seconds is cutting it, but with fast LAN network, it's good.
	// if you have visitors who will request chat via modem, keep this at 15 or more.
	$chat_timeout = 15 ;

	// FOOTPRINT_IDLE: buffer time to tell if a visitor is idle or has left.
	// let's use 15 because the tracking image is refreshed every 10 seconds.  so 15
	// is plenty of time to give for buffer.
	$FOOTPRINT_IDLE = 15 ;	// seconds

	// sets the admin traffic monitor request refresh time (10 or higher is RECOMMENDED)
	// but don't set too high or it will take a long time to see new visitors
	$TRAFFIC_MONITOR_REFRESH = 10 ; // seconds
?>