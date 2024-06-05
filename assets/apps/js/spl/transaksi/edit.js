$(document).ready(function () {
    $(document).on("click", ".btn-approve-karyawan", function (e) {
        const action = this.dataset.action;
        const color = (action == "confirm" ? "bg-confirm" : "bg-reject"); 
        $(this).closest("tr.row-karyawan")
            .removeClass("bg-confirm bg-reject")
            .addClass(color);
    });

    $(document).on("click", ".btn-edit", function (e) {
        let element = $(this).closest("tr.row-karyawan");
        element.removeClass("bg-confirm bg-reject");
        // element.find("select[name='nik[]']").prop("disabled", false)
        element.find(".input-edit").prop("disabled", false).prop("readonly", false);
    });
});