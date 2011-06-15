<h2>Zusammenfassung</h2>

<p>Du hast folgende Antworten gegeben:</p>

<table border='0'>
	<thead><tr>
		<th>Fragestellung</th>
		<th>Deine Angabe</th>
	</tr></thead>
	<tbody>
		{foreach from=$properties item=prop}
			<tr>
				<td valign='top'>{$prop.label|htmlspecialchars}</td>
				<td><i>{$prop.value|htmlspecialchars}</i></td>
			</tr>
		{/foreach}
	</tbody>
</table>

<form {$form_finish_form_data.attributes}>
	<center>
		{$form_finish_form_data.hidden}
		<input type="submit" value="&raquo; Abschliessen">
		<a href="{$base_url}fragen/seite/1">Zur&uuml;ck zu den Fragen</a>
	</center>
</form>



