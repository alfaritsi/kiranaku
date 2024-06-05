<?php
/**
 * @application  : View Info Kirana Komentar(Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */
?>
<tr class="table-komentars" data-id="<?php echo $id; ?>">
    <td colspan="5" class="bg-success">
        <table class="table table-bordered my-datatable">
            <thead>
            <th>Nama</th>
            <th>Komentar</th>
            <th>Tanggal</th>
            <th>Publish</th>
            <th>Action</th>
            </thead>
            <tbody>
            <?php
            foreach ($komentars as $komentar) {
                $enId = $this->generate->kirana_encrypt($komentar->id_komentar);
                $na = ($komentar->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";

                echo "<tr>";
                echo "<td>" . $komentar->nama_karyawan . "</td>";
                echo "<td>" . date_format(date_create($komentar->tanggal), "d.m.Y H:i") . "</td>";
                echo "<td>" . $komentar->komentar .
                    "</td>";
                echo "<td>" . $na . "</td>";

                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
                if ($komentar->na == 'n') {
                    echo "<li><a href='#' class='komentar_set_active' data-id='" . $enId . "' data-action='delete_na'><i class='fa fa-times'></i> Not Publish</a></li>";
                } else {
                    echo "<li><a href='#' class='komentar_set_active' data-id='" . $enId . "' data-action='activate_na'><i class='fa fa-check'></i> Publish</a></li>";
                }
                echo "    </ul>
				                          </div>
				                        </td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </td>
</tr>

