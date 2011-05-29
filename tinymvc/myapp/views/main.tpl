<html>
<head>
	<title>BRN-Volksvermessung</title>
</head>
<body>
	<h1>Volksvermessung</h1>
	<a href="{$base_url}">Start</a>
	{if $bogen_id}
		<div id='idShowBogenId'>Aktuelle Bogen-Nummer: {$bogen_id}</div>
	{/if}
	<div id='idContent'>
		{$content}
	</div>
</body>
</html>

