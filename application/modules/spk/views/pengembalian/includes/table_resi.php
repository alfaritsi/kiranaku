<?php if ($leg_level_id == 2): ?>
    <a href='javascript:void(0)' class="pengembalian-resi-add" data-id_pengembalian="<?php echo $id_pengembalian; ?>"><span
                class='badge bg-green'><i class="fa fa-plus-circle"></i>&nbsp;Resi</span></a>&nbsp;
<?php endif; ?>
<a href='javascript:void(0)' data-toggle='collapse' data-target='#table-resi-<?php echo $id_pengembalian; ?>'><span
            class='badge bg-light-blue'><i class='fa fa-search'></i> Lihat resi</span></a><br/><br/>
<div class="box box-info collapse"
     id="table-resi-<?php echo $id_pengembalian; ?>">
    <div class="box-body no-padding">
        <table class='table table-striped table-bordered'>
            <thead>
            <tr>
                <th>Ekspedisi</th>
                <th class="text-nowrap">Nomor Resi</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (count($resis) > 0) :
                foreach ($resis as $resi) { ?>
                    <tr>
                        <td><?php echo $resi->ekspedisi ?></td>
                        <td><?php echo $resi->no_resi ?></td>
                        <?php if (!empty($resi->links)) : ?>
                            <td>
                                <div class='input-group-btn'>
                                    <button type='button' class='btn btn-default btn-xs dropdown-toggle'
                                            data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
                                    <ul class='dropdown-menu pull-right'>
                                        <?php echo $resi->links ?>
                                    </ul>
                                </div>
                            </td>
                        <?php else: ?>
                            <td>&nbsp;</td>
                        <?php endif; ?>
                    </tr>
                <?php } else: ?>
                <tr>
                    <td colspan="3" class="text-center">Belum ada resi</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>