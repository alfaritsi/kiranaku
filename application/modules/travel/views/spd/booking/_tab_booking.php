
<!-- <table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-booking"
       data-page-length='10' data-order='[]' data-length-change="false"> <!- //my-datatable-extends-order -->
<script type="text/javascript">
    var tipe_screen = "<?php echo $tipe_screen[0]; ?>";
</script>
<div class="row">
    <div class="col-sm-2">
        <div class="form-group">
            <label> Pemesanan : </label>
            <select class="form-control select2" multiple id="kelengkapan" name="kelengkapan"  style="width: 110%;">
                <option value="">Silahkan Pilih Pabrik</option>
                <option value="lengkap">Sudah dipesankan</option>
                <option value="tidak_lengkap">Outstanding Pemesanan</option>                                
            </select>
        </div>
    </div>
</div>
<table class="table table-bordered table-striped " id="sspTable">
    <thead>
        <tr>   

            <th width="5%">ID</th>
            <th width="5%">NIK</th>
            <th width="10%">Nama</th>
            <th width="5%">No Trip</th>
            <th width="10%">Aktifitas</th>
            <th width="15%">Tujuan</th>
            <th width="10%">Berangkat</th>
            <th width="10%">Kembali</th>
            <th data-orderable="false" width="5%"></th>
        </tr>
    </thead>
</table> 