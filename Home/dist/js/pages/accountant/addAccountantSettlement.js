 $(document).ready(function(){
   $(".entry3").hide();
   $(".entry4").hide();

   function validateField($field){
    var val = $.trim($field.val());
    var $group = $field.closest('.form-group');
    if(val === ''){
     $field.addClass('is-invalid');
     $group.find('.invalid-feedback').addClass('show');
    }
    else{
     $field.removeClass('is-invalid');
     $group.find('.invalid-feedback').removeClass('show');
    }
   }

   $(document).on('blur change', '#settlementForm input[type="date"], #settlementForm select, #settlementForm input[type="text"]', function(){
    validateField($(this));
   });

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

    validateField($("#receDate1")); validateField($("#accountName1")); validateField($("#receivDescription1"));
    validateField($("#receDate"+dataCount)); validateField($("#accountName"+dataCount)); validateField($("#receivDescription"+dataCount));

    if(trCell11 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else if(trCell21 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else if(trCell31 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else if(trCell41 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else if(trCell51 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else if(trCell13 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else if(trCell23 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else if(trCell33 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else if(trCell43 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else if(trCell53 == ""){ $(".msgData").text("Dont Skip Filed Empty");}
    else{
     $(".msgData").text('');
     if(dataCount < 4){
      var nextRow = (Number(dataCount)+1);
      $("#entryCount").val(nextRow);
      $(".entry"+nextRow).slideDown(200);
     }
    }
   });
   $("#settlementSave").prop('disabled', true);
   $("#accountName1").load("dist/php/Acc/allCodeSelect.php");
   $("#accountName1").change(function(){
    var oldCode = $(this).val();
    $.ajax({
     url:'dist/php/Acc/nextCodeSelect.php',
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
     url:'dist/php/Acc/nextCodeSelect2.php',
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
     url:'dist/php/Acc/nextCodeSelect3.php',
     type:"POST",
     data:{accCode:oldCode4,code2:oldCode5,code3:oldCode6},
     success: function(getSecoundAccount3){
      $("#accountName3").html(getSecoundAccount3);
     }
    });
    return false;
   });

   // Live balance check as the user types (instead of only on 'change'),
   // plus a running balance readout instead of a plain text message
   function checkBalance(){
    var dataCount = Number($("#entryCount").val());
    var totalDeb = 0;
    var totalCre = 0;
    for(var i=1; i<=dataCount; i++){
     totalDeb += Number($("#debtorAmount"+i).val()) || 0;
     totalCre += Number($("#creditorAmount"+i).val()) || 0;
    }
    var diff = totalDeb - totalCre;
    if(diff === 0){
     $("#settlementSave").prop('disabled', false);
     $("#settlementBalance").html('<span class="text-success">Balanced - Debtor '+totalDeb.toFixed(2)+' = Creditor '+totalCre.toFixed(2)+'</span>');
    }
    else{
     $("#settlementSave").prop('disabled', true);
     $("#settlementBalance").html('<span class="text-danger">Unbalanced - Debtor '+totalDeb.toFixed(2)+' / Creditor '+totalCre.toFixed(2)+' (diff '+diff.toFixed(2)+')</span>');
    }
   }

   $(document).on('input change', '[id^="debtorAmount"], [id^="creditorAmount"]', function(){
    checkBalance();
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
     if(cell11 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell21 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell31 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell41 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell51 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell12 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell22 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell32 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell42 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell52 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else{
      var $btn = $('#settlementSave');
      var originalText = $btn.text();
      $.ajax({
       url:'dist/php/Acc/saveNewSettlement.php',
       type:"POST",
       data:{count:getDataCount,c1:cell11,c2:cell21,c3:cell31,c4:cell41,c5:cell51,c6:cell12,c7:cell22,c8:cell32,c9:cell42,c10:cell52},
       beforeSend:function(){
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
       },
       success: function(saveReceivedCashDone){
        $btn.prop('disabled', false).text(originalText);
        if(saveReceivedCashDone == 1){
         $('.msgData').removeClass('alert-danger');
         $('.msgData').addClass('alert-success');
         $('.msgData').hide().html("Data Saved").fadeIn(150);
         $(".msgData").delay(2000).fadeOut(600);
         $(".data_display").load("dist/html/Acc/accountantSettlement.html");
        }
        else{
         $('.msgData').removeClass('alert-success');
         $('.msgData').addClass('alert-danger');
         $('.msgData').hide().html(saveReceivedCashDone).fadeIn(150);
         $(".msgData").delay(6000).fadeOut(600);
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
     if(cell11 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell21 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell31 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell41 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell51 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell12 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell22 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell32 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell42 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell52 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell13 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell23 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell33 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell43 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell53 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else{
      var $btn = $('#settlementSave');
      var originalText = $btn.text();
      $.ajax({
       url:'dist/php/Acc/saveNewSettlement.php',
       type:"POST",
       data:{count:getDataCount,c1:cell11,c2:cell21,c3:cell31,c4:cell41,c5:cell51,c6:cell12,c7:cell22,c8:cell32,c9:cell42,c10:cell52,c11:cell13,c12:cell23,c13:cell33,c14:cell43,c15:cell53},
       beforeSend:function(){
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
       },
       success: function(saveReceivedCashDone){
        $btn.prop('disabled', false).text(originalText);
        if(saveReceivedCashDone == 1){
         $('.msgData').removeClass('alert-danger');
         $('.msgData').addClass('alert-success');
         $('.msgData').hide().html("Data Saved").fadeIn(150);
         $(".msgData").delay(2000).fadeOut(600);
         $(".data_display").load("dist/html/Acc/accountantSettlement.html");
        }
        else{
         $('.msgData').removeClass('alert-success');
         $('.msgData').addClass('alert-danger');
         $('.msgData').hide().html(saveReceivedCashDone).fadeIn(150);
         $(".msgData").delay(6000).fadeOut(600);
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
     if(cell11 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell21 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell31 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell41 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell51 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell12 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell22 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell32 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell42 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell52 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell13 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell23 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell33 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell43 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell53 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell14 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell24 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell34 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell44 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else if(cell54 == ''){$(".msgData").text('Dont Skip Filed Empty');}
     else{
      var $btn = $('#settlementSave');
      var originalText = $btn.text();
      $.ajax({
       url:'dist/php/Acc/saveNewSettlement.php',
       type:"POST",
       data:{count:getDataCount,c1:cell11,c2:cell21,c3:cell31,c4:cell41,c5:cell51,c6:cell12,c7:cell22,c8:cell32,c9:cell42,c10:cell52,c11:cell13,c12:cell23,c13:cell33,c14:cell43,c15:cell53,c16:cell14,c17:cell24,c18:cell34,c19:cell44,c20:cell54},
       beforeSend:function(){
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
       },
       success: function(saveReceivedCashDone){
        $btn.prop('disabled', false).text(originalText);
        if(saveReceivedCashDone == 1){
         $('.msgData').removeClass('alert-danger');
         $('.msgData').addClass('alert-success');
         $('.msgData').hide().html("Data Saved").fadeIn(150);
         $(".msgData").delay(2000).fadeOut(600);
         $(".data_display").load("dist/html/Acc/accountantSettlement.html");
        }
        else{
         $('.msgData').removeClass('alert-success');
         $('.msgData').addClass('alert-danger');
         $('.msgData').hide().html(saveReceivedCashDone).fadeIn(150);
         $(".msgData").delay(6000).fadeOut(600);
        }
       }
      });
     }
    }
    return false;
   });
  });
