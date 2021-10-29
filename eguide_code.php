<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
require_once __DIR__ . '/header.php';

/*-----------變數過濾----------*/
$op = Request::getString('op');
$setup = Request::getText('setup');

$form = <<<EOF

<p>可選擇種類有text、checkbox、radio、textarea、select、hidden、const</p>

<ul>
	<li>加上"*" 為必填項目。</li>
	<li>加上"#" 可於表格後方加上描述或是備註。</li>
	<li>利用"," 為區隔指令的記號。如需要再引數里使用這個符號必須以HTML的特殊記號。</li>
	<li>"+" 為 (checkbox, radio, select) 此三項的預選項目(checked)。</li>
</ul>

<table class="table table-sm table-bordered">
	<tbody>
		<tr class="hd">
			<td>新增內容</td>
			<td>表單形態</td>
		</tr>
		<tr>
			<td>姓名*</td>
			<td>姓名* <input name="samp1" title="input" /></td>
		</tr>
		<tr>
			<td>姓名*,size=5</td>
			<td>姓名* <input name="samp2" size="5" title="input" /></td>
		</tr>
		<tr>
			<td>姓名*,size=5,#備註</td>
			<td>姓名* <input name="samp3" size="5" title="input" /> 備註</td>
		</tr>
		<tr>
			<td>選項,radio,項目1+,項目2,項目3</td>
			<td>選項 <input checked="checked" name="samp4" title="input" type="radio" value="1" /> 項目1 &nbsp; <input name="samp4" title="input" type="radio" value="2" /> 項目2 &nbsp; <input name="samp4" title="input" type="radio" value="3" /> 項目3 &nbsp;</td>
		</tr>
		<tr>
			<td>選項,checkbox,項目1+,項目2,項目3</td>
			<td>選項 <input checked="checked" name="samp5_1" title="input" type="checkbox" /> 項目1 &nbsp; <input name="samp5_2" title="input" type="checkbox" /> 項目2 &nbsp; <input name="samp5_3" title="input" type="checkbox" /> 項目3 &nbsp;</td>
		</tr>
		<tr>
			<td>選項,select,項目1,項目2,項目3</td>
			<td>選項 <select name="samp6" title="select"><option>項目1</option><option>項目2</option><option>項目3</option> </select></td>
		</tr>
		<tr class="even">
			<td>項目名,const,値</td>
			<td>項目名 値<span style="color:#008080;"><em>（會顯示出來，且無法修改值的隱藏欄位）</em></span></td>
		</tr>
		<tr>
			<td>項目名,hidden,値</td>
			<td><span style="color:#008080;"><em>（不會顯示出來，也無法修改值的隱藏欄位）</em></span></td>
		</tr>
		<tr>
			<td>#說明文或備註</td>
			<td>說明文或備註<em><span style="color:#008080;">（不會送出任何值）</span></em></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>實際範例：</p>

<pre>
<code class="language-markup">姓名*,#請填真實姓名
飲食*,radio,葷食+,素食,不用餐
日期場次,const,2021-06-27
參加場次*,checkbox,上午場,下午場,午夜場
交通方式,select,自行前往,公車,火車,高鐵,飛機</code></pre>

<p>&nbsp;</p>

EOF;

echo Utility::html5($form, true, true, 4);
