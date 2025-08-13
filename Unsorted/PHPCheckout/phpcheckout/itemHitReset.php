<!-- itemHitReset.php -->
<!-- allow hits to be reset -->
<p></p>
<h2>Reset Hits</h2>

<div style="background-color:#FFCCCC;">
<p><br></p>
<p>
	<span style="font-weight:bold;color:red;">WARNING!</span> This lets you set the hits for ALL items to any number. 
<br>To reset hits for one (1) item only use edit item for the specific item.</a>
</p>



<form name="hitResetForm" method="post" action="adminresult.php" onsubmit="popup()" target="popup">

<p>
Reset all hit values to ... <input type="text" name="newHitValue" value="" size=8>
	<input class="submit" type="submit" name="submit" value="Go">
	<input type="hidden" name="goal" value="Reset Hits">
</p>
</form>
<p><br></p>
</div>
<!-- itemHitReset.php -->