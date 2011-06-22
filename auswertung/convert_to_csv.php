<?php
mysql_connect("localhost", "root", "mysql");
mysql_select_db('volksverm_prod');
mysql_set_charset('utf8') or die(mysql_error());

$outfile = fopen("outfile", "w");
$heads = array('intId', 'bogenID', 'angelegt', 'abgeschickt');

$query = mysql_query("SELECT DISTINCT(property) FROM t_person_properties") or die(mysql_error());
$properties = array();
while($row=mysql_fetch_row($query)) {
	$heads[] = $row[0];
	$properties[] = $row[0];
}

fputcsv($outfile, $heads);
$queryPerson = mysql_query("SELECT * FROM t_persons") or die(mysql_error());
while($rowPerson = mysql_fetch_assoc($queryPerson)) {
	$line = array(
		$rowPerson['id'], $rowPerson['bogen_id'], $rowPerson['created_at'], $rowPerson['submitted_at']);
	foreach($properties as $prop)
		$line[] = query_property($rowPerson['id'], $prop);
	fputcsv($outfile, $line);
}

fclose($outfile);

function query_property($person_id, $property) {
	$query = mysql_query("SELECT value FROM t_person_properties WHERE person_id = ".intval($person_id)." AND property='".mysql_real_escape_string($property)."' LIMIT 1") or die(mysql_error());
	$row = mysql_fetch_row($query);
	if (!$row)
		return null;
	return $row[0];
}
?>
