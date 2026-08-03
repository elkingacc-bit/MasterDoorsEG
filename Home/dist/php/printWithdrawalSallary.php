<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $stafeId=(int)$_GET['stafId'];
 $transactionsDate=date("Y-m-d");
 $actionMonth=date("m");
 function getWeekOfMonth($dateString) {
  $date = new DateTime($dateString);
  // Get the day of the month (1-31)
  $dayOfMonth = (int)$date->format('d');
  // Get the day of the week for the 1st of the month (1 for Monday, 7 for Sunday - 'N' format)
  $firstDayOfMonth = new DateTime($date->format('Y-m-01'));
  $dayOfWeekForFirst = (int)$firstDayOfMonth->format('N');
  // Calculate the number of days elapsed in the current week so far relative to the first day of the month
  // If the first day is Monday (1), days elapsed in week 1 = 0 (1-1=0).
  // If the first day is Sunday (7), days elapsed in week 1 = 6 (7-1=6).
  $daysElapsedInFirstWeek = $dayOfWeekForFirst - 1;
  // The number of full weeks passed plus 1 for the current week.
  // The calculation here essentially counts how many days into the month we are, 
  // offset by the start day of the week.
  $weekOfMonth = ceil(($dayOfMonth + $daysElapsedInFirstWeek) / 7);
  return (int)$weekOfMonth;
 }
 $monthWeek=getWeekOfMonth($transactionsDate);
 if($monthWeek == 1){$weekForm="الأسبوع الأول";}
 else if($monthWeek == 2){$weekForm="الأسبوع الثانى";}
 else if($monthWeek == 3){$weekForm="الأسبوع الثالث";}
 else if($monthWeek == 4){$weekForm="الأسبوع الرابع";}
 else if($monthWeek == 5){$weekForm="الأسبوع الأخير";}
 if($actionMonth == 01){$monthForm="شهر يناير";}
 else if($actionMonth == 02){$monthForm="شهر فبراير";}
 else if($actionMonth == 03){$monthForm="شهر مارس";}
 else if($actionMonth == 04){$monthForm="شهر أبريل";}
 else if($actionMonth == 05){$monthForm="شهر مايو";}
 else if($actionMonth == 06){$monthForm="شهر يونيو";}
 else if($actionMonth == 07){$monthForm="شهر يوليو";}
 else if($actionMonth == 8){$monthForm="شهر أغسطس";}
 else if($actionMonth == 9){$monthForm="شهر سبتمبر";}
 else if($actionMonth == 10){$monthForm="شهر أكتوبر";}
 else if($actionMonth == 11){$monthForm="شهر نوفمبر";}
 else if($actionMonth == 12){$monthForm="شهر ديسمبر";}
 $a="نظير أيام عمل خلال";
 $b="من";
 $c="عام";
 $formTitel=$a." ".$weekForm." ".$b." ".$monthForm.date("Y");


$installments = 0;
 // Sallary
 $sqlSalesInoice="SELECT `staffRId`,count(`attendDate`) as dayWork, sum(`penalty`) as nag, sum(`Reward`) as plu 
 FROM `outsidemanpower` WHERE `paymentRef` = 0 AND `staffRId` = $stafeId group by `staffRId`";
 $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 01");
 $salesInoiceData=mysqli_fetch_assoc($querySalesInoice);
 
 // Stafe Data
 $sqlStaff="SELECT `staffname`,`staffposition`,`dayval` FROM `allstaff` WHERE `id` = $stafeId";
 $queryStaff=mysqli_query($link,$sqlStaff)or die("ERROR_SNSC : 01");
 $staffData=mysqli_fetch_assoc($queryStaff);
 
 $workAmount=($salesInoiceData['dayWork'] * $staffData['dayval'] );
 $rewordAmount=($salesInoiceData['plu'] * $staffData['dayval'] );
 $pelantyAmount=($salesInoiceData['nag'] * $staffData['dayval'] );
 $net=(($workAmount + $rewordAmount ) - $pelantyAmount);
 // Advance
 $sqlAdvanceStatment="SELECT `empId`,sum(`recived`) as adv ,sum(`cashback`) as cas, `installment` 
 FROM `advance` WHERE `recevedRef` = 2 AND `empId`= $stafeId GROUP BY `empId`,`installment` ";  
 $queryAdvanceStatment=mysqli_query($link,$sqlAdvanceStatment);
 $cheakAdvance=mysqli_num_rows($queryAdvanceStatment);
 if($cheakAdvance > 0){
  $advanceStatment=mysqli_fetch_assoc($queryAdvanceStatment);
  $valid= ( $advanceStatment['adv'] - $advanceStatment['cas']);
  if($valid > 0){
   $installments=$advanceStatment['installment'];        
  }
  $netSallary=($net - $installments);
 } 
 else{
  $netSallary=$net;
 }
