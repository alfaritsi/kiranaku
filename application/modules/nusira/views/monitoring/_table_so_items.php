<div id="table-items-<?php echo $no_so ?>" class="hide">
    <div class="box box-info" style="margin-top:10px; max-width: 70%;">
        <div class="box-header with-border">
            <h3 class="box-title">Sales Order Items</h3>
        </div>
        <div class="box-body no-padding">
            <table class='table table-bordered table-so-detail'>
                <thead>
                <tr>
                    <th>No Item</th>
                    <th>Kode SAP</th>
                    <th>Deskripsi</th>
                    <th>UOM</th>
                    <th>Order Qty</th>
<!--                    <th>Tgl Delivery</th>-->
<!--                    <th>Stock in Hand</th>-->
<!--                    <th>Stock Reserved</th>-->
                    <th width="20%">Action</th>
<!--                    <th width="5%">Action</th>-->
                </tr>
                </thead>
                <tbody>
                <?php if (count($items) > 0) :
                    foreach ($items as $item) { ?>
                        <tr>
                            <td class="text-center"><?php echo $item['no_pos'] ?></td>
                            <td class="text-center"><?php echo $item['no_mat'] ?></td>
                            <td class="text-center"><?php echo $item['nama_mat'] ?></td>
                            <td class="text-center"><?php echo $item['uom'] ?></td>
                            <td class="text-center"><?php echo $item['qty_ord'] ?></td>
<!--                            <td class="text-center">--><?php //echo (isset($item['tanggal_delivery'])) ? $item['tanggal_delivery'] : '-' ?><!--</td>-->
<!--                            <td class="text-center">--><?php //echo $item['qty_stock'] ?><!--</td>-->
<!--                            <td class="text-center">--><?php //echo $item['qty_reserve'] ?><!--</td>-->
                            <td class="text-center">
                                <?php echo $item['action'] ?>
                            </td>
                        </tr>
                    <?php } else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada item</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
