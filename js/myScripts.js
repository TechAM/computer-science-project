var INVALID_MISSING_BORDER = "2px solid rgba(255, 200, 145, 0.75)";
var BOOTSTRAP_INPUT_BORDER = "1px solid rgb(206, 212, 218)";

function initialize() {
	//choose a random colour from the 6 colours of the Rubik's cube and paint some of the content in this colour
	var colors =["rgb(255,145,145)","rgb(255,200,145)","rgb(230,230,110)","rgb(225,225,225)","rgb(115,210,230)","rgb(105,230,130)"];

	var rnd = Math.floor(Math.random() * 6);
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

	var regControls = document.getElementsByClassName("regInput");

	for(var i=0; i<regControls.length; i++){
		//when tooltip appears for invalid field, make it disappear upon further input
 		regControls[i].oninput = function(){
 			this.setCustomValidity('');
 		};

		//remove borders around input fields when text is input into them
 		regControls[i].onkeypress = function(){
 			this.style.border=BOOTSTRAP_INPUT_BORDER;
 		};
	}

	// var passwordField = document.getElementById("password");
	// var confirmPasswordField = document.getElementById("confirm_password");

	// passwordField.onkeypress = function(){
 // 		this.style.border=BOOTSTRAP_INPUT_BORDER;
 // 		confirmPasswordField.style.border=BOOTSTRAP_INPUT_BORDER;
	// }
	// confirmPasswordField.onkeypress = function(){
 // 		this.style.border=BOOTSTRAP_INPUT_BORDER;
 // 		passwordField.style.border=BOOTSTRAP_INPUT_BORDER;
	// }
}

//if some fields are missing display appropriate input message
function missingData(data){
	var message = "Please input: " + data.join(", ") + ".";

	for(var i=0; i<data.length; i++){
		document.getElementById(data[i]).style.border="2px solid rgba(255, 0, 0, 0.75)";
	}
	displayMessage(message);
}

//if some fields are invalid display appropriate error message
function invalidData(data){
	var message = "Invalid: " + data.join(", ") +".";

	for (var i=0; i<data.length;i++){
		if(data[i]=="password"){
			message += " Passwords don't match";
			document.getElementById("confirm_password").style.border=INVALID_MISSING_BORDER;
		}
		document.getElementById(data[i]).style.border=INVALID_MISSING_BORDER;
	}
	displayMessage(message);
}

//if other user(s) exist with username/email display message display appropriate error message
function usersExist(data){
	var message = "That " + data[0] + " is already taken.";
	document.getElementById(data[0]).style.border=INVALID_MISSING_BORDER;
	displayMessage(message)
}

function checkPass(currentPass){
	var strengthBar = document.getElementById("strengthBar");

	//set baseline strength as the quotient of length divided by 3
	var length = currentPass.length
	strength = Math.floor(length/2);

	//increasing the strength by certain numbers scaled for each digit, uppercase letter or punctuation detected
	//stop increasing strength for a certain group if exceeded limit
	var numDigits = currentPass.replace(/\D/g, "").length;
	strength += strengthIncrease(numDigits, 8, 0.7);
	var numUpperCase = currentPass.replace(/[^A-Z]/g,"").length;
	strength+=strengthIncrease(numUpperCase, 6, 0.5);
	var numPunctuation = currentPass.replace(/[\w]/g, "").length;
	strength += strengthIncrease(numPunctuation, 8, 0.5);


	//for each group of consecutive digits, letters or punctuation decrease the strength
	var consecDigits = (currentPass.match(/\d{4}/g) || []).length;
	var consecLetters = (currentPass.match(/[A-Za-z]{3}/g) || []).length;
	var consecPunctuation = (currentPass.match(/[\W]{4}/g) || []).length;

	var totalConsec = consecDigits + consecLetters + consecPunctuation;
	strength -= totalConsec*0.2;

	//maximum strength is 10;
	if(strength >10){
		strength = 10;
	}

	strengthBar.style.width = strength*10 + "%";
	strengthBar.setAttribute("data-strength", strength);
	strengthBar.setAttribute("aria-valuenow", strength);
	document.getElementById("strengthDisplay").innerHTML = "Strength: "+(Math.round( strength * 10) / 10);
}

//calculate increase in strength given 
// - occurences of a pattern
// - the maximum number of occurences that can result in a strength increase
// - by how much should the strength should be increased per occurence
function strengthIncrease(occurences, max, multiplier){
	var strengthIncrease = 0;

	if(occurences>0){
		if(occurences<=max){
			strengthIncrease +=multiplier*occurences;
		}else{
			strengthIncrease += max *multiplier;
		}
	}
	return strengthIncrease;
}

function displayMessage(message){
	var errorBox = document.getElementById("errorBox");
	errorBox.classList.remove("alert-success");
	errorBox.classList.add("alert-danger");
	errorBox.style.visibility="visible";
	document.getElementById("errorMsg").innerHTML=message;

}