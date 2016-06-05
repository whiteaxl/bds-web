
<!-- wysiwyg editor -->
<script language="javascript" type="text/javascript">
	var editor_path				= "wysiwyg/";
	var editor_site_url			= "{SITE_URL}";
	var editor_url_image_upload	= "{S_IMAGE_UPLOAD}";
</script>
<script src="wysiwyg/setting.js" type="text/javascript"></script>
<script src="wysiwyg/wysiwyg.js" type="text/javascript"></script>
<!-- wysiwyg editor -->

<script type="text/javascript">

window.onload = function()
{
	// Automatically calculates the editor base path based on the _samples directory.
	// This is usefull only for these samples. A real application should use something like this:
	// oFCKeditor.BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
	var sBasePath = document.location.href.substring(0,document.location.href.lastIndexOf('_samples')) ;

	var oFCKeditor = new FCKeditor('content_detail') ;  
	oFCKeditor.BasePath	= sBasePath ;
	oFCKeditor.ReplaceTextarea() ;
	
}
</script>

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

<div align="center"><br><span class="error">{ERROR_MSG}&nbsp;</span><br><br>

<DIV class=tooltip id="tip_topic" style="width: 250px" align="left">{L_TOPIC_TIP}</DIV>
<DIV class=tooltip id="tip_is_hot" style="width: 250px" align="left">{L_IS_HOT_TIP}</DIV>
<DIV class=tooltip id="tip_article_type" style="width: 300px" align="left">{L_ARTICLE_TYPE_TIP}</DIV>
<DIV class=tooltip id="tip_posttime" style="width: 250px" align="left">{L_POST_TIME_TIP}</DIV>

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="EDITFORM" method="POST" action="{S_ACTION}" enctype="multipart/form-data">
<input type="hidden" id="used_files" name="used_files" value="{USED_FILES}">
<input type="hidden" name="page_id" value="{PAGE_ID}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="20%">{L_CAT}:</td>
	    <td class=tdtext2 width="80%">
			<select class=form name="cat_id" size=1>
				<option value="0"> - - - - - - {L_CHOOSE} - - - - - - </option>
				<!-- START: catrow -->
				<option value="{catrow:ID}">{catrow:PREFIX}{catrow:NAME}</option>
				<!-- END: catrow -->
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_TOPIC}:</td>
	    <td class=tdtext2>
			<select class=form name="topic_id" size=1>
				<option value="0"> - - - - - - {L_CHOOSE} - - - - - - </option>
				<!-- START: topicrow -->
				<option value="{topicrow:ID}">{topicrow:TITLE}</option>
				<!-- END: topicrow -->
			</select>
			&nbsp; {L_TOPIC_SEARCH}: <input class="form" name="topic_id_search" value="{TOPIC_ID_SEARCH}" size="10">
			&nbsp; <img onMouseMove="javascript: show_tip('tip_topic', event);" onMouseOut="javascript: hide_tip('tip_topic');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0">
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_META_KEYWORDS}:</td>
	    <td class=tdtext2><input class=form type="text" id="meta_keywords" name="meta_keywords" value="{META_KEYWORDS}" size="60" maxlength="255"> <span class="date">({L_OPTIONAL})</span></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_META_DESC}:</td>
	    <td class=tdtext2><input class=form type="text" id="meta_desc" name="meta_desc" value="{META_DESC}" size="60" maxlength="255"> <span class="date">({L_OPTIONAL})</span></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_PIC_THUMB}:</td>
	    <td class=tdtext2>
			<!-- START IF: picthumb -->
			{PIC_THUMB} <input type="checkbox" name="remove_thumb" value="1" {REMOVE_THUMB_CHECK}> {L_REMOVE}<br>
			<!-- END IF: picthumb -->
			<input class=form type="file" name="pic_thumb" size="47"> {L_THUMB_SIZE}
	    </td>
	  </tr>

	  <tr><td colspan="2" class="tdtext2">&nbsp;</td></tr>
	  <tr>
	    <td class=tdtext1>{L_IS_HOT}:</td>
	    <td class=tdtext2>
			<input type="radio" name="is_hot" value="{SYS_ARTICLE_NORMAL}" checked>{L_NORMAL} &nbsp; &nbsp;
			<input type="radio" name="is_hot" value="{SYS_ARTICLE_HOT}">{L_HOT}
			&nbsp; <img onMouseMove="javascript: show_tip('tip_is_hot', event);" onMouseOut="javascript: hide_tip('tip_is_hot');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0">
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_ARTICLE_TYPE}:</td>
	    <td class=tdtext2>
			<input type="radio" name="article_type" value="{SYS_ARTICLE_FULL}" checked onclick="javascript: changeArticleType({SYS_ARTICLE_FULL})">{L_TYPE_FULL} &nbsp; &nbsp;
			<input type="radio" name="article_type" value="{SYS_ARTICLE_SUMMARY}" onclick="javascript: changeArticleType({SYS_ARTICLE_SUMMARY})">{L_TYPE_SUMMARY} &nbsp; &nbsp;
			<input type="radio" name="article_type" value="{SYS_ARTICLE_LINK}" onclick="javascript: changeArticleType({SYS_ARTICLE_LINK})">{L_TYPE_LINK}
			&nbsp; <img onMouseMove="javascript: show_tip('tip_article_type', event);" onMouseOut="javascript: hide_tip('tip_article_type');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0">
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_TITLE}:</td>
	    <td class=tdtext2>
			<input class=form type="text" id="title" name="title" value="{TITLE}" size="80">
			<!-- START IF: wysiwyg_title -->
			<script language="javascript" type="text/javascript">editor_create(document.EDITFORM, 'title', 'simple', '600', '25');</script>
			<!-- END IF: wysiwyg_title -->
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_CONTENT_DESC}:</td>
	    <td class=tdtext2>
			<textarea class=form rows="6" id="content_desc" name="content_desc" cols="70">{CONTENT_DESC}</textarea>
			<script language="javascript" type="text/javascript">editor_create(document.EDITFORM, 'content_desc', 'simple', '100%');</script>
	    </td>
	  </tr>
	  <tr id="Div_Detail">
	    <td class=tdtext1 valign="top">{L_CONTENT_DETAIL}:</td>
	    <td class=tdtext2>
			<textarea class=form rows="18" id="content_detail" name="content_detail" cols="70">{CONTENT_DETAIL}</textarea>
	    </td>
	  </tr>
	  <tr id="Div_Author">
	    <td class=tdtext1>{L_AUTHOR}</td>
	    <td class=tdtext2><input class=form type="text" name="author" value="{AUTHOR}" size="40"></td>
	  </tr>
	  <tr id="Div_URL" style="display: none">
	    <td class=tdtext1>{L_CONTENT_URL}</td>
	    <td class=tdtext2><input class=form type="text" name="content_url" value="{CONTENT_URL}" size="70"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_POST_TIME}:</td>
	    <td class=tdtext2>
		    <table border="0" cellpadding="0" cellspacing="1" width="100%">
		      <tr>
		        <td width="10%"><span class="textbody">{L_DATE}:</span></td>
		        <td width="90%">
					<select class=form size="1" name="month">
				        <option value="1">January</option>
				        <option value="2">February</option>
				        <option value="3">March</option>
				        <option value="4">April</option>
				        <option value="5">May</option>
				        <option value="6">June</option>
				        <option value="7">July</option>
				        <option value="8">August</option>
				        <option value="9">September</option>
				        <option value="10">October</option>
				        <option value="11">November</option>
				        <option value="12">December</option>
			        </select>
				
					<select class=form size="1" name="day">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select>
			
					<select class=form size="1" name="year">
						<option value="2000">2000</option>
						<option value="2001">2001</option>
						<option value="2002">2002</option>
						<option value="2003">2003</option>
						<option value="2004">2004</option>
						<option value="2005">2005</option>
						<option value="2006">2006</option>
						<option value="2007">2007</option>
						<option value="2008">2008</option>
						<option value="2009">2009</option>
						<option value="2010">2010</option>
						<option value="2011">2011</option>
						<option value="2012">2012</option>
						<option value="2013">2013</option>
						<option value="2014">2014</option>
						<option value="2015">2015</option>
						<option value="2016">2016</option>
						<option value="2017">2017</option>
						<option value="2018">2018</option>
						<option value="2019">2019</option>
						<option value="2020">2020</option>
					</select>
					&nbsp; <img onMouseMove="javascript: show_tip('tip_posttime', event);" onMouseOut="javascript: hide_tip('tip_posttime');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0">
				</td>
		      </tr>
		      <tr>
		        <td width="10%"><span class="textbody">{L_TIME}:</span></td>
		        <td width="90%"><input class=form type="text" name="time" value="{TIME}" size="4"> <span class="date">{L_TIME_EXPLAIN}</span></td>
		      </tr>
		    </table>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_STATUS}</td>
	    <td class=tdtext2><input type="radio" name="enabled" value="{SYS_ENABLED}" checked>{L_ENABLED} &nbsp; &nbsp;<input type="radio" name="enabled" value="{SYS_DISABLED}">{L_DISABLED} &nbsp; &nbsp;<input type="radio" name="enabled" value="{SYS_APPENDING}">{L_APPENDING}</td>
	  </tr>
	  <!-- START IF: addrow -->
	  <tr>
	    <td class=tdtext1>{L_PAGE_TO}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="page_to" value="pagelist" checked>{L_PAGE_LIST} &nbsp; <input class=form2 type="radio" name="page_to" value="pageadd">{L_PAGE_ADD}</td>
	  </tr>
	  <!-- END IF: addrow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><input class=submit type="submit" name="submit" value="{L_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>
