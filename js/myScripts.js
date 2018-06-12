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
	document.getElementById("cardForm").style.borderColor=color1;
	document.getElementById("pageHead").style.color = color2;

	//color the links
	var links = document.getElementsByTagName("a");
	for(var i=0;i<links.length;i++){
		links[i].style.color=color2;
	}


	var formControls = document.getElementsByClassName("form-control");
	for(var i=0; i<formControls.length; i++){
 		formControls[i].setCustomValidity('');
	}

	
}

//if some fields are missing then display the appropriate input message
function missingData(data){
	var message = "Please input: " + data.join(", ") + ".";

	document.getElementById("errorBox").style.visibility="visible";
	for(var i=0; i<data.length; i++){
		document.getElementById(data[i]).style.border="2px solid rgba(255, 0, 0, 0.75)";
	}

	document.getElementById("errorMsg").innerHTML=message;
}

//if some fields are invalid displau the appropriate error message
function invalidData(data){
	var message = "Invalid: " + data.join(", ") +".";
	document.getElementById("errorBox").style.visibility="visible";

	for (var i=0; i<data.length;i++){
		if(data[i]=="password"){
			message += " Passwords don't match";
			document.getElementById("confirm_password").style.border="2px solid rgb(255,200,145,0.75)";
		}
		document.getElementById(data[i]).style.border="2px solid rgb(255,200,145,0.75)";
	}
	document.getElementById("errorMsg").innerHTML=message;
}

function usersExist(data){
	var message = "That " + data[0] + " is already taken";
	console.log(message);
	document.getElementById("errorBox").style.visibility="visible";

	document.getElementById(data[0]).style.border="2px solid rgba(255,200,145,0.75)";
	document.getElementById("errorMsg").innerHTML=message;
}