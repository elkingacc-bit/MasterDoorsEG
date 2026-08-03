<?php
 $sqlStatment="SELECT `transactionDate`,`withdrawal` FROM `cash_transaction` WHERE `empCode` = $salesId  AND `account`= 312105100001 ORDER BY `transactionDate` ASC";  
 $queryStatment=mysqli_query($link,$sqlStatment);
 while($statment=mysqli_fetch_assoc($queryStatment)){
 
 }
?>