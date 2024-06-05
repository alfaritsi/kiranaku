<ul class="list-unstyled list-fasilitas">
    <?php foreach ($datas as $available_fasilitas) : ?>
        <li>
            <?php if (!$available_fasilitas->available): ?>
                <i class="fa fa-minus-circle"></i>
            <?php else : ?>
                <i class="fa fa fa-check-square-o"></i>
            <?php endif;
            echo $available_fasilitas->nama;
            ?>

        </li>
    <?php endforeach; ?>
</ul>