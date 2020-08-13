<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Shop/cat_store')}" frame="true" refresh="true">
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <input type="hidden" name="cat_id" value="{pigcms{$cat_id}"/>
        <input type="hidden" name="cat_fid" value="{pigcms{$cat_fid}"/>
        <volist name="store_list" id="vo">
        <tr>
            <th>
                {pigcms{$vo.name}
            </th>
            <td>
                <input type="text" name="cat_sort_{pigcms{$vo.store_id}" value="{pigcms{$vo.cat_sort}" />
            </td>
        </tr>
        </volist>
    </table>
    <div class="btn hidden">
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
        <input type="reset" value="取消" class="button" />
    </div>
</form>
<include file="Public:footer"/>