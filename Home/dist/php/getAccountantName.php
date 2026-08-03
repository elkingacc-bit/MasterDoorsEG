<?php
 # Accountant Code
 $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $searchCode";  
 $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
 # Customer Code
 $sqlCustomerCodeData="SELECT `customername` FROM `customers` WHERE `customercode` = $searchCode";  
 $queryCustomerCodeData=mysqli_query($link,$sqlCustomerCodeData);
 # Supplier Code
 $sqlSupplierCodeData="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $searchCode";  
 $querySupplierCodeData=mysqli_query($link,$sqlSupplierCodeData);
 # Emp Code
 $sqlEmpCodeData="SELECT `fullname` FROM `users` WHERE `userid` = $searchCode";  
 $queryEmpCodeData=mysqli_query($link,$sqlEmpCodeData);
 # Staff Code
 $sqlStaffCodeData="SELECT `staffname` FROM `allstaff` WHERE `id` = $searchCode";  
 $queryStaffCodeData=mysqli_query($link,$sqlStaffCodeData);
 if(mysqli_num_rows($queryAccountantCodeData) > 0 )
 {
  $accData=mysqli_fetch_assoc($queryAccountantCodeData);        
  $name=$accData['accountName'];
 }
 else if(mysqli_num_rows($queryCustomerCodeData) > 0 )
 {
  $accData=mysqli_fetch_assoc($queryCustomerCodeData);        
  $name=$accData['customername'];
 }
 else if(mysqli_num_rows($querySupplierCodeData) > 0 )
 {
  $accData=mysqli_fetch_assoc($querySupplierCodeData);        
  $name=$accData['suppliername'];
 }
 else if(mysqli_num_rows($queryEmpCodeData) > 0 )
 {
  $accData=mysqli_fetch_assoc($queryEmpCodeData);        
  $name=$accData['fullname'];
 }
 else if(mysqli_num_rows($queryStaffCodeData) > 0 )
 {
  $accData=mysqli_fetch_assoc($queryStaffCodeData);        
  $name=$accData['staffname'];
 }
 else
 {
  $name="No Name";
 }
?>