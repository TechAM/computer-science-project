<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<meta name="author" content="Avi Mukesh"/>

		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/> -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="css/mainPageStyles.css"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
  		<script src="js/myScripts.js"></script>
<!--   		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->

  	<<!-- script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
  	<!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script> -->
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript">	
    	$(function (){
			console.log("document loaded");
			var request;


			$("#registrationForm").on("submit", function(e){
				//prevents page from refreshing upon submitting
				e.preventDefault();
				console.log("form submitted");

				//abort pending requests
				if(request){
					request.abort();
				}

				var $form = $(this);
				var $inputs = $form.find("input");

				//JSON object for PHP to process
				var serializedData = {
					"username": $("#username").val(),
					"email": $("#email").val(),
					"password": $("#password").val(),
					"confirm_password": $("#confirm_password").val()
				}

				// var serializedData = $form.serialize();
				console.log(serializedData);
				//disable inputs during processing
				$inputs.prop("disabled", true);

				//create AJAX call request, post the form data
				request = $.ajax({
				  type: "post",
				  // url: "ajax.php",
				  url: "php/processRegistration.php",
				  data: serializedData,
				  dataType: "json",
				  success: function(data){
				  		console.log("request success!");
				  }
				});

				request.done(function(response, textStatus, jqXHR){
					console.log("response success!");

					//pass the missing data array to JS function for processing
					$.getScript("js/myScripts.js", function(){
						missingData(response);
					});

				});

				request.fail(function(jqXHR, textStatus, errorThrown){
					console.error("The following error occurred: " + textStatus + errorThrown);
				});

				//always reenable the inputs after processing
				request.always(function(){
					$inputs.prop("disabled", false);
				});
			});

    	});

		</script>
		<title>Cube Nation</title>
	</head>

	<body onload="rndColor();" id="body">
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
								<input type="text" class="form-control" name="username" placeholder="Username"/><br>
							 	<input type="password" class="form-control" name="password" placeholder="Password"/><br>
							 	<p class="card-text" style="float:left;">Click <a class="card-link" href="">here</a> if you've forgotten your password</p>
							 	<input type="submit" class="btn btn-light"/>
							</form>
						</div>

						<div class="card-footer">
							<p class="card-text">Or sign up <a class="card-link" data-toggle="modal" href="#registrationForm">now</a></p>
						</div>
					</div>
				</div>


				<!--modal registration form that pops up -->
				<div id="registrationForm" class="modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">Sign up</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<div class="modal-body">
								<form  method="post" target="_blank">
									<div class="form-group">
										<label for="username" class="label">Username:</label>
										<input type="text" id="username" class="form-control" name="username" required autofocus oninvalid="setCustomValidity('Please enter a username')" oninput="setCustomValidity('')"/><br>
									</div>
									<div class="form-group">
										<label for="email" class="label">Email address:</label>
							 			<input type="email" id="email" class="form-control" name="email" required oninvalid="setCustomValidity('Please enter a valid email address')" oninput="setCustomValidity('')"/><br>
									</div>
									<div class="form-group">
										<label for="password" class="label">Password:</label>
							 			<input type="password" id="password" class="form-control" name="password" required oninvalid="setCustomValidity('Please enter a valid password')" oninput="setCustomValidity('')"/><br>
									</div>
									<div class="form-group">
										<label for="confirm_password" class="label">Confirm password:</label>
							 			<input type="password" id="confirm_password" class="form-control" name="confirm_password" oninvalid="setCustomValidity('Please enter a username')" oninput="setCustomValidity('')"/><br>
									</div>
							 		<input type="submit" name="submit" value="Sign up" class="btn btn-dark" />
								</form>
								<div id="errorBox" class="alert alert-danger">
									<p id="errorMsg"></p>
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