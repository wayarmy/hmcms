	<nav class="topbar navbar" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#topnavbar">
                <span class="sr-only">Toggle navigation</span> 
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="topnavbar">
            <ul class="nav navbar-nav">
               <?php hm_admin_topnavbar(); ?>
            </ul>
			
            <ul class="nav navbar-nav navbar-right">
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                    class="glyphicon glyphicon-user"></span><?php echo user_name(); ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
							<a href="?run=user.php&action=edit">
								<span class="glyphicon glyphicon-cog"></span><?php echo hm_lang('account'); ?>
							</a>
						</li>
                        <li class="divider"></li>
                        <li>
							<a href="?run=login.php&action=logout">
								<span class="glyphicon glyphicon-off"></span><?php echo hm_lang('log_out'); ?>
							</a>
						</li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>