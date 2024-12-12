<{if $block.id|default:false}>
    <h2 class="my">
        <{if $block.enable|default:false && ($block.number + $block.candidate) > $block.signup_count && $block.end_date|strtotime >= $smarty.now}>
            <i class="fa fa-check text-success" aria-hidden="true"></i>
        <{else}>
            <i class="fa fa-times text-danger" aria-hidden="true"></i>
        <{/if}>
        <{$block.title|default:''}>
    </h2>

    <{if $block.detail|default:false}>
        <div class="alert alert-info">
            <{$block.detail|default:''}>
        </div>
    <{/if}>

    <h4 class="my">
        <small>
            <div><i class="fa fa-calendar" aria-hidden="true"></i> <{$smarty.const._MB_TAD_SIGNUP_ACTION_DATE}><{$smarty.const._TAD_FOR}><{$block.action_date|default:''|substr:0:-3}></div>
            <div><i class="fa fa-calendar-check" aria-hidden="true"></i> <{$smarty.const._MB_TAD_SIGNUP_END_DATE}><{$smarty.const._TAD_FOR}><{$block.end_date|default:''|substr:0:-3}></div>
            <div>
                <i class="fa fa-users" aria-hidden="true"></i> <{$smarty.const._MB_TAD_SIGNUP_STATUS}><{$smarty.const._TAD_FOR}><{$block.signup_count|default:0}>/<{$block.number|default:0}>
                <{if $block.candidate|default:false}><span data-toggle="tooltip" title="<{$smarty.const._MB_TAD_SIGNUP_CANDIDATES_QUOTA}>">(<{$block.candidate|default:''}>)</span><{/if}>
            </div>
        </small>
    </h4>

    <div class="text-center my-3">
        <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_data_create&action_id=<{$block.id|default:''}>" class="btn btn-lg btn-info <{if !($block.enable|default:false && ($block.number + $block.candidate) > $block.signup_count && $xoops_isuser|default:false && $block.end_date|strtotime >= $smarty.now)}>disabled<{/if}>"><i class="fa fa-plus" aria-hidden="true"></i> <{$smarty.const._MB_TAD_SIGNUP_APPLY_NOW}></a>
    </div>
<{/if}>