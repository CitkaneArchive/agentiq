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

function dbopen($sql,$dbase) {
	$dbh = pg_connect($dbase);
	
	if (!$dbh) {
		die("Error in connection: " . pg_last_error());
	};

	$dbresult = pg_query($dbh, $sql);
	if (!$dbresult) {
		die("Error in SQL query: " . pg_last_error());
	};
	return array($dbresult,$dbh);
};
function dbclose($dbresult, $dbh) {
	pg_free_result($dbresult);
	pg_close($dbh);
};
?>
