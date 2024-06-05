$(document).ready(function(){

    $('.sortable').nestedSortable({
        forcePlaceholderSize: true,
        handle: 'div',
        helper:	'clone',
        items: 'li',
        opacity: .6,
        placeholder: 'placeholder',
        revert: 250,
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div',
        maxLevels: 4,
        isTree: true,
        expandOnHover: 700,
        startCollapsed: false
    });

    $('#btn-simpan-tree').on('click',function (e) {
        let result = $('.sortable').nestedSortable('toArray', {startDepthCount: 0});
        $('#result').val(JSON.stringify(result));

        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            var formData = new FormData($(".form-notification-category-tree")[0]);

            $.ajax({
                url: baseURL + 'notifications/save/category-tree',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if(data.sts == 'OK'){
                        swal('Success',data.msg,'success').then(function(){
                            location.reload();
                        });
                    }else{
                        $("input[name='isproses']").val(0);
                        swal('Error',data.msg,'error');
                    }
                }
            });
        } else {
            swal({
                title: "Silahkan tunggu proses selesai.",
                icon: 'info'
            });
        }
        e.preventDefault();
        return false;
    })

});