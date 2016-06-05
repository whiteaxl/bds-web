
<div align="center">
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

<br><br>

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="TOPICFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="40%">{L_TOPIC_NAME}:</td>
	    <td class=tdtext2 width="60%"><b>{TOPIC_NAME}</b></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>&nbsp; + {L_CHILDS}:</td>
	    <td class=tdtext2>{CHILDREN_COUNTER}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>&nbsp; + {L_ARTICLES}:</td>
	    <td class=tdtext2>{ARTICLE_COUNTER}</td>
	  </tr>
	<!-- START: havechild -->
	  <tr>
	    <td class="tdtext2" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">&nbsp; + {L_DELETE_SUBTOPICS}:</td>
	    <td class=tdtext2>
			<input type="radio" name="del_child" value="1" checked> {L_DELETE}<br>
			<input type="radio" name="del_child" value="0"> {L_MOVE_TO}: 
			<select class="form" name="topic_dest_id">
				<!-- START: topicrow -->
				<option value="{topicrow:ID}">{topicrow:PREFIX}{topicrow:NAME}</option>
				<!-- END: topicrow -->
			</select>
	    </td>
	  </tr>
	<!-- END: havechild -->
	<!-- START: havearticle -->
	  <tr>
	    <td class="tdtext2" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">&nbsp; + {L_DELETE_ARTICLES}:</td>
	    <td class=tdtext2>
			<input type="radio" name="del_article" value="1" checked> {L_DELETE}<br>
			<input type="radio" name="del_article" value="0"> {L_MOVE_TO}: 
			<select class="form" name="art_dest_id">
				<!-- START: topicrow -->
				<option value="{topicrow:ID}">{topicrow:PREFIX}{topicrow:NAME}</option>
				<!-- END: topicrow -->
			</select>
	    </td>
	  </tr>
	<!-- END: havearticle -->
	  <tr>
	    <td class="tdtext2" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><input class=submit type="submit" name="delsm" value="{L_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>
