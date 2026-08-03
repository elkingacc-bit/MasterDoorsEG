<?php
 @session_start();
 date_default_timezone_set('Africa/Cairo');
 //if(!empty($_SESSION['Dept']))
 //{
 if($_SESSION['Dept'] == 'Admin' || $_SESSION['Dept'] == 'Manager'){
  echo"
   <li class='nav-item'><a href='#' class='nav-link UsersLi'><i class='nav-icon fas fa-user'></i><p>Users<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' class='nav-link' id='1_1'><i class='far fa-circle nav-icon'></i><p>Add Users</p></a></li>
     <li class='nav-item'><a href='#' class='nav-link' id='1_2'><i class='far fa-circle nav-icon'></i><p>Manage Users</p></a></li>
    </ul>
   </li>          	   
   <li class='nav-item '>
    <a href='#' class='nav-link AddLi'><i class='nav-icon fas fa-edit'></i><p>Add & Organize<i class='fas fa-angle-left right'></i></p></a> 
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='2_1' class='nav-link'><i class='far fa-circle nav-icon'></i>
	 <p>Add</p></a></li>
     <li class='nav-item'><a href='#' id='2_2' class='nav-link'><i class='far fa-circle nav-icon'></i>
	 <p>Edit</p></a></li>
	  <li class='nav-item'><a href='#' id='2_3' class='nav-link'><i class='far fa-circle nav-icon'></i><p>Assign</p></a></li>     
	  
    </ul>
   </li>
	 <li class='nav-item '>
    <a href='#' class='nav-link OfferLi'><i class='nav-icon fab fa-accusoft'></i><p>Offering<i class='fas fa-angle-left right'></i></p></a> 
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='3_1' class='nav-link'><i class='far fa-circle nav-icon'></i><p>Open</p></a></li>
	 <li class='nav-item'><a href='#' id='3_2' class='nav-link'><i class='far fa-circle nav-icon'></i><p>Offer Study</p></a></li>
	  <li class='nav-item'><a href='#' id='3_3' class='nav-link'><i class='far fa-circle nav-icon'></i><p>Export</p></a></li>
	  <li class='nav-item'><a href='#' id='3_4' class='nav-link'><i class='far fa-circle nav-icon'></i><p>History</p></a></li>
  	</ul>
   </li>
   
   <li class='nav-item'><a href='#' class='nav-link TechLi'><i class='nav-icon fa fa-tasks'></i><p>supplying<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' class='nav-link' id='5_1'><i class='far fa-circle nav-icon'></i><p>Create Supply Order</p></a></li>
	  <li class='nav-item'><a href='#' class='nav-link' id='5_2'><i class='far fa-circle nav-icon'></i><p>Edit Supplier Order</p></a></li>
	 <li class='nav-item'><a href='#' class='nav-link' id='5_3'><i class='far fa-circle nav-icon'></i><p>Deliver Customer Order</p></a></li>
    </ul>
   </li>
   
   <li class='nav-item '>
    <a href='#' class='nav-link RptLi RptLi2'><i class='nav-icon fas fa-chart-pie'></i><p> Reports <i class='fas fa-angle-left right'></i></p></a> 
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='4_1_1' class='nav-link'><i class='far fa-circle nav-icon'></i><p>Stock</p></a></li>
	  <li class='nav-item'><a href='#' id='4_2' class='nav-link'><i class='far fa-circle nav-icon'></i><p>Stock Filter</p></a></li>
	  <li class='nav-item'><a href='#' id='7_4_4' class='nav-link TransLi'><i class='far fa-circle nav-icon'></i><p>Stock History</p></a></li>
	    <li class='nav-item'><a href='#' id='4_4' class='nav-link'><i class='far fa-circle nav-icon'></i><p> Order Status</p></a></li>
	    <li class='nav-item'><a href='#' id='6_4_3' class='nav-link AttHitLi'><i class='far fa-circle nav-icon'></i><p>Attendance History</p></a></li>
	    
	    <li class='nav-item'><a href='#' id='openCustdy' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Custody</p></a></li>
   <li class='nav-item'><a href='#' id='openAdvance' class='nav-link'><i class='fas fa-hands-helping nav-icon'></i><p>Advance Status</p></a></li>   
   
		<li class='nav-item'><a href='#' id='cashStatment' class='nav-link'><i class='far fa-money-bill-alt nav-icon'></i><p>Cash Statement</p></a></li>
<li class='nav-item'><a href='#' id='bankStatment' class='nav-link'><i class='fas fa-landmark nav-icon'></i><p>Bank Statement</p></a></li> 
<li class='nav-item'><a href='#' id='projectExpensis' class='nav-link'><i class='fas fa-city nav-icon'></i><p>Project Expenses</p></a></li>
<li class='nav-item'><a href='#' id='customerStatment' class='nav-link'><i class='fas fa-user-clock nav-icon'></i><p>Customer Accounts</p></a></li>
<li class='nav-item'><a href='#' id='supplierStatment' class='nav-link'><i class='fas fa-user-cog nav-icon'></i><p>Supplier Accounts</p></a></li>
<li class='nav-item'><a href='#' id='incomStatment' class='nav-link'><i class='fas fa-chart-line nav-icon'></i><p>Income Statement</p></a></li>
<li class='nav-item'><a href='#' id='investStatment' class='nav-link'><i class='fas fa-seedling nav-icon'></i><p>Investment Account</p></a></li>
	  <li class='nav-item'><a href='#' id='4_3' class='nav-link'><i class='far fa-circle nav-icon'></i><p>Log Report</p></a></li>
    </ul>
   </li>
   
  ";
  
   echo "

	<script type='text/javascript'>
		
		$(document).ready(function(){
			
			$('.data_display').html('');
	 		$('.data_display').load('dist/html/homePage.html');
			});
	
	</script>
";	 

 }
 //------------------------------------------------------------------------------------------------//
 else if($_SESSION['Dept'] == 'Technical'){
	 
	echo "
	
<li class='nav-item'><a href='#' class='nav-link TechLi'><i class='nav-icon fa fa-tasks'></i><p>Tasks<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
	 <li class='nav-item'><a href='#' class='nav-link SStudyLi' id='8_2'><i class='far fa-circle nav-icon'></i><p>Edit Offers</p></a></li>
     <li class='nav-item'><a href='#' class='nav-link NewOrdLi' id='5_1'><i class='far fa-circle nav-icon'></i><p>Create Supply Order</p></a></li>
	  <li class='nav-item'><a href='#' class='nav-link EditOrdLi' id='5_2'><i class='far fa-circle nav-icon'></i><p>Edit Supplier Order</p></a></li>
	 <li class='nav-item'><a href='#' class='nav-link DelivOrderLi' id='5_3'><i class='far fa-circle nav-icon'></i><p>Deliver Customer Order</p></a></li>
    </ul>
   </li>
    
   <li class='nav-item '>
    <a href='#' class='nav-link RptLi'><i class='nav-icon fas fa-chart-pie'></i><p> Reports <i class='fas fa-angle-left right'></i></p></a> 
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='4_1' class='nav-link StkLi'><i class='far fa-circle nav-icon'></i><p>Stock</p></a></li>
	  <li class='nav-item'><a href='#' id='4_2' class='nav-link StkFiltLi'><i class='far fa-circle nav-icon'></i><p>Stock Filter</p></a></li>
	  <li class='nav-item'><a href='#' id='4_4' class='nav-link'><i class='far fa-circle nav-icon'></i><p> Order Status</p></a></li> 
    </ul>
   </li>		
	
	
	"; 
 }
 
 else if($_SESSION['Dept'] == 'Installation'){
	 
	echo "
	
<li class='nav-item'><a href='#' class='nav-link InstallLi'><i class='nav-icon fa fa-tasks'></i><p>Daliy Work<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' class='nav-link AttedLi' id='6_1'><i class='far fa-circle nav-icon'></i><p>Attendance</p></a></li>
	  <li class='nav-item'><a href='#' class='nav-link EditAttLi' id='6_2'><i class='far fa-circle nav-icon'></i><p>Edit </p></a></li>
	</ul>
   </li>
   
   <li class='nav-item '>
    <a href='#' class='nav-link RptLi'><i class='nav-icon fas fa-chart-pie'></i><p> Reports <i class='fas fa-angle-left right'></i></p></a> 
    <ul class='nav nav-treeview'>
     
	   <li class='nav-item'><a href='#' id='6_4_3' class='nav-link AttHitLi'><i class='far fa-circle nav-icon'></i><p>Attendance History</p></a></li>
	   <li class='nav-item'><a href='#' id='6_4_4' class='nav-link ComisionLi'><i class='far fa-circle nav-icon'></i><p>Commission</p></a></li>
    </ul>
   </li>		
	
	
	"; 
	
	 echo "

	<script type='text/javascript'>
		
		$(document).ready(function(){
			
			$('.data_display').html('');
	 		$('.data_display').load('dist/html/homePageInstall.html');
			});
	
	</script>
";	 
	
 }
 
 else if($_SESSION['Dept'] == 'Store'){
	 
	echo "
	
<li class='nav-item'><a href='#' class='nav-link StkLi'><i class='nav-icon fa fa-tasks'></i><p>Daliy Work<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' class='nav-link RecLi' id='7_1'><i class='far fa-circle nav-icon'></i><p>Receive</p></a></li>
	  <li class='nav-item'><a href='#' class='nav-link ExptLi' id='7_2'><i class='far fa-circle nav-icon'></i><p>Export</p></a></li>
	  <li class='nav-item'><a href='#' class='nav-link ReturnLi' id='7_3'><i class='far fa-circle nav-icon'></i><p>Return</p></a></li>
	</ul>
   </li>
   
   <li class='nav-item '>
    <a href='#' class='nav-link RptLi'><i class='nav-icon fas fa-chart-pie'></i><p> Reports <i class='fas fa-angle-left right'></i></p></a> 
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='4_1' class='nav-link StkLi'><i class='far fa-circle nav-icon'></i><p>Stock</p></a></li>
	  <li class='nav-item'><a href='#' id='4_2' class='nav-link StkFiltLi'><i class='far fa-circle nav-icon'></i><p>Stock Filter</p></a></li>
	   <li class='nav-item'><a href='#' id='7_4_3' class='nav-link TransLi'><i class='far fa-circle nav-icon'></i><p>Stock Transaction</p></a></li>
	   <li class='nav-item'><a href='#' id='7_4_4' class='nav-link TransLi'><i class='far fa-circle nav-icon'></i><p>Stock History</p></a></li>
    </ul>
   </li>		
	
	
	"; 
 }

 else if($_SESSION['Dept'] == 'Sales'){
	 
	echo "
	
<li class='nav-item'><a href='#' class='nav-link SalesLi'><i class='nav-icon fa fa-tasks'></i><p>Daliy Work<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' class='nav-link SOfferLi' id='8_4'><i class='far fa-circle nav-icon'></i><p>Add Customer</p></a></li>
     <li class='nav-item'><a href='#' class='nav-link SOfferLi' id='8_1'><i class='far fa-circle nav-icon'></i><p>Create Offer</p></a></li>

	  <li class='nav-item'><a href='#' class='nav-link SOfferExptLi' id='8_3'><i class='far fa-circle nav-icon'></i><p>Export</p></a></li>
	   <li class='nav-item'><a href='#' id='8_3_4' class='nav-link SOfferHistLi'><i class='far fa-circle nav-icon'></i><p>History</p></a></li>
	</ul>
   </li>
   
   <li class='nav-item '>
    <a href='#' class='nav-link RptLi'><i class='nav-icon fas fa-chart-pie'></i><p> Reports <i class='fas fa-angle-left right'></i></p></a> 
    <ul class='nav nav-treeview'>
    <li class='nav-item'><a href='#' id='8_3_6' class='nav-link allOffersSales'><i class='far fa-circle nav-icon'></i><p>My Offers</p></a></li>
	 <li class='nav-item'><a href='#' id='8_3_5' class='nav-link allPOsSales'><i class='far fa-circle nav-icon'></i><p>Customer Orders</p></a></li>
    </ul>
   </li>		
	
	"; 
	
	echo "

	<script type='text/javascript'>
		
		$(document).ready(function(){
			
			$('.data_display').html('');
	 		$('.data_display').load('dist/php/showEventsSales.php');
			});
	
	</script>
";	 
 }
 
 else if($_SESSION['Dept'] == 'Accountant'){
/*  echo"
     
  <li class='nav-item coding'><a href='#' class='nav-link '><i class='nav-icon far fa-keyboard' ></i>
    <p>Coding<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' class='nav-link' id='newAccountantCode'><i class='fas fa-sitemap nav-icon'></i><p>Chart of Accounts</p></a></li>

    </ul>
   </li>
   
   
   <li class='nav-item cashing'><a href='#' class='nav-link'><i class='fas fa-wallet nav-icon'></i></i>
    <p>Cash<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='cashReceive' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Receipt</p></a></li>
     <li class='nav-item'><a href='#' id='cashWithdraw' class='nav-link'><i class='fas fa-comment-dollar nav-icon'></i><p>Withdrawal</p></a></li>
	 <li class='nav-item'><a href='#'  id='projectSallary' class='nav-link'><i class='nav-icon fas fa-scroll'></i><p>Project payroll</p></a></li>
    </ul>
   </li> 
   
   
   <li class='nav-item advancing'><a href='#' class='nav-link'><i class='nav-icon fas fa-hands-helping'></i>
    <p>Advance<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='advanceWithdraw' class='nav-link'><i class='fas fa-comment-dollar nav-icon'></i><p>Payment</p></a></li>
     <li class='nav-item'><a href='#' id='advanceReceive' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Repayment Installment</p></a></li>
    </ul>
   </li> 
   
   
   <li class='nav-item investing'><a href='#' class='nav-link'><i class='nav-icon fas fa-seedling'></i>
    <p>Investment<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='investmentBuy' class='nav-link'><i class='fas fa-hand-holding-heart nav-icon'></i><p>Buy</p></a></li>
     <li class='nav-item'><a href='#' id='investmentSales' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Sales</p></a></li>
    </ul>
   </li> 
   
   <li class='nav-item purchasing'><a href='#' class='nav-link'><i class='nav-icon fas fa-cart-arrow-down'></i>
    <p>Purchases<i class='fas fa-angle-left right'></i> </p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='newPurchasesOrder' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>Supplier Offer </p></a></li>
     <li class='nav-item'><a href='#' id='newSupplierInvReceived' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>Supplier Invoice</p></a></li>
     <li class='nav-item'><a href='#' id='newSupplierInv' class='nav-link'><i class='fas fa-file-invoice-dollar nav-icon'></i><p>Payment Invoice</p></a></li>
     <li class='nav-item'><a href='#' id='newPaymentSupplier' class='nav-link'><i class='fas fa-file-invoice-dollar nav-icon'></i><p>Payment Supplier</p></a></li>
     <li class='nav-item'><a href='#' id='newPurchesingInv' class='nav-link'><i class='	fas fa-receipt nav-icon'></i><p>Stock Invoice</p></a></li>
    </ul>
   </li>
   
   
   <li class='nav-item salesLi'><a href='#' class='nav-link'><i class='nav-icon fas fa-dolly'></i>
    <p> Sales <i class='fas fa-angle-left right'></i> </p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='newSalesInv' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>New Invoice</p></a></li>
     <li class='nav-item'><a href='#' id='newCollectSalesInv' class='nav-link'><i class='fas fa-file-invoice-dollar nav-icon'></i><p>Collect Invoice</p></a></li>
	  <li class='nav-item' id='projectExtract'><a href='#' class='nav-link'><i class='nav-icon far fa-file-alt'></i><p>Billing in Process</p></a></li>
	  <li class='nav-item'> <a href='#' id='newCollectCustomer' class='nav-link'><i class='fas fa-file-invoice-dollar nav-icon'></i><p>Collect</p></a></li>
    </ul>
   </li>
   <li class='nav-item custdy'><a href='#' class='nav-link'><i class='nav-icon fas fa-donate'></i>
    <p>Custody<i class='fas fa-angle-left right'></i> <span class='badge custdyRepNavCount' style='color: red;'></span></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='custodyWithdraw' class='nav-link'><i class='fas fa-comment-dollar nav-icon'></i><p>Withdrawal</p></a></li>
     <li class='nav-item'><a href='#' id='custodyCashback' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Cashback</p></a></li>
    </ul>
   </li>


   <li class='nav-item statments'><a href='#' id='settlement' class='nav-link'><i class='nav-icon fas fa-balance-scale'></i><p>Adjusting Entry</p></a></li>
   
   
   <li class='nav-item RptLi2'><a href='#' class='nav-link'><i class='nav-icon	fas fa-chart-pie'></i>
    <p>Reports<i class='fas fa-angle-left right'></i></p></a>
    <ul class='nav nav-treeview'>
     <li class='nav-item'><a href='#' id='showCodes' class='nav-link'><i class='fas fa-sitemap nav-icon'></i><p>All Code</p></a></li>
     <li class='nav-item'><a href='#' id='allSalesInvoice' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>Sales Invoice</p></a></li>
     <li class='nav-item'><a href='#' id='allSupplierInvoice' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>Purchases Invoice</p></a></li>
     <li class='nav-item'><a href='#' id='customerStatment' class='nav-link'><i class='fas fa-user-clock nav-icon'></i><p>Customer's Account</p></a></li>
     <li class='nav-item'><a href='#' id='supplierStatment' class='nav-link'><i class='fas fa-user-cog nav-icon'></i><p>Supplier's Account</p></a></li>
     <li class='nav-item'><a href='#' id='projectExpensis' class='nav-link'><i class='fas fa-city nav-icon'></i><p>Project Cost</p></a></li>
     <li class='nav-item'><a href='#' id='projectAccount' class='nav-link'><i class='fas fa-city nav-icon'></i><p>Project Account</p></a></li>
     <li class='nav-item'><a href='#' id='openCustdy' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Custody</p></a></li>
     <li class='nav-item'><a href='#' id='openAdvance' class='nav-link'><i class='fas fa-hands-helping nav-icon'></i><p>Advance Status</p></a></li>     
     <li class='nav-item'><a href='#' id='cashStatment' class='nav-link'><i class='far fa-money-bill-alt nav-icon'></i><p>Cash Statement</p></a></li>
     <li class='nav-item'><a href='#' id='financialTransactions' class='nav-link'><i class=' fas fa-balance-scale nav-icon'></i><p>Financial Transactions</p></a></li>     
     <li class='nav-item'><a href='#' id='bankStatment' class='nav-link'><i class='fas fa-landmark nav-icon'></i><p>Bank Statement</p></a></li>
     <li class='nav-item'><a href='#' id='expensisAnalyze' class='nav-link'><i class='fas fa-dollar-sign nav-icon'></i><p>Expenses Analysis</p></a></li>    
     <li class='nav-item'><a href='#' id='taxStatment' class='nav-link'><i class='fas fa-comments-dollar nav-icon'></i><p>TAX Statement</p></a></li>
    
    </ul>
   </li>
  ";*/
  
  echo"
 <li class='nav-item' data-toggle='tooltip' data-placement='top' title='شجره الحسابات'>
  <a href='#' class='nav-link' id='newAccountantCode'><i class='fas fa-sitemap nav-icon'></i><p>Chart of Accounts</p></a>
 </li>
 <li class='nav-item cashing'>
  <a href='#' class='nav-link'  data-toggle='tooltip' data-placement='top' title='نقديه'>
   <i class='fas fa-wallet nav-icon'></i></i><p>Cash<i class='fas fa-angle-left right'></i></p>
  </a>
  <ul class='nav nav-treeview'>
  <li class='nav-item' data-toggle='tooltip' data-placement='top' title='استلام نقديه'>
   <a href='#' id='cashReceive' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Deposit</p></a>
  </li>
  <li class='nav-item' data-toggle='tooltip' data-placement='top' title='صرف نقديه'>
   <a href='#' id='cashWithdraw' class='nav-link'><i class='fas fa-comment-dollar nav-icon'></i><p>Withdrawal</p></a>
  </li> 
  <div class='dropdown-divider'></div>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='صرف سلفه'>
    <a href='#' id='advanceWithdraw' class='nav-link'><i class='fas fa-comment-dollar nav-icon'></i><p>Advance Withdraw</p></a>
   </li>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='سداد سلفه'>
    <a href='#' id='advanceReceive' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Advance Repayment</p></a>
   </li> 
   <div class='dropdown-divider'></div>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='صرف عهده'>
    <a href='#' id='custodyWithdraw' class='nav-link'><i class='fas fa-comment-dollar nav-icon'></i><p>Custody Withdrawal </p></a>
   </li>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='سداد عهده'>
    <a href='#' id='custodyCashback' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Custody Cashback</p></a>
   </li>
   <div class='dropdown-divider'></div>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='صرف يوميات'>
    <a href='#'id='projectSallary' class='nav-link'><i class='nav-icon fas fa-scroll'></i><p>Project payroll</p></a>
   </li>
  </ul> 
 </li>
 <li class='nav-item investing'>
  <a href='#' class='nav-link' data-toggle='tooltip' data-placement='top' title='إستثمارات'>
   <i class='nav-icon fas fa-seedling'></i><p>Investment<i class='fas fa-angle-left right'></i></p>
  </a>
  <ul class='nav nav-treeview'> 
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='شراء'>
    <a href='#' id='investmentBuy' class='nav-link'><i class='fas fa-hand-holding-heart nav-icon'></i><p>Buy</p></a>
   </li>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='بيع'>
    <a href='#' id='investmentSales' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Sales</p></a>
   </li>
  </ul>
 </li>
 <li class='nav-item purchasing'>
  <a href='#' class='nav-link' data-toggle='tooltip' data-placement='top' title='مشتريات'>
   <i class='nav-icon fas fa-cart-arrow-down'></i><p>Purchases<i class='fas fa-angle-left right'></i> </p>
  </a>
  <ul class='nav nav-treeview'>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='عرض المصنع'>
    <a href='#' id='newPurchasesOrder' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>Manufactur Offer</p></a>
   </li>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='فاتوره مصنع'>
    <a href='#' id='newSupplierInvReceived' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>Manufactur Invoice</p></a>
   </li>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='سداد فاتوره'>
    <a href='#' id='newSupplierInv' class='nav-link'><i class='fas fa-file-invoice-dollar nav-icon'></i><p>Payment Invoice</p></a>
   </li>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='دفعه الى مورد'>
    <a href='#' id='newPaymentSupplier' class='nav-link'><i class='fas fa-file-invoice-dollar nav-icon'></i><p>Payment Supplier</p></a>
   </li>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='فاتوره مشتريات'>
    <a href='#' id='newPurchesingInv' class='nav-link'><i class='	fas fa-receipt nav-icon'></i><p>Supplier Invoice</p></a>
   </li>

<li class='nav-item'>
    <a href='#' id='newPurchesingTaxInv' class='nav-link'><i class='fas fa-receipt nav-icon'></i><p>Tax Invoice</p></a>
   </li>


  </ul> 
 </li> 
 <li class='nav-item salesLi'>
  <a href='#' class='nav-link' data-toggle='tooltip' data-placement='top' title='مبيعات'>
   <i class='nav-icon fas fa-dolly'></i><p> Sales <i class='fas fa-angle-left right'></i> </p>
  </a>
  <ul class='nav nav-treeview'> 
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='فاتوره مبيعات'>
    <a href='#' id='newSalesInv' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>New Invoice</p></a>
   </li>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='تحصيل فاتوره'>
    <a href='#' id='newCollectSalesInv' class='nav-link'><i class='fas fa-file-invoice-dollar nav-icon'></i><p>Collect Invoice</p></a>
   </li>
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='دفعه من عميل'>
    <a href='#' id='newCollectCustomer' class='nav-link'><i class='fas fa-file-invoice-dollar nav-icon'></i><p>Collect Customer</p></a>
   </li>	 
   <li class='nav-item' data-toggle='tooltip' data-placement='top' title='مستخلصات'>
    <a href='#' id='projectExtract' class='nav-link'><i class='nav-icon far fa-file-alt'></i><p>Billing in Process</p></a>
   </li>

   <li class='nav-item'>
    <a href='#' id='newSalesTaxInv' class='nav-link'><i class='fas fa-receipt nav-icon'></i><p>Tax Invoice</p></a>
   </li>


  </ul> 
 </li> 
 <li class='nav-item statments' data-toggle='tooltip' data-placement='top' title='قيد تسويه'>
  <a href='#' id='settlement' class='nav-link'><i class='nav-icon fas fa-balance-scale'></i><p>Adjusting Entry</p></a>
 </li>
 <li class='nav-item RptLi2'><a href='#' class='nav-link'><i class='nav-icon	fas fa-chart-pie'></i>
  <p>Reports<i class='fas fa-angle-left right'></i></p></a>
  <ul class='nav nav-treeview'>
   <li class='nav-item'><a href='#' id='showCodes' class='nav-link'><i class='fas fa-sitemap nav-icon'></i><p>All Code</p></a></li>
   <li class='nav-item'><a href='#' id='allSalesInvoice' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>Sales Invoice</p></a></li>
   <li class='nav-item'><a href='#' id='allSupplierInvoice' class='nav-link'><i class='fas fa-file-invoice nav-icon'></i><p>Purchases Invoice</p></a></li>
   <li class='nav-item'><a href='#' id='customerStatment' class='nav-link'><i class='fas fa-user-clock nav-icon'></i><p>Customer's Account</p></a></li>
   <li class='nav-item'><a href='#' id='supplierStatment' class='nav-link'><i class='fas fa-user-cog nav-icon'></i><p>Supplier's Account</p></a></li>
   <li class='nav-item'><a href='#' id='openCustdy' class='nav-link'><i class='fas fa-hand-holding-usd nav-icon'></i><p>Custody</p></a></li>
   <li class='nav-item'><a href='#' id='openAdvance' class='nav-link'><i class='fas fa-hands-helping nav-icon'></i><p>Advance Status</p></a></li>     
   <li class='nav-item'><a href='#' id='cashStatment' class='nav-link'><i class='far fa-money-bill-alt nav-icon'></i><p>Cash Statement</p></a></li>
   <li class='nav-item'><a href='#' id='financialTransactions' class='nav-link'><i class=' fas fa-balance-scale nav-icon'></i><p>Financial Transactions</p></a></li>     
   <li class='nav-item'><a href='#' id='bankStatment' class='nav-link'><i class='fas fa-landmark nav-icon'></i><p>Bank Statement</p></a></li>
   <li class='nav-item'><a href='#' id='expensisAnalyze' class='nav-link'><i class='fas fa-dollar-sign nav-icon'></i><p>Expenses Analysis</p></a></li>    
   <li class='nav-item'><a href='#' id='taxStatment' class='nav-link'><i class='fas fa-comments-dollar nav-icon'></i><p>TAX Statement</p></a></li>
   <li class='nav-item'><a href='#' id='taxBalance' class='nav-link'><i class='fas fa-comments-dollar nav-icon'></i><p>TAX Invoice</p></a></li>
   <li class='nav-item'><a href='#' id='investStatment' class='nav-link'><i class='fas fa-seedling nav-icon'></i><p>Investment Account</p></a></li>
     <li class='nav-item'><a href='#' id='projectExpensis' class='nav-link'><i class='fas fa-city nav-icon'></i><p>Project Cost</p></a></li>
     <li class='nav-item'><a href='#' id='projectAccount' class='nav-link'><i class='fas fa-city nav-icon'></i><p>Project Account</p></a></li>
<li class='nav-item'><a href='#' id='2_2' class='nav-link'><i class='far fa-circle nav-icon'></i>
     <p>Edit</p></a></li>
     <li class='nav-item'><a href='#' id='salesCommission' class='nav-link'><i class='fas fa-chart-line nav-icon'></i><p>Sales Commission</p></a></li>
     <li class='nav-item'><a href='#' id='incomStatment' class='nav-link'><i class='fas fa-chart-line nav-icon'></i><p>Income Statement</p></a></li>
     <li class='nav-item'><a href='#' id='outcomeCash' class='nav-link'><i class='fas fa-chart-line nav-icon'></i><p>Outcome Statement</p></a></li>
  </ul>
 </li>
";

  
  
 }
