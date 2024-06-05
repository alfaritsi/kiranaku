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
				<div id="carousel-notification-slide"
					 class="carousel slide"
					 data-ride="carousel">
					<ol class="carousel-indicators">
						<?php if (count($notifications_cats) > 0): ?>
							<?php foreach ($notifications_cats as $slidekey => $cat): ?>
								<?php if ($slidekey > 0): ?>
									<?php $slide = floor($slidekey / 4); ?>
								<?php else: ?>
									<?php $slide = 0; ?>
								<?php endif; ?>
								<?php if ($slidekey == 0): ?>
									<li data-target="#carousel-notification-slide"
										data-slide-to="<?php echo $slide; ?>"
										class="active"></li>
								<?php elseif (($slidekey % 4) == 0): ?>
									<li data-target="#carousel-notification-slide"
										data-slide-to="<?php echo $slide; ?>"
										class=""></li>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</ol>
					<div class="carousel-inner">
						<?php if (count($notifications_cats) > 0): ?>
						<?php foreach ($notifications_cats

							as $key => $cat): ?>
						<?php $box_key = $key + 1; ?>
						<?php if ($key == 0): ?>
						<div class="item active">
							<div class="row">
								<?php elseif ($key % 4 == 0): ?>
								<div class="item">
									<div class="row">
										<?php endif; ?>
										<div class="col-lg-3 col-xs-6">
											<?php if ($key % 4 == 0): $background = "bg-red"; ?>
											<?php elseif ($key % 4 == 1): $background = "bg-yellow"; ?>
											<?php elseif ($key % 4 == 2): $background = "bg-green"; ?>
											<?php else: $background = "bg-blue"; ?>
											<?php endif; ?>
											<div class="small-box <?php echo $background; ?>">
												<div class="inner">
													<a data-toggle="tooltip"
													   class="badge" style="background-color: white; color: #333; font-size: inherit;"
													   href="<?php echo($cat->notification_url_all ? $cat->notification_url_all : 'javascript:void(0)'); ?>">New
													</a>
													<h5 title="<?php echo $cat->notification_count; ?> New <?php echo $cat->category_name; ?>"
														style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 15%;"> <?php echo $cat->category_name ?> </h5>
													<p> <?php echo $cat->app_name; ?> </p>
												</div>
												<div class="icon">
													<i class="<?php echo $cat->app_icon; ?>"></i>
												</div>

												<a class="small-box-footer more-info"
												   href="javascript:void(0)"
												   data-title="<?php echo $cat->app_name; ?> - <?php echo $cat->category_name; ?>"
												   data-notif='<?php echo TRIM(json_encode($cat->notifications)); ?>'>
													More info <i class="fa fa-arrow-circle-right"></i>
												</a>

											</div>
										</div>

										<?php if ($key % 4 == 3): ?>
									</div>
								</div>
							<?php endif; ?>

								<?php endforeach; ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>


			<script type="text/javascript">
				$(document).ready(function () {
					$('.carousel').carousel({
						interval: 5000
					})
				});
			</script>


