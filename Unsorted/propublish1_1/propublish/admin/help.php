<? 
$req_level = 2;
$override = 1;
include "inc_t.php";
?>

<html>
<head><title><? echo $la_help ?></title>
</head>
<body>
<p>

<b>List</b><br>
<small>From here, all articles are approved. You can choose to use an editor where you self use html code B, H2 tags 
and similar, or to use the visual editor (wysiwyg) where only word experience is needed in order to use.
<p>
The numbers, 1, 2 and 3 (only for Superadmins), is a prioritymark, where an article marked as 3 will be the 
article that comes first on the frontpage, and 1 is a bit lower priority, but still a priority article
that will reach close to the top on the frontpage.<p>
FP and !FP tells if the article should be posted on the frontpage. Default, it will NOT show
up on the frontpage.</small>
<p>

<b>Lelvels</b> (only admin)<br>
<small>In this program, there are currently 2 levels, 1 and 2. A level 1 is a superuser (can edit all ads, and approve), while 2 is
a limited writer-mode. All ads must be validated by a superuser.</small>
<p>
<b>Articleditor</b><br>
<small>As admin, you can choose the author, otherwise, it is already selected and locked.<p>
The Status is where the author or admin can activate or deactivate articles.<p>
<? echo $la_show_html ?> is how the article should be shown on art.php file (news). Normally,
this should not be selected. If you run a technical site about websitecoding or similar, however, then this option is for you.
Html is then not shown, a B tagg for fold used in article will be displayed as a B tagg, and will NOT make any text Bold.
If you then want Bold text, special chars must be used ([B]). See Charachters allowed for complete list of allowed tags.

</small>

</body>
</html>