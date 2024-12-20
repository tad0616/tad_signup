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
        <i class="fa fa-calendar-check" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_END_DATE_COL}><{$smarty.const._TAD_FOR}><{$action.end_date}>
        <i class="fa fa-users" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_APPLY_MAX}><{$smarty.const._TAD_FOR}><{$action.number}>
        <{if $action.candidate|default:false}><span data-toggle="tooltip" title="<{$smarty.const._MD_TAD_SIGNUP_CANDIDATES_QUOTA}>">(<{$action.candidate}>)</span><{/if}>
    </small>
</h3>

<form action="index.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal">

    <div class="alert alert-success">
        <{$signup_form|default:''}>
    </div>
    <{$token_form|default:''}>
    <input type="hidden" name="op" value="<{$next_op|default:''}>">
    <input type="hidden" name="id" value="<{$id|default:''}>">
    <input type="hidden" name="action_id" value="<{$action.id}>">
    <input type="hidden" name="uid" value="<{$uid|default:''}>">
    <div class="bar">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save" aria-hidden="true"></i> <{$smarty.const._TAD_SAVE}>
        </button>
    </div>
</form>

<{if $smarty.session.can_add|default:false}>
    <div class="bar">
        <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_actions_edit&id=<{$action.id}>" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_EDIT_ACTION}></a>
    </div>
<{/if}>