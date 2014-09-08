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


//Write agent info to PHP array
function write($x,$dbase){
	
	$sql = "SELECT descr, width, device, fontface, borderradius, boxshadow, canvas, audio, video, inlinesvg, flexbox, flexboxlegacy, canvastext, webgl, touch, geolocation, postmessage, websqldatabase, indexeddb, hashchange, history, draganddrop, websockets, rgba, hsla, multiplebgs, backgroundsize, borderimage, textshadow, opacity, cssanimations, csscolumns, cssgradients, cssreflections, csstransforms, csstransforms3d, csstransitions, generatedcontent, localstorage, sessionstorage, webworkers, applicationcache, svg, smil, svgclippaths FROM devices WHERE ";
	$dbresult = dbopen($sql.$x,$dbase);		
	$rowresult=pg_fetch_array($dbresult[0], NULL, PGSQL_ASSOC);
	$resultkeys = array_keys($rowresult);
	$count=0;
	foreach ($rowresult as $row) {
		if($row == 't' || $row == 'f'){
			if ($row == 't'){
			$agentIq[$resultkeys[$count]] = true;
			}
			if ($row == 'f'){
			$agentIq[$resultkeys[$count]] = false;
			}
		}else{
			if ($row == '0'){
				$agentIq[$resultkeys[$count]] = 'var';
			}else{
				$agentIq[$resultkeys[$count]] = $row;					
			}	
		}
		$count++;
	}
	dbclose($result[0],$result[1]);	
	return $agentIq;
}

?>
