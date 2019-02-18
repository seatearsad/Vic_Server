<link href="{pigcms{$static_path}css/footer.css" rel="stylesheet"/>
	<style>
		.footermenu ul{background-color:#404a54;}
		.footermenu ul li a{color:#fff;}
		.footermenu ul li a.active{background-color:#2A3138;}
	</style>
	<footer class="footermenu">
		<ul>
            <li>
                <a <if condition="ACTION_NAME eq 'manage' OR ACTION_NAME eq 'manage_time'">class="active"</if> href="{pigcms{:U('Storestaff/manage')}">
                <img src="{pigcms{$static_path}images/Lngjm86JQq.png" />
                <p>{pigcms{:L('_STORE_MANAGE_')}</p>
                </a>
            </li>
			<li>
				<a <if condition="ACTION_NAME eq 'meal_list' OR ACTION_NAME eq 'meal_edit'">class="active"</if> href="{pigcms{:U('Storestaff/meal_list')}">
				<img src="{pigcms{$static_path}images/s22KaR0Wtc.png"/>
				<p>
				<if condition="$language eq 'en'">
					<if condition="$config.meal_alias_name eq '到店'">Dine</if>
				</if>
				<if condition="$language eq 'cn'">{pigcms{$config.meal_alias_name}</if>
				</p>
				</a>
			</li>
			<li>
				<a <if condition="ACTION_NAME eq 'shop_list' OR ACTION_NAME eq 'shop_edit'">class="active"</if> href="{pigcms{:U('Storestaff/shop_list')}">
				<img src="{pigcms{$static_path}images/s22KaR0Wtc.png"/>
				<p>
				<if condition="$language eq 'en'">
					<if condition="$config.shop_alias_name eq '外卖'">Delivery</if>
				</if>
				<if condition="$language eq 'cn'">{pigcms{$config.shop_alias_name}</if>
				</p>
				</a>
			</li>
			<if condition="isset($config['appoint_page_row'])">
				<li>
					<a <if condition="ACTION_NAME eq 'appoint_list' OR ACTION_NAME eq 'appoint_edit'">class="active"</if> href="{pigcms{:U('Storestaff/appoint_list')}">
					<img src="{pigcms{$static_path}images/3YQLfzfuGx.png"/>
					<p>
					<if condition="$language eq 'cn'">预约</if>
					<if condition="$language eq 'en'">Reservation</if>
					</p>
					</a>
				</li>
			</if>
			<!--li>
				<a id="qrcode_btn">
					<img src="{pigcms{$static_path}images/qrcode.png"/>
					<p>
					<if condition="$language eq 'cn'">扫一扫</if>
					<if condition="$language eq 'en'">Scan</if>
					</p>
				</a>
			</li-->
			<li>
				<a href="javascript:;" onclick="LogOutSys()" <if condition="ACTION_NAME eq 'logout'">class="active"</if> >
				<img src="{pigcms{$static_path}images/J0uZbXQWvJ.png"/>
				<p>
				<if condition="$language eq 'cn'">退出</if>
				<if condition="$language eq 'en'">Exit</if>
				</p>
				</a>
			</li>
		</ul>
	<script type="text/javascript">
	var logoutURl="{pigcms{:U('Storestaff/logout')}"
	function LogOutSys(){
		if(confirm('您确认要退出系统吗？')){
			window.location.href=logoutURl;
		}
	}
	</script>
	</footer>
<div style="display:none;">{pigcms{$config.wap_site_footer}</div>