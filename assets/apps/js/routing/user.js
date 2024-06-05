/*
@application  : Email Roles Javascript
@author       : Octe Reviyanto Nugroho
@contributor    :
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

let checkedTopics = {
    "company": [],
    "buyer": []
};

$(document).ready(function () {
    $(".delete").on("click", function (e) {
        var id = $(this).data("delete");
        $.ajax({
            url: baseURL + 'routing/setting/delete_role',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    swal('Success',data.msg,'success').then(function(){
                        location.reload();
                    });
                } else {
                    swal('Error',data.msg,'error');
                }
            }
        });
    });

    $(".set_active-report").on("click", function (e) {
        var id_report = $(this).data("activate");
        $.ajax({
            url: baseURL + 'email/activate_report',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_report: id_report
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    swal('Success',data.msg,'success').then(function(){
                        location.reload();
                    });
                } else {
                    swal('Error',data.msg,'error');
                }
            }
        });
    });

    /*userrole*/
    $(".select2-user-search").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'routing/master/get_data/user',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
            return markup;
        },
        templateSelection: function (repo) {
            if (repo.posst) $("input[name='caption']").val(repo.posst);
            if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
            else return repo.nama;
        }
    });

    $(".edit").on("click", function (e) {
        var id_user = $(this).data("edit");
        $.ajax({
            url: baseURL + 'routing/setting/get_data/user',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_user: id_user
            },
            success: function (data) {
                // console.log(data);
                $(".title-form").html("Edit User");
                $.each(data, function (i, v) {
                    checkedTopics = {
                        "company": [],
                        "buyer": []
                    };
                    $(".cb").each((i,el) => {
                        $(el).prop('checked', false);
                        $(el).trigger('change');
                    });
                    $("#nama").val(v.nama);
                    $("#business_unit").val(v.business_unit);
                    $("#nik").val(v.nik);
                    $("#jabatan").val(v.jabatan);
                    $("#email").val(v.email);
                    $("#status").val(v.status);
                    $("#tipe_karyawan").val(v.tipe_karyawan);

                    let companies = v.companies !== null && v.companies != "" ? v.companies.split(',') : [];
                    companies.map(function (value, index, array) {
                        $(".cbCompany[value=" + value + "]")
                            .prop('checked', true)
                            .trigger('change');
                    });

                    if(v.companies === null || companies.length == 0)
                    {
                        $('.companiesSaved').removeClass('hide');

                        if(v.business_unit == "HO")
                        {
                            $('.cbCompany')
                                .prop('checked',true)
                                .trigger('change');

                        }else{
                            $('.cbCompany[data-plant="'+v.business_unit+'"]')
                                .prop('checked',true)
                                .trigger('change');
                        }
                    }

                    let buyers = v.buyers !== null && v.buyers != "" ? v.buyers.split(',') : [];
                    buyers.map(function (value, index, array) {
                        $(".cbBuyer[value=" + value + "]")
                            .prop('checked', true)
                            .trigger('change');
                    });

                    if(v.buyers === null || buyers.length == 0)
                    {
                        $('.buyersSaved').removeClass('hide');

                        $('.cbBuyer')
                            .prop('checked',true)
                            .trigger('change');
                    }

                    $("input[name='id_user']").val(v.id_user);
                    // $("#btn-new").removeClass("hidden");
                    $(".box-footer").removeClass("hide");
                });
            }
        });
    });

    $("#btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
    });

    function resetCheckbox(tableEl) {
        tableEl.DataTable().destroy();
        $("input[type='checkbox']", tableEl).prop("checked", false);
        $("input[type='checkbox']", tableEl).removeAttr("checked");
    }

    const initChangeEventCheckbox = (cbClass, valInput, btnModal, singular) => {
        $(cbClass).change(function () {
            // let checked = checkedTopics[singular];
            let checked = [];
            if($(valInput).val()!="")
                checked = $(valInput).val().split(',');

            if ($(this).is(":checked"))
                checked.push($(this).val());
            else
                checked = checked.filter((value, index, self) => {
                    return self.indexOf($(this).val(), index) !== index;
                });

            checked = Array.from(new Set(checked));

            $(valInput).val(checked.join(','));
            if (checked.length > 0)
                $(btnModal).html("<b>" + checked.length + " " + singular + " dipilih</b> Show");
            else
                $(btnModal).html("Show");

            checkedTopics[singular] = checked;
        });
    };

    initChangeEventCheckbox(".cbCompany", "#companies", "#btnModalCompanies", "company");
    initChangeEventCheckbox(".cbBuyer", "#buyers", "#btnModalBuyers", 'buyer');

    $("#modalCompany").on('shown.bs.modal', function () {
        $('#tableCompanies').dataTable({
            'destroy': true,
            "order": [[1, "desc"]],
            "columnDefs": [
                {"orderable": false, "targets": 0}
            ]
        });
    });

    $("#modalBuyer").on('shown.bs.modal', function () {
        $('#tableBuyers').dataTable({
            'destroy': true,
            "order": [[1, "asc"]],
            "columnDefs": [
                {"orderable": false, "targets": 0}
            ]
        });
    });

    $('#plantFilter').select2({
        allowClear:true
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate();
        if (empty_form == 0) {

            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-user")[0]);

                $.ajax({
                    url: baseURL + 'routing/setting/save/user',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success',data.msg,'success').then(function(){
                                location.reload();
                            });
                        } else {
                            swal('Error',data.msg,'error').then(function(){
                                $("input[name='isproses']").val(0);
                            });
                        }
                    }
                });
            } else {

                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });
});