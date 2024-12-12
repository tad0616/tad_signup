<?php
use Xmf\Request;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\Utility;
require_once __DIR__ . '/header.php';

/*-----------變數過濾----------*/
$setup = Request::getText('setup');

if ($setup) {
    $setup = urldecode($setup);
    $TadDataCenter = new TadDataCenter('tad_signup');
    $allCol = '';
    $setups = \explode("\n", $setup);
    $sort = 0;
    foreach ($setups as $i => $setup) {
        foreach ($TadDataCenter->col_kind as $col_kind => $colv) {
            $is_col = 'is' . ucfirst($col_kind);
            $$is_col = '';
        }
        $col = $TadDataCenter->getColSetup($setup);

        $attrs = [];
        if (in_array($col['kind'], ['radio', 'checkbox', 'select'])) {
            $optionsText = implode(',', $col['options']);
        } else {
            unset($col['attrs']['class']);
            foreach ($col['attrs'] as $key => $value) {
                $attrs[] = "{$key}={$value}";
            }
            $optionsText = implode(',', $attrs);
        }

        $checked = $col['require'] ? 'checked' : '';
        $is_col = 'is' . ucfirst($col['kind']);
        $$is_col = 'selected';

        $allCol .= <<<EOF
<tr id="form_data{$i}">
    <td>
        <input type="checkbox" id="require{$i}" value="*" $checked>
    </td>
    <td>
        <input class="form-control form-control-sm" type="text" id="label{$i}" value="{$col['label']}">
    </td>
    <td>
        <select id="form_tag{$i}" class="form-control form-select form-control form-select-sm" placeholder="請輸入type">
            <option value="text" $isText>文字框</option>
            <option value="checkbox" $isCheckbox>複選</option>
            <option value="radio" $isRadio>單選</option>
            <option value="textarea" $isTextarea>大量文字</option>
            <option value="select" $isSelect>下拉選單</option>
            <option value="hidden" $isHidden>隱藏欄位</option>
            <option value="const" $isConst>常數欄位</option>
        </select>
    </td>
    <td>
        <input class="form-control form-control-sm" type="text" id="option{$i}" value="$optionsText">
    </td>
    <td>
        <input class="form-control form-control-sm" type="text" id="value{$i}" value="{$col['value']}">
    </td>
    <td>
        <input class="form-control form-control-sm" type="text" id="help{$i}" value="{$col['help']}">
    </td>
    <td>
        <a href="#" id="{$i}" class="text-danger remove_me">
            <i class="fa fa-times-circle" aria-hidden="true"></i>
        </a>
    </td>
</tr>
EOF;
    }

}

$i++;

if ($setup) {
    $form_index = "var form_index={$i};";
} else {
    $form_index = "
    var form_index=0;
    form_index = clone_form(form_index);
    ";

}

$form = <<<EOF
<script type="text/javascript">

    $(document).ready(function(){
        // $('#sort').sortable({ opacity: 0.6, cursor: 'move'});

        $form_index

        $("#add_form").click(function(){
            form_index = clone_form(form_index);
        });

        $(".remove_me").click(function(){
            $(this).closest("#form_data" + $(this).prop("id")).remove();
        });

    });

    var data_num=$i;

    function clone_form(form_index){

        form_index++;
        data_num++;
        console.log(data_num);
        //複製一份IP設定表單
        $("#new_form").append($("#form_data").clone().prop("id","form_data" + form_index));

        $("#form_data" + form_index + "  input").each(function(){
            // $(this).prop("name",$(this).data("name") + "[" + form_index+"]");
            $(this).prop("id",$(this).prop("id") + form_index);
            $(this).data("id", form_index);
        });

        $("#form_data" + form_index + " select").each(function(){
            $(this).prop("id",$(this).prop("id") + form_index);
        });

        $("#form_data" + form_index + " div").each(function(){
            $(this).prop("id",$(this).prop("id") + form_index);
        });

        $("#form_data" + form_index + " a").each(function(){
            $(this).prop("id",$(this).data("name") + form_index);
        });


        $("#remove_me" + form_index).click(function(){
            $(this).closest("#form_data" + form_index).remove();
        });

        return form_index;
    }


    function getFormData(){
        var setups=[];

        for(var i=0; i<=data_num;i++){
            var require = $('#require' + i).prop("checked");
            var label = $('#label' + i).val();
            var form_tag = $('#form_tag' + i).val();
            var option = $('#option' + i).val();
            var value = $('#value' + i).val();
            var help = $('#help' + i).val();

            if(label !='' && typeof label != "undefined"){
                var setup_text = label;

                if(require){
                    setup_text +=  '*';
                }

                if(form_tag !='' && typeof form_tag != "undefined"){
                    setup_text += ',' + form_tag;
                }

                if(option !='' && typeof option != "undefined"){
                    if(value!='' && typeof value != "undefined"){
                        var defAry = value.split(',');
                        for (var j=0; j<defAry.length; ++j) {
                            option = option.replace( defAry[j], defAry[j] + "+");
                        }
                    }
                    setup_text += ',' + option;
                }

                if(value !='' && typeof value != "undefined" && form_tag=='const' || form_tag=='hidden'){
                    setup_text += ',' + value;
                }

                if(help !='' && typeof help != "undefined"){
                    setup_text += ',#' + help;
                }

                setups.push(setup_text);
            }
        }

        console.log(setups);

        var checkedText=setups.join("\\n");
        // checkedText = checkedText.replace('+', '%2B');
        window.opener.document.getElementById("setup").value = checkedText;
        window.close();
    }


</script>

<table id="new_form" class="table table-sm">
    <thead>
        <tr>
            <th nowrap>必填</th>
            <th nowrap>題目</th>
            <th nowrap>類型</th>
            <th nowrap>選項或屬性</th>
            <th nowrap>預設值</th>
            <th nowrap>註解</th>
            <th nowrap>刪</th>
        </tr>
    </thead>
    <tbody id="sort">
        $allCol
    </tbody>
</table>


<!--表單樣板-->
<table style="display:none;">
    <tr id="form_data">
        <td>
            <input type="checkbox" id="require"  value="*" checked>
        </td>
        <td>
            <input class="form-control form-control-sm" type="text" id="label" value="">
        </td>
        <td>
            <select id="form_tag" class="form-control form-select form-control form-select-sm" placeholder="請輸入type">
                <option value="text">文字框</option>
                <option value="checkbox">複選</option>
                <option value="radio">單選</option>
                <option value="textarea">大量文字</option>
                <option value="select">下拉選單</option>
                <option value="hidden">隱藏欄位</option>
                <option value="const">常數欄位</option>
            </select>
        </td>
        <td>
            <input class="form-control form-control-sm" type="text"  id="option" value="">
        </td>
        <td>
            <input class="form-control form-control-sm" type="text" id="value" value="">
        </td>
        <td>
            <input class="form-control form-control-sm" type="text" id="help" value="">
        </td>
        <td>
            <a href="#" data-name="remove_me" class="text-danger remove_me">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
            </a>
        </td>
    </tr>
</table>

<div class="bar">
    <a href="#" id="add_form" class="btn btn-success">新增一列</a>
    <input type="button" value="確定" class="btn btn-primary" onclick="getFormData()">
</div>
EOF;

echo Utility::html5($form, true, true, 4);
