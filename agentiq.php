<?php
/*
All Agent IQ code is Copyright (c) 2012 Michael Jonker.

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
if ($_GET['PHPSESSID']){
$sessin = strip_tags($_GET['PHPSESSID']);
session_id ($sessin);
}
session_start();

if (!$_SESSION['agentiq']){
	
	include 'agentiq2.php';
	
}else{
	
	$_SESSION['filter']='approved';	
}

?>
