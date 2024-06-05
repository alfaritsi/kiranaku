<fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
    <legend class="no-pad-top"><h4>Historys</h4></legend>
    <div class="header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button> -->
        <!-- <h4 class="modal-title">History Perjalanan Dinas</h4> -->
    </div>
    <div class="body no-padding">
        <script type="text/template" id="history_spd_template">
            <tr class="template-trip">
                <td width="10%" align="center">
                    <p class="form-control-static no-padding label_tanggal"></p>
                </td>
                <td width="30%">
                    <p class="form-control-static no-padding label_action"></p>
                </td>
                <td width="15%">
                    <p class="form-control-static no-padding label_remark"></p>
                </td>
                <td width="30%">
                    <p class="form-control-static no-padding label_comment"></p>
                </td>
                <td width="15%" align="center">
                    <p class="form-control-static no-padding label_by"></p>
                </td>
            </tr>
        </script>
        <script type="text/template" id="history_spd_template_timeline">
            <li class="time-label">
                <span class="bg-blue span_tgl" >
                    10 Feb. 2014
                </span>
            </li>
            <li>
                <i class="" id="icon_action{no}"></i>
                <div class="timeline-item">
                    <span class="time span_jam"><i class="fa fa-clock-o"></i> 12:05</span>

                    <h3 class="timeline-header action_his">Pengajuan SPD</h3>

                    <div class="timeline-body action_by">
                        Dilakukan oleh Tom Cruise [127]
                    </div>

                    <div class="timeline-footer">
                       
                    </div>
                </div>
            </li>
        </script>
        <ul class="timeline tm_his">

        </ul>
        <!-- <table id="table-history-spd"
               class="table table-responsive table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>Tanggal</th>
                <th>Action</th>
                <th>Remark</th>
                <th>Komentar</th>
                <th>By</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <ul class="timeline">

            <!- timeline time label ->
            <li class="time-label">
                <span class="bg-red">
                    10 Feb. 2014
                </span>
            </li>
            <!-- /.timeline-label ->

            <!-- timeline item ->
            <li>
                <!-- timeline icon ->
                <i class="fa fa-envelope bg-blue"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                    <h3 class="timeline-header"><a href="#">Pengajuan SPD</a></h3>

                    <div class="timeline-body">
                        Dilakukan oleh Tom Cruise [127]
                    </div>

                    <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">...</a>
                    </div>
                </div>
            </li>
            <!-- END timeline item ->
        </ul> -->
    </div>
</fieldset>
