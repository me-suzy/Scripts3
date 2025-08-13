/***************************************************************
Copyright: OSI Codes, PHP Live!
Author: Trent Arbor, trent@osicodes.com ("js king")
***************************************************************/

function do_write( text_to_write )
{
	window.parent.frames['fmain'].window.frames['main'].window.document.write( text_to_write ) ;

	if (typeof(scrollBy) != 'undefined')
	{
		window.parent.frames['fmain'].window.frames['main'].window.scrollBy(0, 6000) ;
		window.parent.frames['fmain'].window.frames['main'].window.scrollBy(0, 6000) ;
	}
	else if (typeof(scroll) != 'undefined')
	{
		window.parent.frames['fmain'].window.frames['main'].window.scroll(0, 6000) ;
		window.parent.frames['fmain'].window.frames['main'].window.scroll(0, 6000) ;
	};
}

function toggle( menuName )
{
	window.parent.frames['fmain'].window.frames['options'].window.do_toggle(menuName) ;
}