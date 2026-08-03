<!DOCTYPE HTML>
<html lang="eng">

<head>
	<title>MTS Reset Password</title>
	<!-- Meta tag Keywords -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="../../../Home/images/GCS_Logo.png" sizes="128x128" />
	<meta name="keywords" content="Video Login Form Responsive Widget,Login form widgets, Sign up Web forms , Login signup Responsive web form,Flat Pricing table,Flat Drop downs,Registration Forms,News letter Forms,Elements"
	/>
    <!-- Jquery -->
    <script src="../../../js/jquery_v3.3.1.js"></script>
     <script src="../../dist/js/NewPass.js"></script>
    <!-- Video js -->
	<script src="../../../js/jquery.vide.min.js"></script>
   
	<!-- //Video js -->
	<script>
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<link rel="stylesheet" href="../../../css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../../../css/fontawesome-all.css">

</head>

<body>
<input type="hidden" id="usname" value="<?php echo $_GET['us'];?>"/>
	<div class="video-w3l" data-vide-bg="video/1">
		<!-- title -->
		<h1>
			<span>MTS</span>&nbsp;New User Password
			</h1>
		<!-- //title -->
		<!-- content -->
		<div class="sub-main-w3">
			<form id="form_NewPWD">
				<div class="form-style-agile">
					<label>
						<i class="fas fa-unlock-alt"></i>New Password</label>
					<input placeholder="New Password" id="NPWD" type="password" autocomplete="off" required >
                    <br>
                    <span class="glyphicon form-control-feedback" id="confirmPassword1"></span>
                </div>
				<div class="form-style-agile">
					<label>
						<i class="fas fa-unlock-alt"></i>Confirm Password</label>
					<input placeholder="Confirm Password" id="CPassword" type="password" autocomplete="off"
                    required disabled>
                    <br>
                    <span class="glyphicon form-control-feedback" id="confirmPassword2"></span>
				</div>
				<!-- switch -->
				<br>
				<!-- //switch -->
				<input type="submit" value="Save" id="saveBtn" style="font-size:18px; font-weight:bold">
				
				<!-- //social icons -->
			</form>
		</div>
		<!-- //content -->

		<!-- copyright -->
		<div class="footer" style="color:#FFFFFF">
                <small class="text-secondary">Copyright &copy; 2023 : <script>document.write(new Date().
				getFullYear());</script>. Developed by <a href="https://qms-egypt.com" target="_blank">QMS-EG
                </a> Team</small>
		</div>
		<!-- //copyright -->
	</div>
</body>

</html>