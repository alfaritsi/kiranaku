<?php $this->load->view('header') ?>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Unggah Transaksi</h3>
                    </div>
                    <form id="form-upload-transaksi" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="row-form">
                                <div class="form-group">
                                    <label for="tipe">Tipe Transaksi</label>
                                    <select name="tipe" id="tipe" class="form-control select2" required>
                                        <option value="ppb">PPB (Permohonan Pembelian Barang)</option>
                                        <option value="po_ho">PO HO</option>
                                        <option value="po_site">PO Site + TTG/GR</option>
                                        <option value="gr_ho">TTG/GR PO HO</option>
                                        <option value="gi">BKB / GI</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="excelData">File input</label>
                                    <input type="file" id="file_excel" name="file_excel" required>

                                    <p class="help-block">*pastikan anda mengunggah file excel sesuai template yang disediakan</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="action_btn" class="btn btn-success" data-btn="submit">Unggah</button>
                            <!-- <button type="button" id="download_btn" class="btn btn-primary pull-right" data-btn="download">Unduh File Template</button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script type="text/javascript">
$(document).ready(function(){
    $("#form-upload-transaksi").on("submit", function(e){
        // const tipe = $("[name=tipe]").val();
        const empty_form = validate('#form-upload-transaksi');
        if (empty_form == 0) {
            const isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($("#form-upload-transaksi")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'plantation/balance/save/upload',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function () {
                        swal('Error', 'Server Error', 'error');
                    },
                    complete: function () {
                        $("input[name='isproses']").val(0);
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

    $("#download_btn").on("click", function() {
        var form = document.createElement("form");
        var element1 = document.createElement("input"); 

        form.method = "POST";
        form.action = baseURL+'plantation/transaksi/download';

        element1.value = $("[name=tipe]").val();
        element1.name = "tipe";
        element1.type = "hidden";

        form.appendChild(element1);

        document.body.appendChild(form);

        form.submit();
    })
});
</script>