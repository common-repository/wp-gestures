<?php
if(!function_exists('add_action')){
require_once('../../../../wp-config.php');
}
?>

function Gesture(){
this.pos=Array();
this.imgs=Array();
this.container=null;
this.init();
};

Gesture.prototype.init=function (){
var oThis=this;
document.onmousedown=function(e){
oEvent=e || window.event;
 oThis.handleMouseDown(oEvent);
}

document.onmouseup=function(e){
var oEvent=e || window.event;
 oThis.handleMouseUp(oEvent);
}
document.oncontextmenu=function(e){
return false;
}
var con=document.createElement('div');
document.body.appendChild(con);
this.container=con;
this.createImgs('right');
this.createImgs('left');
this.createImgs('top');
};

Gesture.prototype.createImgs=function(type){
var oThis=this;
var img=document.createElement('img');
img.src='<?php bloginfo('wpurl')?>/wp-content/plugins/wp-gestures/imgs/ges_' + type + '.png';
img.id='ges_' + type;
img.style.display='none';
this.container.appendChild(img);
this.imgs[type]=img;
};


Gesture.prototype.handlePos=function(oEvent){
if(oEvent.pageX)
return [oEvent.pageX,oEvent.pageY];
else return [oEvent.clientX+(document.documentElement.scrollLeft||document.body.scrollLeft),oEvent.clientY+(document.documentElement.scrollTop||document.body.scrollTop)];
};


Gesture.prototype.handleMouseDown=function(oEvent){
if(oEvent.button!=2)
return ;
var oThis=this;
this.pos=this.handlePos(oEvent);
document.onmousemove=function(e){
oEvent=e || window.event;
 oThis.handleMouseMove(oEvent);
}

};


Gesture.prototype.handleMouseUp=function(oEvent){
this.hiddenImgs();
document.onmousemove=null;
var oThis=this;
document.onmousedown=function(e){
oEvent=e || window.event;
 oThis.handleMouseDown(oEvent);
}
};

Gesture.prototype.hiddenImgs=function(){
	var aTypes=['right','top','left'];
	for(var i=0;i<3;++i){
	if(this.imgs[aTypes[i]].style.display!='none')
	this.imgs[aTypes[i]].style.display='none';
	}
}

Gesture.prototype.handleMouseMove=function(oEvent){
	var curPos=this.handlePos(oEvent);
	var distanceX=curPos[0]-this.pos[0];
	var distanceY=curPos[1]-this.pos[1];
	var t=(distanceX>=0?1:-1)+(distanceY>=0?1:-1);	
	var distance=parseInt(Math.sqrt(distanceX*distanceX+distanceY*distanceY));
	switch(t){
case 2: 
	if(distanceX<distanceY)
	return;
	this.showImg('right',distance);
	break;

case -2:
	if(distanceX>=distanceY)
	this.showImg('top',distance);
	else this.showImg('left',distance);
	break;

case 0:
	if(curPos[0]>this.pos[0]){
	if((-distanceY)<=distanceX)
	this.showImg('right',distance);	
	else this.showImg('top',distance);
}	else{
	if((-distanceX)>=distanceY)
	this.showImg('left',distance);
	}
	break;
	}
};
