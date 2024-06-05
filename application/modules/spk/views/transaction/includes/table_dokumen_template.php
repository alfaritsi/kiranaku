<table class='table table-striped table-bordered table-dokumen'>
    <thead>
    <tr>
        <th width=50%>File Template</th>
        <th width=40%>Upload</th>
		<th>Modified Date</th>
        <th width=10%></th>
    </tr>
    </thead>
    <tbody>
    <?php if (count($data) > 0) : ?>
        <?php foreach ($data as $template): ?>
            <tr>
                <td width=50%><?php echo $template->nama_doc ?></td>
                <td width=40%><?php echo $template->uploadStatus ?"<i class='fa fa-check text-green'></i>":"<i class='fa fa-times text-red'></i>"?></td>
                <td><?php echo $template->tanggal_edit ?></td>
				<td width=10%>
                <?php if ($template->links): ?>
                    <div class='input-group-btn'>
                        <button type='button' class='btn btn-default btn-xs dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
                        <ul class='dropdown-menu pull-right'>
                            <?php echo $template->links; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3" class="text-center">Tidak ada file template</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>