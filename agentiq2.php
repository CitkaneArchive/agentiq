<?php

if (isset($_POST['dirtywidth'])){
	$dirtywidth = strip_tags($_POST['dirtywidth']);
	$dirtyheight = strip_tags($_POST['dirtyheight']);
	 
}else if (isset($_SESSION['dirtywidth'])){
	$dirtywidth = strip_tags($_SESSION['dirtywidth']);
	$dirtyheight = strip_tags($_SESSION['dirtyheight']);		
}
$dirtyhackdone = strip_tags($_SESSION['dirtyhackdone']);
require_once("settings.php");
require_once("strings.php");

//Get various info from ua_parser_php - Thanks to Dave Olsen all involved at https://github.com/tobie/ua-parser 
require("uaparserphp/UAParser.php");
$uaparse = UA::parse();
$spider = $uaparse->isSpider;
$device = $uaparse->device;
$ismobile2 = $uaparse->isMobile;
$devicefull = $uaparse->deviceFull;
$_SESSION['devicefull'] = $devicefull;
$browser = $uaparse->full;
if (!$browser){
	$browser = 'Unknown Browser';
}
$refpage = "http://".strip_tags($_SERVER['HTTP_HOST']).strip_tags($_SERVER['REQUEST_URI']);
$_SESSION['refpage'] = $refpage;
$sessionid = strip_tags('?PHPSESSID='.session_id());
$_SESSION['dirtychrome'] = $dirtychrome;
// Check if it is a mobile device - keys for mobile detection in settings.php. I didn't use ua-parser here because I think this list will be easier to maintain if new devices or browsers emerge which do not comply. eg. skyfire browser has no obvious id as mobile and is missed by ua parser.
$ismobile = contains(strip_tags($_SERVER['HTTP_USER_AGENT']),$mobiles);
$ismobile = $ismobile[0];
if ($ismobile || $ismobile2){
$ismobile = true;	
}

$_SESSION['ismobile'] = $ismobile;
if (!$devicefull){
	if (!$ismobile){
	$devicefull = 'Desktop Device';
	}else{
	$devicefull = 'Mobile Device';	
	}
}
	
$_SESSION['clientinfo'] = $devicefull.' - '.$browser;

//Get and key the user agent - filter out robots
if (!$spider){
	if ($_SERVER['HTTP_USER_AGENT']){
		$fullagent = strip_tags($_SERVER['HTTP_USER_AGENT']);
		$_SESSION['fullagent'] = $fullagent;
		$useragent = uakey($fullagent,$language,$apple,$cruftwords,$msie);
		$dirtyagent = $useragent;
				
	}else{
		$fullagent = 'blank';
		$_SESSION['fullagent'] = $fullagent;
		$useragent = uakey($fullagent,$language,$apple,$cruftwords,$msie);
		$dirtyagent = $useragent;			
	}
}else{
$useragent = 'spider';	
}


// Check if it is a generic mobile browser
function flags($genericflags,$device){

	foreach ($genericflags as $flag){
		if (stripos($device,$flag) !== false) {
			return true;
		}
	}
	return false;
}

//check to see if we need to run the dirty hack
if ($ismobile && !$dirtyhackdone){

	if(flags($genericflags,$device) || !$device) {

		include 'dirtyheighthack.php';

	}
}

//These variable set after dirty-hack to make things more efficient for the double load
require_once('database.php');

if (!$_SESSION['IP']){
	$IP = strip_tags($_SERVER['REMOTE_ADDR']);
	$_SESSION['IP'] = $IP;
}else{
	$IP = strip_tags($_SESSION['IP']);
}
$_SESSION['agent'] = $useragent;
$_SESSION['dirtyagent'] = $dirtyagent;
$_SESSION['dirtychrome'] = $dirtychrome;

//collect matching user agent keys for incoming request
if ($dirtyagent){
	$sql = "SELECT id, dirtyagent, ip, height, width, official FROM devices WHERE dirtyagent = '".$dirtyagent."'";
}else{
	$sql = "SELECT id, agent, ip, official FROM devices WHERE agent = '".$useragent."'";
}
$dbresult = dbopen($sql,$dbase);
$count = 0;
while ($row = pg_fetch_array($dbresult[0], NULL, PGSQL_ASSOC)) {
    $check[$count] = $row;
    $count++;
}

function in_array_r($needle, $haystack, $strict = true) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}



