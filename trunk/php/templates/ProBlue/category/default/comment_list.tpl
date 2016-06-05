
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
					  <!-- END IF: newsletter_enabled -->

					  <!-- START: hotcatrow -->
                      <tr> 
                        <td height="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1" /></td>
                      </tr>
                      <tr> 
                        <td> 
	                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                            <tr> 
	                              <td height="22" background="{TEMPLATE_PATH}/images/bg_titleleftblock.gif"><table width="100%" height="22" border="0" cellpadding="0" cellspacing="0">
	                                  <tr> 
	                                    <td width="15">&nbsp;</td>
	                                    <td align="left" class="leftTitleblock">::| <a class="leftTitleblock" href="{hotcatrow:U_VIEW}"><strong>{hotcatrow:NAME}</strong></a></td>
	                                  </tr>
	                                </table></td>
	                            </tr>
	                            <tr> 
	                              <td background="{TEMPLATE_PATH}/images/bg_leftblock.gif"><table width="100%" border="0" cellspacing="5" cellpadding="0">
	                                  <tr> 
	                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
	                                        <tr> 
	                                          <td align="center">
													<div class="hotcatcontent">{hotcatrow:ARTICLE_THUMB}<br>
													<strong>{hotcatrow:ARTICLE_TITLE}</strong></div>
											  </td>
	                                        </tr>
	                                        <tr> 
	                                          <td height="10" align="left" background="{TEMPLATE_PATH}/images/dotline_left.gif" class="backGXM"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
	                                        </tr>
	                                      </table></td>
	                                  </tr>
	                                </table></td>
	                            </tr>
	                            <tr> 
	                              <td height="17" background="{TEMPLATE_PATH}/images/bottom_leftblock.gif" class="backG"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	                                  <tr> 
	                                    <td>&nbsp;</td>
	                                    <td width="70" align="center"><a class="more" href="{hotcatrow:U_VIEW}">{L_VIEW_MORE}</a></td>
	                                  </tr>
	                                </table></td>
	                            </tr>
	                          </table>
                        </td>
                      </tr>
					  <!-- END: hotcatrow -->

                      <tr> 
                        <td>&nbsp;</td>
                      </tr>

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
                              <td><img src="{TEMPLATE_PATH}/images/bottom_leftlogo.gif" width="150" height="19"></td>
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
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainText">
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
								                <td class="catnav">{L_COMMENT}</td>
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

								<strong>&raquo;</strong> <a class="btitle" href="{U_VIEW_ARTICLE}">{ARTICLE_TITLE}</a><br><br>
						
								<!-- START: commentrow -->
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr><td height="1" bgcolor="#77A7AD"><img src="spacer.gif" width=1 height=1></td></tr>
									<tr><td>
											<table width="100%" cellspacing="0" cellpadding="3" border="0">
											<tr><td bgcolor="#EEEEEE" colspan="2"><span class="mtitle">{commentrow:TITLE}</span></td></tr>
											<tr><td class="date"><strong><a class="date" href="mailto:{commentrow:EMAIL}"><img src="{TEMPLATE_PATH}/images/comment_2.gif" ALT="{commentrow:EMAIL}" border=0></a> {commentrow:NAME}</strong></td><td align="right" nowrap><span class="date">{commentrow:DATE}</span></td></tr>
											<tr><td colspan="2"><div align="justify"><br>{commentrow:CONTENT}&nbsp;</div></td></tr>
											</table>
									</td></tr>
									<tr><td height="1" bgcolor="#77A7AD"><img src="spacer.gif" width=1 height=1></td></tr>
								</table>
								<br><br>
								<!-- END: commentrow -->
								
								<div align="right">{PAGE_OUT}&nbsp;</div>
						
								<a name="comment"></a>
								<img src="{TEMPLATE_PATH}/images/comment_1.gif" border=0> <strong>{L_COMMENT_NEW}:</strong><br><br>
								<div align="center"><span class="errorMsg">{ERROR_MSG}</span></div>
								<form name="COMMENTFORM" method="POST" action="{S_COMMENT_ACTION}" onsubmit="javascript: return checkCmForm(this);">
								<input type="hidden" name="flag" value="post">
								<input type="hidden" name="number_id" value="{NUMBER_ID}">
								<table width="100%" border="0" cellspacing="0" cellpadding="5">
								<tr><td class="tdtext1">
									<table width="100%" border="0" cellspacing="0" cellpadding="10">
									<tr><td class="tdtext2">
										<table width="100%" border="0" cellpadding="2" cellspacing="0">
											<tr><td>{L_NAME}:</td><td><input class="myForm" type="text" name="name" value="{CM_NAME}" size="35"></td></tr>
											<tr><td>{L_EMAIL}:</td><td><input class="myForm" type="text" name="email" value="{CM_EMAIL}" size="35"></td></tr>
											<tr><td>{L_TITLE}:</td><td><input class="myForm" type="text" name="title" value="{CM_TITLE}" size="70"></td></tr>
											<tr><td valign="top">{L_CONTENT}:</td><td><textarea class="myForm" name="content" cols="70" rows="10">{CM_CONTENT}</textarea></td></tr>
											<tr><td>&nbsp;{L_NUMBER}</td><td><input class="myForm" type="text" name="number_value" size="10"> {IMAGE_NUMBER}</td></tr>
											<tr><td>&nbsp;</td><td><br><input class="mySubmit" type="submit" name="smcomment" value="{L_COMMENT_SUBMIT}"></td></tr>
										</table>
									</td></tr>
									</table>
								</td></tr>
								</table>
								</form>
								<br><br>
						
								<script language="javascript" type="text/javascript">
									function checkCmForm(the_form){
										var name	= the_form.name.value;
										var email	= the_form.email.value;
										var title	= the_form.title.value;
										var content	= the_form.content.value;
										
										if ( (name == "") || (email == "") || (title == "") || (content == "") ){
											alert("{L_ERROR_NOT_FULL}");
											the_form.name.focus();
											return false;
										}
										return true;
									}
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

					  <!-- START IF: event_enabled -->
                      <tr> 
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td height="17" class="backG"><table width="100%" height="17" border="0" cellpadding="0" cellspacing="0" class="titleRBlock">
                                  <tr> 
                                    <td width="4"><img src="{TEMPLATE_PATH}/images/topcalendar_01.gif" width="4" height="17" alt=""></td>
                                    <td background="{TEMPLATE_PATH}/images/topcalendar_02.gif">::| <a class="titleRBlock" href="{U_EVENT}">{L_EVENT}</a></td>
                                    <td width="8"><img src="{TEMPLATE_PATH}/images/topcalendar_03.gif" width="8" height="17" alt=""></td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr> 
                              <td class="borderLR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr> 
                                    <td bgcolor="#4D9EEF"><table width="100%" height="20" border="0" cellpadding="0" cellspacing="0" class="calendar">
                                        <tr> 
                                          <td align="right"><a class="calendar" href="{U_EVENT}"><strong>{CURRENT_MONTH_YEAR}</strong></a></td>
                                          <td width="10">&nbsp;</td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                  <tr> 
                                    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" class="calendar">
                                        <tr align="center" bgcolor="#FFCC00"> 
                                          <td width="15%" height="16"><strong>{L_SUNDAY}</strong></td>
                                          <td width="14%" height="16"><strong>{L_MONDAY}</strong></td>
                                          <td width="14%" height="16"><strong>{L_TUESDAY}</strong></td>
                                          <td width="14%" height="16"><strong>{L_WEDNESDAY}</strong></td>
                                          <td width="14%" height="16"><strong>{L_THURDAY}</strong></td>
                                          <td width="14%" height="16"><strong>{L_FRIDAY}</strong></td>
                                          <td width="15%" height="16"><strong>{L_SATURDAY}</strong></td>
                                        </tr>
										<!-- START: eventrow -->
                                        <tr align="center" bgcolor="#f2f2f2"> 
											<!-- START: eventrow:eventcol -->
												<!-- START IF: eventrow:eventcol:havecol -->
											<td height="16">{eventrow:eventcol:DAY}</td>
												<!-- END IF: eventrow:eventcol:havecol -->
												<!-- START NOIF: eventrow:eventcol:havecol -->
											<td height="16" bgcolor="#CCCCCC">&nbsp;</td>
												<!-- END NOIF: eventrow:eventcol:havecol -->
											<!-- END: eventrow:eventcol -->
										</tr>
										<!-- END: eventrow -->
                                      </table></td>
                                  </tr>
                                  <tr> 
                                    <td bgcolor="#EAEAEA">&nbsp;</td>
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
					  <!-- END IF: event_enabled -->

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
