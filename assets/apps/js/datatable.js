/*
@application    : KIRANAKU
@author         : Akhmad Syaiful Yamang (8347)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function(){
    $.fn.dataTable.moment( 'DD.MM.YYYY' );
    $.fn.dataTable.moment( 'DD.MM.YYYY HH:mm' );
    $.fn.dataTable.moment( 'DD.MM.YYYY HH:mm:ss' );
    $.fn.dataTable.moment( 'HH:mm' );
	$(".my-datatable").DataTable({
    	ordering : false
    });

    $(".my-datatable-order").DataTable();

    $(".my-datatable-extends").DataTable({
        ordering : false,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends",this).data("page") ? $(".my-datatable-extends",this).data("page") : 10,
		paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true
    });

    $(".my-datatable-extends-order").DataTable({
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
		paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true
    });

    $(".my-datatable-extends-order-no-collapse").DataTable({
    	ordering : true,
        scrollX : true
    });

    $(".my-datatable-order-col2").DataTable({
    	order: [[1, 'asc']],
        scrollX : true
    });
});
