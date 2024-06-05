/*
@application    : K-AIR
@author         : Akhmad Syaiful Yamang (8347)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function () {
    $(document).ajaxError(function () {
        KIRANAKU.hideLoading();
        $("input[name='isproses']").val(0);
    });

    $(document).ajaxStart(function () {
        Pace.restart();
        KIRANAKU.showLoading();
    });

    $(document).ajaxStop(function () {
        KIRANAKU.hideLoading();
        $("input[name='isproses']").val(0);
    });

    $('input').keydown(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    $(document).on("keyup", ".angka", function (e) {
        var angka = $(this).val().replace(/[^0-9.,^-]*/g, '');
        if ($(this).attr("min")) {
            if ($(this).attr("min") >= 0)
                angka = $(this).val().replace(/[^0-9.,]*/g, '');
            // angka = ($(this).val() < parseFloat($(this).attr("min")) ? $(this).attr("min") : $(this).val());
            angka = (angka < parseFloat($(this).attr("min")) ? $(this).attr("min") : angka);
        }
        // var angka = $(this).val().replace(/[^0-9.,]*/g, ''); //tanpa minus
        $(this).val(angka);
        e.preventDefault();
        return false;
    });

    $(document).on("change", ".angka", function (e) {
        var angka = $(this).val().replace(/,/g, "");
        angka = numberWithCommas(angka);
        $(this).val(angka);
        e.preventDefault();
        return false;
    });

    $(document).on("click", ".logout", function (e) {
        $.ajax({
            url: baseURL + 'home/logout',
            type: 'POST',
            dataType: 'JSON',
            data: {
                signout: "yes"
            },
            success: function (data) {
                if (data.msg == "OK") {
                    localStorage.clear();
                    location.href = baseURL;
                }
            }
        });
        e.preventDefault();
        return false;
    });

    $(document).on("click", ".btn-reset-form", function (e) {
        var element = $(this).data("form");
        $(element)[0].reset();
    });

    $("input").attr("autocomplete", "off");

    if ($(".select2").length > 0) {
        $(".select2").each(function () {
            $(this).select2({
                allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
                placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
            });
        });
    }
    if ($(".select2modal").length > 0) {
        $(".modal").each(function () {
            var elemModal = $(this).attr("id");
            $("#" + elemModal + " .select2modal").select2({
                dropdownParent: $("#" + elemModal + " .modal-content"),
                allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
                placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
            });
        });
    }

    //=================sidebar==================//
    if ($("ul.sidebar-menu").length > 0) {
        $.ajax({
            url: baseURL + 'home/get_menu',
            type: 'POST',
            dataType: 'JSON',
            data: {
                parent: 0
            },
            beforeSend: function () {
                var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                $("body .overlay-wrapper").append(overlay);
            },
            success: function (data) {
                // console.log(JSON.stringify(data));
                var output = "";
                if (data) {
                    if (data.view && data.view !== "") {
                        output += data.view;
                    } else {
                        $("body .content").html("");
                        kiranaConfirm({
                            title: "Kiranaku",
                            text: "Anda belum memiliki akses menu, silahkan login ulang atau hubungi Tim IT",
                            dangerMode: true,
                            useButton: null,
                            showConfirmButton: true,
                            showCancelButton: false,
                            confirmButtonText: "OK",
                            successCallback: function () {
                                $(".logout").click();
                            },
                        });
                    }
                }

                // console.log(output);
                $("ul.sidebar-menu").html(output);
            },
            complete: function () {
                $("body .overlay-wrapper .overlay").remove();

                setTimeout(function () {
                    adjustDatatableWidth();
                    if ($("#calendar").length > 0)
                        $("#calendar").fullCalendar("render");
                    adjustNotification();
                    getNotificationData();
                }, 1500);

                /** add active class and stay opened when selected */
                var url = window.location;

                // for sidebar menu entirely but not cover treeview
                $('ul.sidebar-menu a').filter(function () {
                    return this.href == url;
                }).parent().addClass('active');

                // for treeview
                $('ul.treeview-menu a').filter(function () {
                    return this.href == url;
                }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
            }
        });

        $(document).on("keyup", ".sidebar-form input[type='text']", function () {
            var searchText = $(this).val().toLowerCase();

            $('ul.sidebar-menu li').each(function () {
                var currentLiText = $(this).text().toLowerCase(),
                    showCurrentLi = currentLiText.indexOf(searchText) !== -1;

                if (showCurrentLi) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    } else {
        if (localStorage.getItem("username_kirana")) {
            $("input[name='username']").val(localStorage.getItem("username_kirana"));
            $("input[name='remember']").prop("checked", true);
        }
    }

    var collapse = localStorage.getItem("collapse");
    if (collapse == "true" || collapse == null) {
        $("body").addClass("sidebar-collapse");
    } else {
        $("body").removeClass("sidebar-collapse");
    }

    $(".sidebar-toggle").on("click", function () {
        var collapse = $('body').hasClass("sidebar-collapse");
        if (collapse === true) {
            localStorage.setItem("collapse", "false");
        } else {
            localStorage.setItem("collapse", "true");
        }

        setTimeout(function () {
            if ($("#calendar").length > 0)
                $("#calendar").fullCalendar("render");
            adjustNotification();
            adjustDatatableWidth();
        }, 1500);
    });

    $(document).on("mouseenter", ".main-sidebar", function () {
        setTimeout(function () {
            if ($("#calendar").length > 0)
                $("#calendar").fullCalendar("render");
            adjustNotification();
            adjustDatatableWidth();
        }, 1500);
    });
    //=================sidebar==================//

    $(".slimscroll").slimscroll({
        color: "rgba(0,0,0,0.2)",
        size: "5px",
        alwaysVisible: true
    });

    if ($(".kiranadatepicker").length > 0) {
        $(".kiranadatepicker").each(function () {
            $(".kiranadatepicker").datepicker({
                endDate: ($(this).data("enddate") != null ? $(this).data("enddate") : ''),
                todayHighlight: true,
                disableTouchKeyboard: true,
                format: ($(this).data("format") != null ? $(this).data("format") : "dd.mm.yyyy"),
                startView: ($(this).data("startview") != null ? $(this).data("startview") : "days"),
                minViewMode: ($(this).data("minviewmode") != null ? $(this).data("minviewmode") : "days"),
                autoclose: ($(this).data("autoclose") != null ? $(this).data("autoclose") : false)
            });
        });
    }

    $(document).on('shown.bs.dropdown', '.dataTable .input-group-btn:has(>.dropdown-menu)', function () {
        var $menu = $("ul", this);
        offset = $menu.offset();
        position = $menu.position();
        $('body').append($menu);
        $menu.show();
        $menu.css('position', 'absolute');
        $menu.css('top', (offset.top) + 'px');
        $menu.css('left', (offset.left) + 'px');
        $menu.css('max-width', (Math.abs(position.left) * 2) + 'px');
        $(this).data("myDropdownMenu", $menu);
    });

    $(document).on('hide.bs.dropdown', '.dataTable .input-group-btn:has(.dropdown-toggle)', function () {
        $(this).append($(this).data("myDropdownMenu"));
        $(this).data("myDropdownMenu").removeAttr('style');

    });

    if ($.fn.datepicker)
        $.fn.datepicker.dates['id'] = {
            days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
            daysShort: ["Ming", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
            daysMin: ["Mn", "Sn", "Sl", "Rb", "Km", "Jm", "Sa"],
            months: ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
            monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            today: "Hari Ini",
            clear: "Clear",
            format: "dd.mm.yyyy",
            titleFormat: "MM yyyy",
            /* Leverages same syntax as 'format' */
            weekStart: 1
        };

    $(document).on('click', '#kirana_care', function () {
        var gb = $(this).data("image");
        $("#KiranaModals .modal-dialog").addClass("modal-lg");
        $("#KiranaModals .modal-title").html("Kirana Care");
        $("#KiranaModals .modal-body").html('<div style="height: 500px;overflow-y: scroll;"><img src="' + gb + '" style="width: 100%;"></div>');
        $('#KiranaModals').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });

        $("#KiranaModals .modal-body div").slimScroll({
            height: "500px",
            color: "rgba(0,0,0,0.2)",
            size: "5px",
            alwaysVisible: true
        });
    });

    if ($("#KiranaModalsBase").length == 0) {

        $("#KiranaModals").attr("id", "KiranaModalsBase");
        $("#KiranaModalsBase").clone().attr("id", "KiranaModals").appendTo("section.content");
        $("#KiranaModalsBase .modal-content").empty();
    }
});

function get_child_menu(child, output_child) {
    output_child += "<ul class='treeview-menu'>";
    $.each(child, function (x, y) {
        var url_ext = (y.url_external !== null && y.url_external !== "-") ? baseURL + y.url_external : 'javascript:void(0)';
        var target = y.target == null ? "" : "target='" + y.target + "'";
        output_child += "<li>";
        output_child += "<a href='" + url_ext + "' style='display: inline-flex; width: 100%;' " + target + ">";
        output_child += "<div style='padding-right: 10px;'><i class='fa " + (y.kelas !== '-' ? y.kelas : 'fa-circle-o') + "'></i></div>";
        output_child += "<div style='white-space: normal;'>" + y.nama + "</div>";
        var output_child2 = "";
        output_child += "<span class='pull-right-container'>";
        if (y.child && y.child.length > 0) {
            output_child2 += get_child_menu(y.child, output_child2);
            output_child += "<i class='fa fa-angle-left pull-right'></i>";
        }
        output_child += "<span class='label label-primary pull-right hide notification-badge' " +
            "data-code='" + y.notification_categories + "' " +
            ">0</span>";
        output_child += "</span>";
        output_child += "</a>";
        output_child += output_child2;
        output_child += "</li>";
    });
    output_child += "</ul>";
    return output_child;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function between(x, min, max) {
    return (parseFloat(x) >= parseFloat(min) && parseFloat(x) <= parseFloat(max));
}

function minusValue(val) {
    return (val < 0 ? '(' + val * -1 + ')' : val);
}

function generateDatetimeFormat(tgl) {
    // alert(tgl);
    if (tgl != null && tgl != "") {
        var date = new Date(tgl);
        var hours = date.getHours();
        hours = hours < 10 ? '0' + hours : hours;
        var minutes = date.getMinutes();
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var tanggal = date.getDate();
        tanggal = tanggal < 10 ? '0' + tanggal : tanggal;
        var month = (date.getMonth() * 1) + (1 * 1);
        month = month < 10 ? '0' + month : month;
        return tanggal + "." + month + "." + date.getFullYear() + "  " + hours + ":" + minutes;
    } else {
        return "";
    }
}

function regenerateDatetimeFormat(tgl, format_start, format_to) {
    var date = moment(tgl, format_start).format(format_to);
    return date;
}

function generateDateFormat(tgl) {
    if (tgl != null && tgl != "") {
        var date = new Date(tgl);
        var tanggal = date.getDate();
        tanggal = tanggal < 10 ? '0' + tanggal : tanggal;
        var month = (date.getMonth() * 1) + (1 * 1);
        month = month < 10 ? '0' + month : month;
        return tanggal + "." + month + "." + date.getFullYear();
    } else {
        return "";
    }
}

function generate_table(elem) {
    $(elem).DataTable().destroy();

    if (typeof $(elem).data("textcenter") !== "undefined") {
        var textcenter = $(elem).data("textcenter").toString();
        if (textcenter.indexOf("-") >= 0) {
            textcenter = textcenter.split("-").map(function (item) {
                return parseInt(item, 10)
            })
        }

        if (Array.isArray(textcenter) == false) textcenter = [parseInt(textcenter)];
    }

    if (typeof $(elem).data("textright") !== "undefined") {
        var textright = $(elem).data("textright").toString();
        if (textright.indexOf("-") >= 0) {
            textright = textright.split("-").map(function (item) {
                return parseInt(item, 10)
            })
        }

        if (Array.isArray(textright) == false) textright = [parseInt(textright)];
    }

    var table = $(elem).DataTable({
        pageLength: $(elem).data("page") ? $(elem).data("page") : 10,
        paging: $(elem).data("paging") ? $(elem).data("paging") : true,
        columnDefs: [{
            targets: (typeof $(elem).data("textright") !== "undefined" ? textright : []),
            className: 'text-right'
        },
        {
            targets: (typeof $(elem).data("textcenter") !== "undefined" ? textcenter : []),
            className: 'text-center'
        }
        ],
        searching: $(elem).data("searching") ? $(elem).data("searching") : true,
        info: $(elem).data("info") ? $(elem).data("info") : true,
        ordering: $(elem).data("ordering") ? $(elem).data("ordering") : true,
        scrollX: $(elem).data("scrollx") ? $(elem).data("scrollx") : false
    });

    return table;
}

function dateDiff(datepart, fromdate, todate) {
    datepart = datepart.toLowerCase();
    var diff = todate - fromdate;
    var divideBy = {
        y: 31557600000, // 365.25 * d
        m: 2629800000, // (365.25 * d) / 12
        w: 604800000, // 7 * d
        d: 86400000,
        h: 3600000,
        n: 60000,
        s: 1000
    };

    return Math.floor(diff / divideBy[datepart]);
}

var validator = null;
// clear validation untuk jquery validation
$.fn.clearValidation = function () {
    $('[name]', this).each(function () {
        validator.successList.push(this);
        validator.showErrors();
    });
    validator.resetForm();
    validator.reset();
};

function validate(target = "form", newMethod = false, opt = { rules: {}, messages: {} }) {
    var element = $("input, select, textarea", $(target));
    var count = 0;
    if (newMethod) {
        validator = $(target).validate({
            errorElement: "em",
            rules: opt.rules,
            messages: opt.messages,
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.appendTo(element.parents('.form-group > div'));
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group > div").addClass("has-error").removeClass("has-success");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group > div").addClass("has-success").removeClass("has-error");
            }

        });

        if (!$(target).valid())
            count = 1;
    } else {
        $.each(element, function (i, v) {
			console.log(v);
            if ((v.value.trim() == '' || ((v.value === 0 || v.value === '0') && (!v.hasAttribute('min') || v.min > 0))) && v.hasAttribute('required')) {
                v.focus();
                v.style.borderColor = "red";
                if (v.tagName == 'SELECT' && v.nextSibling.firstChild.firstChild) {
                    v.nextSibling.firstChild.firstChild.style.borderColor = "red";
                }
                count++;
            } else {
                if (v.tagName == 'SELECT' && v.nextSibling.firstChild != null) {
                    v.nextSibling.firstChild.firstChild.style.borderColor = "#d2d6de";
                }
                v.style.borderColor = "#d2d6de";
            }
        });

        if (count > 0) {
            kiranaAlert("notOk", "Form tidak boleh kosong", "warning", "no");
        }
    }

    return count;
}

function login() {
    var formData = new FormData($(".form-login-user")[0]);
    var remember_me = $("input[name='remember']").prop("checked");
    var username = $("input[name='username']").val();
    var searchParams = new URLSearchParams(window.location.search);
    var param = null;
    if (searchParams.has('ref')) {
        param = searchParams.get('ref');
        formData.append('ref', param);
    }
    $.ajax({
        url: baseURL + 'home/checking',
        type: 'POST',
        dataType: 'JSON',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            if (data.sts == 'OK') {
                localStorage.setItem("username_lock_kirana", username);

                if (remember_me === true) {
                    localStorage.setItem("username_kirana", username);
                } else {
                    localStorage.removeItem("username_kirana");
                }


                if (data.link == null) {
                    location.href = baseURL + "home";
                } else {
                    location.href = baseURL + data.link;
                }
            } else {
                let msg = "Periksa kembali data yang dimasukkan";
                if (data.msg)
                    msg = data.msg;
                kiranaAlert("notOk", msg, "warning", "no");
            }
        }
    });
}

