<?php
//'fixes' back button
if ($_SESSION['dirtyhackdone']) {
	?>
	<script type="text/javascript">          
		window.location = '<?php echo $refpage; ?>';
	</script>
	<?php
}
?>

<!DOCTYPE html> 
<html id='html' style='height:100%; width:100%'>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable:no;">

</head>
<body id='body' style='margin:0; height:100%; width:100%'>
	<div id='test' style='width:100%; height:100%'></div>

<script type='text/javascript'>

var width
var height
var dhwidth
var dhheight
var foo

function dirtyheighthack(){

		width = window.outerWidth
		if (window.outerHeight >= screen.height){
			height = window.outerHeight
		}else{
			height = screen.height
		}
		if (width >= height){
			if (width == dhwidth && height == dhheight) {
				foowidth = width
				width = height
				height = foowidth
				senddims();
				return null;
			}else{
				dhwidth = width
				dhheight = height
				setTimeout(function(){
					dirtyheighthack()
					},100)
			}
		}else{
			senddims();
			return null;
		}

}

function senddims(){

	var myForm = document.getElementById('form')
	var myInputwidth = document.getElementById('inputwidth')
	var myInputheight = document.getElementById('inputheight')
	myInputwidth.setAttribute("value",width)
	myInputheight.setAttribute("value",height)
	myForm.submit();
	return null;
}

</script>
<form style="display:none;" id="form" method="POST" action="<?php echo $_SESSION['refpage'].$sessionid; ?>">
   <input id = "inputwidth" name="dirtywidth">
   <input id = "inputheight" name="dirtyheight">
</form>
<script type='text/javascript'>

window.onload = function(){
	

var element = document.getElementById('test')
var body = document.getElementById('body')
var html = document.getElementById('html')
element.style.height=element.clientHeight+100+'px'
body.scrollTop = 100
html.scrollTop = 100
dirtyheighthack()
}

</script>
</body>
</html>
<?php
$_SESSION['dirtyhackdone'] = true;
die();
?>
