
  <tr>
  	<!-- // LEFT // -->
    <td width="19%" style="height: 90%" valign="top" bgcolor="#EBF2FA">
		<table width="98%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="100%" colspan="3">
				<table width="100%" style="height: 23px;" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="7" style="background-image: url({TEMPLATE_PATH}/images/catmenu_01.gif);"><img src="{TEMPLATE_PATH}/images/blank.gif" width="7" alt=""></td>
						<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/catmenu_02a.gif);" align="center" valign="bottom"><a class="home" href="{U_ALL_NEWS}">{L_ALL_NEWS}</a></td>
						<td width="14" style="background-image: url({TEMPLATE_PATH}/images/catmenu_02b.gif);"><img src="{TEMPLATE_PATH}/images/blank.gif" width="14" height="1" alt=""></td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- START: menucatrow -->
		<tr>
			<td style="background-image: url({TEMPLATE_PATH}/images/catmenu_06.gif);" width="7" height="2"><img src="{TEMPLATE_PATH}/images/blank.gif" width="7" height="2" alt=""></td>
			<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/catmenu_04.gif);"></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/catmenu_08.gif);" width="1"><img src="{TEMPLATE_PATH}/images/blank.gif" width="1" height="2" alt=""></td>
		</tr>
		<tr>
			<td style="background-image: url({TEMPLATE_PATH}/images/catmenu_06.gif);" width="7"></td>
			<td class="tdcatmenu" width="99%" onclick="mClick(this);" onmouseout="mOut(this);" onmouseover="mOver(this);" nowrap>{menucatrow:PREFIX} <a class="catmenu" href="{menucatrow:U_VIEW}">{menucatrow:NAME}</a></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/catmenu_08.gif);" width="1"></td>
		</tr>
			<!-- START: menucatrow:subcatrow -->
			<tr>
				<td style="background-image: url({TEMPLATE_PATH}/images/catmenu_06.gif);" width="7" height="2"><img src="{TEMPLATE_PATH}/images/blank.gif" width="7" height="2" alt=""></td>
				<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/catmenu_04.gif);"></td>
				<td style="background-image: url({TEMPLATE_PATH}/images/catmenu_08.gif);" width="1"><img src="{TEMPLATE_PATH}/images/blank.gif" width="1" height="2" alt=""></td>
			</tr>
			<tr>
				<td style="background-image: url({TEMPLATE_PATH}/images/catmenu_06.gif);" width="7"></td>
				<td class="tdcatsubmenu" width="99%" onclick="mClick(this);" onmouseout="mOut(this);" onmouseover="mOver(this);" nowrap>{menucatrow:subcatrow:PREFIX}&raquo; <a class="catsubmenu" href="{menucatrow:subcatrow:U_VIEW}">{menucatrow:subcatrow:NAME}</a></td>
				<td style="background-image: url({TEMPLATE_PATH}/images/catmenu_08.gif);" width="1"></td>
			</tr>
			<!-- END: menucatrow -->
		<!-- END: menucatrow -->
		<tr>
			<td colspan="3" width="100%" height="12">
				<table width="100%" style="height: 12px;" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="15"><img src="{TEMPLATE_PATH}/images/catmenu_26a.gif" width="15" height="12" alt=""></td>
						<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/catmenu_26b.gif);"></td>
						<td width="1"><img src="{TEMPLATE_PATH}/images/catmenu_27.gif" width="1" height="12" alt=""></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
	<br>
	<table width="98%"  border="1" style="border-color: #6C93B1; border-collapse: collapse" cellpadding="1" cellspacing="0" bgcolor="#DFEBF6">
	  <tr>
		<td>
			<table bgcolor="#256FB5" width="100%"  border="0" cellspacing="0" cellpadding="1">
			  <tr>
				<td align="center" class="tdleftmenu">{L_NEWSLETTER}</td>
			  </tr>
			</table>
		</td>
	  </tr>
	  <tr>
		<td align="center">
			<form name="NEWSLTFORM" target="NEWSLTFORM" method="POST" action="{S_NEWSLETTER}">
			<table border="0" cellpadding="1" cellspacing="0">
			<tr><td height="25">
					<select class=form name="cat_id" style="width: 125px">
						<option value="0">- - - {L_CAT} - - -</option>
						<!-- START: newsltcatrow -->
						<option value="{newsltcatrow:ID}">{newsltcatrow:PREFIX} {newsltcatrow:NAME}</option>
						<!-- END: newsltcatrow -->
					</select>			
			</td></tr>
			<tr><td height="25" class="newsltText">{L_YOUR_NAME}:<br><input class=form type="text" name="name" value="" size="18" style="width: 125px"></td></tr>
			<tr><td height="25" class="newsltText">{L_YOUR_EMAIL}:<br><input class=form type="text" name="email" value="" size="18" style="width: 125px"></td></tr>
			<tr><td align="center">
				<div onMouseOver="javascript: this.style.cursor='hand';" onclick="javascript: check_newsltform(document.NEWSLTFORM);">
				<table width="62" style="height: 20px;" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="10"><img src="{TEMPLATE_PATH}/images/button_blue_01.gif" width="10" height="20" alt=""></td>
						<td width="48" height="20" style="background-image: url({TEMPLATE_PATH}/images/button_blue_02.gif);" align="center" class="imgbutton">{L_SUBMIT}</td>
						<td width="4"><img src="{TEMPLATE_PATH}/images/button_blue_03.gif" width="4" height="20" alt=""></td>
					</tr>
				</table>
				</div>
			</td></tr>
			</table>
			</form>
		</td>
	  </tr>
	</table>

	<!-- START: pollrow -->
	<br>
	<form name="POLL_{pollrow:ID}" target="POLL_{pollrow:ID}" method="POST" action="{pollrow:S_ACTION}">
	<table width="98%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="5" style="background-image: url({TEMPLATE_PATH}/images/poll_01.gif);"></td>
			<td width="99%" colspan="3">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td style="background-image: url({TEMPLATE_PATH}/images/pollmenu_01.gif);" align="center" valign="bottom" class="tdleftmenu">{L_POLL}</td>
						<td width="10" style="background-image: url({TEMPLATE_PATH}/images/pollmenu_02.gif);"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="5"><img src="{TEMPLATE_PATH}/images/poll_03.gif" width="5" height="5" alt=""></td>
			<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/poll_04.gif);"></td>
			<td colspan="2" width="6"><img src="{TEMPLATE_PATH}/images/poll_05.gif" width="6" height="5" alt=""></td>
		</tr>
		<tr>
			<td width="5" style="background-image: url({TEMPLATE_PATH}/images/poll_06.gif);"></td>
			<td width="98%" bgcolor="#DFEBF6">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				  <tr>
				    <td colspan="3" width="100%"><p align="justify"><span class="textbody">{pollrow:QUESTION}</span></p></td>
				  </tr>
				  <tr>
				    <td colspan="3" width="100%" align="center">-----------</td>
				  </tr>
				  <!-- START: pollrow:optionrow -->
				  <tr>
				    <td colspan="3" width="100%"><input type="{pollrow:MULTIPLE}" name="{pollrow:optionrow:INPUTNAME}" value="{pollrow:optionrow:ID}"> {pollrow:optionrow:CONTENT}</td>
				  </tr>
				  <!-- END: pollrow:optionrow -->
				  <tr>
				    <td width="49%" height="28" align="right" valign="bottom">
						<div onMouseOver="javascript: this.style.cursor='hand';" onclick="javascript: vote_poll(document.POLL_{pollrow:ID});">
						<table width="55" style="height:20px;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10"><img src="{TEMPLATE_PATH}/images/button_blue_01.gif" width="10" height="20" alt=""></td>
								<td width="90%" height="20" style="background-image: url({TEMPLATE_PATH}/images/button_blue_02.gif);" align="center" class="imgbutton">{L_POLL_HIT}</td>
								<td width="4"><img src="{TEMPLATE_PATH}/images/button_blue_03.gif" width="4" height="20" alt=""></td>
							</tr>
						</table>
						</div>
					</td>
					<td width="2%">&nbsp;</td>
					<td width="49%" height="28" valign="bottom">
						<div onMouseOver="javascript: this.style.cursor='hand';" onclick="javascript: open_window('{pollrow:U_VIEW_RESULT}', 450, 400);">
						<table width="55" style="height:20px;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="3"><img src="{TEMPLATE_PATH}/images/rbutton_blue_01.gif" width="3" height="20" alt=""></td>
								<td style="height: 90%; background-image: url({TEMPLATE_PATH}/images/rbutton_blue_02.gif);" align="center" class="imgbutton">{L_POLL_RESULT}</td>
								<td width="9"><img src="{TEMPLATE_PATH}/images/rbutton_blue_03.gif" width="9" height="20" alt=""></td>
							</tr>
						</table>
						</div>
					</td>
				  </tr>
				</table>
			</td>
			<td width="5" style="background-image: url({TEMPLATE_PATH}/images/poll_08.gif);"></td>
			<td width="1" style="background-image: url({TEMPLATE_PATH}/images/poll_09.gif);"></td>
		</tr>
		<tr>
			<td colspan="2" width="99%">
				<table width="100%" style="height: 11px;" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="11"><img src="{TEMPLATE_PATH}/images/pollbottom_01.gif" width="11" height="11" alt=""></td>
						<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/pollbottom_02.gif);"></td>
					</tr>
				</table>
			</td>
			<td colspan="2" width="6"><img src="{TEMPLATE_PATH}/images/poll_11.gif" width="6" height="11" alt=""></td>
		</tr>
	</table>
	</form>
	<!-- END: pollrow -->

	<script language="javascript" type="text/javascript">
		function vote_poll(the_form){
			width		= 450;
			height		= 400;
			top_val		= (screen.height - height)/2 - 30;
			if (top_val < 0){ top_val	= 0; }
			left_val	= (screen.width - width)/2;
		
			window.open('', the_form.name, "toolbar=0,location=0,status=1,menubar=0,scrollbars=1,resizable=1,width="+ width +",height="+ height +", top="+ top_val +",left="+ left_val);
			the_form.submit();
		}	
	</script>

	<!-- START IF: hotcatrow -->
	<br>
	<table width="98%"  border="1" cellpadding="1" cellspacing="0" bgcolor="#F1F5F8" style="border-color: #6C93B1; border-collapse: collapse">
		<!-- START: hotcatrow -->
		<tr><td style="background-image: url({TEMPLATE_PATH}/images/hotcatbg.gif);"><a class="hotcat" href="{hotcatrow:U_VIEW}"><strong>{hotcatrow:NAME}</strong></a></td></tr>
		<tr><td align="center"><div class="hotcatcontent">{hotcatrow:ARTICLE_THUMB}<br>{hotcatrow:ARTICLE_TITLE}</div></td></tr>
		<!-- END: hotcatrow -->
	</table>
	<!-- END IF: hotcatrow -->

	<br>

	</td>
  	<!-- // LEFT END // -->
	
	<!-- // CENTER // -->
	<td width="1%">&nbsp;</td>
    <td width="60%" align="center" valign="top">

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		  <tr><td height="2"></td></tr>
		  <tr><td>
				<table width="100%" border="1" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" style="border-color: #77A7AD; border-collapse: collapse">
				<tr><td class="tdmtop">{CAT_NAVIGATOR}&nbsp;</td></tr>
				</table>
		  </td></tr>
		  <tr><td height="10"></td></tr>
		</table>
	
		<!-- START IF: havehot -->
		<table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%">
		  <tr>
		    <td class="tdtopfocus" width="100%">{HOT_TITLE}<br><span class="date">{HOT_DATE}</span></td>
		  </tr>
		  <tr>
		    <td class="tdtopfocus" width="100%"><p align="justify">{HOT_THUMB_LARGE}{HOT_PREVIEW}</p></td>
		  </tr>
		  <tr>
		    <td bgcolor="#FFFFFF" width="100%" height="8"></td>
		  </tr>
		</table>
		<br>
		<!-- END IF: havehot -->

		<!-- START IF: articlerow -->
		<table border="0" width="100%" cellpadding="2" cellspacing="0">
			<!-- START: articlerow -->
			<tr><td>{articlerow:TITLE}<br><span class="date">{articlerow:DATE}</span></td></tr>
			<tr><td>{articlerow:THUMB_SMALL} <div align="justify">{articlerow:PREVIEW}</div></td></tr>
			<tr><td><hr size="1"></td></tr>
			<!-- END: articlerow -->
		</table>
		<br>
		<!-- END IF: articlerow -->
		
		<!-- START IF: nextrow -->
		<table border="0" width="100%" cellpadding="1" cellspacing="0">
			<!-- START: nextrow -->
			<tr><td width="3%" nowrap valign="top">&nbsp; +&nbsp;</td><td valign="top">{nextrow:TITLE}</td></tr>
			<!-- END: nextrow -->
		</table>
		<!-- END IF: nextrow -->
		
		<br><div align="right" class="pageNav">{PAGE_OUT}</div><br>

	</td>
	<td width="1%">&nbsp;</td>
	<!-- // CENTER END // -->
	
	<!-- // RIGHT // -->
    <td width="19%" valign="top" bgcolor="#EEF8ED" align="right">
		<table width="98%" border="1" style="border-color: #8CA257; border-collapse: collapse" cellpadding="1" cellspacing="0" bgcolor="#EEF8ED">
		  <tr>
			<td>
				<table bgcolor="#8CA257" width="100%"  border="0" cellspacing="0" cellpadding="1">
				  <tr>
					<td align="center" class="tdrightmenu"><a class="rightmenu" href="{U_EVENT}">{L_EVENT}</a></td>
				  </tr>
				</table>
			</td>
		  </tr>
		  <tr>
		    <td>
				<table width="100%" border="1" style="border-color: #FFFFFF; border-collapse: collapse" cellspacing="0" cellpadding="1">
					<tr>
						<td width="15%" class="eventmenu" align="center">{L_SUNDAY}</td>
						<td width="14%" class="eventmenu" align="center">{L_MONDAY}</td>
						<td width="14%" class="eventmenu" align="center">{L_TUESDAY}</td>
						<td width="14%" class="eventmenu" align="center">{L_WEDNESDAY}</td>
						<td width="14%" class="eventmenu" align="center">{L_THURDAY}</td>
						<td width="14%" class="eventmenu" align="center">{L_FRIDAY}</td>
						<td width="15%" class="eventmenu" align="center">{L_SATURDAY}</td>
					</tr>
					<!-- START: eventrow -->
					<tr>
						<!-- START: eventrow:eventcol -->
						<td class="event" align="center">{eventrow:eventcol:DAY}</td>
						<!-- END: eventrow:eventcol -->
					</tr>
					<!-- END: eventrow -->
					<tr><td height="18" colspan="7" class="eventbottom" align="center"><a class="eventbottom" href="{U_EVENT}">{CURRENT_MONTH_YEAR}</a></td></tr>
					</table>
			</td>
		  </tr>
		</table>
		<br>

		<!-- START: newspicture -->
		<table width="98%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="5" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_01.gif);"></td>
				<td colspan="3">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="background-image: url({TEMPLATE_PATH}/images/rightmenubg_01.gif);" align="center" valign="bottom" class="tdrightmenu"><a class="rightmenu" href="{U_NEWS_PICTURE}">{L_NEWS_PICTURE}</a></td>
							<td width="10" style="background-image: url({TEMPLATE_PATH}/images/rightmenubg_02.gif);"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="5"><img src="{TEMPLATE_PATH}/images/rightmenu_03.gif" width="5" height="5" alt=""></td>
				<td width="141" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_04.gif);"></td>
				<td colspan="2"><img src="{TEMPLATE_PATH}/images/rightmenu_05.gif" width="6" height="5" alt=""></td>
			</tr>
			<tr>
				<td width="5" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_06.gif);"></td>
				<td width="99%" bgcolor="#EEF8ED">
					<table width="100%" cellpadding="0" border="0">
					<tr><td align="center">
							<div id="div_NewsPicture" align="justify" onMouseOver="javascript: newspic_pause_start('ImgLoading_NewsPic', 'pause', 'T_NewsPic', '{TEMPLATE_PATH}');" onMouseOut="javascript: newspic_pause_start('ImgLoading_NewsPic', 'start', 'T_NewsPic', '{TEMPLATE_PATH}');">
								<center><a onclick="javascript: return openImage('{newspicture:PIC_FULL}', {newspicture:WIDTH}, {newspicture:HEIGHT});" href="#">{newspicture:PIC_THUMB}</a></center>
								<br>{newspicture:CONTENT}
							</div>
							<!-- START IF: global_newspicrow -->
							<center><br><img id="ImgLoading_NewsPic" src="{TEMPLATE_PATH}/images/ajax_loading.gif" border="0" alt="" onMouseOver="javascript: newspic_pause_start('ImgLoading_NewsPic', 'pause', 'T_NewsPic', '{TEMPLATE_PATH}');" onMouseOut="javascript: newspic_pause_start('ImgLoading_NewsPic', 'start', 'T_NewsPic', '{TEMPLATE_PATH}');"></center>
							<!-- END IF: global_newspicrow -->

							<script language="javascript" type="text/javascript">
								var newspic_info		= new Array();
								var newspic_total		= {newspicture:TOTAL_PICS};
								var newspic_start		= 0;
								var newspic_rand_time	= {newspicture:RAND_TIME} * 1000;

								<!-- START: global_newspicrow -->
								newspic_info[{global_newspicrow:NUM}]	= "newspic_display('{global_newspicrow:PIC_THUMB}', '{global_newspicrow:PIC_FULL}', '{global_newspicrow:WIDTH}', '{global_newspicrow:HEIGHT}', '{global_newspicrow:CONTENT}');";
								<!-- END: global_newspicrow -->

								//Start newspic
								if ( newspic_total >= 2 ){
									var T_NewsPic	= setTimeout("newspic_change()", newspic_rand_time);
								}
							</script>
						</td>
					</tr>
					</table>
				</td>
				<td width="6" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_08.gif);"></td>
				<td width="1" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_09.gif);"></td>
			</tr>
			<tr>
				<td colspan="2">
					<table width="100%" style="height: 11px;" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10"><img src="{TEMPLATE_PATH}/images/rightmenubottom_01.gif" width="10" height="11" alt=""></td>
							<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/rightmenubottom_02.gif);"></td>
						</tr>
					</table>
				</td>
				<td colspan="2"><img src="{TEMPLATE_PATH}/images/rightmenu_11.gif" width="6" height="11" alt=""></td>
			</tr>
		</table>
		<br>
		<!-- END: newspicture -->

		<!-- START IF: logorow -->
		<table width="98%" border="1" style="border-color: #8CA257; border-collapse: collapse" cellpadding="1" cellspacing="0" bgcolor="#EEF8ED">
		  <tr>
			<td>
				<table bgcolor="#8CA257" width="100%"  border="0" cellspacing="0" cellpadding="1">
				  <tr>
					<td align="center" class="tdrightmenu">{L_LOGO}</td>
				  </tr>
				</table>
			</td>
		  </tr>
		  <tr>
		    <td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td height="5"></td></tr>
				<!-- START: logorow -->
				<tr><td align="center"><a href="{logorow:U_VIEW}" target="_blank" title="{logorow:TITLE}">{logorow:IMG}</a></td></tr>
				<tr><td height="5"></td></tr>
				<!-- END: logorow -->
				</table>
			</td>
		  </tr>
		</table>
		<br>
		<!-- END IF: logorow -->
	
		<!-- START IF: hotarticlerow -->
		<table width="98%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="5" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_01.gif);"></td>
				<td width="99%" colspan="3">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="background-image: url({TEMPLATE_PATH}/images/rightmenubg_01.gif);" align="center" valign="bottom" class="tdrightmenu">{L_HOT_ARTICLES}</td>
							<td width="10" style="background-image: url({TEMPLATE_PATH}/images/rightmenubg_02.gif);"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="5"><img src="{TEMPLATE_PATH}/images/rightmenu_03.gif" width="5" height="5" alt=""></td>
				<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_04.gif);"></td>
				<td colspan="2" width="6"><img src="{TEMPLATE_PATH}/images/rightmenu_05.gif" width="6" height="5" alt=""></td>
			</tr>
			<!-- START: hotarticlerow -->
			<tr>
				<td width="5" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_06.gif);"></td>
				<td width="98%" bgcolor="#EEF8ED">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr><td height="5"><img src="{TEMPLATE_PATH}/images/spacer.gif" alt=""></td></tr>
					<tr><td>{hotarticlerow:THUMB_ICON} {hotarticlerow:TITLE}</td></tr>
					<tr><td height="5"><img src="{TEMPLATE_PATH}/images/spacer.gif" alt=""></td></tr>
					</table>
				</td>
				<td width="5" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_08.gif);"></td>
				<td width="1" style="background-image: url({TEMPLATE_PATH}/images/rightmenu_09.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" bgcolor="#8CA257"></td>
			</tr>
			<!-- END: hotarticlerow -->
		</table>
		<br>
		<!-- END IF: hotarticlerow -->

	</td>
	<!-- // RIGHT END // -->
  </tr>
  