<style>
    table.tableTree tbody tr.leaf td {
        position: relative;
        padding: 3px 0 3px 25px;
        display: inline-grid;
        word-break: break-all;
        align-items: center;
    }

    .branch-wrapper {
        color: black;
    }

    .carousel-indicators {
        bottom: -10px;
    }

    .carousel-indicators li {
        background: #cecece;
        border: 1px solid black;
    }
</style>


<div class="row">
    <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><strong>Notifikasi Kiranaku</strong></h3>

                <div class="box-tools pull-right">
                    <button type="button"
                            class="btn btn-box-tool"
                            data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body notif-wrapper">
                <div id="carousel-notification-slide-box"
                     class="carousel slide"
                     data-ride="carousel">
                    <ol class="carousel-indicators">
                        <?php if (count($notifications_apps) > 0): ?>
                            <?php foreach ($notifications_apps as $slidekey => $app): ?>
                                <?php if ($slidekey > 0): ?>
                                    <?php $slide = floor($slidekey / 4); ?>
                                <?php else: ?>
                                    <?php $slide = 0; ?>
                                <?php endif; ?>
                                <?php if ($slidekey == 0): ?>
                                    <li data-target="#carousel-notification-slide-box"
                                        data-slide-to="<?php echo $slide; ?>"
                                        class="active"></li>
                                <?php elseif (($slidekey % 4) == 0): ?>
                                    <li data-target="#carousel-notification-slide-box"
                                        data-slide-to="<?php echo $slide; ?>"></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ol>
                    <div class="carousel-inner"
                         role="listbox">
                        <?php if (count($notifications_apps) > 0): ?>
                        <?php foreach ($notifications_apps

                            as $key => $app): ?>

                        <?php if ($key == 0): ?>
                        <div class="item active">
                            <div class="row">
                            <?php elseif (($key % 4) == 0): ?>
                            <div class="item">
                                <div class="row">
                                <?php endif; ?>
                                <div class="col-lg-3 col-xs-6">
                                    <?php $row = $key + 1; ?>
                                    <?php if ($key == 0 || ($row % 5) == 0): ?>
                                    <div class="small-box bg-red">
                                    <?php elseif ($key == 1 || ($row % 6) == 0): ?>
                                    <div class="small-box bg-yellow">
                                    <?php elseif ($key == 2 || ($row % 7) == 0): ?>
                                    <div class="small-box bg-green">
                                    <?php else: ?>
                                    <div class="small-box bg-blue">
                                    <?php endif; ?>
                                        <div class="inner">
                                            <h3> <?php echo $app->notification_count ?> </h3>
                                            <p> <?php echo $app->label_name; ?> </p>
                                        </div>
                                        <div class="icon">
                                            <i class="<?php echo $app->app_icon; ?>"></i>
                                        </div>

                                        <!-- <div class="box-body"> -->
                                        <a class="small-box-footer"
                                           data-toggle="collapse"
                                           href="#collapseExample<?php echo $row; ?>"
                                           role="button"
                                           aria-expanded="false"
                                           aria-controls="collapseExample">
                                            More info <i class="fa fa-arrow-circle-right"></i>
                                        </a>
                                    </div>
                                </div>

                                                <?php if ($key != 0 && ($key % 4) == 3): ?>
                                    </div>
                                        </div>
                                    <?php endif; ?>

                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>

                                </div>
<!--                                <a class="left carousel-control"-->
<!--                                   href="#carousel-notification-slide"-->
<!--                                   data-slide="prev"-->
<!--                                   style="margin-left: -100px;">-->
<!--                                    <span class="fa fa-angle-left"></span>-->
<!--                                </a>-->
<!--                                <a class="right carousel-control"-->
<!--                                   href="#carousel-notification-slide"-->
<!--                                   data-slide="next"-->
<!--                                   style="margin-right: -80px;">-->
<!--                                    <span class="fa fa-angle-right"></span>-->
<!--                                </a>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