?>
<center>
<div style="width:17cm;">
 <img src="../../dist/img/logoMarker.png" style="width:75px; height:100px;" class="float-left">
 <h3 class="float-right">شركه ماستر دورز جروب</h3>
 <br>
 <h3 style="margin-left:1cm;padding-top: 3cm;">إذن صرف نقديه </h3>
 <br>
 <div class="float-right" dir="rtl" style="padding-top: 10px;">
  <form>
   <div class="form-group row ">
    <label for="formDate" class="col-sm-2 col-form-label">التاريخ</label>
    <div class="col-sm-10">
     <input type="text" readonly class="form-control-plaintext " id="formDate" value="<?php echo $transactionsDate; ?>">
    </div>
   </div>
   
   <div class="form-group row">
    <label for="inputName" class="col-sm-2 col-form-label">المستفيد</label>
    <div class="col-sm-10">
     <input type="text" readonly class="form-control-plaintext " id="inputName"  value="<?php echo $staffData['staffname']; ?>">
    </div>
   </div>
   
   <div class="form-group row ">
    <label for="staffAmount" class="col-sm-2 col-form-label">المبلغ</label>
    <div class="col-sm-10">
     <input type="text" readonly class="form-control-plaintext " id="staffAmount" value="<?php echo number_format(($netSallary), 2); ?>">
    </div>
   </div>
   
   <div class="form-group row">
    <label for="formData" class="col-sm-2 col-form-label"> البيان</label>
    <div class="col-sm-10">
     <input type="text" readonly class="form-control-plaintext" style="width: 70vw" id="formData"  value="<?php echo $formTitel; ?>">
    </div>
   </div>

<hr>

   <div class="form-group row">
    <label for="formData2" class="col-sm-2 col-form-label">المستلم</label>
    <div class="col-sm-2">
     <input type="text" readonly class="form-control-plaintext" style="width: 30vw" id="formData2" >
    </div>
   


<label for="formData3" class="col-sm-2 col-form-label"> الحسابات</label>
    <div class="col-sm-2">
     <input type="text" readonly class="form-control-plaintext" style="width: 30vw" id="formData3">
    </div>






   </div>

   <hr>
  </form>
 </div>

</div>

<table class='table table-sm ' dir="rtl"  style="width: 75%;">
 <tbody>
  <tr>
   <td style="text-align: left;">التاريخ</td>
   <td><?php echo $transactionsDate; ?></td>  
   <td style="text-align: left;">الاسم</td>
   <td><?php echo $staffData['staffname']; ?></td>
   <td style="text-align: left;">الرتبه</td>
   <td><?php echo $staffData['staffposition']; ?></td>
  </tr>

  <tr>
   <td style="text-align: left;">اجر اليوم</td>
   <td><?php echo $staffData['dayval']; ?> <span>&nbsp;جنيه</span> </td>
   <td style="text-align: left;">عدد الايام</td>
   <td><?php echo $salesInoiceData['dayWork'] ?><span>&nbsp;يوم</span></td>
   <td style="text-align: left;">اجماى الراتب</td>
   <td><?php echo number_format(($workAmount), 2); ?><span>&nbsp;جنيه</span></td>
  </tr>


  <tr>
  	 <td></td>
  	  <td></td>
  	   <td></td>
   <td style="text-align: left;"> السهرات</td>
   <td><?php echo $salesInoiceData['plu'] ?> <span>&nbsp;يوم</span>  </td>
   <td><?php echo  number_format(($rewordAmount), 2); ?><span>&nbsp;جنيه</span></td>
  </tr>
  <tr>
 <td></td>
 <td></td>
   <td></td>
 <td style="text-align: left;">جزاءات</td>
   <td><?php echo $salesInoiceData['nag'] ?><span>&nbsp;يوم</span></td>
   <td><?php echo  number_format(($pelantyAmount), 2); ?><span>&nbsp;جنيه</span></td>
  </tr>


  <tr>

 <td></td>
 <td></td>
   <td></td>
   <td></td>
 <td style="text-align: left;"> الصافي</td>
   <td><?php echo  number_format(($net), 2); ?><span>&nbsp;جنيه</span></td>

  </tr>




  <tr>
 
 <td></td>
 <td></td>
   <td></td>
   <td></td>
 <td style="text-align: left;"> اقساط</td>
   <td><?php echo  number_format(($installments), 2); ?><span>&nbsp;جنيه</span></td>
  </tr>


  <tr>
 <td></td>
 <td></td>
   <td></td>
   <td></td>
 <td style="text-align: left;">المستحق</td>
   <td><?php echo  number_format(($netSallary), 2); ?><span>&nbsp;جنيه</span></td>

  </tr>



 </tbody>
</table>

</center>



  




<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>