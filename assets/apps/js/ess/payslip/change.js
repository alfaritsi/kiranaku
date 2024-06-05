$(document).ready(function () {
    $(document).on("focus blur", ".pass-with-button input[type='password']", function (e) {
        if (e.type == 'focusin') {
            $(this).css("border-color", "#3c8dbc");
            $(this).css("border-right", "0");
            $(this).closest(".pass-with-button").find(".input-group-btn button").css("border-color", "#3c8dbc");
        }
        else {
            $(this).css("border-color", "");
            $(this).css("border-right", "");
            $(this).closest(".pass-with-button").find(".input-group-btn button").css("border-color", "");
        }

    });

    $(document).on("click", ".pass-btn", function () {
        let type = $(this).closest(".pass-with-button").find("input:eq(0)").attr("type");
        if (type == 'password') {
            $(this).attr("title", "Hide");
            $(this).find("i").removeClass("fa-eye");
            $(this).find("i").addClass("fa-eye-slash");
            $(this).closest(".pass-with-button").find("input[type='password']").attr("type", "text");
        }
        else {
            $(this).attr("title", "Show");
            $(this).find("i").removeClass("fa-eye-slash");
            $(this).find("i").addClass("fa-eye");
            $(this).closest(".pass-with-button").find("input[type='text']").attr("type", "password");
        }
    });

    $(document).on("click", "button[name='action_btn']", function () {
        $.validator.addMethod("customPassword", function (value, element) {
            let regex = new RegExp("(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[A-Za-z0-9]{6,10}$", "g");
            return this.optional(element) || regex.test(value);
        }, 'Password harus terdiri dari 6-10 karakter, dengan ketentuan harus berisi huruf besar, huruf kecil, dan angka');

        var empty_form = validate(
            "#form-payslip-password",
            true,
            {
                rules: {
                    new_pass: {
                        required: true,
                        customPassword: true
                    },
                    new_pass_conf: {
                        required: true,
                        equalTo: '#new_pass',

                    }
                },
                messages: {
                    new_pass_conf: "Password tidak sesuai",
                }
            }
        );
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                var formData = new FormData($("#form-payslip-password")[0]);
                $.ajax({
                    url: baseURL + "ess/payslip/save/password",
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                        $("body .overlay-wrapper").append(overlay);
                    },
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg, "success", "yes");
                        } else {
                            kiranaAlert(data.sts, data.msg, "error", "no");
                            $("input[name='isproses']").val(0);
                        }
                    },
                    error: function () {
                        kiranaAlert("notOK", "Server Error", "error", "no");
                    },
                    complete: function () {
                        $("body .overlay-wrapper .overlay").remove();
                    }
                });
            } else {
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
            }
        }
    });
});