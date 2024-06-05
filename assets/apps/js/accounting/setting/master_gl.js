$(document).ready(function () {

    $('.tbl-gl').DataTable({
		ordering: true,
		columnDefs: [
			{"className": "text-left", "targets": 0},
			{"type": 'date-eu',"className": "text-center", "targets": 1},
			{"className": "text-center", "targets": 2},
			{"className": "text-center", "targets": 3}
		],
		order: [1, 'desc'],
	});

    var theSelect2Elements = null;
    $(".select-gl").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'accounting/setting/get_data/gl',
            dataType: 'json',
            delay: 750,
            data: function(params) {
                return {
                    autocomplete: true,
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
        
            if (repo.loading) return repo.text;

            let markup = '<div class="clearfix">' + repo.SAKNR + ' - [' + repo.TXT50 + ']</div>';
            
            return markup;
        },
        templateSelection: function(repo) {
            

            let markup = "Pilih G/L Account";
            console.log("masuk sini");
            if (repo) {
                
                if(repo.id){
                    
                    $("input[name='deskripsi']").val(repo.TXT50);                    
                    markup = repo.SAKNR + ' - [' + repo.TXT50 + ']';

                }
                
            }

            return markup;
        }
    }).on('select2:open', function(e){ 
        theSelect2Elements = e.currentTarget; 
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-gl")[0]);

				$.ajax({
					url: baseURL + 'accounting/setting/save/gl',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						console.log(data);
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					complete: function () {
						$("input[name='isproses']").val(0);
					}
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
	});

	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "accounting/setting/set_data/master/gl",
			type: 'POST',
			dataType: 'JSON',
			data: {
				gl_account : $(this).data($(this).attr("class")),
				type : $(this).attr("class")
			},
			success: function(data){
				if(data.sts == 'OK'){
					kiranaAlert(data.sts, data.msg);
				}else{
					kiranaAlert("notOK", data.msg, "warning", "no");
				}
			}
		});
		e.preventDefault();
		return false;
	});

});