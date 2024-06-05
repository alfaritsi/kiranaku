<table class='table table-striped table-bordered table-dokumen'>
    <thead>
    <tr>
        <th width=50%>File Vendor</th>
        <th width=20%>Upload</th>
        <th width=10%>Modified Date</th> 
        <th width=10%>Mandatory</th>
        <th width=10%></th> 
    </tr>
    </thead> 
    <tbody>
    <?php if (count($data) > 0) : ?>
        <?php foreach ($data as $vendor): ?>
            <?php if($vendor->links!=null){ ?> 
			<tr>
                <td width=50%><?php echo $vendor->nama_doc ?></td>
                <td width=20%><?php echo $vendor->uploadStatus ?"<i class='fa fa-check text-green'></i>":"<i class='fa fa-times text-red'></i>"?></td>
                <td width=10%><?php echo $vendor->tanggal_edit ?></td>
				<td width=10%><?php echo $vendor->mandatory_doc ?></td>
                <td width=10%>
                    <div class='input-group-btn'>
                        <button type='button' class='btn btn-default btn-xs dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
                        <ul class='dropdown-menu pull-right'>
                            <?php echo $vendor->links; ?>
                        </ul>
                    </div>
                </td>
            </tr>
			<?php }?>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="text-center">Tidak ada file vendor</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>