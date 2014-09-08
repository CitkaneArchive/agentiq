<?php
function contains($str,$arr) {

    foreach($arr as $a) {

		$regex = '/\b'.$a.'\b(?!-)/i';
		if (preg_match($regex, $str)) {
			$contains = array(true,$a);
			return $contains;
		}

	}
    return false;
}

function uakey($uagent,$language,$apple,$cruftwords,$msie){
	if (contains($uagent,$apple)){
		$uagentappl = preg_split('/KHTML, like Gecko/',$uagent);
		$uagent = $uagentappl[0];
	}
	if (contains($uagent,$msie)){
		$uagentmsie = preg_split('/NET/',$uagent);
		$uagent = $uagentmsie[0];
	}	
	if (contains($uagent,$cruftwords)){
		$contains = contains($uagent, $cruftwords);
		unset($contains[0]);
		foreach($contains as $strip){	
			$uagent = str_replace($strip ,'',$uagent);			
		}
	}
	if (contains($uagent,$language)){		
		$contains = contains($uagent, $language);
		unset($contains[0]);
		foreach($contains as $strip){	
			$uagent = str_replace($strip ,'',$uagent);			
		}
	}
	$uagent = preg_replace('~[\W\s]~','',$uagent);
	return md5($uagent);
}


?>