function adjustNotification() {
    if ($(".slideshow-wrapper").hasClass("collapsed-box") === false) {
        var heightBody = $(".slideshow-wrapper .box-body").outerHeight();
        // untuk kirana care
        $(".kiranacare-wrapper .box-body.kiranacare-body").animate({ height: (heightBody) }, 500);
        // var heightHeader    = $(".slideshow-wrapper .box-header").outerHeight();
        $(".kiranacare-wrapper .box-body.kiranacare-body a").animate({
            "height": (heightBody - 25),
            "max-height": (heightBody - 25)
        }, 500);
        //Destroy if it exists
        $(".kiranacare-wrapper .box-body.kiranacare-body ul").slimScroll({ destroy: true }).height("auto");
        //Add slimscroll
        $(".kiranacare-wrapper .box-body.kiranacare-body ul").slimScroll({
            height: (heightBody - 25) + "px",
            color: "rgba(0,0,0,0.2)",
            size: "5px"
        });
        var heightBodyNews = $("#news-wrapper").outerHeight();
        $("#newrole-wrapper .newrole-container").animate({ height: (heightBodyNews) }, 500);
        //Destroy if it exists
        $("#newrole-wrapper .newrole-container").slimScroll({ destroy: true }).height("auto");
        //Add slimscroll
        $("#newrole-wrapper .newrole-container").slimScroll({
            height: (heightBodyNews - 20) + "px",
            color: "rgba(0,0,0,0.2)",
            size: "5px"
        });
    }
}

