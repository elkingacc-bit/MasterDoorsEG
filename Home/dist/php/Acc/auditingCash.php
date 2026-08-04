<div class="table-responsive-lg">
 <table class='table table-sm table-bordered table-striped myTableSalesInvoCust w-100'>
  <thead class='bg-info text-center'>
   <th class="col-1">SN</th>
   <th>Number</th>
   <th>Date</th>
   <th>Customer</th>
   <th>SupTotal</th>
   <th>Value Added Tax</th>
   <th>Total</th>
  </thead>
  <tbody>
   <?php
    /*
     date_default_timezone_set("Africa/Cairo");
     include_once("../connection.php");
     $customer=$_POST['customer'];
     // Get Sales Invoice From Sales Invoice
     $sqlSalesInoice="SELECT `salesInvoiceId`,`salesInvoiceNumber`,`jopRef`,`salesInvoiceDate`,`customerCode`,`salesInvoiceType`,`salesInvoiceSupTotal`,`invoiceDiscount`,
     `salesInvoictVat`,`totalInvoice`,`invoiceCollectAmount` FROM `salesInvoice` WHERE `customerCode` = $customer";
     $querySalesInoice=mysqli_query($link,$sqlSalesInoice)or die("ERROR_SNSC : 01");
     $sn=0;
     While($salesInoiceData=mysqli_fetch_assoc($querySalesInoice)){
      $sn++;
      $invId=$salesInoiceData['jopRef'];
      $invRef=$salesInoiceData['salesInvoiceId'];
      // Get Customer Data
      $sqlCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $salesInoiceData[customerCode]";
      $quaryCustomer=mysqli_query($link,$sqlCustomer)or die("ERROR_SNSC : 02");
      $customerData=mysqli_fetch_assoc($quaryCustomer);     
      $validAmount=($salesInoiceData['totalInvoice']-$salesInoiceData['invoiceCollectAmount']);
      echo"<tr>
       <td>$sn</td>
       <td>$salesInoiceData[salesInvoiceNumber]</td>
       <td>$salesInoiceData[salesInvoiceDate]</td>
       <td>$customerData[customername]</td>
       <td>".number_format(($salesInoiceData['salesInvoiceSupTotal']), 2)."</td>
       <td>".number_format(($salesInoiceData['salesInvoictVat']), 2)."</td>
       <td>".number_format(($salesInoiceData['totalInvoice']), 2)."</td>
      </tr>";
     }
    */
   ?>
  </tbody>
 </table>
</div>        
