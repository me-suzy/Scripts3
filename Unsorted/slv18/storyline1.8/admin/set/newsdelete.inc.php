<font class='catdis'> > Delete News </font><p>

<?
if( !$_POST["deletesubmit"] ) {
?>
	<form method='post' action='main.php?newsdelete'>
	News id# <input type='text' name='nid'><br>
	<input type='submit' name='deletesubmit' value='delete news item' onClick='return confirm("Sure you want to delete?")'>
	</form>
<?
} else {
	
	$dl->delete("sl18_news",array('nid'=>$_POST["nid"]));
	print "Post has been deleted";

}