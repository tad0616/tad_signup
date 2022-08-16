<h2 class="my">
    <{if $enable && ($number + $candidate) > $signup_count && $end_date|strtotime >= $smarty.now}>
        <i class="fa fa-check text-success" aria-hidden="true"></i>
    <{else}>
        <i class="fa fa-times text-danger" aria-hidden="true"></i>
    <{/if}>
    <{$title}>
    <small><i class="fa fa-calendar" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_ACTION_DATE}><{$smarty.const._TAD_FOR}><{$action_date}></small>
</h2>

<div class="alert alert-info">
    <{$detail}>
</div>

<!-- AddToAny BEGIN -->
<div class="a2a_kit a2a_kit_size_32 a2a_default_style">
    <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
    <a class="a2a_button_facebook"></a>
    <a class="a2a_button_printfriendly"></a>
</div>
<script async src="https://static.addtoany.com/menu/page.js"></script>
<!-- AddToAny END -->

<{$files}>

<h3 class="my">
    <{$smarty.const._MD_TAD_SIGNUP_APPLIED_DATA}>
    <small>
        <i class="fa fa-calendar-check-o" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_END_DATE_COL}><{$smarty.const._TAD_FOR}><{$end_date}>
        <i class="fa fa-users" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_APPLY_MAX}><{$smarty.const._TAD_FOR}><{$number}>
        <{if $candidate}><span data-toggle="tooltip" title="<{$smarty.const._MD_TAD_SIGNUP_CANDIDATES_QUOTA}>">(<{$candidate}>)</span><{/if}>
    </small>
</h3>

<{if $smarty.session.can_add && $uid == $now_uid }>
    <div class="bar">
        <a href="javascript:del_action('<{$id}>')" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_DESTROY_ACTION}></a>
        <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_actions_edit&id=<{$id}>" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_EDIT_ACTION}></a>
        <a href="<{$xoops_url}>/modules/tad_signup/html.php?id=<{$id}>" class="btn btn-primary"><i class="fa fa-html5" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_EXPORT_HTML}></a>

        <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_data_pdf_setup&id=<{$id}>" class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_EXPORT_SIGNIN_TABLE}></a>
    </div>
<{/if}>


<table class="table" data-toggle="table" data-pagination="true" data-search="true" data-mobile-responsive="true">
    <thead>
        <tr>
            <{foreach from=$titles item=col_name name=tdc}>
                <th data-sortable="true" nowrap class="c"><{$col_name}></th>
            <{/foreach}>
            <th data-sortable="true" nowrap class="c"><{$smarty.const._MD_TAD_SIGNUP_ACCEPT}></th>
            <th data-sortable="true" nowrap class="c"><{$smarty.const._MD_TAD_SIGNUP_APPLY_DATE}></th>
        </tr>
    </thead>
    <tbody>
        <{foreach from=$signup item=signup_data}>
            <tr>
                <{foreach from=$titles item=col_name}>
                    <{assign var=user_data value=$signup_data.tdc.$col_name}>
                    <td>
                        <{if ($smarty.session.can_add && $uid == $now_uid) || $signup_data.uid == $now_uid}>
                            <{foreach from=$user_data item=data}>
                                <div>
                                    <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_data_show&id=<{$signup_data.id}>"><{$data}></a>
                                </div>
                            <{/foreach}>
                        <{else}>
                            <{if strpos($col_name, $smarty.const._MD_TAD_SIGNUP_NAME)!==false}>
                                <{foreach from=$user_data item=data}>
                                    <{if preg_match("/[a-z]/i", $data)}>
                                        <div><{$data|regex_replace:"/[a-z]/":'*'}></div>
                                    <{else}>
                                        <div><{$data|substr_replace:'O':3:3}></div>
                                    <{/if}>
                                <{/foreach}>
                            <{else}>
                                <div>****</div>
                            <{/if}>
                        <{/if}>
                    </td>
                <{/foreach}>

                <td>
                    <{if $signup_data.accept==='1'}>
                        <div class="text-primary">
                            <{if $smarty.session.can_add && $uid == $now_uid}>
                                <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-warning" data-toggle="tooltip" title="<{$smarty.const._MD_TAD_SIGNUP_CHANGE_TO}><{$smarty.const._MD_TAD_SIGNUP_NOT_ACCEPT}>"><i class="fa fa-times" aria-hidden="true"></i></a>
                            <{/if}>
                            <{$smarty.const._MD_TAD_SIGNUP_ACCEPT}>
                        </div>
                    <{elseif $signup_data.accept==='0'}>
                        <div class="text-danger">
                            <{if $smarty.session.can_add && $uid == $now_uid}>
                                <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-warning" data-toggle="tooltip" title="<{$smarty.const._MD_TAD_SIGNUP_CHANGE_TO}><{$smarty.const._MD_TAD_SIGNUP_ACCEPT}>"><i class="fa fa-check" aria-hidden="true"></i></a>
                            <{/if}>
                            <{$smarty.const._MD_TAD_SIGNUP_NOT_ACCEPT}>
                        </div>
                    <{else}>
                        <div class="text-muted"><{$smarty.const._MD_TAD_SIGNUP_ACCEPT_NOT_YET}></div>
                        <{if $smarty.session.can_add && $uid == $now_uid}>
                            <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=0" class="btn btn-sm btn-danger" data-toggle="tooltip" title="<{$smarty.const._MD_TAD_SIGNUP_NOT_ACCEPT}>"><i class="fa fa-times" aria-hidden="true"></i></a>
                            <a href="<{$xoops_url}>/modules/tad_signup/index.php?op=tad_signup_data_accept&id=<{$signup_data.id}>&action_id=<{$id}>&accept=1" class="btn btn-sm btn-success" data-toggle="tooltip" title="<{$smarty.const._MD_TAD_SIGNUP_ACCEPT}>"><i class="fa fa-check" aria-hidden="true"></i></a>
                        <{/if}>
                    <{/if}>
                </td>
                <td>
                    <{$signup_data.signup_date}>
                    <{if $signup_data.tag}>
                        <div><span class="badge badge-primary bg-primary"><{$signup_data.tag}></span></div>
                    <{/if}>
                </td>
            </tr>
        <{/foreach}>
    </tbody>
