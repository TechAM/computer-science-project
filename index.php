<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<meta name="author" content="Avi Mukesh"/>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="css/mainPageStyles.css"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">

  		<script src="js/myScripts.js"></script>
	  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	    <script type="text/javascript">	
	    	$(function (){
	    		//when input is received in either password or confirm password fields, set border of other field as well as self to default
	    		$("#password, #confirm_password").keypress(function(){
	    			$("#confirm_password").css("border", BOOTSTRAP_INPUT_BORDER);
	    			$("#password").css("border", BOOTSTRAP_INPUT_BORDER);

	    		});
				//make the username and password hints popovers
				$(".regPopovers").popover();

				$("#regForm").on("submit", function(e){					
					var request;
					//prevents page from refreshing upon submitting
					e.preventDefault();

					//abort pending requests
					if(request){
						request.abort();
					}

					//don't carry on processing registration if password isn't strong enough
					if(document.getElementById("strengthBar").getAttribute("data-strength")<8.0){
						$.getScript("js/myScripts.js", displayMessage("Password is not strong enough"));
						document.getElementById("password").style.border=INVALID_MISSING_BORDER;
					}else{
						var $form = $(this);
						var $inputs = $form.find("input");

						var data = $form.serialize();
						//disable inputs during processing
						$inputs.prop("disabled", true);

						//create AJAX call request, post the form data to PHP server
						request = $.ajax({
						  type: "post",
						  url: "php/processRegistration.php",
						  data: data,
						  dataType: "json",
						  success: function(data){
						  		console.log("request success!");
						  }
						});

						//if successfully received response from PHP server
						request.done(function(response, textStatus, jqXHR){
							$.getScript("js/myScripts.js", function(){
								//error if one or more of the 3 main fields are missing
								if(response.missingData.length==0){
									//error if one of more fields are invalid
									if(response.invalidData.length==0){
											//error if user with username/email exists
											if(response.existingData.length==0){
												$("#errorBox").css("visibility", "visible");
												$("#errorBox").removeClass("alert-danger");
												$("#errorBox").addClass("alert-success");
												$("#errorMsg").html("Sign up success!");
												$(".regInput").css("border",BOOTSTRAP_INPUT_BORDER);
											}else{
												usersExist(response.existingData);
											}
									}else{
										invalidData(response.invalidData);
									}
								}else{
									missingData(response.missingData);
								}
							});

						});

						request.fail(function(jqXHR, textStatus, errorThrown){
							$("#errorMsg").html(textStatus + errorThrown);
							console.error("The following error occurred: " + textStatus + errorThrown);
						});

						//always reenable the inputs after processing
						request.always(function(){
							$inputs.prop("disabled", false);
						});
					}
				});

	    	});
		</script>
		<title>Cube Nation</title>
	</head>

	<body onload="initialize();" id="body">
		<!--2 wrappers: outer wrapper sets position of inner wrapper within page, inner wrapper sets white background, border and centers the content-->
		<div id="wrapper-outer">
			<div id="wrapper-inner">
				<div class="container">
					<!-- header contained within dark theme jumbotron div -->
					<div class="jumbotron bg-dark text-light">
						<h1 id="pageHead" class="headers">Cube Nation</h1>
					</div>
	

					<!-- dark theme form to sign in has username and password fields with submit button -->
					<div id="cardForm" class="card bg-dark text-light">
						<div class="card-header">
							<h4 class="headers">Log in</h4>
						</div>

						<div class="card-body">
							<form action="" method="post" target="_blank">
								<input type="text" class="form-control loginInput" name="username" placeholder="Username"/><br>
							 	<input type="password" class="form-control loginInput" name="password" placeholder="Password"/><br>
							 	<p class="card-text" style="float:left;">Click <a class="card-link" href="">here</a> if you've forgotten your password</p>
							 	<input type="submit" class="btn btn-light"/>
							</form>
						</div>

						<div class="card-footer">
							<p class="card-text">Or sign up <a class="card-link" data-toggle="modal" href="#registrationFormDiv">now</a></p>
						</div>
					</div>
				</div>

				<!--modal registration form that pops up -->
				<div id="registrationFormDiv" class="modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">Sign up</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<div class="modal-body">
								<form id="regForm" method="post" target="_blank">
									<div class="form-group">
										<label for="username" class="label">Username:</label>
										<div id="usernamePopover" class="regPopovers" data-placement="top" data-content="Username can only contain lowercase and uppercase letters, numbers, underscores and hyphens." data-trigger="hover">
											<input type="text" id="username" class="form-control regInput" name="username" autofocus oninvalid="setCustomValidity('Please enter a username')"/><br>
										</div>
									</div>
									<div class="form-group">
										<label for="email" class="label">Email address:</label>
							 			<input type="email" id="email" class="form-control regInput" name="email"  oninvalid="setCustomValidity('Please enter a valid email address')"/><br>
									</div>
									<div class="form-group">
										<label for="password" class="label">Password:</label>
										<div id="passwordPopover" class="regPopovers" data-placement="top" data-content="Password must have strength of at least 8 by using mixture of uppercase and lower case letters, numbers and punctuation." data-trigger="hover">
							 				<input type="password" id="password" class="form-control regInput" name="password" oninvalid="setCustomValidity('Please enter a valid password')" onkeyup="checkPass(this.value)" /><br>
							 			</div>

							 			<!-- progress bar to inciate password strength -->
		 								<span id="strengthDisplay" class="badge badge-success">Strength: 0</span>

							 			<div class="progress">
		 									<div id="strengthBar" class="progress-bar bg-success" data-strength="0" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="10">
		 									</div>
		 								</div>
									</div>
									<div class="form-group">
										<label for="confirm_password" class="label">Confirm password:</label>
							 			<input type="password" id="confirm_password" class="form-control regInput" name="confirm_password" oninvalid="setCustomValidity('Please enter a username')" oninput="setCustomValidity('')"/><br>
									</div>
							 		<input type="submit" id="submit" name="submit" value="Sign up" class="btn btn-dark"/>
								</form>
								<div id="errorBox" class="alert alert-danger alert-dismissible">
									<p id="errorMsg"></p>
									<button type="button" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn btn-dark" data-dismiss="modal" value="Close"  />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>