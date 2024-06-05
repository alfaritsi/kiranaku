<div class="modal fade" role="dialog" id="modal-detail-spd-pengajuan" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    Detail Pengajuan Perjalanan Dinas
                    <span class="badge badge-pembatalan bg-red margin-r-5 hide">Pengajuan Pembatalan</span>
                    <span class="badge badge-status margin-r-5">Menunggu</span>
                </h4>
            </div>
            <div class="modal-body no-padding">
                <div class="nav-tabs-custom tab-success margin-bottom-none">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#modal-tab-detail-pengajuan" data-toggle="tab">Pengajuan</a>
                        </li>
                        <li>
                            <a href="#modal-tab-detail-um" data-toggle="tab">Uang Muka</a>
                        </li>
                        <li class="hide">
                            <a href="#modal-tab-deklarasi" data-toggle="tab">Deklarasi</a>
                        </li>
                        <li>
                            <a href="#modal-tab-history" data-toggle="tab">History</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="modal-tab-detail-pengajuan">
                            <?php $this->load->view('_tab_modal_detail_spd_pengajuan_pengajuan') ?>
                        </div>
                        <div class="tab-pane" id="modal-tab-detail-um">
                            <?php $this->load->view('_tab_modal_detail_spd_pengajuan_uangmuka') ?>
                        </div>
                        <div class="tab-pane" id="modal-tab-deklarasi">
                            <?php $this->load->view('_tab_modal_detail_spd_pengajuan_deklarasi') ?>
                        </div>
                        <div class="tab-pane" id="modal-tab-history">
                            <?php $this->load->view('_tab_modal_detail_spd_pengajuan_history') ?>
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