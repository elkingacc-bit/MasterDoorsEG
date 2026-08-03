	<style>
.img-itemsStock {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 500px;
  
}

h1 {font-size:14px; font-weight:bold;
}
.dataTables_wrapper .dt-buttons {
  float:right;  
  text-align:right;
  padding-left:3%;
  }

@media print {
  .tooltip { visibility: hidden; }
}

.table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
   background-color: #D6D5D5; 
}
.btn-link {
  padding-left: 0
}
</style>
<div class="table-responsive">
<table  class="myTableCStock table table-sm table-striped table-bordered" cellspacing="0" width="100%" style="font-weight:bold">
					<thead class="bg-warning">
						<tr>
							<th></th>
                            <th>Part No</th>
                            <th>Name</th>
                            <th>manufactur</th>
                            <th>Supplier</th>
                            <th data-toggle='tooltip' data-placement='right' title='warehouse Stock'>Stock</th>
                            <th>Cost</th>
                            <th>Price</th>
                            <th>T-Cost</th>
                            <th></th>
                            <th></th>
						</tr>
					</thead>
                    <tfoot>
						<tr>
							<th ></th>
                            <th ></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th ></th>
                            <th ></th>
                            <th style="color:red" ></th>
                            <th></th>
                            <th></th>
						</tr>
					</tfoot>
				</table>
  </div>              
<script type="text/javascript" language="javascript" class="init">
	
$(document).ready(function () {

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});
	
var currentdate = new Date(); 
    var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
//dateTime for Print					
	
	var myTable = $('.myTableCStock').DataTable({
		processing: true,
		serverSide: true,
		fixedHeader:false,
	    scrollX: true,
        scrollCollapse: true,
        paging: false,
		ajax: 'dist/php/CachWHSSPAdmin.php',
		searchDelay: 500,
		//deferLoading: 0,
		
		 columnDefs: [
            {
                targets: 10,
                data: null,
                defaultContent: '<button class="btn btn-link btn-xs DescripInfo" style="font-size:16px" data-toggle="tooltip" data-placement="left" title="Item Full Info"><i class="fa fa-search-plus" ></i></button>'
            },
			 
           {
			bSearchable: false,
			bVisible: false,
			aTargets: [0,9]
			},
		{
			"targets": 7,
			"createdCell": function (td, cellData, rowData, row, col) {
			  
				$(td).css('color', 'blue')
			}
		  },
		  
		 {
			"targets": 8,
			"createdCell": function (td, cellData, rowData, row, col) {
			  
				$(td).css('color', 'red')
			}
		  }, 
		  
		  {
			"targets": 6,
			"createdCell": function (td, cellData, rowData, row, col) {
			  
				$(td).css('color', 'red')
			}
		  }, 	
		
		{
			"targets": 1,
			
        	"render": function ( td, cellData, rowData, row, col) {
				
				return '<button class="btn btn-link float-left itemImage" >'+rowData[1]+'</button>';
			
			},
		},
		   
        ],
		
		 fixedHeader: false,
             scrollY:'35vh',
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[5, "desc"]], 
		 
			"aaSorting": [[ 5, "desc" ]],
			
			  dom: 'Bfrtip',
       buttons: [
	   
	   {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
			title:'WearHouse_Stock_Info '+datetime,
			filename: function () {
			return "WearHouse_Stock_Info" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [1,2,3,4,5]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'WearHouse_Stock_Info'+datetime,
			 filename: function () {
			return "WearHouse_Stock_Info" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [1,2,3,4,5]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Company Managment System {Master Doors EG} | WearHouse Stock Info '+datetime,
	  footer: false,
	   exportOptions: {
		   
                   columns: [1,2,3,4,5],
				   
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.2; filter: alpha(opacity=15); width: 450px; height:200px" />');
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( {'font-size' :'inherit',  'text-align': 'left'} );
  },
	}
 ],	
 
 	    "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 8 ).footer() ).html(
                Number((pageTotal).toFixed(1)).toLocaleString());
				
			$("#totalcostWH").val(Number(pageTotal).toFixed(0));
        }
		 
		
	});

	setInterval(function() {
		myTable.ajax.reload();
	}, 20000 );
	
 $('.myTableCStock tbody').on('click', '.DescripInfo', function (e) {
        var dataInfo = myTable.row( $(this).parents('tr') ).data();
		 $.ajax({
                url:'dist/php/fullItemInfo.php',
                type:'POST',
                data:{Descripid:dataInfo[0]},
                //dataType:'html',
				success: function(showMoreDscData)
				{
				
                $('.ShowStockData').html('');
                $('.ShowStockData').html(showMoreDscData);
				$(".myModal").modal('toggle');
				}          
        		});
		
     } );
	 
 $('.myTableCStock tbody').on('click', '.itemImage', function (e) {
        var dataEdit = myTable.row( $(this).parents('tr') ).data();
				
            $.ajax({
                url:'dist/php/showStockItemsImage.php',
                type:'POST',
                data:{imagePath:dataEdit[9], itemCode:dataEdit[0]},
                
				success: function(showImage)
				{
				
                $('.ShowStockData').html('');
                $('.ShowStockData').html(showImage);
				$(".myModal").modal('toggle');
				}          
        		});
	});
	



});//doc.ready

</script>


