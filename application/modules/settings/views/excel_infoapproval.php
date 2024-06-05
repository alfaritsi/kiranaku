<table class="table table-bordered my-datatable-extends-order" border='1'>
    <thead>
    <th>Nama</th>
    <th>NIK</th>
    <th>Entity</th>
    <th>Bagian</th>
    <th>Ext</th>
    <th>Approval</th>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach ($approval as $dt) {
        $lokasi = ($dt->ho == 'y') ? "Head Office" : $dt->nama_pabrik;
        if ($dt->ho == 'y') {
            $bagian = (empty($dt->nama_departemen)) ? $dt->nama_divisi : $dt->nama_departemen;
        } else {
            $bagian = (empty($dt->nama_seksi)) ? $dt->nama_departemen : $dt->nama_seksi;
            $bagian = (empty($bagian)) ? $dt->nama_sub_divisi : $bagian;
            $bagian = (empty($bagian)) ? $dt->nama_pabrik : $bagian;
        }
        $enId = $this->generate->kirana_encrypt($dt->id_karyawan);
        $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
        echo "<tr>";
//                                echo "<td>" . $i++ . "</td>";
        echo "<td>" . $dt->nama . "</td>";
        echo "<td>" . $dt->nik . "</td>";
        echo "<td>" . $lokasi . "</td>";
        echo "<td>" . $bagian . "</td>";
        echo "<td>" . $dt->telepon . "</td>";
        echo "<td><ul><li>" . join('</li><li>',explode(', ',$dt->atasan_nama)) . "</li></ul></td>";

        echo "</tr>";
    }
    ?>
    </tbody>
</table>