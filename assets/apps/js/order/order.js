$(document).ready(function(){
	
	$('#Carousel').carousel({
        interval: 5000
    })

	$("#btn-next").on("click", function(){
		$('#box-awal').addClass('hidden');
		$('#title-top').html('<strong>Katalog</strong>');			
		$('#box-katalog').removeClass('hidden');			
	});	

	$("#btn-prev2").on("click", function(){
		$('#box-akhir').addClass('hidden');
		$('#title-top').html('<strong>Katalog</strong>');			
		$('#box-katalog').removeClass('hidden');			
	});

	$("#btn-prev").on("click", function(){
		$('#box-katalog').addClass('hidden');
		$('#title-top').html('<strong>Form Order</strong>');			
		$('#box-awal').removeClass('hidden');			
	});		

	$("#btn-rfq").on("click", function(){
		$('#box-katalog').addClass('hidden');
		$('#title-top').html('<strong>Katalog</strong>');						
		$('#box-katalog-detail').addClass('hidden');			
		$('#box-akhir').removeClass('hidden');			
	});

	$("#btn-lsg").on("click", function(){
		$('#box-katalog').addClass('hidden');
		$('#title-top').html('<strong>Summary</strong>');						
		$('#box-akhir').removeClass('hidden');			
	});

	$("#btn-submit").on("click", function(){
		kiranaAlert("OK", "Data Berhasil Ditambahkan", "success", "yes");			
	});

	$("#items").on("click", function(){
		$('#box-katalog').addClass('hidden');
		$('#title-top').html('<strong>Katalog</strong>');						
		$('#box-katalog-detail').removeClass('hidden');			
	});

	//keperluan testing mockup==================================================================

	$("#btn-req1").on("click", function(){
		$('#product1').addClass('selecteds');
		$("#btn-req1").addClass('hidden');			
		$("#btn-cancel1").removeClass('hidden');			
	});

	$("#btn-cancel1").on("click", function(){
		$('#product1').removeClass('selecteds');
		$("#btn-req1").removeClass('hidden');			
		$("#btn-cancel1").addClass('hidden');			
	});

	$("#btn-req2").on("click", function(){
		$('#product2').addClass('selecteds');
		$("#btn-req2").addClass('hidden');			
		$("#btn-cancel2").removeClass('hidden');			
	});

	$("#btn-cancel2").on("click", function(){
		$('#product2').removeClass('selecteds');
		$("#btn-req2").removeClass('hidden');			
		$("#btn-cancel2").addClass('hidden');			
	});

	$("#btn-req3").on("click", function(){
		$('#product3').addClass('selecteds');
		$("#btn-req3").addClass('hidden');			
		$("#btn-cancel3").removeClass('hidden');			
	});

	$("#btn-cancel3").on("click", function(){
		$('#product3').removeClass('selecteds');
		$("#btn-req3").removeClass('hidden');			
		$("#btn-cancel3").addClass('hidden');			
	});

	$("#btn-req4").on("click", function(){
		$('#product4').addClass('selecteds');
		$("#btn-req4").addClass('hidden');			
		$("#btn-cancel4").removeClass('hidden');			
	});

	$("#btn-cancel4").on("click", function(){
		$('#product4').removeClass('selecteds');
		$("#btn-req4").removeClass('hidden');			
		$("#btn-cancel4").addClass('hidden');			
	});

	$("#btn-req5").on("click", function(){
		$('#product5').addClass('selecteds');
		$("#btn-req5").addClass('hidden');			
		$("#btn-cancel5").removeClass('hidden');			
	});

	$("#btn-cancel5").on("click", function(){
		$('#product5').removeClass('selecteds');
		$("#btn-req5").removeClass('hidden');			
		$("#btn-cancel5").addClass('hidden');			
	});

	$("#btn-req6").on("click", function(){
		$('#product6').addClass('selecteds');
		$("#btn-req6").addClass('hidden');			
		$("#btn-cancel6").removeClass('hidden');			
	});

	$("#btn-cancel6").on("click", function(){
		$('#product6').removeClass('selecteds');
		$("#btn-req6").removeClass('hidden');			
		$("#btn-cancel6").addClass('hidden');			
	});

	$("#btn-req7").on("click", function(){
		$('#product7').addClass('selecteds');
		$("#btn-req7").addClass('hidden');			
		$("#btn-cancel7").removeClass('hidden');			
	});

	$("#btn-cancel7").on("click", function(){
		$('#product7').removeClass('selecteds');
		$("#btn-req7").removeClass('hidden');			
		$("#btn-cancel7").addClass('hidden');			
	});

	$("#btn-req8").on("click", function(){
		$('#product8').addClass('selecteds');
		$("#btn-req8").addClass('hidden');			
		$("#btn-cancel8").removeClass('hidden');			
	});

	$("#btn-cancel8").on("click", function(){
		$('#product8').removeClass('selecteds');
		$("#btn-req8").removeClass('hidden');			
		$("#btn-cancel8").addClass('hidden');			
	});


	$("#btn-req-modal").on("click", function(){
		$('#modal1').addClass('selecteds');
		$("#btn-req-modal").addClass('hidden');			
		$("#btn-cancel-modal").removeClass('hidden');			
	});

	$("#btn-cancel-modal").on("click", function(){
		$('#modal1').removeClass('selecteds');
		$("#btn-req-modal").removeClass('hidden');			
		$("#btn-cancel-modal").addClass('hidden');			
	});

	$("#btn-req-modal2").on("click", function(){
		$('#modal2').addClass('selecteds');
		$("#btn-req-modal2").addClass('hidden');			
		$("#btn-cancel-modal2").removeClass('hidden');			
	});

	$("#btn-cancel-modal2").on("click", function(){
		$('#modal2').removeClass('selecteds');
		$("#btn-req-modal2").removeClass('hidden');			
		$("#btn-cancel-modal2").addClass('hidden');			
	});

	$("#btn-comp-modal").on("click", function(){
		$("#comp1").removeClass('hidden');			
	});

	$("#btn-detail").on("click", function(){
		$('#view_modal').modal('show');
	});

	$("#btn-detail2").on("click", function(){
		$('#view_modal').modal('show');
	});

	$("#btn-detail3").on("click", function(){
		$('#view_modal').modal('show');
	});

	$("#btn-detail4").on("click", function(){
		$('#view_modal').modal('show');
	});

	$("#btn-detail5").on("click", function(){
		$('#view_modal2').modal('show');
	});

	$("#btn-detail6").on("click", function(){
		$('#view_modal2').modal('show');
	});

	$("#btn-detail7").on("click", function(){
		$('#view_modal2').modal('show');
	});

	$("#btn-detail8").on("click", function(){
		$('#view_modal2').modal('show');
	});

	//==========================================================================================

})