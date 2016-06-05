<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<base href="{SITE_URL}">
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset={L_CHARSET}">
<meta name="Keywords" content="{SITE_KEYWORDS}">
<meta name="Description" content="{SITE_DESC}">
<title>{SITENAME} {BROWSER_NAVIGATOR}</title>
<link href="{TEMPLATE_PATH}/style.css" rel="stylesheet" type="text/css">
<link href="jslib/dtree.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="./jslib/cms.js"></script>
<script language="javascript" type="text/javascript" src="./jslib/ajax.js"></script>
<script language="javascript" type="text/javascript">
	var menu_color	= "";

	function mOver(src) {
		menu_color	= src.style.backgroundColor;
		src.style.cursor = 'hand';
		src.style.backgroundColor = '#A3AC86';
	}
	function mOut(src) {
		src.style.cursor = 'default';
//		src.style.backgroundColor = '#3A89D3';
		src.style.backgroundColor = menu_color;
	}
	function mClick(src) {
		if(event.srcElement.tagName=='TD'){
			src.children.tags('A')[0].click();
		}
	}
	
	function check_searchform(the_form){
		if (the_form.keyword.value == ""){
			window.alert("{L_ERROR_KEYWORD}");
			return false;
		}
		return true;	
	}

	function check_newsltform(the_form){
		if ( (the_form.cat_id.selectedIndex == 0) || (the_form.name.value == "") || (the_form.email.value == "") ){
			window.alert("{L_ERROR_NOT_FULL}");
			the_form.email.focus();
			return false;
		}

		var the_at    = the_form.email.value.indexOf("@");
		var the_dot   = the_form.email.value.indexOf(".");
		var the_space = the_form.email.value.indexOf(" ");

		if ( (the_at!=-1)&&(the_at!=0)&&(the_dot!=-1)&&(the_dot>the_at + 1)&&(the_dot<the_form.email.value.length-1)&&(the_space==-1) ){
			width		= 250;
			height		= 150;
			top_val		= (screen.height - height)/2 - 30;
			if (top_val < 0){ top_val	= 0; }
			left_val	= (screen.width - width)/2;
		
			window.open('', 'NEWSLTFORM', "toolbar=0,location=0,status=1,menubar=0,scrollbars=1,resizable=1,width="+ width +",height="+ height +", top="+ top_val +",left="+ left_val);
			the_form.submit();
			return true;
		}

		window.alert("{L_ERROR_EMAIL}");
		the_form.email.focus();
		return false;
	}
</script>
</head>
<body bgcolor="#FFFFFF" style="margin-left: 5px; margin-right: 5px; margin-top: 0px; margin-bottom: 0px">
<div align="center">

<table width="780" style="height:100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<!-- // HEADER // -->
    <td colspan="5" align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="360" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="99%" height="51" style="background-image: url({TEMPLATE_PATH}/images/header_01.gif);" class="sitename" align="center" valign="bottom" nowrap>{SITENAME}</td>
					<td width="76"><img src="{TEMPLATE_PATH}/images/header_02.gif" width="76" height="51" alt=""></td>
				  </tr>
				  <tr>
					<td width="99%" height="27" style="background-image: url({TEMPLATE_PATH}/images/header_08.gif);" align="center" class="siteSlogan">{SITE_SLOGAN}&nbsp;</td>
					<td width="76" height="27" style="background-image: url({TEMPLATE_PATH}/images/header_09.gif);">&nbsp;</td>
				  </tr>
				</table>
			</td>
		    <td valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="100%" height="7" style="background-image: url({TEMPLATE_PATH}/images/header_03bg.gif);"><img src="{TEMPLATE_PATH}/images/spacer.gif" border="0" alt=""></td>
				  </tr>
				  <tr>
					<td width="100%" height="18" style="background-image: url({TEMPLATE_PATH}/images/header_06bg.gif);">
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td align="center"><a class="tmenu" href="{U_HOME}">{L_HOME}</a></td>
							<td width="4"><img src="{TEMPLATE_PATH}/images/header_col.gif" alt=""></td>
							<td align="center"><a class="tmenu" href="{U_FAQ}">{L_FAQ}</a></td>
							<td width="4"><img src="{TEMPLATE_PATH}/images/header_col.gif" alt=""></td>
							<td align="center"><a class="tmenu" href="{U_RSS}">{L_RSS}</a></td>
							<td width="4"><img src="{TEMPLATE_PATH}/images/header_col.gif" alt=""></td>
							<td align="center"><a class="tmenu" href="{U_WEBLINK}">{L_WEBLINK}</a></td>
							<td width="4"><img src="{TEMPLATE_PATH}/images/header_col.gif" alt=""></td>
							<td align="center"><a class="tmenu" href="{U_SITEMAP}">{L_SITEMAP}</a></td>
							<td width="4"><img src="{TEMPLATE_PATH}/images/header_col.gif" alt=""></td>
							<td align="center"><a class="tmenu" href="{U_CONTACT}">{L_CONTACT}</a></td>
						  </tr>
						</table>
					</td>
				  </tr>
				  <tr>
					<td width="100%" height="53" style="background-image: url({TEMPLATE_PATH}/images/header_07bg.gif);">&nbsp;</td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
	</td>
  	<!-- // HEADER END // -->
    </tr>
	<tr style="height: 18px;">
		<td align="right" class="sText">..:: {L_HOT_NEWS}:</td>
		<td></td>
		<td colspan="3">
			<!-- START: globalHotArticle -->
			<span id="theTicker">&nbsp;</span>
			<script language="javascript" type="text/javascript">
				var theSummaries = new Array({globalHotArticle:TITLE_STRINGS});
				var theSiteLinks = new Array({globalHotArticle:URL_STRINGS});
			</script>
			<script language="javascript" type="text/javascript" src="jslib/ticker_engine.js"></script>
			<!-- END: globalHotArticle -->
		</td>
		<td colspan="2">&nbsp;</td>
	</tr>
	