<br>

<script language="javascript" type="text/javascript">
	var the_form = window.document.EDITFORM;

	select_list("{CAT_ID}",the_form.cat_id);
	select_list("{TOPIC_ID}",the_form.topic_id);
	if ( ("{TOPIC_ID_SEARCH}" != "0") && (select_list("{TOPIC_ID_SEARCH}",the_form.topic_id)) ){
		the_form.topic_id_search.value	= "";
	}
	radio_list("{IS_HOT}",the_form.is_hot);
	radio_list("{ARTICLE_TYPE}",the_form.article_type);
	changeArticleType({ARTICLE_TYPE});

	select_list("{MONTH}",the_form.month);
	select_list("{DAY}",the_form.day);
	select_list("{YEAR}",the_form.year);

	radio_list("{ENABLED}",the_form.enabled);

	<!-- START IF: addrow -->
	radio_list("{PAGE_TO}",the_form.page_to);
	<!-- END IF: addrow -->

	function add_usedFile(the_file){
		var the_form = window.document.EDITFORM;

		if (the_form.used_files.value != ""){
			the_form.used_files.value += ","+ the_file;
		}
		else{
			the_form.used_files.value += the_file;
		}
	}

	function changeArticleType(the_type){
		var tr_detail		= document.getElementById('Div_Detail');
		var tr_author		= document.getElementById('Div_Author');
		var tr_url			= document.getElementById('Div_URL');

		switch (the_type){
			case {SYS_ARTICLE_SUMMARY}:
				tr_detail.style.display		= "none";
				tr_author.style.display		= "none";
				tr_url.style.display		= "none";
				break;
			case {SYS_ARTICLE_LINK}:
				tr_detail.style.display		= "none";
				tr_author.style.display		= "none";
				tr_url.style.display		= "";
				break;
			default:
				tr_detail.style.display		= "";
				tr_author.style.display		= "";
				tr_url.style.display		= "none";
		}
	}
</script>
