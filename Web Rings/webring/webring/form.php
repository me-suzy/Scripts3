<form method="post" action="process.php">
<input type="hidden" name="added" value="<? echo strtotime("now"); ?>">
	<table cellpadding="5">
		<tr>
			<td colspan="2"><b>action:</b> <input type="radio" name="action" value="submit"> submit <input type="radio" name="action" value="modify"> modify <input type="radio" name="action" value="remove"> remove</td>
		</tr>
		<tr>
			<td><b>site id:</b> (for modify & remove only)<br> <input type="text" name="id" size="35"></td>
			<td><b>password:</b> (don't modify password)<br> <input type="password" name="password" size="35"></td>
		</tr>
		<tr>
			<td><b>name:</b><br><input type="text" name="name" size="35"></td>
			<td><b>email:</b><br><input type="text" name="email" size="35"></td>
		</tr>
		<tr>
			<td><b>site name:</b><br><input type="text" name="site_name" size="35"></td>
			<td><b>url:</b><br><input type="text" name="url" size="35"></td>
		</tr>
		<tr>
			<td colspan="2"><b>description:</b> (255 char. limit)<br><textarea name="description" cols="61" rows="5"></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td align="right"><input type="submit" name="submit" value="submit"></td>
		</tr>
	</table>
</form>
