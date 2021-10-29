<h2 class="my"><{$smarty.const._MD_TAD_SIGNUP_ACTION_SETTING}></h2>

<form action="index.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal">

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TAD_SIGNUP_TITLE}>
        </label>
        <div class="col-sm-10">
            <input type="text" name="title" id="title" class="form-control validate[required]" value="<{$title}>" placeholder="<{$smarty.const._MD_TAD_SIGNUP_KEYIN}><{$smarty.const._MD_TAD_SIGNUP_TITLE}>">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TAD_SIGNUP_DETAIL}>
        </label>
        <div class="col-sm-10">
            <{$editor}>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TAD_SIGNUP_END_DATE_COL}>
        </label>
        <div class="col-sm-10">
            <input type="text" name="end_date" id="end_date" class="form-control validate[required]" value="<{$end_date}>" placeholder="<{$smarty.const._MD_TAD_SIGNUP_KEYIN}><{$smarty.const._MD_TAD_SIGNUP_END_DATE_COL}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00' , startDate:'%y-%M-%d %H:%m:%s}'})">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TAD_SIGNUP_ACTION_DATE}>
        </label>
        <div class="col-sm-10">
            <input type="text" name="action_date" id="action_date" class="form-control validate[required]" value="<{$action_date}>" placeholder="<{$smarty.const._MD_TAD_SIGNUP_KEYIN}><{$smarty.const._MD_TAD_SIGNUP_ACTION_DATE}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:00' , startDate:'%y-%M-%d %H:%m:%s}'})">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TAD_SIGNUP_NUMBER}>
        </label>
        <div class="col-sm-10">
            <input type="number" name="number" id="number" class="form-control validate[required]" value="<{$number}>" placeholder="<{$smarty.const._MD_TAD_SIGNUP_KEYIN}><{$smarty.const._MD_TAD_SIGNUP_NUMBER}>">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TAD_SIGNUP_CANDIDATES_QUOTA}>
        </label>
        <div class="col-sm-10">
            <input type="number" name="candidate" id="candidate" class="form-control validate[required]" value="<{$candidate}>" placeholder="<{$smarty.const._MD_TAD_SIGNUP_KEYIN}><{$smarty.const._MD_TAD_SIGNUP_CANDIDATES_QUOTA}>">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <div><{$smarty.const._MD_TAD_SIGNUP_SETUP}></div>
            <div onClick="popupwindow('eguide_code.php', 'eguide code', 1024, 600)" style="cursor: pointer; color: rgb(45, 167, 110);">example</div>
            <div onClick="popupwindow('eguide_editor.php', 'eguide editor', 1024, 600)" style="cursor: pointer; color: rgb(102, 19, 128);"><i class="fa fa-pencil" aria-hidden="true"></i> <{$smarty.const._MD_TAD_SIGNUP_EDITOR}></div>
        </label>
        <div class="col-sm-10">
            <textarea name="setup" id="setup" class="form-control validate[required]" placeholder="<{$smarty.const._MD_TAD_SIGNUP_KEYIN}><{$smarty.const._MD_TAD_SIGNUP_SETUP}>"><{$setup}></textarea>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TAD_SIGNUP_ENABLE}>
        </label>
        <div class="col-sm-10" style="padding-top: 8px;">
            <div class="form-check-inline radio-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="enable" value="1" <{if $enable=='1'}>checked<{/if}>>
                    <{$smarty.const._YES}>
                </label>
            </div>
            <div class="form-check-inline radio-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="enable" value="0" <{if $enable=='0'}>checked<{/if}>>
                    <{$smarty.const._NO}>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TAD_SIGNUP_UPLOADS}>
        </label>
        <div class="col-sm-10">
            <{$upform}>
        </div>
    </div>

    <{$token_form}>
    <input type="hidden" name="uid" value="<{$uid}>">
    <input type="hidden" name="id" value="<{$id}>">
    <input type="hidden" name="op" value="<{$next_op}>">
    <div class="bar">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save" aria-hidden="true"></i> <{$smarty.const._TAD_SAVE}>
        </button>
    </div>
</form>

<script language="javascript">

    function popupwindow(url, title, w, h) {
        const left = (screen.width/2)-(w/2);
        const top = (screen.height/2)-(h/2);
        var setupText = $('#setup').val();
        setupText = setupText.replace('+', '%2B');
        const toUrl = url+'?setup='+encodeURIComponent(setupText);
        return window.open(toUrl, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    }
</script>
