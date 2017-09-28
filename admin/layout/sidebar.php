		<!-- Sidebar -->

		<div id="sidebar-wrapper">
			<ul id="sidebar_menu" class="sidebar-nav">
				<li class="sidebar-brand"><a id="menu-toggle" href="#"><?php echo SITE_NAME; ?><span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a>
				</li>
			</ul>
			<ul class="sidebar-nav" id="sidebar">
				<?php 
				admin_menu_con_tax();
				admin_menu_user(); 
				
				if(ALLOW_PLUGIN_PAGE == TRUE){
					admin_menu_plugin();
				}
				
				if(ALLOW_THEME_PAGE == TRUE){
					admin_menu_theme();
				}else{
					
					if(ALLOW_MENU_PAGE == TRUE){
						admin_menu_menu(array('level'=>1));
					}
					
					if(ALLOW_BLOCK_PAGE == TRUE){
						admin_menu_block(array('level'=>1));
					}
					
				}

				if(ALLOW_COMMAND_PAGE == TRUE){
					admin_menu_command();
				}
				
				admin_menu_optimize(); 
				
				admin_page();
				?>
			</ul>
		</div>
		
		<!-- Sidebar -->