$(document).ready(function(){
    $("#formula").on("change", function(){
        if($(this).select2("data") && $(this).select2("data")[0].text == "Norma"){
            $.ajax({
                url: baseURL+'pcs/setting/get_data/norma',
                type: 'POST',
                dataType: 'JSON',
                success: function(data){
                    var norma   = "<label for='norma'>Norma</label>";
                    norma      += "<select class='form-control select2' name='norma' id='norma' required='required'>";
                    norma      += "<option value='0'>Silahkan pilih norma</option>"; 
                    $.each(data, function(i, v){
                        norma      += "<option value='"+v.id_mnorma+"'>"+v.norma+"</option>"; 
                    });
                    norma      += "</select>";

                    $("#container-norma").html(norma);

                    $("select[name='norma']").select2();
                }
            });
        }else{
            $("#container-norma").html("");
        }
    });

	$("#coa").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'pcs/master/get_master_COA',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                    // not_gruping : 'formcoa'
                };
            },
            processResults: function (data, page) {
                return {
                    results: data.items  
                };
            },
            cache: false
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
				                            if (repo.loading) return repo.text;
				                            var markup = '<div class="clearfix">'+ repo.FULL_GLTXT+'</div>';
				                            return markup;
				                        },
        templateSelection: function(repo) {
				                        	if(repo.FULL_GLTXT){
			                                    return repo.FULL_GLTXT;
			                                }else{
			                                    return repo.text;
			                                }
				                        }
    });

    $("#coa").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });

    $(document).on("change", "#formula, #norma", function(e){
        $("#coa").val(null).trigger('change');

        var formula     = $("#formula").val();
        var formula_txt = $("#formula").select2("data")[0].text;
        var norma       = $("#norma").val() != 0 ? $("#norma").val() : null;
        
        if((formula_txt !== "Norma" && !norma) || (formula_txt == "Norma" && norma)){
            $.ajax({
                url: baseURL+'pcs/setting/get_data/formcoa',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    formula : formula,
                    norma : norma
                },
                success: function(data){
                    $.each(data, function(i, v){
                        var no_coa  = v.no_COA_list.slice(0, -1).split(",");
                        var coa     = v.COA_list.slice(0, -1).split(",");
                        var array   = [];

                        $.each(coa, function(x, y){
                            var control = $('#coa').empty().data('select2');
                            var adapter = control.dataAdapter;
                            var nama = coa;
                            array.push({"id":no_coa[x],"text":y});

                            adapter.addOptions(adapter.convertToOptions(array));
                            $('#coa').trigger('change');
                        });
                        $('#coa').val(no_coa).trigger('change');

                    });
                }
            });
        }
    });

    $(document).on("click", "button[name='action_btn']", function(e){
        var empty_form = validate();
        if(empty_form == 0){
            var isproses        = $("input[name='isproses']").val();
            if(isproses == 0){
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-setting-formcoa")[0]);

                $.ajax({
                    url: baseURL+'pcs/setting/save/formcoa',
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
        e.preventDefault();
        return false;
    });
});