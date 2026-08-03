// JavaScript Document
$(document).ready(function() {
	"use strict";
		
    $("#form_login").submit(function(){	
	
	var username=$("#Name").val();
	var password=$("#Password").val();
	//var remember_me = $("#remember_me");
	var ref="Home/";
	var ref2="Home/dist/php/newPass.php?us="+username;
	//if($("#remember_me").is(":checked"))
	//{
		//var remeber = $("#remember_me").val();
	//}

	if(username === "" || null)
	{
		alert("Please Type Your Username!");
		$("#Name").css("border-color","red");
		$("#Name").focus();
	}
	else if(password === "" || null)
	{
		alert("Please Type Your Password!");
		$("#Password").css("border-color","red");
		$("#Password").focus();
	}
	else 
	{
//remeberCheck:remeber
		$.ajax({
			url:"Home/dist/php/checkLogIn.php",
			type:"POST",
			data:{user:username,pass:password},
			success: function(logInApp)
			{
				
				if(logInApp == 0)
				{
					alert("Username is Not In Database.!");
				}
				else if(logInApp == 1)
				{					
			alert("Username or Password Incorrect, Please Take care you have 5 Attempt Then User Account Will be Lock.");
				}
				else if(logInApp == 2)
				{
			alert("User Account is locked, Please connect the Your Administrator to Help You By Un-lock your User Account's");
				}
				
				else if(logInApp == 5)
				{
					alert("Please make sure The Receved Passowrd from Your Admin Is Correct."); 
				}
				
				else if(logInApp == 3)
				{
					window.location.href= ref2; 
				}
				else if(logInApp == 4)
				{
					window.location.href= ref; 
				}
				else
				{
					alert(logInApp);
				}
				
			}
			
			});
		}
		
	
		return false;
		});



});