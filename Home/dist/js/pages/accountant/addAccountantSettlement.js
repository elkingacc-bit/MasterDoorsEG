 $(document).ready(function(){
   $(".entry3").hide();
   $(".entry4").hide();
   $("#settlementAdd").click(function(){
    var dataCount = $("#entryCount").val();
    var trCell11 = $("#receDate1").val();
    var trCell21 = $("#accountName1").val();
    var trCell31 = $("#debtorAmount1").val();
    var trCell41 = $("#creditorAmount1").val();
    var trCell51 = $("#receivDescription1").val();
    var trCell13 = $("#receDate"+dataCount).val();
    var trCell23 = $("#accountName"+dataCount).val();
    var trCell33 = $("#debtorAmount"+dataCount).val();
    var trCell43 = $("#creditorAmount"+dataCount).val();
    var trCell53 = $("#receivDescription"+dataCount).val();
    if(trCell11 == ""){ alert("Dont Skip Filed Empty");}
    else if(trCell21 == ""){ alert("Dont Skip Filed Empty");}
    else if(trCell31 == ""){ alert("Dont Skip Filed Empty");}
    else if(trCell41 == ""){ alert("Dont Skip Filed Empty");}
    else if(trCell51 == ""){ alert("Dont Skip Filed Empty");}
    else if(trCell13 == ""){ alert("Dont Skip Filed Empty");}
    else if(trCell23 == ""){ alert("Dont Skip Filed Empty");}
    else if(trCell33 == ""){ alert("Dont Skip Filed Empty");}
    else if(trCell43 == ""){ alert("Dont Skip Filed Empty");}
    else if(trCell53 == ""){ alert("Dont Skip Filed Empty");}
    else{
     if(dataCount < 4){
      var nextRow = (Number(dataCount)+1);
      $("#entryCount").val(nextRow);
      $(".entry"+nextRow).show();
     }
    }
   });
   $("#settlementSave").prop('disabled', true);
   $("#accountName1").load("dist/php/allCodeSelect.php");
   $("#accountName1").change(function(){
    var oldCode = $(this).val();
    $.ajax({
     url:'dist/php/nextCodeSelect.php',
     type:"POST",
     data:{accCode:oldCode},
     success: function(getSecoundAccount){
      $("#accountName2").html(getSecoundAccount);
     }
    });
    return false;
   });
   $("#accountName2").change(function(){
    var oldCode2 = $(this).val();
    var oldCode3 = $("#accountName1").val();
    $.ajax({
     url:'dist/php/nextCodeSelect2.php',
     type:"POST",
     data:{accCode:oldCode2,code2:oldCode3},
     success: function(getSecoundAccount2){
      $("#accountName3").html(getSecoundAccount2);
     }
    });
    return false;
   });
   $("#accountName3").change(function(){
    var oldCode4 = $("#accountName1").val();
    var oldCode5 = $(this).val();
    var oldCode6 = $("#accountName2").val();
    $.ajax({
     url:'dist/php/nextCodeSelect3.php',
     type:"POST",
     data:{accCode:oldCode4,code2:oldCode5,code3:oldCode6},
     success: function(getSecoundAccount3){
      $("#accountName3").html(getSecoundAccount3);
     }
    });
    return false;
   });
   $("#debtorAmount1").change(function(){
    var dept1 = $("#debtorAmount1").val();
    var totalDeb =(Number(dept1));
    var cre1 = $("#creditorAmount1").val();
    var totalCre = (Number(cre1));
    if( totalDeb == totalCre){$("#settlementSave").prop('disabled', false);}
    else{$(".msgData").html('Settlement Unbalanced');}
    return false;
   });
   $("#creditorAmount1").change(function(){
    var dept1 = $("#debtorAmount1").val();
    var totalDeb =(Number(dept1));
    var cre1 = $("#creditorAmount1").val();
    var totalCre = (Number(cre1));
    if( totalDeb == totalCre){$("#settlementSave").prop('disabled', false);}
    else{$(".msgData").html('Settlement Unbalanced');}
    return false;
   });
   $("#debtorAmount2").change(function(){
    var dept1 = $("#debtorAmount1").val();
    var dept2 = $("#debtorAmount2").val();
    var totalDeb =(Number(dept1)+Number(dept2));
    var cre1 = $("#creditorAmount1").val();
    var cre2 = $("#creditorAmount2").val();
    var totalCre = (Number(cre1)+Number(cre2));
    if( totalDeb == totalCre){$("#settlementSave").prop('disabled', false);}
    else{$(".msgData").html('Settlement Unbalanced');}
    return false;
   });
   $("#creditorAmount2").change(function(){
    var dept1 = $("#debtorAmount1").val();
    var dept2 = $("#debtorAmount2").val();
    var totalDeb =(Number(dept1)+Number(dept2));
    var cre1 = $("#creditorAmount1").val();
    var cre2 = $("#creditorAmount2").val();
    var totalCre = (Number(cre1)+Number(cre2));
    if( totalDeb == totalCre){$("#settlementSave").prop('disabled', false);}
    else{$(".msgData").html('Settlement Unbalanced');}
    return false;
   });
   $("#debtorAmount3").change(function(){
    var dept1 = $("#debtorAmount1").val();
    var dept2 = $("#debtorAmount2").val();
    var dept3 = $("#debtorAmount3").val();
    var totalDeb =(Number(dept1)+Number(dept2)+Number(dept3));
    var cre1 = $("#creditorAmount1").val();
    var cre2 = $("#creditorAmount2").val();
    var cre3 = $("#creditorAmount3").val();
    var totalCre = (Number(cre1)+Number(cre2)+Number(cre3));
    if( totalDeb == totalCre){$("#settlementSave").prop('disabled', false);}
    else{$(".msgData").html('Settlement Unbalanced');}
    return false;
   });
   $("#creditorAmount3").change(function(){
    var dept1 = $("#debtorAmount1").val();
    var dept2 = $("#debtorAmount2").val();
    var dept3 = $("#debtorAmount3").val();
    var totalDeb =(Number(dept1)+Number(dept2)+Number(dept3));
    var cre1 = $("#creditorAmount1").val();
    var cre2 = $("#creditorAmount2").val();
    var cre3 = $("#creditorAmount3").val();
    var totalCre = (Number(cre1)+Number(cre2)+Number(cre3));
    if( totalDeb == totalCre){$("#settlementSave").prop('disabled', false);}
    else{$(".msgData").html('Settlement Unbalanced');}
    return false;
   });
   $("#settlementSave").click(function(){
    var getDataCount = $("#entryCount").val();
    if(getDataCount == 2){
     var cell11 = $("#receDate1").val();
     var cell21 = $("#accountName1").val();
     var cell31 = $("#debtorAmount1").val();
     var cell41 = $("#creditorAmount1").val();
     var cell51 = $("#receivDescription1").val();
     var cell12 = $("#receDate2").val();
     var cell22 = $("#accountName2").val();
     var cell32 = $("#debtorAmount2").val();
     var cell42 = $("#creditorAmount2").val();
     var cell52 = $("#receivDescription2").val();
     if(cell11 == ''){alert('Dont Skip Filed Empty');}
     else if(cell21 == ''){alert('Dont Skip Filed Empty');}
     else if(cell31 == ''){alert('Dont Skip Filed Empty');}
     else if(cell41 == ''){alert('Dont Skip Filed Empty');}
     else if(cell51 == ''){alert('Dont Skip Filed Empty');}
     else if(cell12 == ''){alert('Dont Skip Filed Empty');}
     else if(cell22 == ''){alert('Dont Skip Filed Empty');}
     else if(cell32 == ''){alert('Dont Skip Filed Empty');}
     else if(cell42 == ''){alert('Dont Skip Filed Empty');}
     else if(cell52 == ''){alert('Dont Skip Filed Empty');}
     else{
      $.ajax({ 
       url:'dist/php/saveNewSettlement.php',
       type:"POST",
       data:{count:getDataCount,c1:cell11,c2:cell21,c3:cell31,c4:cell41,c5:cell51,c6:cell12,c7:cell22,c8:cell32,c9:cell42,c10:cell52},
       beforeSend:function(){
        $('#saverRceivedCash').prop('disabled', true);
       },
       success: function(saveReceivedCashDone){
        if(saveReceivedCashDone == 1){
         $('.msg').removeClass('alert alert-danger');
         $('.msg').addClass('alert alert-success');
         $('.msg').html("Data Saved");
         $(".msg").fadeOut(3000);
         $(".data_display").load("dist/html/accountantSettlement.html");
        }
        else{
         $('.msg').removeClass('alert alert-success');
         $('.msg').addClass('alert alert-danger');
         $('.msg').html(saveReceivedCashDone);
         $(".msg").fadeOut(9000);
        }
       }
      });
     }
    }
    else if(getDataCount == 3){
     var cell11 = $("#receDate1").val();
     var cell21 = $("#accountName1").val();
     var cell31 = $("#debtorAmount1").val();
     var cell41 = $("#creditorAmount1").val();
     var cell51 = $("#receivDescription1").val();
     var cell12 = $("#receDate2").val();
     var cell22 = $("#accountName2").val();
     var cell32 = $("#debtorAmount2").val();
     var cell42 = $("#creditorAmount2").val();
     var cell52 = $("#receivDescription2").val();
     var cell13 = $("#receDate3").val();
     var cell23 = $("#accountName3").val();
     var cell33 = $("#debtorAmount3").val();
     var cell43 = $("#creditorAmount3").val();
     var cell53 = $("#receivDescription3").val();
     if(cell11 == ''){alert('Dont Skip Filed Empty');}
     else if(cell21 == ''){alert('Dont Skip Filed Empty');}
     else if(cell31 == ''){alert('Dont Skip Filed Empty');}
     else if(cell41 == ''){alert('Dont Skip Filed Empty');}
     else if(cell51 == ''){alert('Dont Skip Filed Empty');}
     else if(cell12 == ''){alert('Dont Skip Filed Empty');}
     else if(cell22 == ''){alert('Dont Skip Filed Empty');}
     else if(cell32 == ''){alert('Dont Skip Filed Empty');}
     else if(cell42 == ''){alert('Dont Skip Filed Empty');}
     else if(cell52 == ''){alert('Dont Skip Filed Empty');}
     else if(cell13 == ''){alert('Dont Skip Filed Empty');}
     else if(cell23 == ''){alert('Dont Skip Filed Empty');}
     else if(cell33 == ''){alert('Dont Skip Filed Empty');}
     else if(cell43 == ''){alert('Dont Skip Filed Empty');}
     else if(cell53 == ''){alert('Dont Skip Filed Empty');}
     else{
      $.ajax({ 
       url:'dist/php/saveNewSettlement.php',
       type:"POST",
       data:{count:getDataCount,c1:cell11,c2:cell21,c3:cell31,c4:cell41,c5:cell51,c6:cell12,c7:cell22,c8:cell32,c9:cell42,c10:cell52,c11:cell13,c12:cell23,c13:cell33,c14:cell43,c15:cell53},
       beforeSend:function(){
        $('#saverRceivedCash').prop('disabled', true);
       },
       success: function(saveReceivedCashDone){
        if(saveReceivedCashDone == 1){
         $('.msg').removeClass('alert alert-danger');
         $('.msg').addClass('alert alert-success');
         $('.msg').html("Data Saved");
         $(".msg").fadeOut(3000);
         $(".data_display").load("dist/html/accountantSettlement.html");
        }
        else{
         $('.msg').removeClass('alert alert-success');
         $('.msg').addClass('alert alert-danger');
         $('.msg').html(saveReceivedCashDone);
         $(".msg").fadeOut(9000);
        }
       }
      });
     }
    }
    else if(getDataCount == 4){
     var cell11 = $("#receDate1").val();
     var cell21 = $("#accountName1").val();
     var cell31 = $("#debtorAmount1").val();
     var cell41 = $("#creditorAmount1").val();
     var cell51 = $("#receivDescription1").val();
     var cell12 = $("#receDate2").val();
     var cell22 = $("#accountName2").val();
     var cell32 = $("#debtorAmount2").val();
     var cell42 = $("#creditorAmount2").val();
     var cell52 = $("#receivDescription2").val();
     var cell13 = $("#receDate3").val();
     var cell23 = $("#accountName3").val();
     var cell33 = $("#debtorAmount3").val();
     var cell43 = $("#creditorAmount3").val();
     var cell53 = $("#receivDescription3").val();
     var cell14 = $("#receDate4").val();
     var cell24 = $("#accountName4").val();
     var cell34 = $("#debtorAmount4").val();
     var cell44 = $("#creditorAmount4").val();
     var cell54 = $("#receivDescription4").val();
     if(cell11 == ''){alert('Dont Skip Filed Empty');}
     else if(cell21 == ''){alert('Dont Skip Filed Empty');}
     else if(cell31 == ''){alert('Dont Skip Filed Empty');}
     else if(cell41 == ''){alert('Dont Skip Filed Empty');}
     else if(cell51 == ''){alert('Dont Skip Filed Empty');}
     else if(cell12 == ''){alert('Dont Skip Filed Empty');}
     else if(cell22 == ''){alert('Dont Skip Filed Empty');}
     else if(cell32 == ''){alert('Dont Skip Filed Empty');}
     else if(cell42 == ''){alert('Dont Skip Filed Empty');}
     else if(cell52 == ''){alert('Dont Skip Filed Empty');}
     else if(cell13 == ''){alert('Dont Skip Filed Empty');}
     else if(cell23 == ''){alert('Dont Skip Filed Empty');}
     else if(cell33 == ''){alert('Dont Skip Filed Empty');}
     else if(cell43 == ''){alert('Dont Skip Filed Empty');}
     else if(cell53 == ''){alert('Dont Skip Filed Empty');}
     else if(cell14 == ''){alert('Dont Skip Filed Empty');}
     else if(cell24 == ''){alert('Dont Skip Filed Empty');}
     else if(cell34 == ''){alert('Dont Skip Filed Empty');}
     else if(cell44 == ''){alert('Dont Skip Filed Empty');}
     else if(cell54 == ''){alert('Dont Skip Filed Empty');}
     else{
      $.ajax({ 
       url:'dist/php/saveNewSettlement.php',
       type:"POST",
       data:{count:getDataCount,c1:cell11,c2:cell21,c3:cell31,c4:cell41,c5:cell51,c6:cell12,c7:cell22,c8:cell32,c9:cell42,c10:cell52,c11:cell13,c12:cell23,c13:cell33,c14:cell43,c15:cell53,c16:cell14,c17:cell24,c18:cell34,c19:cell44,c20:cell54},
       beforeSend:function(){
        $('#saverRceivedCash').prop('disabled', true);
       },
       success: function(saveReceivedCashDone){
        if(saveReceivedCashDone == 1){
         $('.msg').removeClass('alert alert-danger');
         $('.msg').addClass('alert alert-success');
         $('.msg').html("Data Saved");
         $(".msg").fadeOut(3000);
         $(".data_display").load("dist/html/accountantSettlement.html");
        }
        else{
         $('.msg').removeClass('alert alert-success');
         $('.msg').addClass('alert alert-danger');
         $('.msg').html(saveReceivedCashDone);
         $(".msg").fadeOut(9000);
        }
       }
      });
     }
    }
    return false;
   });
  });