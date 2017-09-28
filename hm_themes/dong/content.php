<?php get_template_part('header'); ?>
        <div class="row">
            <div class="col-lg-12">
				<div class="row">
					<div class="col-md-9">
						<div class="main_content">
							
							<div class="post-content">
								<h1 class="post-title"><?php echo get_con_val("name=name&id=$id"); ?></h1>
								<article>
									<?php echo get_con_val("name=content&id=$id"); ?>
								</article>
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
