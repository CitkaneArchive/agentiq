<?php
/*
All Agent IQ code is Copyright 2012 by Michael Jonker.

This file is part of Agent IQ.

Agent IQ is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Agent IQ is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Agent IQ.  If not, see <http://www.gnu.org/licenses/>

Agent IQ includes works under other copyright notices and distributed
according to the terms of the GNU General Public License or a compatible
license, including:

jQuery - Copyright (c) 2010 John Resig
Modernizr - Copyright (c) Faruk Ates, Paul Irish, Alex Sexton

*/

require_once('settings.php');
require_once('database.php');
?>
<style type="text/css">
<?php
require_once('css/agentiqreset.css');
require_once('css/agentiq.css');

?>
</style>
<div class = 'agentiq'>
<?php
session_start();



function tableadd($x) {
	if ($x == 't') {
		return ("<td class='true'>" . $x . "</td>");
	}else if ($x == 'f') {
		return ("<td class='false'>" . $x . "</td>");
	}else{
		return ("<td class='false'>bollocks</td>");
	}
	
}
if (!$filter){
	$filter = $_SESSION['filter'];
}
if ($filter == 'same_guest') {
	
?>
	<div class = 'agentiq'>Your browser was added to the database, thanks!</div>
<?php
	echo '<div class="active agentiq">'.$agentIq['descr'].'</div>';
} else if ($filter == 'approved') {
?>
				<div class = 'agentiq'>Your browser config is already in the database:</div>
<?php
		echo '<div class="active agentiq">' . $agentIq['descr'] . '</div>';
	} else {
		echo '<div class="active agentiq">' . $agentIq['descr'] . '</div>';
	}


$sql = "SELECT * FROM devices ORDER BY official ASC, device DESC, count DESC, descr DESC";


$dbresult = dbopen($sql,$dbase);


$row2 = pg_fetch_row($dbresult[0], NULL, PGSQL_BOTH);
$title=array_keys($row2);
$count2 = count($title);
?>
		<div id='devices' class='agentiq'>
			<table>
				
				<tr class='title agentiq'>
					<td class='uagent agentiq'>Discovered Devices <br>('Official' in colour blocks)</td>
					<td class='title2 agentiq'>Type</td>
					<td class='title2 agentiq'>Width</td>
					<td class='title2 agentiq'>Height</td>
<?php
		for ($i=29; $i<=$count2; $i=$i+2) {
			echo '<td class="title agentiq"><a class="tdwrap agentiq">'.$title[$i].'</a></td>';		
		}
?>
				</tr>

<?php

$check[0] = $row2;
	echo '<tr>';
	if ($check[0]['official']=='t'){
	echo '<td class="uagent official agentiq">'. $check[0]['descr'] . '</td>';
	}else{
	echo '<td class="uagent agentiq">'. $check[0]['descr'] . '</td>';	
	}
	if ($check[0]['device'] == 'mobile') {
		echo '<td class="device mobile agentiq">'.$check[0]['device'].'</td>';
	}else{
		echo '<td class="device desktop agentiq">'.$check[0]['device'].'</td>';
	}

	if ($check[0]['width'] !== 'var') {
		echo "<td class='aiq agentiq'>";
		echo "<span class='size'>".$check[0]['width']."</span>";
		echo "</td>";
	} else {
		echo '<td class="agentiq">var</td>';
	}
	if ($check[0]['height'] !== 'var') {
		echo "<td class='aiq agentiq'>";
		echo "<span class='size'>".$check[0]['height']."</span>";
		echo "</td>";
	} else {
		echo '<td class="agentiq">var</td>';
	}
	for ($i=14; $i<=$count2/2-1; $i++) {

		echo tableadd($check[0][$i]);	
		}

	echo '</tr>';


$count = 1;

while ($row = pg_fetch_array($dbresult[0], NULL, PGSQL_BOTH)) {
	$check[$count] = $row;
	echo '<tr class = "agentiq">';
	if ($check[$count]['official']=='t'){
	echo '<td class="uagent official agentiq">'. $check[$count]['descr'] . '</td>';
	}else{
	echo '<td class="uagent agentiq">'. $check[$count]['descr'] . '</td>';	
	}
	if ($check[$count]['device'] == 'mobile') {
		echo '<td class="device mobile agentiq">'.$check[$count]['device'].'</td>';
	}else{
		echo '<td class="device desktop agentiq">'.$check[$count]['device'].'</td>';
	}

	if ($check[$count]['width'] !== 'var') {
		echo "<td class='aiq agentiq".$check[$count]['width']."'>";
		echo "<span class='size agentiq'>".$check[$count]['width']."</span>";
		echo "</td>";
	} else {
		echo '<td class="agentiq">var</td>';
	}
	if ($check[$count]['height'] !== 'var') {
		echo "<td class='aiq agentiq".$check[$count]['height']."'>";
		echo "<span class='size agentiq'>".$check[$count]['height']."</span>";
		echo "</td>";
	} else {
		echo '<td class="agentiq">var</td>';
	}
	for ($i=14; $i<=$count2/2-1; $i++) {

		echo tableadd($check[$count][$i]);	
		}

	echo '</tr>';
	$count++;
}

dbclose($dbresult[0],$dbresult[1]);

?>
			</table>
		</div>
</div>
