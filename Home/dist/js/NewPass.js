$(document).ready(function(){
	
"use strict";

$("#NPWD").blur(function(){
		var passValidation1 = $("#NPWD").val();
		var passValidation2 = $("#CPassword").val();
		var Result = (/[A-Z]/.test(passValidation1) && /[0-9]/.test(passValidation1) && /[^A-Za-z0-9]/.test(passValidation1) && passValidation1.length>=8);

if(Result === true)
	{
        $("#confirmPassword1").text("Good").css("color","green");
		$("#CPassword").prop("disabled", false);
		$("#saveBtn").prop("disabled", false);
    }
else if(Result === false)
	{
       $("#confirmPassword1").text("Password must contain at least one uppercase, one lowercase, number and must be 8").css("color","#FFFFFF");
	   $("#CPassword").prop("disabled", true);
	   $("#saveBtn").prop("disabled", true);
	}
	else
	{
		
	}
	
		
});

$("#CPassword").blur(function(){
		var pass2Validation1 = $("#NPWD").val();
		var pass2Validation2 = $("#CPassword").val();
		
	if(pass2Validation1 != pass2Validation2)
	{
		$("#confirmPassword2").text("password is not matched").css("color","#FFFFFF");
		$("#saveBtn").prop("disabled", true);
	   	setTimeout(function(){
		$("#CPassword").val('');
		$('#CPassword').focus();				
				}, 1500);
	}
	else
	{
		$("#confirmPassword2").text('');
		$("#confirmPassword2").text("matched").css("color","green");
		$("#saveBtn").prop("disabled", false);
	}
		
});



	$("#form_NewPWD").submit(function(){
		
		var newPwd=$("#NPWD").val();
		var confPwd=$("#CPassword").val();
		var username=$("#usname").val();
		var ref="../../../Home/";
		if(newPwd == "" || confPwd == "")
		{
			alert("Please type the new password!");
		}
		else if( confPwd == "")
		{
			alert("Please Confirm the password!");
		}
		else if(newPwd != confPwd)
		{
			alert("Password is not Matched");
			$("#CPassword").focus();
		}
		else
		{
		$.ajax({
				url:"../php/UpdateNewPWD.php",
				type:"POST",
				data:{ NPWD:newPwd, CPWD:confPwd, UserN:username},
				success: function(doneUpdatePWD)
				{
					if(doneUpdatePWD == 1)
					{
						alert("Successfully Updated Your Password");
						window.location.href= ref;
						
					}
					else
					{
						alert(doneUpdatePWD);
					}
				}
			
			
			});
		}
		return false;
		});		

});