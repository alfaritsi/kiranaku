<?php if ($leg_level_id == 2): ?>
    <a href='javascript:void(0)' class="pengembalian-keterangan-add" data-id_pengembalian="<?php echo $id_pengembalian; ?>"><span
                class='badge bg-green'><i class="fa fa-plus-circle"></i>&nbsp;Keterangan</span></a>&nbsp;
<?php endif; ?>
<a href='javascript:void(0)' data-toggle='collapse' data-target='#table-keterangan-<?php echo $id_pengembalian; ?>'><span
            class='badge bg-light-blue'><i class='fa fa-search'></i> Lihat keterangan</span></a><br/><br/>
<div class="box box-info collapse"
     id="table-keterangan-<?php echo $id_pengembalian; ?>">
    <div class="box-body no-padding">
        <table class='table table-striped table-bordered'>
            <thead>
            <tr>
                <th>Keterangan</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (count($keterangans) > 0) :
                foreach ($keterangans as $keterangan) { ?>
                    <tr>
                        <td><?php echo $keterangan->keterangan?></td>
                        <?php if (!empty($keterangan->links)) : ?>
                            <td>
                                <div class='input-group-btn'>
                                    <button type='button' class='btn btn-default btn-xs dropdown-toggle'
                                            data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
                                    <ul class='dropdown-menu pull-right'>
                                        <?php echo $keterangan->links ?>
                                    </ul>
                                </div>
                            </td>
                        <?php else: ?>
                            <td>&nbsp;</td>
                        <?php endif; ?>
                    </tr>
                <?php } else: ?>
                <tr>
                    <td colspan="3" class="text-center">Belum ada keterangan</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>