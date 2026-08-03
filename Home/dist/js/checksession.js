$(document).ready(function () {


$(".userInfo").load("dist/php/userInfo.php");
$(".userImage").load("dist/php/userImage.php");

    $.ajax({

            url:"dist/php/checkSession.php",
            success:function(checked){

                if(checked == 1)
                {
                    var ref1 = "../../";
                    window.location.href= ref1;
                }

            }

    });

    const timeout = 900000;  // 900000 ms = 15 minutes
    var idleTimer = null;
    $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
        clearTimeout(idleTimer);

        idleTimer = setTimeout(function () {
                var ref = "dist/php/logOut.php";
                    window.location.href= ref;
        }, timeout);
    });
    $("body").trigger("mousemove");
});