function adjustDatatableWidth() {
    if ($(".dataTables_wrapper").length > 0) $("table.dataTable").DataTable().columns.adjust(); //.draw();
}

// Auto datatable width adjust untuk table di dalam tabs
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    if ($('.dataTable', $('.tab-pane.active')).length > 0)
        adjustDatatableWidth();
    if ($('#news-wrapper').length > 0)
        adjustNotification();
});

function kiranaAlert(sts, msg, icon, reload, html) {
    if (!icon) icon = "success";
    if (!reload) reload = "yes";
    if (!html) html = false;
    title = document.title.split(" | ");

    if (typeof sts === 'string')
        if (sts == "OK") {
            swal({
                title: title[0],
                text: msg,
                icon: icon,
                html: html,
                type: icon
            }).then(function () {
                if (reload == "yes") location.reload();
                else if (reload == "no") false;
                else location.href = reload;
            });
        } else {
            swal({
                title: title[0],
                text: msg,
                html: html,
                icon: icon,
                html: html,
                type: icon
            });
        }
    else {
        let {
            text = "Gagal",
            icon = "error",
            html = false,
            reload = true,
            callback = null
        } = sts;

        swal({
            title: title[0],
            text: text,
            html: html,
            icon: icon,
            type: icon
        }).then(function () {
            if (typeof callback === "function")
                callback();
            else {
                if (reload === true) location.reload();
                else if (reload === false) false;
                else location.href = reload;
            }
        });
    }
}

