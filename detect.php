<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
//Prevent caching so that back button behaviour works - must be at top of code with no white space!!!!

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
require_once('strings.php');


$sessin = strip_tags($_GET['PHPSESSID']);
session_id ($sessin);

session_start();
//'fixes' back button
if (strip_tags($_SESSION['detectdone'])) {
	?>
	<script type="text/javascript"> 
		window.location = '<?php echo $refpage; ?>';
	</script>
	<?php
}
unset($_SESSION['agentiq']);
$useragent = strip_tags($_SESSION['agent']);
$dirtyagent = strip_tags($_SESSION['dirtyagent']);
$refpage = strip_tags($_SESSION['refpage']);
$dirtywidth = strip_tags($_SESSION['dirtywidth']);
$dirtyheight = strip_tags($_SESSION['dirtyheight']);
$sessionid = strip_tags('?PHPSESSID='.session_id());
$ismobile = strip_tags($_SESSION['ismobile']);


if (strip_tags($_SESSION['isposted']) == 'yes'){	
	include 'process.php';
}
$_SESSION['isposted'] = 'yes';

?>

<!DOCTYPE html> 
<html class='agentiq'>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable:no;">
		<link type="text/css" rel="stylesheet" media="all" href="css/agentiqreset.css" />
		<link type="text/css" rel="stylesheet" media="all" href="css/agentiq.css" />
		<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="js/modernizr.js"></script>
		<script type="text/javascript">	
		
		//Not fully implemented yet - got to investigate issues with Apple Retina Displays
		if (window.devicePixelRatio){
			var ratio = window.devicePixelRatio;
		}else{
			var ratio = 1;
		}

		var width;
		var height;
		var device;
		var width2;
		var height2;
		var myInput;		
		var address = '<?php echo $refpage; ?>'
		var noreturn

		</script> 	
		
		
		<script type="text/javascript">
		//Create a form used to post to database in process.php
		var myForm = document.createElement("form");
			myForm.method="POST" ;
			myForm.action = '/agentiq/detect.php<?php echo $sessionid; ?>';	
		
		//For mobile devices - the only reliable way to get device width and height is for the user to physically rotate the device. Height is not reliable due to certain devices permanantly displaying the OS menu and chrome. Width is reliable.
		function getsize(x) {			
			if (x == 'landscape'){
				height = window.outerWidth
				$('h1').html('Please rotate your device to teach our site how to best display itself');
				$(window).resize(function() {
					
					function widthset(){
						
						if (window.outerWidth !== height){
							$('h1').html('Just a moment now - Thank you for making the internet better.');
							if (noreturn !=='stop'){
								width = window.outerWidth
								
								post();
								return null
							}else{
								return null
							}
							noreturn ='stop'
							}else{
								//loop until the new orientation has registered on device
								setTimeout(function(){
								widthset();					
								},100)	
							}						
						}
				widthset();					
				})
			}else if (x == 'portrait'){	
				width =  window.outerWidth
				$('h1').html('Please rotate your device to teach our site how to best display itself');
				$(window).resize(function() {
					
					function heightset(){
						
						if (window.outerWidth !== width){
							$('h1').html('Just a moment now - Thank you for making the internet better.');
							if (noreturn !=='stop'){
								height = window.outerWidth
								
								post();
								return null
							}else{
								return null
							}
							noreturn ='stop'
							}else{
								//loop until the new orientation has registered on device
								setTimeout(function(){
								heightset();					
								},100)	
							}						
						}
				heightset();					
				})		
			}
		}

		//Add non Modernizr data to form and submit to database
		function dataenter(name,value){
		myInput = document.createElement("input") ;
		myInput.setAttribute("name", name) ;
		myInput.setAttribute("value", value);
		myForm.appendChild(myInput) ;
		}
		function post() {
			dataenter('device',device)			
			dataenter('width',width)
			dataenter('height',height)
			dataenter('density',ratio)
			//alert ('posting to process')
			$('#form').submit();
			return null
		}
		
		// create a document which is going to scroll to remove address bar on mobile before doing tests
		$(document).ready(function () {
			$('body').css({'height':'200%'})
			$('html, body').scrollTop(50).css({'width':window.outerWidth})
			$('h1').css({'paddingTop':70})
			
			//Report on device physical properties
			function getpix() {
				
				if (ismobile == 'yes' && !Modernizr.touch) {
					width='var';
					height='var';
					device = 'fakeagent';
					post();
					return null						
				} else if (ismobile == 'yes') {
					if (window.outerWidth > 0){
						width = window.outerWidth;
					}else{
						width = undefined
					}
					if (window.outerHeight > 0){
						height = window.outerHeight;
					}else{
						height = undefined
					}									
					device = 'mobile';
					if (dirtywidth && dirtyheight){
						width = dirtywidth;
						height = dirtyheight;
						post();
						return null;
					}
				}else{
				//set width to zero if device is not 'mobile' ie. the window will be resizeable
				width='var';
				height='var';
				device = 'desktop';
				post();
				return null;
				}
				//loop round till the numbers have settled due to screen scaling taking a while on mobile loading
				if (device == 'mobile' && width == width2 && height == height2 && width && height) {
					//report in portrait mode consistently
					if (width > height) {
						getsize('landscape');
					}
					if (height > width) {
					getsize('portrait');
					}

				}else{
				width2 = width;
				height2 = height;
				setTimeout(function(){
					getpix()
					},100)
				}
	
			}
			//add all the non-function Modernizr properties. There are potentially more properties to be got by running functions - one step at a time though.
			for (key in Modernizr) {
				if (Modernizr.hasOwnProperty(key) && (eval('Modernizr.'+key)==true || eval('Modernizr.'+key)==false)) {
					myInput = document.createElement("input") ;
					myInput.setAttribute("name", key) ;
					myInput.setAttribute("value", eval('Modernizr.'+key));
					myForm.appendChild(myInput) ;			
				};
			}
			//add the complete form of data to the document - ready for posting	
			document.body.appendChild(myForm);
			$('form').attr('id','form').css({'display':'none'});
			//transfer ismobile and dirtywidth/height to javascript
				<?php 
				if ($ismobile){ ?> var ismobile = 'yes'; <?php
				}else{ ?> var ismobile = 'no'; <?php }
				if ($dirtywidth){ ?>var dirtywidth = '<?php echo $dirtywidth; ?>'; <?php 
				}else{ ?> var dirtywidth = false; <?php }
				if ($dirtyheight){ ?>var dirtyheight = '<?php echo $dirtyheight; ?>'; <?php 
				}else{ ?> var dirtyheight = false; <?php } ?>
			//initial function to start checks
			getpix()
		})		
		</script> 
		  

	</head>
	<body> 
				
		<img class = 'agentiq loading' src='images/loading.gif'> 
		<h1></h1>    
	</body>
</html>
