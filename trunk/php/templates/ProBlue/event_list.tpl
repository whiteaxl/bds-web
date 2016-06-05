
          <tr> 
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="top"> 
                  <td width="150"> 
                    <!--  left col -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td> 
                          <!--  left menu -->
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td><table width="100%" height="20" border="0" cellpadding="0" cellspacing="0">
                                  <tr> 
                                    <td width="4"><img src="{TEMPLATE_PATH}/images/bg_navigation_01.gif" width="4" height="20" alt=""></td>
                                    <td background="{TEMPLATE_PATH}/images/bg_navigation_02.gif" align="right"><a class="leftTitleblock2" href="{U_ALL_NEWS}">{L_ALL_NEWS}</a> &nbsp;</td>
                                    <td width="24"><img src="{TEMPLATE_PATH}/images/bg_navigation_03.gif" width="24" height="20" alt=""></td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr> 
                              <td height="2"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                            </tr>
							<!-- START: menucatrow -->
                            <tr> 
                              <td height="21" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr> 
                                    <td width="16"><img src="{TEMPLATE_PATH}/images/bgleftmenu_01.gif" width="16" height="21" alt=""></td>
                                    <td style="padding-left: 2px; padding-right: 2px;" background="{TEMPLATE_PATH}/images/bgleftmenu_02.gif">{menucatrow:PREFIX} <a class="leftMenu" href="{menucatrow:U_VIEW}">{menucatrow:NAME}</a></td>
                                    <td width="1"><img src="{TEMPLATE_PATH}images/bgleftmenu_03.gif" width="1" height="21" alt=""></td>
                                  </tr>
                                </table></td>
                            </tr>
								<!-- START: menucatrow:subcatrow -->
	                            <tr> 
	                              <td align="left" bgcolor="#99CCFF"><table width="100%" border="0" cellpadding="0" cellspacing="2" class="leftsubMenu">
	                                  <tr> 
	                                    <td width="15" align="center" nowrap>{menucatrow:subcatrow:PREFIX}&raquo;</td>
	                                    <td align="left"><a class="leftSubMenu" href="{menucatrow:subcatrow:U_VIEW}">{menucatrow:subcatrow:NAME}</a></td>
	                                  </tr>
	                                </table></td>
	                            </tr>
								<!-- END: menucatrow:subcatrow -->
							<!-- END: menucatrow -->
                            <tr> 
                              <td height="2"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                            </tr>
                            <tr> 
                              <td height="8">
									<table width="100%" height="8" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="9"><img src="{TEMPLATE_PATH}/images/bottom_leftmenu_01.gif" width="9" height="8" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bottom_leftmenu_02.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
										<td width="10"><img src="{TEMPLATE_PATH}/images/bottom_leftmenu_03.gif" width="10" height="8" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                          </table>
                          <!--  end left menu -->
                        </td>
                      </tr>
                      <tr> 
                        <td height="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1" /></td>
                      </tr>

					  <!-- START: pollrow -->
                      <tr> 
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td height="22"><table width="100%" height="22" border="0" cellpadding="0" cellspacing="0">
                                  <tr> 
                                    <td width="15"><img src="{TEMPLATE_PATH}/images/bg_titleleftblock_01.gif" width="15" height="22" alt=""></td>
                                    <td background="{TEMPLATE_PATH}/images/bg_titleleftblock_02.gif" align="left" class="leftTitleblock">::| {L_POLL}</td>
                                    <td width="6"><img src="{TEMPLATE_PATH}/images/bg_titleleftblock_03.gif" width="6" height="22" alt=""></td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr> 
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr> 
								    <td width="1" bgcolor="#2B77DB"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                                    <td bgcolor="#D8EFFC" style="padding: 5px;"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="mainText">
										<form name="POLL_{pollrow:ID}" target="POLL_{pollrow:ID}" method="POST" action="{pollrow:S_ACTION}" onsubmit="javascript: return vote_poll(document.POLL_{pollrow:ID});">
                                          <tr> 
                                            <td><strong>{pollrow:QUESTION}</strong></td>
                                          </tr>
                                          <tr> 
                                            <td height="5"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                                          </tr>
                                          <tr> 
                                            <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="mainText">
											    <!-- START: pollrow:optionrow -->
                                                <tr> 
                                                  <td width="20" height="20" align="center"><input type="{pollrow:MULTIPLE}" name="{pollrow:optionrow:INPUTNAME}" value="{pollrow:optionrow:ID}"></td>
                                                  <td height="20" align="left">{pollrow:optionrow:CONTENT}</td>
                                                </tr>
											    <!-- END: pollrow:optionrow -->
                                              </table></td>
                                          </tr>
                                          <tr> 
                                            <td height="5" align="right"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                                          </tr>
                                          <tr> 
                                            <td align="right">
												<input name="smVote" type="image" src="{TEMPLATE_PATH}/images/vote.gif" width="62" height="24" border="0">
												<input name="smResult" type="image" src="{TEMPLATE_PATH}/images/result.gif" width="62" height="24" border="0"> 
											</td>
                                          </tr>
                                        </form>
                                      </table></td>
								    <td width="1" bgcolor="#2B77DB"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr> 
                              <td height="17" class="backG">
									<table width="100%" height="17" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="1"><img src="{TEMPLATE_PATH}/images/bottom_leftblock_01.gif" width="1" height="17" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bottom_leftblock_02.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
										<td width="83"><img src="{TEMPLATE_PATH}/images/bottom_leftblock_03.gif" width="83" height="17" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr> 
                        <td height="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1" /></td>
                      </tr>
					  <!-- END: pollrow -->

						<script language="javascript" type="text/javascript">
							function vote_poll(the_form){
								var width		= 520;
								var height		= 400;
								var top_val		= (screen.height - height)/2 - 30;
								if (top_val < 0){ top_val	= 0; }
								var left_val	= (screen.width - width)/2;
							
								window.open('', the_form.name, "toolbar=0,location=0,status=1,menubar=0,scrollbars=1,resizable=1,width="+ width +",height="+ height +", top="+ top_val +",left="+ left_val);
								the_form.submit();
								return false;
							}	
						</script>

					  <!-- START IF: newsletter_enabled -->
                      <tr> 
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td height="22"><table width="100%" height="22" border="0" cellpadding="0" cellspacing="0">
                                  <tr> 
                                    <td width="15"><img src="{TEMPLATE_PATH}/images/bg_titleleftblock_01.gif" width="15" height="22" alt=""></td>
                                    <td background="{TEMPLATE_PATH}/images/bg_titleleftblock_02.gif" align="left" class="leftTitleblock">::| {L_NEWSLETTER}</td>
                                    <td width="6"><img src="{TEMPLATE_PATH}/images/bg_titleleftblock_03.gif" width="6" height="22" alt=""></td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr> 
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr> 
								    <td width="1" bgcolor="#2B77DB"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                                    <td bgcolor="#D8EFFC" style="padding: 5px;" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="mainText">
										<form name="NEWSLTFORM" target="NEWSLTFORM" method="POST" action="{S_NEWSLETTER}" onsubmit="javascript: return check_newsltform(document.NEWSLTFORM);">
                                          <tr>
										    <td align="left">
												<select class="myForm" name="cat_id" style="width: 135px">
													<option value="0">- - - {L_CAT} - - -</option>
													<!-- START: newsltcatrow -->
													<option value="{newsltcatrow:ID}">{newsltcatrow:PREFIX} {newsltcatrow:NAME}</option>
													<!-- END: newsltcatrow -->
												</select>
											</td>
										  </tr>
										  <tr><td align="left" height="38">{L_YOUR_NAME}:<br><input class="myForm" type="text" name="name" value="" size="18" style="width: 130px"></td></tr>
										  <tr><td align="left">{L_YOUR_EMAIL}:<br><input class="myForm" type="text" name="email" value="" size="18" style="width: 130px"></td></tr>
                                          <tr><td height="5" align="right"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td></tr>
                                          <tr><td align="right"><input name="smNewsletter" type="image" src="{TEMPLATE_PATH}/images/submit.gif" width="62" height="24" border="0"></td></tr>
                                        </form>
                                      </table></td>
								    <td width="1" bgcolor="#2B77DB"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr> 
                              <td height="17" class="backG">
									<table width="100%" height="17" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="1"><img src="{TEMPLATE_PATH}/images/bottom_leftblock_01.gif" width="1" height="17" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bottom_leftblock_02.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
										<td width="83"><img src="{TEMPLATE_PATH}/images/bottom_leftblock_03.gif" width="83" height="17" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr> 
                        <td>&nbsp;</td>
                      </tr>
					  <!-- END IF: newsletter_enabled -->

					  <!-- START IF: left_logorow -->
                      <tr> 
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td class="borderLRT"><table width="100%" border="0" cellspacing="4" cellpadding="0">
								  <!-- START: left_logorow -->
                                  <tr><td align="center">{left_logorow:LOGO}</td></tr>
								  <!-- END: left_logorow -->
                                </table></td>
                            </tr>
                            <tr> 
                              <td height="19">
									<table width="100%" height="19" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="5"><img src="{TEMPLATE_PATH}/images/bottom_leftlogo_01.gif" width="5" height="19" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bottom_leftlogo_02.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
										<td width="12"><img src="{TEMPLATE_PATH}/images/bottom_leftlogo_03.gif" width="12" height="19" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr> 
                        <td>&nbsp;</td>
                      </tr>
					  <!-- END IF: left_logorow -->

                    </table>
                    <!--  end left col -->
                  </td>
                  <td width="15">&nbsp;</td>
                  <td> 
                    <!--  middle col -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td align="left">

								<table width="100%" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_blockblue.gif" class="backGX">
								  <tr> 
								    <td width="15" valign="top"><img src="{TEMPLATE_PATH}/images/left_blockblue.gif" width="15" height="16"></td>
								    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
								        <tr> 
								          <td align="left" valign="top"> 
								            <table height="16" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_titleblock.gif" class="backGX">
								              <tr> 
								                <td width="5"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
								                <td class="catnav">{L_EVENT}</td>
								                <td width="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
								                <td width="5" bgcolor="#FFFFFF"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
								              </tr>
								          </table></td>
								          <td width="45" class="more" align="right">&nbsp;</td>
								        </tr>
								      </table></td>
								    <td width="9" valign="top"><img src="{TEMPLATE_PATH}/images/right_blockblue.gif" width="9" height="16"></td>
								  </tr>
								  <tr><td height="8"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td></tr>
								</table>

								<form name="EVENTFORM" method="POST" action="{U_EVENT}">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainText">
								<tr>
									<td><a href="{U_PREV_MONTH}"><img src="{TEMPLATE_PATH}/images/arrow_prev.gif" border="0" ALT="{L_PREV_MONTH}" align="bottom"></a>&nbsp;</td>
									<td align="center" nowrap>
									<!-- {CURRENT_MONTH_YEAR} -->
									<select class="myForm" size="1" name="month">
										<!-- START: monthrow -->
								        <option value="{monthrow:NUM}">{monthrow:NAME}</option>
										<!-- END: monthrow -->
							        </select>
						
									<input class="myForm" type="text" name="year" value="{EVENT_YEAR}" size="4" maxlength="4" style="width: 50px; height: 14px">
									<input class="mySubmit" type="submit" name="eventBut" value="{L_GO}">	
									</td>
									<td align="right"><a href="{U_NEXT_MONTH}"><img src="{TEMPLATE_PATH}/images/arrow_next.gif" border="0" ALT="{L_NEXT_MONTH}" align="absbottom"></a>&nbsp;</td>
								</tr>
								<tr><td colspan="3" height="3"></td></tr>
								</table>

								<table class="mainText" width="100%" border="1" style="border-color: #8CA257; border-collapse: collapse" cellpadding="1" cellspacing="0" bgcolor="#EEF8ED">
								  <tr>
								    <td>
										<table class="calendar" width="100%" border="0" style="border-color: #FFFFFF; border-collapse: collapse" cellspacing="2" cellpadding="1">
											<tr height="16" bgcolor="#FFCC00">
												<td width="15%" align="center"><strong>{L_SUNDAY}</strong></td>
												<td width="14%" align="center"><strong>{L_MONDAY}</strong></td>
												<td width="14%" align="center"><strong>{L_TUESDAY}</strong></td>
												<td width="14%" align="center"><strong>{L_WEDNESDAY}</strong></td>
												<td width="14%" align="center"><strong>{L_THURDAY}</strong></td>
												<td width="14%" align="center"><strong>{L_FRIDAY}</strong></td>
												<td width="15%" align="center"><strong>{L_SATURDAY}</strong></td>
											</tr>
											<!-- START: maineventrow -->
											<tr height="16" bgcolor="#E4E3E3"> 
												<!-- START: maineventrow:eventcol -->
													<!-- START IF: maineventrow:eventcol:havecol -->
												<td align="center">{maineventrow:eventcol:DAY}</td>
													<!-- END IF: maineventrow:eventcol:havecol -->
													<!-- START NOIF: maineventrow:eventcol:havecol -->
												<td bgcolor="#f2f2f2">&nbsp;</td>
													<!-- END NOIF: maineventrow:eventcol:havecol -->
												<!-- END: maineventrow:eventcol -->
											</tr>
											<!-- END: meventrow -->
											</table>
									</td>
								  </tr>
								</table>
								</form>
								
								<script language="javascript" type="text/javascript">
									the_form	= window.document.EVENTFORM;
									
									select_list("{EVENT_MONTH}", the_form.month);
								</script>

						  </td>
                      </tr>
                    </table>
                    <!--  end middle col -->
                  </td>
                  <td width="15">&nbsp;</td>
                  <td width="150"> 
                    <!--  right col -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">

					  <!-- START: globalLatestBox -->
                      <tr> 
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td height="22">
									<table width="100%" height="22" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="4"><img src="{TEMPLATE_PATH}/images/bg_titlerightblock_01.gif" width="4" height="22" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bg_titlerightblock_02.gif" class="rightTitleblock">::| {L_LATEST_BOX}</td>
										<td width="8"><img src="{TEMPLATE_PATH}/images/bg_titlerightblock_03.gif" width="8" height="22" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                            <tr> 
                              <td class="borderLR2" align="center" height="130">{globalLatestBox:MESSAGE}
									<!-- START IF: globalLatestBox:Articlerow -->
									<marquee width="90%" height="130" direction=up scrollamount="2" onmouseover="this.stop()" onmouseout="this.start()">
									<table id="TBL_Latest" style="display: none" width="100%" border="0" cellspacing="0" cellpadding="0">
										<!-- START: globalLatestBox:Articlerow -->
                                        <tr> 
                                          <td align="center"><a class="otherNews" href="{globalLatestBox:Articlerow:U_VIEW}">{globalLatestBox:Articlerow:THUMB_SMALL} {globalLatestBox:Articlerow:TITLE}</a></td>
                                        </tr>
                                        <tr> 
                                          <td height="10" align="left" background="{TEMPLATE_PATH}/images/dotline_gray.gif" class="backGXM"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                                        </tr>
										<!-- END: globalLatestBox:Articlerow -->
                                    </table>
									</marquee>
									<!-- END IF: globalLatestBox:Articlerow -->
							  </td>
                            </tr>
                            <tr> 
                              <td height="12">
									<table width="100%" height="12" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="13"><img src="{TEMPLATE_PATH}/images/bottom_rightblock_01.gif" width="13" height="12" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bottom_rightblock_02.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
										<td width="3"><img src="{TEMPLATE_PATH}/images/bottom_rightblock_03.gif" width="3" height="12" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr> 
                        <td height="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                      </tr>
					  <!-- END: globalLatestBox -->

					  <!-- START: newspicture -->
                      <tr> 
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td width="100%" height="37" valign="bottom">
                                <table background="{TEMPLATE_PATH}/images/newspic_02.gif" height="37" width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="149" background="{TEMPLATE_PATH}/images/newspic_01.gif" align="center" valign="bottom" class="titleRBlock"><a class="titleRBlock" href="{U_NEWS_PICTURE}">{L_NEWS_PICTURE}</a></td>
			                        <td><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
									<td width="1"><img src="images/newspic_04.gif" width="1" height="37" alt=""></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr> 
                              <td class="borderLR2"><table width="100%" border="0" cellspacing="4" cellpadding="0">
                                  <tr> 
                                    <td align="left" class="mainText">
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
                                </table></td>
                            </tr>
                            <tr> 
                              <td height="12">
									<table width="100%" height="12" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="13"><img src="{TEMPLATE_PATH}/images/bottom_rightblock_01.gif" width="13" height="12" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bottom_rightblock_02.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
										<td width="3"><img src="{TEMPLATE_PATH}/images/bottom_rightblock_03.gif" width="3" height="12" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr> 
                        <td height="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                      </tr>
					  <!-- END: newspicture -->

					  <!-- START IF: hotarticlerow -->
                      <tr> 
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td height="22">
									<table width="100%" height="22" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="4"><img src="{TEMPLATE_PATH}/images/bg_titlerightblock_01.gif" width="4" height="22" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bg_titlerightblock_02.gif" class="rightTitleblock">::| {L_HOT_ARTICLES}</td>
										<td width="8"><img src="{TEMPLATE_PATH}/images/bg_titlerightblock_03.gif" width="8" height="22" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                            <tr> 
                              <td class="borderLR2"><table width="100%" border="0" cellspacing="4" cellpadding="0">
                                  <tr> 
                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
										<!-- START: hotarticlerow -->
                                        <tr> 
                                          <td align="left">{hotarticlerow:THUMB_ICON} {hotarticlerow:TITLE}</td>
                                        </tr>
                                        <tr> 
                                          <td height="10" align="left" background="{TEMPLATE_PATH}/images/dotline_gray.gif" class="backGXM"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                                        </tr>
										<!-- END: hotarticlerow -->
                                      </table></td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr> 
                              <td height="12">
									<table width="100%" height="12" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="13"><img src="{TEMPLATE_PATH}/images/bottom_rightblock_01.gif" width="13" height="12" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bottom_rightblock_02.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
										<td width="3"><img src="{TEMPLATE_PATH}/images/bottom_rightblock_03.gif" width="3" height="12" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr> 
                        <td height="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                      </tr>
					  <!-- END IF: hotarticlerow -->

					  <!-- START IF: right_logorow -->
                      <tr> 
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td class="borderLRT"><table width="100%" border="0" cellspacing="4" cellpadding="0">
								  <!-- START: right_logorow -->
                                  <tr><td align="center">{right_logorow:LOGO}</td></tr>
								  <!-- END: right_logorow -->
                                </table></td>
                            </tr>
                            <tr> 
                              <td height="12">
									<table width="100%" height="12" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="13"><img src="{TEMPLATE_PATH}/images/bottom_rightblock_01.gif" width="13" height="12" alt=""></td>
										<td background="{TEMPLATE_PATH}/images/bottom_rightblock_02.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
										<td width="3"><img src="{TEMPLATE_PATH}/images/bottom_rightblock_03.gif" width="3" height="12" alt=""></td>
									</tr>
									</table>
							  </td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr> 
                        <td height="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                      </tr>
					  <!-- END IF: right_logorow -->

                    </table>
                    <!--  end right col -->
                  </td>
                </tr>
              </table></td>
          </tr>
