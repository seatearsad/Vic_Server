<div id="navbar" class="navbar navbar-default">
	<div class="navbar-container" id="navbar-container">
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
			<span class="sr-only">Toggle sidebar</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<div class="navbar-header pull-left">
			<a href="{pigcms{:U('Index/index')}" class="navbar-brand" style="padding: 5px 0 0 0;"> 
				<small> 
					<img src="{pigcms{$config.site_merchant_logo}" style="height:38px;width:38px;"/> {pigcms{$config.site_name} - {pigcms{:L('MERCHANT_BACKEND_BKADMIN')}
				</small>
			</a>
		</div>
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">

				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle"> 
						<img class="nav-user-photo" src="{pigcms{$static_public}images/user.jpg" alt="Jason&#39;s Photo" /> 
						<span class="user-info"> <small>{pigcms{:L('WELCOME_BKADMIN')}</small> {pigcms{$merchant_session.name}</span>
						<i class="ace-icon fa fa-caret-down"></i>
					</a>
					<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="{pigcms{$config.site_url}" target="_blank">
								<i class="ace-icon fa fa-link"></i> {pigcms{:L('HOME_BKADMIN')}
							</a>
						</li>
						<!--li>
							<a href="#">
								<i class="ace-icon fa fa-share-alt"></i> 推荐好友
							</a>
						</li-->
						<li>
							<a href="{pigcms{:U('Config/merchant')}">
								<i class="ace-icon fa fa-user"></i> {pigcms{:L('BASIC_INFO_BKADMIN')}
							</a>
						</li>
						<!--li>
							<a href="{pigcms{:U('Pay/index')}"> 
								<i class="ace-icon fa fa-smile-o"></i> 对帐平台
							</a>
						</li-->
						<li class="divider"></li>
						<li>
							<a href="{pigcms{:U('Login/logout')}"> 
								<i class="ace-icon fa fa-power-off"></i> {pigcms{:L('EXIT_BKADMIN')}
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>