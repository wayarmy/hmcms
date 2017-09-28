<?php get_template_part('header'); ?>
        <div class="row">
            <div class="col-lg-12">
				<div class="row">
					<div class="col-md-9">
						<div class="main_content">
						<?php
							foreach( query_content() as $cid ){
								$name = get_con_val("name=name&id=$cid");
								$link = request_uri("type=content&id=$cid");
								$description = get_con_val("name=description&id=$cid");
								$public_time = get_con_val("name=public_time&id=$cid");
								$content_thumbnail = get_con_val("name=content_thumbnail&id=$cid");
								$img = create_image("file=$content_thumbnail&w=300&h=200");
						?>
							<div class="row content_item">
								<div class="col-md-3">
									<a href="<?php echo $link; ?>" title="<?php echo $name; ?>">
										<img src="<?php echo $img; ?>"/>
									</a>
								</div>
								<div class="col-md-9">
									<h2>
										<a href="<?php echo $link; ?>" title="<?php echo $name; ?>" rel="bookmark"><?php echo $name; ?></a>
									</h2>
									<p><?php echo $description; ?></p>
									<p><span class="post_time"><?php echo date('d-m-Y',$public_time); ?></p>
								</div>
							</div>
						<?php
							}
						?>	
							<div class="pagination_wrapper">
								<?php pagination(); ?>
							</div>
							
						</div>
					</div>
					<div class="col-md-3">
						<?php get_template_part('sidebar'); ?>	
					</div>
				</div>
            </div>
        </div>
        <!-- /.row -->
<?php get_template_part('footer'); ?>		
