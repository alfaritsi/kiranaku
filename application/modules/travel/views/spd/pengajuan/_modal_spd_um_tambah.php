<div class="modal fade" role="dialog" id="modal-spd-tambah-um" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Form Pengajuan Uang muka Tambahan</h4>
            </div>
            <div class="modal-body no-padding">
                <form class="form-horizontal form-pengajuan-um-tambah">
                    <input type="hidden" name="id_travel_header">
                    <div class="nav-tabs-custom tab-success margin-bottom-none">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#modal-tab-um-tambahan" data-toggle="tab">Uang Muka</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="modal-tab-um-tambahan">
                                <?php $this->load->view('pengajuan/_tab_modal_spd_pengajuan_uangmuka_tambahan') ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button name="simpan_btn_tambah_um" class="btn btn-success" type="button">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>