function kiranaConfirm(parameters) {
    let {
        title = "Konfirmasi",
        text = "Apa anda yakin?",
        icon = "warning",
        useButton = true,
        showConfirmButton = true,
        showCancelButton = true,
        successCallback = null,
        failCallback = null,
        dangerMode = false,
        confirmButtonText = "Oke",
        cancelButtonText = "Batal"
    } = parameters;

    if (cancelButtonText == "") {
        cancelButtonText = "Batal";
    }

    if (confirmButtonText == "") {
        confirmButtonText = "Oke";
    }

    swal({
        title: title,
        text: text,
        type: icon,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        showConfirmButton: (useButton == null ? showConfirmButton : useButton),
        showCancelButton: (useButton == null ? showCancelButton : useButton),
        dangerMode: dangerMode,
    })
        .then((result) => {
            if (result.value && typeof successCallback === "function")
                successCallback();
            else if (typeof failCallback === "function")
                failCallback();
        });
}

String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

let KIRANAKU = {
    createValidator: function (elements) {
        const jElements = elements.jquery ? elements : $(elements);

        const validatorElement = jElements.validate({
            ignore: '.hide input , .hide select, .hide textarea',
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    if (element.parents('.form-group').length) {
                        if ($(element).closest(".form-group").find("div").not('.input-group').length > 0)
                            error.appendTo(element.parents('.form-group > div'));
                        else
                            error.appendTo(element.parents('.form-group'));
                    } else if (element.parents('td').length) {
                        error.appendTo(element.parents('td'));
                    }
                }
            },
            highlight: function (element, errorClass, validClass) {
                if ($(element).parents('.form-group').length) {
                    if ($(element).closest(".form-group").find("div").length > 0)
                        $(element).parents(".form-group > div").addClass("has-error").removeClass("has-success");
                    else
                        $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
                } else if ($(element).parents('td').length) {
                    $(element).parents("td").addClass("has-error").removeClass("has-success");
                }
            },
            unhighlight: function (element, errorClass, validClass) {

                if ($(element).parents('.form-group').length) {
                    if ($(element).closest(".form-group").find("div").length > 0)
                        $(element).parents(".form-group > div").addClass("has-success").removeClass("has-error");
                    else
                        $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
                } else if ($(element).parents('td').length) {
                    $(element).parents("td").addClass("has-success").removeClass("has-error");
                }
            }

        });

        jElements.data('validator', validatorElement);
    },
    isNullOrEmpty: function (value, returnIfNotNull = false, returnIfNull = true) {
        if (value == null || typeof value === "undefined" || (typeof value === "string" && value.trim() === ""))
            return returnIfNull;
        else
            return returnIfNotNull;
    },
    isNotNullOrEmpty: function (value) {
        return this.isNullOrEmpty(value, true, false)
    },
    alert: kiranaAlert,
    confirm: function (parameters) {
        let {
            title = "Konfirmasi",
            text = "Apa anda yakin?",
            icon = "warning",
            useButton = true,
            showConfirmButton = true,
            showCancelButton = true,
            successCallback = null,
            failCallback = null,
            dangerMode = false
        } = parameters;

        swal({
            title: title,
            text: text,
            type: icon,
            confirmButtonText: "Oke",
            cancelButtonText: "Batal",
            showConfirmButton: (useButton == null ? showConfirmButton : useButton),
            showCancelButton: (useButton == null ? showCancelButton : useButton),
            dangerMode: dangerMode,
        })
            .then((result) => {
                if (result.value && typeof successCallback === "function")
                    successCallback();
                else if (typeof failCallback === "function")
                    failCallback();
            });
    },
    showLoading: function () {
        var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";

        if ($("body .overlay").length > 0)
            $("body .overlay").remove();

        if ($("body .modal").hasClass("in") === true) {
            $("body .modal.in .modal-content").append(overlay);
        } else {
            $("body .overlay-wrapper").append(overlay);
        }
    },
    hideLoading: function () {
        $("body .overlay").remove();
    },
    isProses: function () {
        return $("input[name='isproses']").val();
    },
    startProses: function (startLoading = false) {
        $("input[name='isproses']").val(1);
        if (startLoading) {
            this.showLoading();
        }
    },
    endProses: function (stopLoading = false) {
        $("input[name='isproses']").val(0);
        if (stopLoading) {
            this.hideLoading();
        }
    },
    convertNumeric: function (elements, options = {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        allowDecimalPadding: false,
        decimalPlaces: 0,
        modifyValueOnWheel: false
    }) {
        const jElements = elements.jquery ? elements : $(elements);
        jElements.each(function (i, el) {
            if ($(el).data('numeric-data')) {
                $(el).data('numeric-data').update(options);
            } else {
                const numericEl = new AutoNumeric(el, options);
                $(el).data('numeric-data', numericEl);
            }
        });
    },
    convertNumericLabel: function (elements, options = {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        allowDecimalPadding: false,
        readOnly: true,
        noEventListeners: true,
        decimalPlaces: 0
    }) {
        this.convertNumeric(elements, options);
    },
    numericSet: function (elements, value) {
        const jElements = elements.jquery ? elements : $(elements);
        jElements.each(function (i, el) {
            if ($(el).data('numeric-data')) {
                $(el).data('numeric-data').set(value);
            } else {
                if ($(el).is('input'))
                    $(el).val(value);
                else
                    $(el).html(value);
            }
        });
    },
    numericGet: function (elements, defaultValue = 0) {
        let value = defaultValue;
        const jElements = elements.jquery ? elements : $(elements);
        jElements.each(function (i, el) {
            if ($(el).data('numeric-data')) {
                value = $(el).data('numeric-data').getNumber();
            } else {
                value = $(el).val();
            }
        });
        return value;
    },
    select2: function (elem = $(".content-wrapper")) {
        if ($(".select2", elem).length > 0) {
            $(".select2", elem).each(function () {
                $(this).select2({
                    maximumSelectionLength: $(this).attr("data-maximumselectionlength") ? $(this).attr("data-maximumselectionlength") : 0,
                    allowClear: $(this).attr("data-allowclear") == "true" ? true : false,
                    placeholder: $(this).attr("data-placeholder") ?
                        $(this).attr("data-placeholder") : "Silahkan Pilih"
                });
            });

            $(".select2", elem).on("select2:clear, select2:unselect", function (e) {
                $(this).on("select2:opening.cancelOpen", function (evt) {
                    evt.preventDefault();
                    $(this).off("select2:opening.cancelOpen");
                });
            });

            $(".select2", elem).on("select2:clearing", function (e) {
                if (typeof $(this).attr("multiple") !== undefined && $(this).attr("multiple") !== false && $(this).attr("multiple") !== undefined && $(this).hasClass("autocomplete") == true) {
                    $(this).empty().trigger("change");
                }
            });

            $(".select2", elem).on("select2:opening", function (e) {
                if ($(this).hasClass("autocomplete") == true) {
                    if ($(this).val() === "" || $(this).attr("multiple") == undefined) {
                        $(this).empty().trigger("change.select2");
                    }
                }
            });

            $(".select2", elem).on("select2:closing", function (e) {
                if ($(this).hasClass("autocomplete") == true) {
                    if ($(this).val() === "" || $(this).val() == null) {
                        var newOption = new Option("", "", false, false);
                        $(this).append(newOption).trigger('change.select2');
                    }
                }
            });
        }
    }
};

