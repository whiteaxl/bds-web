<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="{SITE_URL}">
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset={L_CHARSET}">
<meta name="Keywords" content="{META_KEYWORDS}">
<meta name="Description" content="{META_DESC}">
<title>{SITE_NAME} {BROWSER_NAVIGATOR}</title>
<link rel="shortcut icon" href="{TEMPLATE_PATH}/images/icon.gif">
<link href="{TEMPLATE_PATH}/style.css" rel="stylesheet" type="text/css">
<link href="jslib/dtree.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="./jslib/cms.js"></script>
<script language="javascript" type="text/javascript" src="./jslib/ajax.js"></script>
<script language="javascript" type="text/javascript">
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

		the_form.email.value	= the_form.email.value.replace(" ", "");
		var the_at				= the_form.email.value.indexOf("@");
		var the_dot				= the_form.email.value.lastIndexOf(".");

		if ( (the_at > 0) && (the_dot > the_at + 1) && (the_dot < the_form.email.value.length - 1) ){
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

	function onload_display(){
		//We hide and display marquee when page has been fully loaded in order to prevent broken page on some browsers
		if ( document.getElementById('TBL_Latest') ){
			document.getElementById('TBL_Latest').style.display	= "";	
		}
	}
</script>
</head>
<body onload="onload_display();" leftmargin="0" topmargin="8" marginwidth="0" marginheight="0">
<center>
  <table width="780" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> 
              <!--  header -->
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="4" bgcolor="#0086C3"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                </tr>
                <tr>
                  <td height="2"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                </tr>
                <tr> 
                  <td height="84" background="{TEMPLATE_PATH}/images/bgtop.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
						<td height="84" width="15">&nbsp;</td>
						<td height="84"><a name="top"></a>
							<table width="100%" cellspacing="0" cellpadding="1" border="0">
							<tr><td align="left" class="sitename">{SITE_NAME}</td></tr>
							<tr><td align="center" class="siteSlogan">{SITE_SLOGAN}</td></tr>
							</table>
						</td>
                        <td width="278" height="84" valign="top"><img src="{TEMPLATE_PATH}/images/soso_news_express.jpg" width="278" height="47"></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              <!--  end header -->
            </td>
          </tr>
          <tr> 
            <td> 
              <!--topmenu -->
              <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#1964CA">
                <tr> 
                  <td align="center"><table width="99%" border="0" cellspacing="0" cellpadding="0" background="{TEMPLATE_PATH}/images/bg_menu.gif">
                      <tr> 
                        <td width="8"><img src="{TEMPLATE_PATH}/images/lefttopmenu.gif" width="8" height="39"></td>
                        <td class="currentDay" align="center"><!-- TodayDateBegin --> {TODAY_DATE_TIME} <!-- TodayDateEnd --></td>

                        <td width="40" align="left"><img src="{TEMPLATE_PATH}/images/topmenu_icon.gif" width="32" height="31"></td>
                        <td align="right" nowrap="nowrap"><a href="{U_HOME}" class="topMenu">{L_HOME}</a></td>
<!--
                        <td width="40" align="left"><img src="{TEMPLATE_PATH}/images/topmenu_icon.gif" width="32" height="31"></td>
                        <td align="right"><a href="{U_EVENT}" class="topMenu">{L_EVENT}</a></td>
-->
                        <td width="40" align="left"><img src="{TEMPLATE_PATH}/images/topmenu_icon.gif" width="32" height="31"></td>
                        <td align="right" nowrap="nowrap"><a href="{U_FAQ}" class="topMenu">{L_FAQ}</a></td>

                        <td width="40" align="left"><img src="{TEMPLATE_PATH}/images/topmenu_icon.gif" width="32" height="31"></td>
                        <td align="right" nowrap="nowrap"><a href="{U_RSS}" class="topMenu">{L_RSS}</a></td>

                        <td width="40" align="left"><img src="{TEMPLATE_PATH}/images/topmenu_icon.gif" width="32" height="31"></td>
                        <td align="right" nowrap="nowrap"><a href="{U_WEBLINK}" class="topMenu">{L_WEBLINK}</a></td>

                        <td width="40" align="left" nowrap="nowrap"><img src="{TEMPLATE_PATH}/images/topmenu_icon.gif" width="32" height="31"></td>
                        <td align="right" nowrap="nowrap"><a href="{U_SITEMAP}" class="topMenu">{L_SITEMAP}</a></td>

                        <td width="40" align="left"><img src="{TEMPLATE_PATH}/images/topmenu_icon.gif" width="32" height="31"></td>
                        <td align="right" nowrap="nowrap"><a href="{U_CONTACT}" class="topMenu">{L_CONTACT}</a></td>
                        <td width="36" height="39" background="{TEMPLATE_PATH}/images/bg_menu.gif"><img src="{TEMPLATE_PATH}/images/righttopmenu.gif" width="36" height="39"></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td height="30" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td width="9">&nbsp;</td>
                        <td><table width="100%" height="25" border="0" cellpadding="0" cellspacing="0" class="borderAll">
                            <tr height="22">
								<td width="50%" bgcolor="#ADCEFA" align="center"><table width="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
									<tr> 
										<td align="left" style="padding-left: 5px; padding-right: 5px;">
											<!-- START: globalHotArticle -->
											<span id="theTicker">&nbsp;</span>
											<script language="javascript" type="text/javascript">
												var theSummaries = new Array({globalHotArticle:TITLE_STRINGS});
												var theSiteLinks = new Array({globalHotArticle:URL_STRINGS});
											</script>
											<script language="javascript" type="text/javascript" src="jslib/ticker_engine.js"></script>
											<!-- END: globalHotArticle -->
										</td>
									</tr>
								</table></td>
								<td bgcolor="#ADCEFA" align="right">
									<table border="0" cellspacing="0" cellpadding="0">
									<form name="SEARCHFORM" method="POST" action="{S_ARTICLE_SEARCH}" onsubmit="javascript: return check_searchForm();">
									<tr> 
										<td class="newsDate">::| {L_SEARCH_KEYWORD}:</td>
										<td width="6">&nbsp;</td>
                                            <td><input class="myForm" type="text" name="s_keyword" value="{SEARCH_KEYWORD}" size="20" style="width: 100px;"></td>
										<td width="6">&nbsp;</td>
										<td><input name="s_submit" type="image" src="{TEMPLATE_PATH}/images/search2.gif" width="62" height="21" border="0"></td>
										<td width="8">&nbsp;</td>
										<td align="center"><a href="{U_ADVANCE_SEARCH}" class="advanceSearch">[{L_ADVANCE_SEARCH}]</a></td>
										<td width="8">&nbsp;</td>
									</tr>
									</form>
									</table>
									
									<script language="javascript" type="text/javascript">
										var search_form	= document.SEARCHFORM;
									
										function check_searchForm(){
											if (search_form.s_keyword.value == ""){
												alert("{L_ERROR_KEYWORD}");
												search_form.s_keyword.focus();
												return false;
											}
										return true;
										}
									</script>
								
								</td>
                            </tr>
                          </table></td>
                        <td width="10">&nbsp;</td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td height="11" background="{TEMPLATE_PATH}/images/bottom_topmenu.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr> 
            <td height="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
          </tr>
 