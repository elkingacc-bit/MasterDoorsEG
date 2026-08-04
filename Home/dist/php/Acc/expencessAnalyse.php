<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <?php
  date_default_timezone_set("Africa/Cairo");
  include_once("../connection.php");
  $sYear=$_POST['startDate'];
 ?>
 <h3>General Expencess Analysis <small><?php echo $sYear; ?> </small>  </h3>    
 <table class="table table-bordered table-striped w-100 myTableExpencess">
  <thead class="bg-info  text-center">
   <th>Account</th>
   <th>Jan</th>
   <th>Feb</th>
   <th>Mar</th>
   <th>Apr</th>
   <th>May</th>
   <th>Jun</th>
   <th>Jul</th>
   <th>Aug</th>
   <th>Sep</th>
   <th>Oct</th>
   <th>Nov</th>
   <th>Des</th>
   <th>Total</th>
  </thead>
  <tbody>
   <?php
    $getAccountCode="SELECT `accountCode`, `accountName` FROM `accountantcode` WHERE `codeLen` = 12 AND `accountCode` LIKE '310%'"; 
    $getAccountCodeQuery=mysqli_query($link,$getAccountCode); 
    $totalNetExpences=0;
    WHILE($accountCode=mysqli_fetch_assoc($getAccountCodeQuery)){
     echo "<tr><td>$accountCode[accountName]</td>";
     $code=$accountCode['accountCode'];
     for ($i2=1; $i2 <=12 ; $i2++) { 
      $getItemDataTreasury="SELECT sum(`income`) as incom,sum(`withdrawal`) as expences FROM `cash_transaction` 
      WHERE account = $code AND year(`transactionDate`) = $sYear AND month(`transactionDate`) = $i2";
      $getItemDataTreasuryQuery=mysqli_query($link,$getItemDataTreasury);
      $itemsDataTreasury=mysqli_fetch_assoc($getItemDataTreasuryQuery);
      $netExpences=($itemsDataTreasury['expences']-$itemsDataTreasury['incom']);
      echo "<td>".number_format(($netExpences), 2)."</td>";
     }
     $getItemDataTreasury2="SELECT sum(`income`) as incom,sum(`withdrawal`) as expences FROM `cash_transaction` 
      WHERE account = $code AND year(`transactionDate`) = $sYear ";
      $getItemDataTreasuryQuery2=mysqli_query($link,$getItemDataTreasury2);
      $itemsDataTreasury2=mysqli_fetch_assoc($getItemDataTreasuryQuery2);
      $netExpences2=($itemsDataTreasury2['expences']-$itemsDataTreasury2['incom']);
     echo"<td>".number_format(($netExpences2), 2)."</td></tr>";
    }
   ?>
  </tbody>
  <tfoot>
   <th>Total</th>
   <th>1</th>
   <th>2</th>
   <th>3</th>
   <th>4</th>
   <th>5</th>
   <th>6</th>
   <th>7</th>
   <th>8</th>
   <th>9</th>
   <th>10</th>
   <th>11</th>
   <th>12</th>
   <th>T</th>
  </tfoot>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();         
   
  var table = $('.myTableExpencess').DataTable( {
     
      fixedHeader: false,
             scrollY:'35vh',
             scrollX: true,
            scrollCollapse: true,
            paging: false, 
            order:[[13, "desc"]], 
         
 dom: 'Bfrtip',
       buttons: [
       
       {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
            title:'General Expencess_Analysis '+datetime,
            filename: function () {
            return "Expencess_Analysis" },
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4,5,6,7,8,9,10,11,12,13]
            },
            footer: false,
            
        },
        
        {
            extend: 'pdf',
            text: 'PDF',
            title:'General Expencess_Analysis '+datetime,
             filename: function () {
            return "Expencess_Analysis" },
            extension: '.pdf',
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13]
            },
            footer: false,
            
        },
        
    {
      extend: 'print',
      text: 'Print',
      className: 'btn btn-secondary',
      title:'General Expencess_Analysis '+datetime,
      footer: true,
       exportOptions: {
           
                   columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.1; filter: alpha(opacity=15); width: 350px; height:400px" />');
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( {'font-size' :'inherit',  'text-align': 'left'} );
  },
    }
 ],          
 
  "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };

        total = api
            .column( 1 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 1, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 1 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   

        total = api
            .column( 2 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 2, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 2 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
    
        total = api
            .column( 3 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 3, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 3 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
        
        total = api
            .column( 4 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 4, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 4 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   


        total = api
            .column( 5 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 5, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 5 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   


        total = api
            .column( 6 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 6, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 6 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   


        total = api
            .column( 7 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 7, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 7 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   



        total = api
            .column( 8 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 8, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 8 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   

        total = api
            .column( 9 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 9, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 9 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   

        total = api
            .column( 10 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 10, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 10 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   



        total = api
            .column( 11 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 11, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 11 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
         


         total = api
            .column( 12 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 12, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 12 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");      


             total = api
            .column( 13 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 13, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 13 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            

                                 
        }

   });




});
</script>