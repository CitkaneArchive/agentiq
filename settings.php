<?php
$dbase = "host=localhost dbname=agentiq user=user password=password";

$threshhold = 5; //how many matching user agent values received from unique IP's before the record becomes 'official'. If a record is 'official' it will never be tested again. Higher values will give you more reliable results. 

$noJSwidth = 320; //Fallback width to assign to generic mobile browsers which do not have Javascript enabled

/*------------------------------------------------------------------------------------------------------------------------
Variable to be used in various tests - do not change unless you know what you are doing!!!!
------------------------------------------------------------------------------------------------------------------------*/

//identifiers for a mobile device
$mobiles = array('iPhone','iPad','Ipod','Android', 'BlackBerry','Mobile', 'Mobi', 'Tablet', 'Phone', 'IEMobile', 'skyfire','XBLWP7','ZuneWP7');

//identifiers for Apple devices
$apple = array('iPhone','iPad','Ipod');

//identifiers MSIE
$msie = array('MSIE');

//words such as 'compatible' which are just user agent cruft - these are removed before keying the agents
$cruftwords = array('compatible','KHTML','like','Gecko');

//Flags for devices which do not identify their model in the user agent. These will run the 'Dirty Width Hack' or fall back to the $noJSwidth width if JS is not enabled
$genericflags = array('generic','iPhone','iPad','iPod');

//language strings to be filtered out
$language = array("ar_ae","ar_bh","ar_dz","ar_eg","ar_iq","ar_jo","ar_kw","ar_lb","ar_ly","ar_ma","ar_om","ar_qa","ar_sa","ar_sy","ar_tn","ar_ye","de_at","de_ch","de_li","de_lu","en_au","en_bz","en_ca","en_gb","en_ie","en_jm","en_nz","en_tt","en_us","en_za","es_ar","es_bo","es_cl","es_co","es_cr","es_do","es_ec","es_gt","es_hn","es_mx","es_ni","es_pa","es_pe","es_pr","es_py","es_sv","es_uy","es_ve","fr_be","fr_ca","fr_ch","fr_lu","it_ch","nl_be","pt_br","ro_md","ru_md","sv_fi","zh_cn","zh_hk","zh_sg","zh_tw","ar-ae","ar-bh","ar-dz","ar-eg","ar-iq","ar-jo","ar-kw","ar-lb","ar-ly","ar-ma","ar-om","ar-qa","ar-sa","ar-sy","ar-tn","ar-ye","de-at","de-ch","de-li","de-lu","en-au","en-bz","en-ca","en-gb","en-ie","en-jm","en-nz","en-tt","en-us","en-za","es-ar","es-bo","es-cl","es-co","es-cr","es-do","es-ec","es-gt","es-hn","es-mx","es-ni","es-pa","es-pe","es-pr","es-py","es-sv","es-uy","es-ve","fr-be","fr-ca","fr-ch","fr-lu","it-ch","nl-be","pt-br","ro-md","ru-md","sv-fi","zh-cn","zh-hk","zh-sg","zh-tw","af","be","bg","ca","cs","da","de","el","en","es","et","eu","fa","fi","fo","fr","ga","gd","he","hi","hr","hu","id","is","it","ja","ji","ko","ko","ku","lt","lv","mk","ml","ms","mt","nl","nb","nn","no","pa","pl","pt","rm","ro","ru","sb","sk","sl","sq","sr","sv","th","tn","tr","ts","uk","ur","ve","vi","xh","zu");


//To get width when a user comes in on mobile landscape some guessework has to be done. Unlike width, there is no crossbrowser standard for height detection as the chrome is inconsistently included or excluded. Some have chrome top and bottom. We thus add this value to the registered height and result will be this value under, over or on the mark (assuming this value is the most common chrome height in pixels).
$dirtychrome = 60;
?>
