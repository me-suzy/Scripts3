<?
/* set this to prevent this page from being cached */
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
?>

<html>
<body>
<div align=center>


<?
require("mod.guests.php");
$guests = new guests();
?>

<?= $guests->num(); ?> insgesamt<br>
<?= $guests->num(0); ?> angemeldet<br>
<?= $guests->num(1); ?> bezahlt<br>
<?= $guests->num(2); ?> platz reserviert<br>
<?= $guests->num(3); ?> eingecheckt<br>


</div>
</body>
</html>