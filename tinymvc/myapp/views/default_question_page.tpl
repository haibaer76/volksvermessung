<h2>{$question_heading}</h2>
{debug}
<form {$form_questions_form_data.attributes}>
	{$form_questions_form_data.hidden}
	<table border='0'>
	{foreach from=$questions item=question}
		<tr>
			<td valign='top'><b>{$question.label|htmlspecialchars}</b></td>
			<td>
				{assign var="form_id" value=$question.property}
				{$form_questions_form_data.$form_id.html}
			</td>
		</tr>
	{/foreach}
	</table>
	<br/>
	<center>
		<input type="submit" value="&raquo; Weiter">
		Seite <b>{$page_number}</b> von <b>{$total_pages}</b>
		{if ($previous_question_link)}
			 - <a href="{$base_url}{$previous_question_link}">Vorherige Seite</a>
		{/if}
	</center>
</form>