?>
<script type="text/javascript">
 $(document).ready(function(){
	"use strict";
	//admin users
	$('#1_1').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".UsersLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/addUser.html');
	 $('.m-0').html('');
	 $('.m-0').html('Add New User');
	 return false;
	});
	$('#1_2').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".UsersLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/showAllUsers.html');
	 $('.m-0').html('');
	 $('.m-0').html('Users Status');
	 return false;
	});
	
	$('#2_1').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".AddLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/allAdded.html');
	 $('.m-0').html('');
	 $('.m-0').html('New Add');
	 return false;
	});
	$('#2_2').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".AddLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/allEdit.html');
	 $('.m-0').html('');
	 $('.m-0').html('Edit');
	 return false;
	});
	
	$('#2_3').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".AddLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/groupingOrganize.html');
	 $('.m-0').html('');
	 $('.m-0').html('Organize Stock');
	 return false;
	});
	$('#3_1').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".OfferLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/newOffer.html');
	 $('.m-0').html('');
	 $('.m-0').html('Create New Offer');
	 return false;
	});
	$('#3_2').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".OfferLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/editOffer.html');
	 $('.m-0').html('');
	 $('.m-0').html('Create Offers');
	 return false;
	});
	$('#3_3').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".OfferLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/exportOffer.html');
	 $('.m-0').html('');
	 $('.m-0').html('Export Offer');
	 return false;
	});
	$('#3_4').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".OfferLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/historyOffer.html');
	 $('.m-0').html('');
	 $('.m-0').html('Offer History Report');
	 return false;
	});
	
	$('#4_1').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/currentStock.html');
	 $('.m-0').html('');
	 $('.m-0').html('All Stock Status');
	 return false;
	});
	$('#4_1_1').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/currentStockAdmin.html');
	 $('.m-0').html('');
	 $('.m-0').html('All Stock Status');
	 return false;
	});
	$('#4_2').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/stockFilter.html');
	 $('.m-0').html('');
	 $('.m-0').html('Stock Filter');
	 return false;
	});
	$('#4_3').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/logRpt.html');
	 $('.m-0').html('');
	 $('.m-0').html('Stock Filter');
	 return false;
	});
	$('#4_4').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/CustOrderRpt.html');
	 $('.m-0').html('');
	 $('.m-0').html('Order Status Report');
	 return false;
	});
	
	$('#5_1').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".TechLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/createSuppOrder.html');
	 $('.m-0').html('');
	 $('.m-0').html('Create Supplier Order');
	 return false;
	});
	$('#5_2').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".TechLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/editSuppOrder.html');
	 $('.m-0').html('');
	 $('.m-0').html('Edit Supplier Order');
	 return false;
	});
	$('#5_3').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".TechLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/DeliverCustOrder.html');
	 $('.m-0').html('');
	 $('.m-0').html('Deliver Items for Customer Order ');
	 return false;
	});
	
	$('#6_1').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".InstallLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/addStaffAtted.html');
	 $('.m-0').html('');
	 $('.m-0').html('Staff Attendance in Project');
	 return false;
	});
	$('#6_2').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".InstallLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/editStaffAtted.html');
	 $('.m-0').html('');
	 $('.m-0').html('Edit Staff Attendance for Today');
	 return false;
	});
	$('#6_4_3').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/showStaffAttedHist.html');
	 $('.m-0').html('');
	 $('.m-0').html('Staff Attendance History Report');
	 return false;
	});
	$('#6_4_4').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/showInstallCommision.html');
	 $('.m-0').html('');
	 $('.m-0').html('Orders Commission Report');
	 return false;
	});
	
	$('#7_1').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".StkLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/receiveStock.html');
	 $('.m-0').html('');
	 $('.m-0').html('Receiving New Stock');
	 return false;
	});
	$('#7_2').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".StkLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/exportStock.html');
	 $('.m-0').html('');
	 $('.m-0').html('Export New Stock');
	 return false;
	});
	$('#7_3').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".StkLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/returnStock.html');
	 $('.m-0').html('');
	 $('.m-0').html('Return New Stock');
	 return false;
	});
	$('#7_4_3').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/showStockHist.html');
	 $('.m-0').html('');
	 $('.m-0').html('Stock Transaction History Report');
	 return false;
	});
	$('#7_4_4').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/StockImportExportHist.html');
	 $('.m-0').html('');
	 $('.m-0').html('Stock History Report');
	 return false;
	});
	
	$('#8_1').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".SalesLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/newOfferSales.html');
	 $('.m-0').html('');
	 $('.m-0').html('Receiving New Stock');
	 return false;
	});
	$('#8_2').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".SalesLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/editOfferSales.html');
	 $('.m-0').html('');
	 $('.m-0').html('Create Offer Items');
	 return false;
	});
	$('#8_3').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".SalesLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/exportOfferSales.html');
	 $('.m-0').html('');
	 $('.m-0').html('Export Offer Items');
	 return false;
	});
	$('#8_4').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".SalesLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/AddCustomerSales.html');
	 $('.m-0').html('');
	 $('.m-0').html('Add New Customer');
	 return false;
	});
	$('#8_3_4').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".SalesLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/historyOfferSales.html');
	 $('.m-0').html('');
	 $('.m-0').html('Offer History Report');
	 return false;
	});
	
	$('#8_3_5').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/AllPOSales.html');
	 $('.m-0').html('');
	 $('.m-0').html('Customers Orders');
	 return false;
	});
	//
	$('#8_3_6').click(function(){
	 $('.nav-link').removeClass("active");
	 $(".RptLi").addClass('active ');
	 $(this).addClass('active ');
	 $('.data_display').html('');
	 $('.data_display').load('dist/html/openedOffersSales.html');
	 $('.m-0').html('');
	 $('.m-0').html('My Opened Offers');
	 return false;
	});
	
 //-------------------------*{ Accountant Js }*------------------------- \\
  /*--------------------{ Coding }--------------------*/
  /*---------------{ Coding 1 }---------------*/
  $("#newAccountantCode").click(function(){
   $('.nav-link').removeClass("active");
   $(".coding").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Add New Accountant Code");
   $(".data_display").load("dist/html/addNewAccountantCode.html");
  });
  /*---------------{ Coding 2 }---------------*/
  $("#addPartner").click(function(){
   $('.nav-link').removeClass("active");
   $(".coding").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Add New Partner");
   $(".data_display").load("dist/html/addNewPartner.html");
  });
  /*--------------------{ Cash }--------------------*/  
  /*---------------{ Cash 1 }---------------*/
  $("#cashReceive").click(function(){
   $('.nav-link').removeClass("active");
   $(".cashing").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Cash Receive");
   $(".data_display").load("dist/html/newCashReceive.html");
  });
  /*---------------{ Cash 2 }---------------*/
  $("#cashWithdraw").click(function(){
   $('.nav-link').removeClass("active");
   $(".cashing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Cash Withdraw");
   $(".data_display").load("dist/html/newCashWithdraw.html");
  });
  /*---------------{ Cash 3 }---------------*/
  $("#projectSallary").click(function(){
   $('.nav-link').removeClass("active");
   $(".cashing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("All Project Sallary Data");
   $(".data_display").load("dist/html/withdrawprojectSallary.html");
  });
  /*--------------------{ Advance }--------------------*/ 
  /*---------------{ Advance 1 }---------------*/
  $("#advanceReceive").click(function(){
   $('.nav-link').removeClass("active");
   $(".advancing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Add Advance Installment");
   $(".data_display").load("dist/html/newAdvanceReceive.html");
  });
  /*---------------{ Advance 2 }---------------*/ 
  $("#advanceWithdraw").click(function(){
   $('.nav-link').removeClass("active");
   $(".advancing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Add Advance payment");
   $(".data_display").load("dist/html/newAdvanceWithdraw.html");
  });
  /*--------------------{ Invesment }--------------------*/ 
  /*---------------{ Invesment 1 }---------------*/
  $("#investmentBuy").click(function(){
   $('.nav-link').removeClass("active");
   $(".investing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Add  Investment");
   $(".data_display").load("dist/html/newInvestmentBuy.html");
  });
  /*---------------{ Invesment 2 }---------------*/
  $("#investmentSales").click(function(){
   $('.nav-link').removeClass("active");
   $(".investing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("New Sales Investment");
   $(".data_display").load("dist/html/newInvestmentSales.html");
  });
  /*--------------------{ Purchesing }--------------------*/ 
  /*---------------{ Purchesing 1 }---------------*/
  $("#newPurchasesOrder").click(function(){
   $('.nav-link').removeClass("active");
   $(".purchasing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("New Supplier Order Offer");
   $(".data_display").load("dist/html/addPurchasesOrder.html");
  });
  /*---------------{ Purchesing 2 }---------------*/
  $("#newSupplierInvReceived").click(function(){
   $('.nav-link').removeClass("active");
   $(".purchasing").addClass('active ');
   $(this).addClass('active ');
   var suppType =1;
   $.ajax({
    url:'dist/php/purchesingInvoiceData.php',
    type:"POST",
    data:{repType:suppType},
    success: function(getInvPaiedData){
     $(".data_display").html('');
     $(".m-0").html("New Supplier Invoice Received");
     $(".data_display").html(getInvPaiedData)
    }
   });
   return false;
  });
  /*---------------{ Purchesing 3 }---------------*/
  $("#newSupplierInv").click(function(){
   $('.nav-link').removeClass("active");
   $(".purchasing").addClass('active ');
   $(this).addClass('active ');
   var supplierType =5;
   $.ajax({
    url:'dist/php/purchesingInvoiceData.php',
    type:"POST",
    data:{repType:supplierType},
    success: function(getInvData){
     $(".data_display").html('');
     $(".m-0").html("Supplier Invoice Paid");
     $(".data_display").html(getInvData)
    }
   });
   return false;
  });
  /*---------------{ Purchesing 4 }---------------*/
  $("#newPurchesingInv").click(function(){
   $('.nav-link').removeClass("active");
   $(".purchasing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Add New Stock Invoice");
   $(".data_display").load("dist/html/newBuyStockInvoice.html");
  });
  /*---------------{ Purchesing 5 }---------------*/
  $("#newPaymentSupplier").click(function(){
   $('.nav-link').removeClass("active");
   $(".purchasing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Supplier Payment");
   $(".data_display").load("dist/html/newPaymentSupplierTotal.html");
  });
  /*---------------{ Purchesing 6 }---------------*/
  $("#newPurchesingTaxInv").click(function(){
   $('.nav-link').removeClass("active");
   $(".purchasing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Supplier Payment");
   $(".data_display").load("dist/html/addPurchaseInvoice.php");
  });


  /*--------------------{ Sales }--------------------*/ 
  /*---------------{ Sales 1 }---------------*/
  $("#newSalesInv").click(function(){
   $('.nav-link').removeClass("active");
   $(".salesLi").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Add New Sales Invoice");
   $(".data_display").load("dist/html/newSalesInvoice.html");
  });
  /*---------------{ Sales 2 }---------------*/
  $("#newCollectSalesInv").click(function(){
   $('.nav-link').removeClass("active");
   $(".salesLi").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Sales Invoice Account");
   $(".data_display").load("dist/html/newSalesInvoiceCollect.html");
  });
  /*---------------{ Sales 3 }---------------*/
  $("#projectExtract").click(function(){
   $('.nav-link').removeClass("active");
   $(".salesLi").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Collect Billing in Process");
   $(".data_display").load("dist/html/newProjectExtract.html");
  });
  /*---------------{ Sales 4 }---------------*/

  $("#newCollectCustomer").click(function(){
   $('.nav-link').removeClass("active");
   $(".salesLi").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Customer Collect");
   $(".data_display").load("dist/html/newCustomerCollect.html");
  });

$("#newSalesTaxInv").click(function(){
   $('.nav-link').removeClass("active");
   $(".purchasing").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Supplier Payment");
   $(".data_display").load("dist/html/addItemWithdrawal.php");
  });
  

  /*--------------------{ Custdy }--------------------*/ 
  /*---------------{ Custdy 1 }---------------*/
  $("#custodyWithdraw").click(function(){
   $('.nav-link').removeClass("active");
   $(".custdy").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("New Withdraw Custody");
   $('.data_display').load('dist/html/withdrawCustody.html');
  });
  /*---------------{ Custdy 2 }---------------*/
  $("#custodyCashback").click(function(){
   $('.nav-link').removeClass("active");
   $(".custdy").addClass('active ');
   $(this).addClass('active ');  
   $(".data_display").html('');
   $(".m-0").html("New Cashback Custody");
   $('.data_display').load('dist/html/custodyCashback.html');
  });
  /*--------------------{ Adjusting }--------------------*/ 
  /*---------------{ Adjusting 1 }---------------*/
  $("#settlement").click(function(){
   $('.nav-link').removeClass("active");
   $(".statments").addClass('active ');
   $(this).addClass('active ');
   $(".data_display").html('');
   $(".m-0").html("Add New Adjusting Entry");
   $(".data_display").load("dist/html/accountantSettlement.html");
  });
  /*--------------------{ Reports }--------------------*/ 
  /*---------------{ Reports 1 }---------------*/
  $("#showCodes").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active ');  
   $(".m-0").html("All Accountant Code");
   $(".data_display").load("dist/html/allCode.html");
  }); 
  /*---------------{ Reports 2 }---------------*/
  $("#allSalesInvoice").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("All Sales Invoice Data");
   $(".data_display").load("dist/html/salesInvoiceRep.html");
  });
  /*---------------{ Reports 3 }---------------*/
  $("#allSupplierInvoice").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active ');   
   $(".data_display").html('');
   $(".m-0").html("All Suppliers Invoice Data");
   $(".data_display").load("dist/html/suppliersInvoiceRep.html");
  });
  /*---------------{ Reports 4 }---------------*/
  $("#customerStatment").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Customer's Account");
   $(".data_display").load("dist/html/customerStatment.html");
  });
  /*---------------{ Reports 5 }---------------*/
  $("#supplierStatment").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Supplier's Account");
   $(".data_display").load("dist/html/supplierStatment.html");
  });
  /*---------------{ Reports 6 }---------------*/
  $("#projectExpensis").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active ');   
   $(".data_display").html('');
   $(".m-0").html("project Cost");
   $('.data_display').load('dist/html/allPoExpencess.html');
  });
  /*---------------{ Reports 7 }---------------*/
  $("#projectAccount").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active ');   
   $(".data_display").html('');
   $(".m-0").html("project Account");
   $('.data_display').load('dist/html/profitProject.html');
  });
  /*---------------{ Reports 8 }---------------*/
  $("#openCustdy").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active ');   
   $(".data_display").html('');
   $(".m-0").html("All Open Custody");
   $(".data_display").load("dist/html/allCustodyData.html");
  });
  /*---------------{ Reports 9 }---------------*/
  $("#openAdvance").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Advance Transaction");
   $(".data_display").load("dist/html/allOpenAdvance.html");
  });
  /*---------------{ Reports 10 }---------------*/
  $("#cashStatment").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Cash Statement");
   $(".data_display").load("dist/html/cashStatment.html");
  });
  /*---------------{ Reports 11 }---------------*/
  $("#financialTransactions").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("All Financial Transactions");
   $(".data_display").load("dist/html/allFinancialTransactions.html");
  });
  /*---------------{ Reports 12 }---------------*/
  $("#bankStatment").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Bank Statement");
   $(".data_display").load("dist/html/bankStatment.html");
  });
  /*---------------{ Reports 13 }---------------*/
  $("#expensisAnalyze").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("");
   $(".data_display").load("dist/html/expencessAnalyze.html");
  });
  /*---------------{ Reports 14 }---------------*/
  $("#taxStatment").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Value Added Tax Statement");
   $(".data_display").load("dist/html/taxStatment.html");
  });

$("#taxBalance").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Value Added Tax Statement");
   $(".data_display").load("dist/php/itemsBalance.php");
  });
  

  /*---------------{ Reports 15 }---------------*/
  $("#incomStatment").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active ');   
   $(".data_display").html('');
   $(".m-0").html("Cash Flow Statement");
   $(".data_display").load("dist/php/showIncomStatment.php");
  });
  /*---------------{ Reports 16 }---------------*/
  $("#investStatment").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Invesment Transaction");
   $(".data_display").load("dist/html/allInvestStatment.html");
  });
  /*---------------{ Reports 16 }---------------*/
  $("#salesCommission").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Sales Commission");
   $(".data_display").load("dist/html/allSalesCommission.html");
  });
  
  /*---------------{ Reports 16 }---------------*/
  $("#outcomeCash").click(function(){
   $('.nav-link').removeClass("active");
   $(".RptLi2").addClass('active ');
   $(this).addClass('active '); 
   $(".data_display").html('');
   $(".m-0").html("Sales Commission");
   $(".data_display").load("dist/php/allOutComeCash.php");
  });



 });// docoument dot ready



</script>