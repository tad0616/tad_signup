<h2 class="my">
    <{if $action.enable|default:false}>
        <i class="fa fa-check text-success" aria-hidden="true"></i>
    <{else}>
        <i class="fa fa-times text-danger" aria-hidden="true"></i>
    <{/if}>
    <{$action.title}>
    <small><i class="fa fa-calendar" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_ACTION_DATE}><{$smarty.const._TAD_FOR}><{$action.action_date}></small>
</h2>

<div class="alert alert-info">
    <{$action.detail}>
</div>

<h3 class="my">
    <{$smarty.const._MD_TAD_SIGNUP_APPLY_FORM}>
    <small>
        <i class="fa fa-calendar-check-o" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_END_DATE_COL}><{$smarty.const._TAD_FOR}><{$action.end_date}>
        <i class="fa fa-users" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_APPLY_MAX}><{$smarty.const._TAD_FOR}><{$action.number}>
        <{if $action.candidate|default:false}><span data-toggle="tooltip" title="<{$smarty.const._MD_TAD_SIGNUP_CANDIDATES_QUOTA}>">(<{$action.candidate}>)</span><{/if}>
    </small>
</h3>

<table class="table">
    <{foreach from=$tdc key=title item=signup name=tdc}>
        <tr>
            <th><{$title|default:''}></th>
            <td>
                <{foreach from=$signup key=i item=val name=signup}>
                    <div><{$val|default:''}></div>
                <{/foreach}>
            </td>
        </tr>
    <{/foreach}>
</table>

<{if $can_add || $uid == $now_uid}>
    <div class="bar">
        <a href="javascript:del_data('<{$id|default:''}>')" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_DESTROY_APPLY}></a>
        <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_data_edit&id=<{$id|default:''}>&action_id=<{$action_id|default:''}>" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_EDIT_APPLY}></a>
    </div>
<{/if}>