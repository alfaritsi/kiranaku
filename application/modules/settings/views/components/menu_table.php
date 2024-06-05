<?php
foreach ($menus as $dt) {

    $enId = $this->generate->kirana_encrypt($dt->id_menu);
    $pEnId = $this->generate->kirana_encrypt($dt->id_parent);
    $na = ($dt->na == 'n') ? "<i class='fa fa-check-square text-success'></i>" : "<i class='fa fa-minus-square text-danger'></i>";
    $pnode = ($dt->id_parent != 0) ? "data-pnode='treetable-parent-$pEnId'" : "";
    echo "<tr data-node='treetable-$enId' $pnode>";
    echo "<td>&nbsp;</td>";
    echo "<td>" . $dt->nama . "</td>";
//        echo "<td>" . ($dt->id_parent == 0 ? "Root" : "") . "</td>";
    echo "<td>" . $dt->url . "</td>";
    echo "<td>" . $dt->url_external . "</td>";
    echo "<td>" . $dt->urut . "</td>";
    echo "<td><i class='fa " . $dt->kelas . "'></i></td>";
    echo "<td>" . $na . "</td>";

    echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
    if ($dt->na == 'n') {
        echo "
                <li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                <li><a href='#' class='akses' data-akses='" . $enId . "'><i class='fa fa-users'></i> Hak Akses Karyawan</a></li>
                <li><a href='#' class='delete' data-delete='" . $enId . "'><i class='fa fa-trash-o'></i> Hapus</a></li>
                  ";
    }
    if ($dt->na == 'n') {
        echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='delete_na'><i class='fa fa-times'></i> Not Publish</a></li>";
    } else {
        echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='activate_na'><i class='fa fa-check'></i> Publish</a></li>";
    }
    echo "    </ul>
				                          </div>
				                        </td>";
    echo "</tr>";
}
?>