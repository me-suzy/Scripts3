<!--
// copyright OSI Codes, PHP Live! Support
// http://www.osicodes.com
function dounique()
{ var date = new Date() ; return date.getTime() ; }

var tracker_refresh = 10000 ; // 1000 = 1 second
var do_tracker_flag_<? echo $HTTP_GET_VARS['btn'] ?> = 1 ;
var start_tracker = dounique() ;
var time_elapsed ;
var refer = escape( document.referrer ) ;

var initiate = 0 ;
var chat_opened = 0 ;
var pullimage_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?> = new Image ;
var date = new Date() ;
var unique = dounique() ;
var url = escape( location.toString() ) ;
var phplive_image_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?> = "<? echo $HTTP_GET_VARS['base_url'] ?>/image.php?l=<? echo $HTTP_GET_VARS['l'] ?>&x=<? echo $HTTP_GET_VARS['x'] ?>&deptid=<? echo $HTTP_GET_VARS['deptid'] ?>&page="+url+"&unique="+unique+"&refer="+refer ;

function checkinitiate_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>()
{
	initiate = pullimage_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>.width ;
	if ( ( initiate == 2 ) && !chat_opened )
	{
		chat_opened = 1 ;
		launch_support_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>() ;
	}
	else if ( ( initiate == 1 ) && chat_opened )
		chat_opened = 0 ;
}
function do_tracker_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>()
{
	// check to make sure they are not idle for more then 1 hour... if so, then
	// they left window open and let's stop the tracker to save server load time.
	// (1000 = 1 second)
	var unique = dounique() ;
	time_elapsed = unique - start_tracker ;
	if ( time_elapsed > 3600000 )
		do_tracker_flag_<? echo $HTTP_GET_VARS['btn'] ?> = 0 ;

	pullimage_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?> = new Image ;
	pullimage_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>.src = "<? echo $HTTP_GET_VARS['base_url'] ?>/image_tracker.php?l=<? echo $HTTP_GET_VARS['l'] ?>&x=<? echo $HTTP_GET_VARS['x'] ?>&page="+url+"&unique="+unique ;

	pullimage_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>.onload = checkinitiate_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?> ;
	if ( do_tracker_flag_<? echo $HTTP_GET_VARS['btn'] ?> == 1 )
		setTimeout("do_tracker_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>()",tracker_refresh) ;
}
function launch_support_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>()
{
	var request_url_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?> = "<? echo $HTTP_GET_VARS['base_url'] ?>/request.php?l=<? echo $HTTP_GET_VARS['l'] ?>&x=<? echo $HTTP_GET_VARS['x'] ?>&deptid=<? echo $HTTP_GET_VARS['deptid'] ?>&page="+url ;
	newwin = window.open( request_url_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>, unique, 'scrollbars=no,menubar=no,resizable=0,location=no,screenX=50,screenY=100,width=450,height=350' ) ;
	newwin.focus() ;
}
var status_image_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?> = "<a href='JavaScript:void(0)' OnClick='launch_support_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>()'><img src="+phplive_image_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>+" border=0 alt=\"Click for Live Support!\"></a>" ;
document.write( status_image_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?> ) ;
do_tracker_<? echo $HTTP_GET_VARS['btn'] ?>_<? echo $HTTP_GET_VARS['deptid'] ?>() ;
//-->