//check if user agent exists in database and redirect to 'learning page' if not
if (!$check){
	
	dbclose($dbresult[0],$dbresult[1]);
	$filter = 'new_agent';
	$_SESSION['filter'] = $filter;
	$dirtywidth = false;
	$dirtyheight = false;
	learn($sessionid,$dirtywidth,$dirtyheight);
		
}else{
	
	if (!$dirtyhackdone){
		
	//check if user agent is approved	
		if ($check[0]['official']) {
			$fetchid = $check[0]['id'];
			$filter = 'approved';
			$_SESSION['filter'] = $filter;	
		} else if (in_array_r($IP,$check)){
			
	//Check if the same guest is coming back with the same header and return results for that header		
			$count = 0;		
			foreach ($check as $row) {					
				if ($IP == $row['ip']) {
					$fetchid = $row['id'];
					$filter = 'same_guest';
					$_SESSION['filter'] = $filter;				
				}
				$count++;
			}		
		} else {

	//re-send header to learning page
					
			$filter = 'retest';
			$_SESSION['filter'] = $filter;
			$dirtywidth = $check[0]['width'];
			$dirtyheight = $check[0]['height'];			
			learn($sessionid,$dirtywidth,$dirtyheight);
					
		}
	}else{
		
	//check if user agent is approved

		if ($check[0]['official'] && dimhack($check[0]['width'],$check[0]['height'],$dirtywidth,$dirtyheight)) {

			$fetchid = $check[0]['id'];
			$filter = 'approved';
			$_SESSION['filter'] = $filter;	
		} else if (in_array_r($IP,$check)){

	//Check if the same guest is coming back with the same header and return results for that header		
			$count = 0;	
			foreach ($check as $row) {	

				if ($IP == $row['ip'] && dimhack($row['width'],$row['height'],$dirtywidth,$dirtyheight)) {
					$fetchid = $row['id'];
					$filter = 'same_guest';
					$_SESSION['filter'] = $filter;
													
				}
				$count++;
			}
			if ($filter !== 'same_guest'){
					$dirtywidth = false;
					$dirtyheight = false;
					$filter = 'retest';
					$_SESSION['filter'] = $filter;
					learn($sessionid,$dirtywidth,$dirtyheight);	
			}		
		} else {

	//get proper dims and re-send header to learning page
			
			foreach ($check as $row) {
				$width3 = $row['width'];
				$height3 = $row['height'];				
					if ($width3 == $dirtywidth || $height3 == $dirtyheight){
						dimhack($width3,$height3,$dirtywidth,$dirtyheight);
					}
			}
			$filter = 'retest';
			$_SESSION['filter'] = $filter;
			
			$dirtywidth = $_SESSION['dirtywidth'];
			$dirtyheight = $_SESSION['dirtyheight'];
			learn($sessionid,$dirtywidth,$dirtyheight);
						
		}		
	}	
}

function dimhack($width,$height,$dirtywidth,$dirtyheight) {
	if ($dirtywidth	!== $width){
		if ($dirtywidth >= ($width - $_SESSION['dirtychrome']) && $dirtywidth <= $width){
				$dirtywidth = $width;
				$_SESSION['dirtywidth'] = $dirtywidth;							
			}
		}
	if ($dirtyheight !== $height){
		if ($dirtyheight >= ($height - $_SESSION['dirtychrome']) && $dirtyheight <= $height){
				$dirtyheight = $height;
				$_SESSION['dirtyheight'] = $dirtyheight;
			}
		}
	if ($dirtywidth == $width && $dirtyheight == $height) {
		$_SESSION['dirtywidth'] = $width;
		$_SESSION['dirtyheight'] = $height;
		return true;
	}else {
		return false;
	}
}

include 'process2.php';

if ($filter !== 'new_agent' && $filter !== 'retest') {
		$agentIq = write("id= ".$fetchid,$dbase);
		$_SESSION['agentiq'] = $agentIq;					
	}else{
		//The fallback for no - js unknown devices
		if (!$ismobile){		
			$agentIq = write("agent = 'nojavascript'",$dbase);
			$_SESSION['agentiq'] = $agentIq;
		} else {
			$agentIq = write("agent = 'nojavascriptmob'",$dbase);
			$_SESSION['agentiq'] = $agentIq;			
		}		
			
		
	}

//Redirect to learning page	
function learn($sessionid,$dirtywidth,$dirtyheight){

	$_SESSION['dirtywidth'] = $dirtywidth;
	$_SESSION['dirtyheight'] = $dirtyheight;
	?>
		<!--use js here so that non-js browsers do not redirect-->
		<script type="text/javascript">	
			window.location = "/agentiq/detect.php<?php echo $sessionid."&refpage=".htmlspecialchars($_SESSION['refpage']); ?>"; //pass refpage for back buttons sake

		</script>			           
	<?php

}	

?>
