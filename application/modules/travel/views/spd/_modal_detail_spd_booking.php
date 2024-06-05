<div class="modal fade" role="dialog" id="modal-detail-spd-booking" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    Detail Transportasi & Penginapan Perjalanan Dinas
                </h4>
            </div>
            <div class="modal-body no-padding">
                <div class="nav-tabs-custom tab-success margin-bottom-none">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#modal-tab-detail-pengajuan" data-toggle="tab">Transportasi</a>
                        </li>
                        <li>
                            <a href="#modal-tab-detail-um" data-toggle="tab">Penginapan</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="modal-tab-detail-pengajuan">
                            <?php $this->load->view('_tab_modal_detail_spd_booking_transportasi') ?>
                        </div>
                        <div class="tab-pane" id="modal-tab-detail-um">
                            <?php $this->load->view('_tab_modal_detail_spd_booking_penginapan') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>