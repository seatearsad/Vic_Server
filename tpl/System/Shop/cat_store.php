<include file="Public:header"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
            <volist name="store_list" id="vo">
			<tr>
				<th>
                    {pigcms{$vo.name}
                </th>
			</tr>
            </volist>
		</table>
<include file="Public:footer"/>