// UnLockUsers

$(document).ready(function() {
	"use strict";
  $("#ConfirmNewPwd").click(function(){
	  
	  var validPWD = $("#adminPwd").val();
	  var newPWD = $("#newPwd").val();
	  var confPWD = $("#confPwd").val();
	  var LockedUser = $('#UserID').val();
	  
	  if(validPWD === "" || null )
	  {
		  alert("Please insert administrator password");
		  $("#adminPwd").focus();
	  }
	  else if(newPWD === "" || null )
	  {
		  alert("Please insert New password");
		  $("#newPwd").focus();
	  }
	  else if(confPWD === "" || null )
	  {
		  alert("Please insert Confirm The password");
		  $("#confPwd").focus();
	  }
	  else if(newPWD !== confPWD)
	  {
		alert("passwords are miss matches!");
		  $("#newPwd").css("border-color","red");
		  $("#confPwd").css("border-color","red");
	  }
	  else
	  {
		  $.ajax({
			  		url:"dist/php/unLockUsers.php",
					type:"POST",
					data:{Valid:validPWD, PWD:newPWD, confrim:confPWD, UID:LockedUser},
					success: function(UnLocked){
						
						if(UnLocked == 1)
							{
								alert("adminstrator password is not correct");
								$("#adminPwd").focus();
							}
						else if(UnLocked == 2)
							{
								alert("successfully update user Password");
								setTimeout(function(){
           						$(".blockedUsers").empty();
								$("#1_2").click();
      							}, 1500);
							}
						else
							{
								alert(UnLocked);
							}
						}
			  
			  });
	  }
	  return false;
	  });

});