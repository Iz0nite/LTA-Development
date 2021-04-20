function getStyle(e, styleName){
	let styleValue = "";
	
	if(document.defaultView && document.defaultView.getComputedStyle){
		styleValue = document.defaultView.getComputedStyle(e, "").getPropertyValue(styleName);
	}else if(e.currentStyle){
		styleName = styleName.replace(/\-(\w)/g, function (strMatch, p1){
			return p1.toUpperCase();
		});
		
		styleValue = e.currentStyle[styleName];
	}
	
	return styleValue;
}

function getMinHeight(){
	let header = document.getElementsByTagName("header")[0];
	let footer = document.getElementsByTagName("footer")[0];
	let mainContainer = document.getElementsByTagName("main")[0];
	mainContainer.style.minHeight = (window.innerHeight - (header.offsetHeight + footer.offsetHeight)) + "px";
}

window.onload = getMinHeight;
window.onresize = getMinHeight;