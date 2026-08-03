// JavaScript Document

/* all button actions on admin interface */
//add new user \\
$(document).ready(function(){

	"use strict";

	$("#pass1").blur(function(){
		var passValidation1 = $("#pass1").val();
		var passValidation2 = $("#pass2").val();
		var Result = (/[A-Z]/.test(passValidation1) && /[0-9]/.test(passValidation1) && /[^A-Za-z0-9]/.test(passValidation1) && passValidation1.length>=8);

if(Result === true)
	{
        $("#confirmPassword1").text("Good").css("color","green");
		$("#pass2").prop("disabled", false);
    }
	 else if(Result === false)
	{
       $("#confirmPassword1").text("Password must contain at least one uppercase, one lowercase, number and must be 8").css("color","red");
	   $("#pass2").prop("disabled", true);
	}
	
		
		});
		
		
	$("#pass2").blur(function(){
		var pass2Validation1 = $("#pass1").val();
		var pass2Validation2 = $("#pass2").val();
		
	if(pass2Validation1 != pass2Validation2)
	{
		$("#confirmPassword2").text("password is not matched").css("color","red");
	   	setTimeout(function(){
		$("#pass2").val('');
		$('#pass2').focus();				
				}, 1500);
	}
	else
	{
		$("#confirmPassword2").text('');
		$("#confirmPassword2").text("matched").css("color","green");
	}
		
		});

	$("#addUserForm").submit(function(){
		
		var fullname = $("#fullName").val();
		fullname = fullname.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
		var username = $("#userName").val();
		username = username.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
		//var userType = $("#userType").val();
		//var email = $("#email").val();
		var pass1 = $("#pass1").val();
		var pass2 = $("#pass2").val();
		var dept = $("#department").val();
		var userPhoto = $("#photo").val();

		if(fullname === "" || null )
		{
			alert('missing field');
			$('#fullName').css("border-color","red");
			setTimeout(function(){
           		$('#fullName').css("border-color","#EBEBEB");
				$('#fullName').focus();				
				}, 1500);
								
			
		}
		else if(username === "" || null )
		{
			alert('missing field');
			$('#userName').css("border-color","red");
			setTimeout(function(){
           		$('#userName').css("border-color","#EBEBEB");
				$('#userName').focus();				
				}, 1500);
		}
		/*else if(userType === "" || null )
		{
			alert('missing field');
			$('#userType').css("border-color","red");
			$('#userType').focus();
		}
		else if(email === "" || null )
		{
			alert('missing field');
			$('#email').css("border-color","red");
			$('#email').focus();
		}*/
		else if(pass1 === "" || null )
		{
			alert('missing field');
			$('#pass1').css("border-color","red");
			setTimeout(function(){
           		$('#pass1').css("border-color","#EBEBEB");
				$('#pass1').focus();				
				}, 1500);
		}
		else if(pass2 === "" || null )
		{
			alert('missing field');
			$('#pass2').css("border-color","red");
			setTimeout(function(){
           		$('#pass2').css("border-color","#EBEBEB");
				$('#pass2').focus();				
				}, 1500);
		}
		else if(pass1 !== pass2)
		{
			alert('passwords are miss matches!');
			$('#pass2').css("border-color","red");
			$('#pass1').css("border-color","red");
			setTimeout(function(){
				$('#pass1').css("border-color","#EBEBEB");
           		$('#pass2').css("border-color","#EBEBEB");
				$('#pass2').val('');
				$('#pass2').focus();				
				}, 1500);
		}
		else if(dept === "" || null )
		{
			alert('missing field');
			$('#department').css("border-color","red");
			setTimeout(function(){
           		$('#department').css("border-color","#EBEBEB");
				$('#department').focus();				
				}, 1500);
		}
		
		/*else if(userPhoto === "" || null )
		{
			alert('missing field');
			$('#photo').css("background-color","red");
			setTimeout(function(){
           		$('#photo').css("background-color","#EBEBEB");
				}, 1500);
		}*/
	else
		{
			$.ajax({
				url:"dist/php/addUser.php",
				type:"POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData:false,
				beforeSend: function(){
					
					$("#saveUserBtn").prop('disabled', true);
					},
					
				success: function(AddNewUser){
					if(AddNewUser == 0)
					{
						alert("Username Is Allready excting in Database.!");
					}
					else if(AddNewUser == 1)
					{
						alert("Data Saved");
						setTimeout(function(){
							$("#1_1").click();
						}, 1500);
						$("#saveUserBtn").prop('disabled', false);
					}
					else
					{
						$("#saveUser").prop('disabled', false);
						alert(AddNewUser);
					}
				}
			});
		}
		return false;
		});

	});// docment.ready function **//
