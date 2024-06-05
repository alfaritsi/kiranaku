<style>
	table.tableTree tbody tr.leaf td {
		position: relative;
		padding: 3px 0 3px 25px;
		display: inline-grid;
		word-break: break-all;
		align-items: center;
	}
</style>


<?php if (count($notifications_apps) == 0): ?>
	<li class="item">
		<div class="well text-center">No notification</div>
	</li>
<?php else: ?>
	<?php foreach ($notifications_apps as $app): ?>
		<li class="item">
			<div class="box">
				<div class="box-header with-border"
					 data-widget="collapse">
					<div class="product-img">
						<div>
							<i class="<?php echo $app->app_icon; ?>"></i>
						</div>
					</div>
					<div class="product-info">
						<a href="javascript:void(0)"
						   class="product-title">
							<?php echo $app->label_name; ?>
						</a>
						<span class="product-description">
							<div class="text-bold"><?php echo $app->app_name; ?></div>
							<em><?php echo $app->notification_count ?>
								notifikasi</em>
						</span>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="display: block;">
					<table class="table tableTree">
						<tbody>
							<?php foreach ($app->categories as $category) : ?>
								<tr data-tt-id="<?php echo $category->notification_category_id; ?>">
									<td>
                                        <a data-toggle="tooltip"
                                             class="badge bg-yellow pull-right"
                                           href="<?php echo $category->notification_url_all; ?>" target="_blank"
                                             data-original-title="<?php echo $category->notification_count; ?> New <?php echo $category->category_name; ?>">
                                            <?php echo $category->notification_count; ?>
                                        </a>
										<div class="branch-wrapper">
											<?php echo $category->category_name; ?>
										</div>
									</td>
								</tr>
								<?php foreach ($category->notifications as $notification) : ?>
									<tr data-tt-id="<?php echo $category->notification_category_id . '.' . $notification->notification_id; ?>"
										data-tt-parent-id="<?php echo $category->notification_category_id; ?>">
										<td><a href="<?php echo $notification->url; ?>"
											   target="_blank"
											   data-toggle="tooltip"
											   data-original-title="<?php echo $notification->notification; ?>"><?php echo $notification->notification; ?></a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endforeach; ?>

						</tbody>
					</table>
				</div>
				<!-- /.box-body -->
			</div>
		</li>
	<?php endforeach; ?>
<?php endif; ?>
