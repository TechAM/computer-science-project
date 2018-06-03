function rndColor() {
	//choose a random colour from the 6 colours of the Rubik's cube and paint some of the content in this colour
	var rnd = Math.floor(Math.random() * 6);
	var colors =["rgb(255,145,145)","rgb(255,200,145)","rgb(230,230,110)","rgb(225,225,225)","rgb(115,210,230)","rgb(105,230,130)"];
	var color1 = colors[rnd];
	var color2;

	//color2 is the opposite of color1 on a Rubik's cube
	if(rnd%2==0){
		color2 = colors[rnd+1];
	}else{
		color2 = colors[rnd-1];
	}

	//color background of page and main page header
	document.getElementById("body").style.backgroundColor = color1;
	document.getElementById("pageHead").style.color = color2;

	//color the links
	var links = document.getElementsByTagName("a");
	for(var i=0;i<links.length;i++){
		links[i].style.color=color2;
	}
}

