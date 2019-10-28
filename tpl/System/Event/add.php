<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Event/modify')}" enctype="multipart/form-data">
		<input name="event_id" value="{pigcms{$event.id|default='0'}" type="hidden">
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">活动名称</th>
				<td>
                    <input type="text" class="input fl" name="name" size="20" validate="maxlength:200,required:true" value="{pigcms{$event.name|default=''}" />
                </td>
			</tr>
            <tr>
                <th width="80">活动描述</th>
                <td>
                    <textarea name="desc" validate="required:true">{pigcms{$event.desc|default=''}</textarea>
                </td>
            </tr>
			<tr>
				<th width="80">活动类型</th>
				<td>
                    <select name="type">
                        <volist name="type" id="vo">
                            <if condition="$i gt 1">
                                <option value="{pigcms{$i-1}" <if condition="$event.type eq ($i-1)">selected</if>>{pigcms{$vo}</option>
                            </if>
                        </volist>
                    </select>
                </td>
			</tr>
			<tr>
				<th width="80">开始时间</th>
				<td>
                    <input type="text" class="input fl" name="begin_time" style="width:120px;" id="d4311" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'d4312\')}'})" <if condition="$event['begin_time'] neq 0">value="{pigcms{$event.begin_time|date='Y-m-d',###}"</if>/>
                    <span id="clear_begin">清空</span>
                </td>
			</tr>
			<tr>
				<th width="80">结束时间</th>
				<td>
                    <input type="text" class="input fl" name="end_time" style="width:120px;" id="d4312" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',minDate:'#F{$dp.$D(\'d4311\')}'})" <if condition="$event['end_time'] neq 0">value="{pigcms{$event.end_time|date='Y-m-d',###}"</if>/>
                    <span id="clear_end">清空</span>
                </td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<style>
    #clear_begin,#clear_end{
        line-height: 30px;
        cursor: pointer;
        color: #0e62cd;
        padding-left: 10px;
    }
</style>

<script>
    $('#clear_begin').click(function () {
        $('input[name=begin_time]').val('');
    });
    $('#clear_end').click(function () {
        $('input[name=end_time]').val('');
    });
</script>
<include file="Public:footer"/>