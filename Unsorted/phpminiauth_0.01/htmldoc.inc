<?

class htmldoc
{

	//Class constructor.
	function htmldoc()
        {
                return;
        }

	function printheader ( $bg_color, $text_color, $link_color, $vlink_color, $alink_color, $title )
	{
		?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<HTML><HEAD><TITLE><?php echo $title; ?></TITLE>
</HEAD>
<BODY bgcolor="<?php echo $bg_color; ?>"
      text="<?php echo $text_color; ?>"
      link="<?php echo $link_color; ?>"
      vlink="<?php echo $vlink_color; ?>"
      alink="<?php echo $alink_color; ?>">
		<?
	}

	function starttimer ( $root_url )
	{
		?>
<META http-equiv="Refresh" content="1800;URL=<?php echo $root_url; ?>/logout.php3;TARGET=_top">
		<?
	}

	function printfooter ()
	{
		?>
</BODY></HTML>
		<?
	}


}

?>
