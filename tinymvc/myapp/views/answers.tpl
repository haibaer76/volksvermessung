<h2>Zusammenfassung</h2>

<p>Du hast folgende Antworten gegeben</p>

<table border='0'>
	<thead><tr>
		<th>Fragestellung</th>
		<th>Deine Angabe</th>
	</tr></thead>
	<tbody>
		{foreach from=$properties item=prop}
			<tr>
				<td valign='top'>{$prop.label|htmlspecialchars}</td>
				<td>{$prop.value|htmlspecialchars}</td>
			</tr>
		{/foreach}
	</tbody>
</table>

<form {$form_finish_form_data.attributes}>
	{$form_finish_form_data.hidden}
	<input type="submit" value="Abschliessen">
</form>

<a href="{$base_url}fragen/seite/1">Zurueck zu den Fragen</a>

