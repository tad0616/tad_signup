<h2 class="my"><{$smarty.const._MD_TAD_SIGNUP_IMPORT}> <{$action.title}> <{$smarty.const._MD_TAD_SIGNUP_DATA_PREVIEW}></h2>
<div class="alert alert-info">
    可報名人數為 <{$action.number}> 人，候補人數 <{$action.candidate}> 人，目前匯入人數共 <{if $preview_data|default:false}><{$preview_data|@count}><{else}>0<{/if}> 人
</div>
<form action="index.php" method="post" id="myForm">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <{foreach from=$head item=title}>
                    <th><{$title|default:''}></th>
                <{/foreach}>
            </tr>
        </thead>
        <tbody>
            <{foreach from=$preview_data key=i item=data name=preview_data}>
                <{if $smarty.foreach.preview_data.iteration > 1}>
                    <tr>
                        <{foreach from=$data key=j item=val}>
                            <{assign var="title" value=$head.$j}>
                            <{assign var="input_type" value=$type.$j}>
                            <{assign var="options_arr" value=$options.$j}>
                            <{if $title!=''}>
                                <td>
                                    <{if $input_type=="checkbox"}>
                                        <{assign var="val_arr" value='|'|explode:$val}>
                                        <{foreach from=$options_arr item=option}>
                                            <div class="form-check-inline checkbox-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="tdc[<{$i|default:''}>][<{$title|default:''}>][]" value="<{$option|default:''}>" <{if $option|in_array:$val_arr}>checked<{/if}>>
                                                    <{$option|default:''}>
                                                </label>
                                            </div>
                                        <{/foreach}>
                                    <{elseif $input_type=="radio"}>
                                        <{foreach from=$options_arr item=option}>
                                            <div class="form-check-inline radio-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="tdc[<{$i|default:''}>][<{$title|default:''}>]" value="<{$option|default:''}>" <{if $option==$val}>checked<{/if}>>
                                                    <{$option|default:''}>
                                                </label>
                                            </div>
                                        <{/foreach}>
                                    <{elseif $input_type=="select"}>
                                        <select name="tdc[<{$i|default:''}>][<{$title|default:''}>]" class="form-control validate[required]">
                                            <{foreach from=$options_arr item=option}>
                                                <option value="<{$option|default:''}>" <{if $option==$val}>selected<{/if}>><{$option|default:''}></option>
                                            <{/foreach}>
                                        </select>
                                    <{elseif $input_type=="const" || $input_type=="hidden"}>
                                        <input type="hidden" name="tdc[<{$i|default:''}>][<{$title|default:''}>]" value="<{$val|default:''}>"><{$val|default:''}>
                                    <{else}>
                                        <input type="text" name="tdc[<{$i|default:''}>][<{$title|default:''}>]" value="<{$val|default:''}>" class="form-control form-control-sm">
                                    <{/if}>
                                </td>
                            <{/if}>
                        <{/foreach}>
                    </tr>
                <{/if}>
            <{/foreach}>
        </tbody>
    </table>

    <{$token_form|default:''}>
    <input type="hidden" name="id" value="<{$action.id}>">
    <input type="hidden" name="op" value="tad_signup_data_import_csv">
    <div class="bar">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_IMPORT}> CSV
        </button>
    </div>
</form>