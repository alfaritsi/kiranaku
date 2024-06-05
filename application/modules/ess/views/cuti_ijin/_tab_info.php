<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>Jenis Ijin</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($list_cuti_ijin as $jenis):?>
    <tr>
        <td><?php echo $jenis->nama ?></td>
        <td><?php echo $jenis->jumlah_label ?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>