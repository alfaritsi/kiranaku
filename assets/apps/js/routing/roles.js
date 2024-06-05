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

$(document).ready(function () {

    $.fn.dataTable.ext.order['dom-checkbox'] = function  ( settings, col )
    {
        return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
            return $('input', td).prop('checked') ? '1' : '0';
        } );
    };

    $(".delete").on("click", function (e) {
        var id = $(this).data("delete");
        $.ajax({
            url: baseURL + 'routing/setting/roles/delete',
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
                    alert(data.msg);
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
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    });

    $(".edit").on("click", function (e) {

        $(".cb").prop('checked', false);
        $(".cb").trigger('change');
        var id_role = $(this).data("edit");
        $.ajax({
            url: baseURL + 'routing/setting/get_data/role',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_role: id_role
            },
            success: function (data) {
                $(".title-form").html("Edit Role");
                $.each(data, function (i, v) {

                    checkedTopics = {
                        "topic": [],
                        "jabatan": [],
                        "divisi": [],
                        "departemen": [],
                        "menu": []
                    };

                    $("#nama_role").val(v.nama_role);
                    $("#topics").val(v.topics);
                    $("#menus").val(v.menus);
                    $("#jabatans").val(v.jabatans);
                    $("#divisis").val(v.divisis);
                    $("#departemens").val(v.departemens);

                    let topics = v.topics !== null ? v.topics.split(',') : [];
                    topics.map(function (value, index, array) {
                        $(".cbTopic[value=" + value + "]")
                            .prop('checked', true);
                        $(".cbTopic[value=" + value + "]")
                            .trigger('change');
                    });

                    let menus = v.menus !== null ? v.menus.split(',') : [];
                    menus.map(function (value, index, array) {
                        $(".cbMenu[value=" + value + "]").prop('checked', true);
                        $(".cbMenu[value=" + value + "]").trigger('change');
                    });

                    let jabatans = v.jabatans !== null ? v.jabatans.split(',') : [];
                    jabatans.map(function (value, index, array) {
                        $(".cbJabatan[value=" + value + "]").prop('checked', true);
                        $(".cbJabatan[value=" + value + "]").trigger('change');
                    });

                    let divisis = v.divisis !== null ? v.divisis.split(',') : [];
                    divisis.map(function (value, index, array) {
                        $(".cbDivisi[value=" + value + "]").prop('checked', true);
                        $(".cbDivisi[value=" + value + "]").trigger('change');
                    });

                    let departemens = v.departemens !== null ? v.departemens.split(',') : [];
                    departemens.map(function (value, index, array) {
                        $(".cbDepartemen[value=" + value + "]").prop('checked', true);
                        $(".cbDepartemen[value=" + value + "]").trigger('change');
                    });

                    $("input[name='hak_export_data'][value=" + v.hak_export_data + "]")
                        .prop('checked', true);
                    $("input[name='hak_data_keuangan'][value=" + v.hak_data_keuangan + "]")
                        .prop('checked', true);
                    $("input[name='hak_general_management'][value=" + v.hak_general_management + "]")
                        .prop('checked', true);

                    $("input[name='id_role']").val(v.id_role);
                    $("#btn-new").removeClass("hidden");
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

    let checkedTopics = {
        "topic": [],
        "jabatan": [],
        "divisi": [],
        "departemen": [],
        "menu": []
    };
    let initChangeEventCheckbox = (cbClass, valInput, btnModal, singular) => {

        $(cbClass).change(function () {
            let checked = checkedTopics[singular];

            if ($(this).is(":checked"))
            {
                checked.push($(this).val());
            }
            else
            {
                checked = checked.filter((value, index, self) => {
                    return self.indexOf($(this).val(), index) !== index;
                });
            }

            checked = Array.from(new Set(checked));

            $(valInput).val(checked.join(','));

            if (checked.length > 0)
                $(btnModal).html("<b>" + checked.length + " " + singular + " dipilih</b> Show");
            else
                $(btnModal).html("Show");

            checkedTopics[singular] = checked;
        });
    };

    initChangeEventCheckbox(".cbTopic", "#topics", "#btnModalTopics", "topic");
    initChangeEventCheckbox(".cbMenu", "#menus", "#btnModalMenus", 'menu');
    initChangeEventCheckbox(".cbJabatan", "#jabatans", "#btnModalJabatans", 'jabatan');
    initChangeEventCheckbox(".cbDivisi", "#divisis", "#btnModalDivisis", 'divisi');
    initChangeEventCheckbox(".cbDepartemen", "#departemens", "#btnModalDepartemens", 'departemen');

    $("#modalTopic").on('shown.bs.modal', function () {
        $('#tableTopics').dataTable({
            'destroy': true,
            "order": [[0, "desc"],[1, "desc"]],
            "columnDefs": [
                {"orderable": true, "targets": 0,"orderDataType": "dom-checkbox"}
            ]
        });
    });

    $("#modalMenu").on('shown.bs.modal', function () {
        $('#tableMenus').dataTable({
            'destroy': true,
            "order": [[0, "desc"]],
            "columnDefs": [
                {"orderable": false, "targets": 0,"orderDataType": "dom-checkbox"}
            ]
        });
    });

    $("#modalJabatan").on('shown.bs.modal', function () {
        $('#tableJabatans').dataTable({
            'destroy': true,
            "order": [[0, "desc"],[1, "desc"]],
            "columnDefs": [
                {"orderable": false, "targets": 0,"orderDataType": "dom-checkbox"}
            ]
        });
    });

    $("#modalDivisi").on('shown.bs.modal', function () {
        $('#tableDivisis').dataTable({
            'destroy': true,
            "order": [[1, "desc"]],
            "columnDefs": [
                {"orderable": false, "targets": 0,"orderDataType": "dom-checkbox"}
            ]
        });
    });

    $("#modalDepartemen").on('shown.bs.modal', function () {
        $('#tableDepartemens').dataTable({
            'destroy': true,
            "order": [[1, "desc"]],
            "columnDefs": [
                {"orderable": false, "targets": 0,"orderDataType": "dom-checkbox"}
            ]
        });
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate('form',true);
        if (empty_form == 0) {

            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-report")[0]);

                $.ajax({
                    url: baseURL + 'routing/setting/save/role',
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
                            swal('Success',data.msg,'success').then(function(){
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