<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="de" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252"/>
	<title>BRN Volksvermessung</title>	
	<link rel="stylesheet" href="./theme/core.css" type="text/css"></link>
</head>
<body>
	<div class="wrapper">
		<a class="header" href="{$base_url}">Start</a>
		
		{if $bogen_id}
			<div id='idShowBogenId'>Aktuelle Bogen-Nummer: {$bogen_id}</div>
		{/if}
		
		<div id='idContent'>
			{$content}
		</div>
		
	</div>
</body>
</html>

