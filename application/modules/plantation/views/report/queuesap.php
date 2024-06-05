<?php $this->load->view('header') ?>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">Data Antrian SAP</h1>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="action-button-datatable" style="float: right;"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-striped" id="tbl-queue" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
                                    <thead>
                                        <th>Tanggal Transaksi</th>
                                        <th>Tanggal Buat</th>
                                        <!-- <th>No</th> -->
                                        <th>Jenis</th>
                                        <th>Pabrik</th>
                                        <th>NO PPB/TTG/BKB</th>
                                        <th>Status SAP</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list as $i => $dt) {
                                            $status_sap = "-";
                                            if ($dt->done_kirim_sap) {
                                                if ($dt->status_sap === 'success') {
                                                    $status_sap = '<span class="label label-success">Success</span><br>' . $dt->keterangan_sap;
                                                } else if ($dt->status_sap === 'fail') {
                                                    $status_sap = '<span class="label label-danger">Fail:</span><br>' . $dt->keterangan_sap;
                                                }
                                            }
                                            echo "<tr>";
                                            // echo "<td>" . ($i + 1) . "</td>";
                                            echo "<td>" . $dt->tanggal_format . "</td>";
                                            echo "<td>" . $dt->tanggal_buat_format . "</td>";
                                            echo "<td>" . $dt->caption . "</td>";
                                            echo "<td>" . $dt->plant . "</td>";
                                            echo "<td>" . $dt->no_transaksi . "</td>";
                                            echo "<td>" . $status_sap . "</td>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script>
    $(document).ready(function() {
        let table = $('#tbl-queue').DataTable({
            "order": [],
            columnDefs: [{
                targets: '_all',
                orderable: false
            }],
            // dom: 'Bfrtip',
            // buttons: [
            //     {
            //         extend: 'excelHtml5',
            //         text: 'Export to Excel',
            //         title: 'Data Antrian SAP',
            //         download: 'open',
            //         orientation:'landscape',
            //         exportOptions: {
            //             columns: [0,1,2,3,4,5,6,7,8,9]
            //         }
            //     }
            // ],
            scrollX: true
        });

        new $.fn.dataTable.Buttons(table, {
            buttons: [{
                extend: 'excel',
                title: '',
                text: 'Export Excel',
                className: 'btn btn-default btn-sm'
            }]
        });

        table.buttons().container().appendTo("#action-button-datatable");

    });
</script>