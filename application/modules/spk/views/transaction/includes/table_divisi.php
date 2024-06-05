<div class="box box-info collapse"
     id="table-divisi-<?php echo $id_spk; ?>">
    <div class="box-header">
        <h5 class="box-title">Status Approval</h5>
    </div>
    <div class="box-body no-padding">
        <table class='table table-striped table-bordered'>
            <thead>
            <tr>
                <th class="text-nowrap">Nama Divisi</th>
                <th>Tanggal Approve</th>
            </tr>
            </thead>
            <tbody>
            <?php if (count($divisis) > 0) :
                foreach ($divisis as $divisi) { ?>
                    <tr>
                        <td><?php echo $divisi->nama_divisi ?></td>
                        <td><?php echo isset($divisi->tanggal_approve) ? $this->generate->generateDateFormat($divisi->tanggal_approve):'-' ?></td>
                    </tr>
                <?php } else: ?>
                <tr>
                    <td colspan="2" class="text-center">Belum ada approval</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>