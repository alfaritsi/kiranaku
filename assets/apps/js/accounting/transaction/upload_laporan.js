
$(document).ready(function(){

    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });


	$(".upload").on("click", function(e){
    	var id	= $(this).data("upload");
    	$(".modal-title").html("Form Upload Document");

    	$.ajax({
    		url: baseURL+'accounting/transaction/get_data/upload_jurnal',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					$("#id").val(v.id);
					$("#action").val("upload");
					$("#doc_no").html("");
					$("#text").html("");
					$("#tipe").html("");
					$("#fileexist").html("");
					$("#infodiv").html("");
					$("#uploaddiv").html("");

					$("#doc_no").append(v.no_doc);					
					$("#text").append(v.text);
					$("#tipe").append(v.tipe);
					$("#uploaddiv").append("<div class='clearfix'></div>"
					                	+"<div class='form-group' style='margin-bottom: 5px;'>"
										+"<label class='col-md-4'>Upload Files</label>"
										+"<div class='col-md-8'>"
										+"<input type='file' name='file[]'' id = 'file' multiple required accept='.pdf'>"
										+"</div>"
					                	+"</div>");

					// $("#jumlahfile").append(v.id);
					if(v.data != "" && v.data2 != null){
	              		var str = v.data2;
	              		var file = str.split("|");
						$.each(file, function(i2, v2){
							if(v2 != ""){
								$("#fileexist").append("<a href='#' target='_blank' style='color:green;'><i class='fa fa-file-pdf-o'></i> "+ v2+ " </a><br/>");
							}
						});

					}else{
						$("#fileexist").append("<p class='form-control-static'> No file exist</p>");
					}
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(".request").on("click", function(e){
    	var id	= $(this).data("request");
    	$(".modal-title").html("Form Pengajuan Re-upload");

    	$.ajax({
    		url: baseURL+'accounting/transaction/get_data/upload_jurnal',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					$("#id").val(v.id);
					$("#action").val("request");
					$("#doc_no").html("");
					$("#text").html("");
					$("#tipe").html("");
					$("#fileexist").html("");
					$("#infodiv").html("");
					$("#uploaddiv").html("");

					$("#doc_no").append(v.no_doc);					
					$("#text").append(v.text);
					$("#tipe").append(v.tipe);
					$("#infodiv").append("<div class='clearfix'></div>"
					                	+"<div class='form-group' style='margin-bottom: 5px;'>"
										+"<label class='col-md-4'>Keterangan</label>"
										+"<div class='col-md-8'>"
										+"<textarea name='info' id='info' required class='form-control' rows='2'></textarea>"
										+"</div>"
					                	+"</div>");


					if(v.data != "" && v.data2 != null){
	              		var str = v.data2;
	              		var file = str.split("|");
						$.each(file, function(i2, v2){
							if(v2 != ""){
								$("#fileexist").append("<a href='#' target='_blank' style='color:green;'><i class='fa fa-file-pdf-o'></i> "+ v2+ " </a><br/>");
							}
						});

					}else{
						$("#fileexist").append("<p class='form-control-static'> No file exist</p>");
					}
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='check_btn']", function(e){
		var chk_arr =  document.getElementsByName("checkjurnal[]");
		var chklength = chk_arr.length;             
		var checked = false;

		for(k=0;k< chklength;k++){
			if (chk_arr[k].checked === true && chk_arr[k].disabled === false) {
				checked = true;
			}
		}         
		if(checked === false){
			kiranaAlert("notOK", "Tidak ada data yang diselect", "warning", "no");
			return false;
		}
		e.preventDefault();
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menchecklist data?",
                dangerMode: true,
                successCallback: function () {
				
					var empty_form = validate(".form-check");

			        if(empty_form == 0){
				    	var isproses 		= $("input[name='isproses']").val();
				    	if(isproses == 0){
				    		$("input[name='isproses']").val(1);

					    	var formData = new FormData($(".form-check")[0]);

							$.ajax({
								url: baseURL+'accounting/transaction/set_data/update/check_upload_laporan',
								type: 'POST',
								dataType: 'JSON',
								data: formData,
								contentType: false,
								cache: false,
								processData: false,
								success: function(data){
									if(data.sts == 'OK'){
					                    kiranaAlert(data.sts, data.msg);
									}else{
					                    kiranaAlert(data.sts, data.msg, "error", "no");
					                    $("input[name='isproses']").val(0);
									}
								}
							});

						}else{
			                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
						}
	                }
                }
            }
        );
    });

	$("#filtersource").change(function() {
	  	var source = $(this).val();
	  	var x = document.getElementById("div_jenis");
	  	if(source == "Vk9Cci9zRFFoSmd1VkFib1M1a2VhQT09"){
			x.style.display = "block";
			$("#filterjenis").attr('required', '');
		}else{
			x.style.display = "none";
			$("#filterjenis").removeAttr('required', '');
		}
    });

	$("#filtersearch").change(function() {
	  	var search = $(this).val();

		$('#filterparam').val("");
	  	if(search == "in_date"){
		    $('#filterparam').datepicker({
		    	format: 'dd.mm.yyyy',
		        changeMonth: true,
		        changeYear: true,
		        autoclose: true
		        // startDate: new Date(date)
		    });
		}else{
			$('#filterparam').datepicker("remove");
		}
		$('#filterparam').focus();
    });


    $('.datePicker').datepicker({
    	format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
        // startDate: new Date(date)
    });

	$(".checkjurnal").change(function() {
      	var checkbox = this.checked;
      	var check = this.value;
		var chk_arr =  document.getElementsByName("checkjurnal[]");
		var chklength = chk_arr.length;             

		var check = check.split("|");

		if (checkbox == true){
	        if(check[1] == "" || check[1] == "-"){ 
	        	kiranaAlert("notOK", "Dokumen tidak dapat dicheck, belum ada attachment", "warning", "no");
	          	this.checked = false;
	        }         
	    }
    });


});


function filtersubmit(){
    
    $('#filterform').submit();
}

$('#example-advanced').treetable({ expandable: true });
// $('#example-advanced').treetable("expandAll");
// Highlight selected row
$('#example-advanced tbody').on('mousedown', 'tr', function() {
	$('.selected').not(this).removeClass('selected');
	$(this).toggleClass('selected');
});
$(document).on("click",".action_btn",function(){
	var status_tree = $(".status_tree").val();
	if(status_tree == "collapsed"){
		$(".status_tree").val("expanded");
		$('#example-advanced').treetable("expandAll");
		$(".action_btn").html("Collapse All");
	}else{
		$(".status_tree").val("collapsed");
		$('#example-advanced').treetable("collapseAll");
		$(".action_btn").html("Expand All");
	}
});

$("#ok_submit").click(function() {
  var from = $("#from").val();
  var plant = $("#plant").val();

  if(from === null || plant === null){
  	alert("Mohon untuk mengisi parameter dengan lengkap dan benar");
  	return false;
  }
});


var source = $("#filtersource").val();
var x = document.getElementById("div_jenis");
if(source == "Vk9Cci9zRFFoSmd1VkFib1M1a2VhQT09"){
	x.style.display = "block";
	$("#filterjenis").attr('required', '');
}else{
	x.style.display = "none";
	$("#filterjenis").removeAttr('required', '');
}
