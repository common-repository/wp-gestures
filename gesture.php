<?php
/*
Plugin Name: Wp-Gestures
Plugin URI: http://www.devilalbum.com/2010/04/plugin-wp-gestures/
Description: This plugin enable a feature that you can use mouse gestures to navigate to the next post or previous post or the home page when on a single post just like mouse gesture in Gmail.
Version: 1.0
Author: yun77op
Author URI: http://devilalbum.com/
License : GPL v3
*/

function ges_script(){
global  $post, $posts ;
$ges_js=WP_PLUGIN_URL . '/wp-gestures/js/gesture.js.php';

$post = $posts[0];
if(is_single()){
$home = get_bloginfo('wpurl');

$pre = get_adjacent_post();
if(!$pre)
$pre=$home;
else $pre = get_permalink($pre);

$next = get_adjacent_post('','',false,'',false);
if(!$next)
$next=$home;
else $next = get_permalink($next) ;

$js_string =<<<EOT
<script type="text/javascript" src="$ges_js"></script>
<script type="text/javascript">
Gesture.prototype.showImg=function(type,distance){
	var gesImg=this.imgs[type];
	if( distance>=20 && distance<80 ){
	gesImg.style.position="absolute";
	this.hiddenImgs();
	gesImg.style.opacity=(distance/80).toFixed(2);
	gesImg.style.filter = "alpha(opacity=" + (distance/80).toFixed(2)*100 + ")";
		switch(type){
	case "top":
		gesImg.style.left=(this.pos[0]-24) + "px";
		gesImg.style.top=(this.pos[1]-99) + "px";
		break;

	case "right":
		gesImg.style.left=(this.pos[0]+61) + "px";
		gesImg.style.top=(this.pos[1]-24) + "px";
	
		break;

	case "left":
		gesImg.style.left=(this.pos[0]-99) + "px";
		gesImg.style.top=(this.pos[1]-24) + "px";

		break;

		}
	gesImg.style.display="block";

	}else if(distance>=80){
	switch(type){
	
		case "top":
			location.href="$home";
			break;

		case "right":
			location.href="$next";	
			break;

		case "left":
			location.href="$pre";
			break;	
	
	}
}else 	{
	gesImg.style.display="none";
	return ;
	}
}
function loadGesture(){
var exp=new Gesture();
}
if (document.addEventListener) {
	document.addEventListener("DOMContentLoaded", loadGesture, false);

} else if (/MSIE/i.test(navigator.userAgent)) {
document.write("<script id='__ie_onload' defer src='javascript:void(0);'><\/script>");
var script = document.getElementById("__ie_onload");
	script.onreadystatechange = function() {
		if (this.readyState == 'complete') {
			loadGesture();
		}
	}

} else if (/WebKit/i.test(navigator.userAgent)) {
	var _timer = setInterval( function() {
		if (/loaded|complete/.test(document.readyState)) {
			clearInterval(_timer);
			loadGesture();
		}
	}, 10);

} else {
	window.onload = function(e) {
		loadGesture();
	}
}
</script>

EOT;
echo $js_string;
}
}

add_action('wp_footer', 'ges_script');
?>