</table>

<!-- 選項統計 -->
<table class="table table-sm table-bordered">
    <tbody>
        <tr>
            <{foreach from=$statistics key=title item=options}>
                <td>
                    <b><{$title}></b>
                    <hr class="my-1">
                    <{foreach from=$options key=i item=count name=options}>
                        <div><{$i}> : <{$count}></div>
                    <{/foreach}>
                </td>
            <{/foreach}>
        </tr>
    </tbody>
</table>



<{if $smarty.session.can_add && $uid == $now_uid}>
    <div class="bar">
        <{if $signup}>
            <div class="input-group">
                <div class="input-group-prepend input-group-addon">
                    <span class="input-group-text"><{$smarty.const._MD_TAD_SIGNUP_EXPORT_APPLY_LIST}><{$smarty.const._TAD_FOR}></span>
                </div>
                <div class="input-group-append input-group-btn">
                    <a href="<{$xoops_url}>/modules/tad_signup/csv.php?id=<{$id}>&type=signup" class="btn btn-info"><i class="fa fa-file-text-o" aria-hidden="true"></i> CSV</a>
                </div>
                <div class="input-group-append input-group-btn">
                    <a href="<{$xoops_url}>/modules/tad_signup/excel.php?id=<{$id}>&type=signup" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                </div>
                <div class="input-group-append input-group-btn">
                    <a href="<{$xoops_url}>/modules/tad_signup/pdf.php?id=<{$id}>" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                </div>
                <div class="input-group-append input-group-btn">
                    <a href="<{$xoops_url}>/modules/tad_signup/word.php?id=<{$id}>" class="btn btn-primary"><i class="fa fa-file-word-o" aria-hidden="true"></i> Word</a>
                </div>
            </div>
        <{/if}>
        <form action="index.php" method="post" class="my-4" enctype="multipart/form-data">
            <div class="input-group">
                <div class="input-group-prepend input-group-addon">
                    <span class="input-group-text"><{$smarty.const._MD_TAD_SIGNUP_IMPORT_APPLY_LIST}> CSV</span>
                </div>
                <input type="file" name="csv" class="form-control" accept="text/csv">
                <div class="input-group-append input-group-btn">
                    <input type="hidden" name="id" value="<{$id}>">
                    <input type="hidden" name="op" value="tad_signup_data_preview_csv">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TAD_SIGNUP_IMPORT}> CSV</button>
                    <a href="<{$xoops_url}>/modules/tad_signup/csv.php?id=<{$id}>" class="btn btn-secondary"><i class="fa fa-file-text-o" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_DOWNLOAD}> CSV <{$smarty.const._MD_TAD_SIGNUP_IMPORT_FILE}></a>
                </div>
            </div>
        </form>

        <form action="index.php" method="post" class="my-4" enctype="multipart/form-data">
            <div class="input-group">
                <div class="input-group-prepend input-group-addon">
                    <span class="input-group-text"><{$smarty.const._MD_TAD_SIGNUP_IMPORT_APPLY_LIST}> Excel</span>
                </div>
                <input type="file" name="excel" class="form-control" accept=".xlsx">
                <div class="input-group-append input-group-btn">
                    <input type="hidden" name="id" value="<{$id}>">
                    <input type="hidden" name="op" value="tad_signup_data_preview_excel">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TAD_SIGNUP_IMPORT}> Excel</button>
                    <a href="<{$xoops_url}>/modules/tad_signup/excel.php?id=<{$id}>" class="btn btn-secondary"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_DOWNLOAD}> Excel <{$smarty.const._MD_TAD_SIGNUP_IMPORT_FILE}></a>
                </div>
            </div>
        </form>
    </div>
<{/if}>
