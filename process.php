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

require_once('database.php');
$_SESSION['added'] = 'new';
$filter = strip_tags($_SESSION['filter']);
$IP = strip_tags($_SESSION['IP']);
$clientinfo = strip_tags($_SESSION['clientinfo']);
$fullagent = strip_tags($_SESSION['fullagent']);
$_SESSION['dirtywidth'] = strip_tags( $_POST['width']);
$_SESSION['dirtyheight'] = strip_tags( $_POST['height']);
$dirtywidth = $_SESSION['dirtywidth'];
$dirtyheight = $_SESSION['dirtyheight'];
$dirtyhackdone = $_SESSION['dirtyhackdone'];
$dpi = strip_tags( $_POST['dpi']); 
if ($dirtyhackdone) {
$useragent = $useragent.' '.'aiq('.$dirtywidth.$dirtyheight.')';	
}
//add comparative data to comma seperated string for SQl
$count=0;
foreach ($_POST as $key => $entry){
	if ($count==0){
		$cols=$key;
		if ($entry == 'true' || $entry == 'false'){
		$vals=$entry;
		}else{
		$vals='\''.$entry.'\'';	
		}
		
	}else{
		$cols=$cols.','.$key;
		if ($entry == 'true' || $entry == 'false'){
		$vals=$vals.','.$entry;	
		}else{
		$vals=$vals.',\''.$entry.'\'';
		}
		
	}
    $count++;
}
//add non-comparative data to comma seperated string for SQl

$cols2=$cols.",ip,count,agent,dirtyagent,fullagent,descr";
$vals2=$vals.",'".$IP."',1,'".$useragent."','".$dirtyagent."','".$fullagent."','".$clientinfo."'";

//add official data to comma seperated string for SQl
$cols3 = $cols.",count,official,agent,dirtyagent,fullagent,descr";
$vals3 = $vals.",1,'1','".$useragent."','".$dirtyagent."','".$fullagent."','".$clientinfo."'";

//add the incoming data to database
if ($filter == 'new_agent' || $filter == 'retest') {

	$sql="INSERT INTO devices (".$cols2.") VALUES (".$vals2.")";
	$dbresult = dbopen($sql,$dbase);
	dbclose($dbresult[0],$dbresult[1]);
}

//get and manipulate the incoming string for comparison
if ($filter == 'retest') {	
	$vals = str_replace("false", "f", $vals);
	$vals = str_replace("true", "t", $vals);
	$vals = str_replace("'", "", $vals);
	$vals = explode(',', $vals);
	natcasesort($vals);
	$vals = implode(',', $vals);
	//get all the matching agents for comparison
	$sql = "SELECT width, height, device, density, fontface, borderradius, boxshadow, canvas, audio, video, inlinesvg, flexbox, flexboxlegacy, canvastext, webgl, touch, geolocation, postmessage, websqldatabase, indexeddb, hashchange, history, draganddrop, websockets, rgba, hsla, multiplebgs, backgroundsize, borderimage, textshadow, opacity, cssanimations, csscolumns, cssgradients, cssreflections, csstransforms, csstransforms3d, csstransitions, generatedcontent, localstorage, sessionstorage, webworkers, applicationcache, svg, smil, svgclippaths, id, count FROM devices WHERE agent = '".$useragent."'";	
	$dbresult = dbopen($sql,$dbase);
	
	//loop through, compare and construct sql for incrementing count on matches
	$count=0;
	while ($row = pg_fetch_array($dbresult[0], NULL, PGSQL_ASSOC)) {
		$rowid = $row['id'];
		$rowcount = $row['count'];
		unset($row['id']);
		unset($row['count']);
		natcasesort($row);
		$row = implode(',', $row);

		if ($row == $vals){
			$idsql[$count] = $rowid;
			if ($rowcount > 1){
				$threshcount = $rowcount;
			}
		}		
		$count++;
	}

	dbclose($dbresult[0],$dbresult[1]);
	//query for all matches to be incremented up
	$idsql = implode(' OR id=', $idsql);
	if ($threshcount){
		$threshcount++;
	}else{
		$threshcount = 2;
	}
	//check if threshhold limit has been reached yet and increment all up if not
	if ($threshcount <= $threshhold) {
		
		$sql = "UPDATE devices SET count=".$threshcount." WHERE id=".$idsql;
		$dbresult = dbopen($sql,$dbase);
		dbclose($dbresult[0],$dbresult[1]);
	}else{
		//if threshhold has been reached, delete all redundant rows and write 'official' one
		$sql="DELETE FROM devices WHERE agent='".$useragent."'";
		$dbresult = dbopen($sql,$dbase);
		dbclose($dbresult[0],$dbresult[1]);
		$sql="INSERT INTO devices (".$cols3.") VALUES (".$vals3.")";
		$dbresult = dbopen($sql,$dbase);
		dbclose($dbresult[0],$dbresult[1]);	
	}
		
	

}
$_SESSION['detectdone'] = true;

?>

	
<script type="text/javascript">
		//alert ('going back to refpage')
		window.location = '<?php echo $refpage; ?>';
</script>

<?php 

die(); 

?>
