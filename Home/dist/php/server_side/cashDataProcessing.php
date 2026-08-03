<?php
 // DB table to use
 $table = 'cash_transaction';
 // Table's primary key
 $primaryKey = 'cash_transaction_id';
 $columns = array(
  array(
   'db'=> 'transactionDate',
   'dt'=> 0,
   'formatter' => function( $d, $row ) {
    return date( 'Y-m-d', strtotime($d));
   }
  ),
  array(
   'db'=> 'income',
   'dt'=> 1,
   'formatter'=> function( $d, $row ) {
    return number_format($d);
   }
  ),
  array(
   'db'=> 'withdrawal',
   'dt'=> 2,
   'formatter'=> function( $d, $row ) {
    return number_format($d);
   }
  ),
  
  array( 'db'=> 'description', 'dt' => 3 ),
  array( 'db'=> 'account',  'dt' => 4 ),
  array( 'db'=> 'empCode',   'dt' => 5 ),
  array( 'db'=> 'empCode', "dt" => 6),
  array( '', "dt" => 7)
 
 );
 // SQL server connection information
 $sql_details = array(
  'user' => 'root',
  'pass' => 'P@ssw0rd',
  'db'   => 'u113768859_cmsdb',
  'host' => 'localhost',
  //'charset' => 'utf8'
 );
 require('ssp.class.php');
 echo json_encode(
  SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
 );
?>