<h2>Passwort setzen</h2>

<p>
Hier musst Du ein Passwort setzen. Mit diesem Passwort kannst Du dann 
sp&auml;ter Deine eingegebenen Daten auch &auml;ndern
</p>

<form {$form_setpasswd_form_data.attributes}>
	{$form_setpasswd_form_data.hidden}
	<table border="0">
	<tr>
		<td>Passwort:</td>
		<td>{$form_setpasswd_form_data.pw1.html}</td>
	</tr>
	<tr>
		<td>Passwort wiederholen:</td>
		<td>{$form_setpasswd_form_data.pw2.html}</td>
	</tr>
	</table>
	<input type='submit' value='Volksvermessung beginnen'>
</form>