function showPdf(url, height, option) {
    if (!height) height = "500px";
    if (!option) option = "allowfullscreen webkitallowfullscreen";

    if (url) {
        return '<iframe src="' + baseURL + 'assets/plugins/pdfjs/web/viewer.html?file=../../../../' + url + '" width="100%" height="' + height + '" ' + option + '></iframe>'
    }
}

function checkImage(imageSrc, myElement, errorImage) {
    var img = new Image();
    img.onload = function () {
        console.log("berhasil");
        $(myElement).attr("src", imageSrc);
    };
    img.onerror = function () {
        console.log("gagal");
        if (typeof errorImage == "undefined")
            errorImage = baseURL + "assets/apps/img/image.png";
        $(myElement).attr("src", errorImage);
    };
    img.src = imageSrc;
}

//PLUGIN sorting Date // columnDefs add date-eu
if (jQuery.fn.dataTableExt)
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-eu-pre": function (date) {
            if (date == null) {
                return 0;
            }

            date = date.replace(" ", "");

            if (!date) {
                return 0;
            }

            var year;
            var eu_date = date.split(/[\.\-\/]/);

            /*year (optional)*/
            if (eu_date[2]) {
                year = eu_date[2];
            } else {
                year = 0;
            }

            /*month*/
            var month = eu_date[1];
            if (month.length == 1) {
                month = 0 + month;
            }

            /*day*/
            var day = eu_date[0];
            if (day.length == 1) {
                day = 0 + day;
            }

            return (year + month + day) * 1;
        },

        "date-eu-asc": function (a, b) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "date-eu-desc": function (a, b) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    });