
<table width="100%" style="height: 32px;" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="23"><img src="{TEMPLATE_PATH}/images/admin/adminmain_01.gif" width="23" height="32" alt=""></td>
		<td width="30"><img src="{TEMPLATE_PATH}/images/admin/adminmain_02.gif" width="30" height="32" alt=""></td>
		<td width="67"><img src="{TEMPLATE_PATH}/images/admin/adminmain_03.gif" width="67" height="32" alt=""></td>
		<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmain_04.gif);" align="center" class="pagetitle">{L_PAGE_TITLE}</td>
		<td width="66"><img src="{TEMPLATE_PATH}/images/admin/adminmain_05.gif" width="66" height="32" alt=""></td>
		<td width="31"><img src="{TEMPLATE_PATH}/images/admin/adminmain_06.gif" width="31" height="32" alt=""></td>
		<td width="27"><img src="{TEMPLATE_PATH}/images/admin/adminmain_07.gif" width="27" height="32" alt=""></td>
	</tr>
</table>   

<div align="center"><br><strong>{GROUP_NAME}</strong><br><br></div>

<DIV class=tooltip id="tip_action" style="width: 200px" align="left">{L_ACTION_TIP}</DIV>
<DIV class=tooltip id="tip_item" style="width: 200px" align="left">{L_ITEM_TIP}</DIV>

<div align="center">
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="GROUPFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td width="40%" class=tdmenu>{L_FUNCTION}</td>
	    <td width="30%" class=tdmenu><input type="checkbox" name="allact" value="1" onclick="javascript: checkFunc(this.checked);">{L_ACTION} 	&nbsp; <img onMouseMove="javascript: show_tip('tip_action', event);" onMouseOut="javascript: hide_tip('tip_action');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0"></td>
	    <td width="30%" class=tdmenu><input type="checkbox" name="allact" value="1" onclick="javascript: checkItem(this.checked);">{L_ITEM} &nbsp; <img onMouseMove="javascript: show_tip('tip_item', event);" onMouseOut="javascript: hide_tip('tip_item');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0"></td>
	  </tr>
	<!-- START: fgrouprow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="3"><b>{fgrouprow:NAME}:</b></td>
	  </tr>
		<!-- START: fgrouprow:funcrow -->
	  <tr>
	    <td class=tdtext1 valign="top" nowrap>&nbsp; &nbsp; <strong>+ {fgrouprow:funcrow:NAME}:</strong></td>
	    <td class=tdtext2 valign="top">{fgrouprow:funcrow:ACTIONS}<br></td>
	    <td class=tdtext2 valign="top">{fgrouprow:funcrow:ITEMS}<br></td>
	  </tr>
		<!-- END: fgrouprow:funcrow -->
	<!-- END: fgrouprow -->
	  <tr>
	  	<td colspan="3" class="tdtext2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="100%" colspan="3" align="center"><input class=submit type="submit" name="submit" value="{L_BUTTON_UPDATE}"> &nbsp; <input class=submit type="reset" name="reset" value="{L_BUTTON_RESET}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form = window.document.GROUPFORM;

	function checkFunc(the_value){
		var count = the_form.elements.length;

		for (i=0;i<count;i++){
			if (the_form.elements[i].name.match('action_')){
				the_form.elements[i].checked = the_value;
			}
		}
	}

	function checkItem(the_value){
		var count = the_form.elements.length;

		for (i=0;i<count;i++){
			if (the_form.elements[i].name.match('item_')){
				the_form.elements[i].checked = the_value;
			}
		}
	}

	function checkPerm(the_status,func_id){
		str = "_" + func_id + "_";
		for (i=0;i<the_form.length;i++){
			tmpstr = the_form.elements[i].name.replace("[","[_");
			tmpstr = tmpstr.replace("]","_]");
			if (tmpstr.match(str)){
				the_form.elements[i].checked = the_status;
			}
		}
	}

	function checkView(the_status,func_id){
		if (!the_status) return false;
		
		str = "func_view_" + func_id + "_";
		for (i=0;i<the_form.length;i++){
			tmpstr = the_form.elements[i].name.replace("[","_");
			tmpstr = tmpstr.replace("]","_");
			if (tmpstr.match(str)){
				the_form.elements[i].checked = the_status;
			}
		}
		return true;
	}
</script>
