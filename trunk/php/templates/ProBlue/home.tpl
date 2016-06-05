
          <tr> 
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="top"> 
                  <td width="150"> 
                    <!-- // left column //-->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td> 
                          <!--//  left menu //-->
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
                          <!--//  end left menu //-->
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
                    <!--//  end left column //-->
                  </td>

                  <td width="15">&nbsp;</td>
                  <td valign="top"> 
                    <!--//  middle column //-->
								<script language="javascript">
									function displayNewsBox(show_id, hide_id, hide_id2){
										document.getElementById(hide_id).style.display			= "none";
										document.getElementById(hide_id2).style.display			= "none";
										document.getElementById(show_id).style.display			= "";
									}
								</script>

								<div id="Div_FocusBox">
						  		<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td bgcolor="#D9EFFC">
									  	<table width="100%" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_blockblue.gif" class="backGX">
	                                      <tr> 
	                                        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
	                                            <tr> 
	                                              <td align="left" valign="top"> 
	                                                <table height="16" border="0" cellpadding="0" cellspacing="0" class="backGX">
	                                                  <tr align="center"> 
	                                                    <td width="110" class="titleBlockNewsM" background="{TEMPLATE_PATH}/images/bg_titleblock.gif"><a class="titleBlockNewsM" href="#" onClick="javascript: displayNewsBox('Div_FocusBox', 'Div_HotBox', 'Div_LatestBox'); return false;">.:: {L_FOCUS_NEWS} ::.</a></td>
														<td width="1" bgcolor="#6633cc"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1"></td>
	                                                    <td width="110" class="titleBlockNewsW"><a class="titleBlockNewsW" href="#" onClick="javascript: displayNewsBox('Div_HotBox', 'Div_FocusBox', 'Div_LatestBox'); return false;">.:: {L_HOT_NEWS} ::.</a></td>
														<td width="1" bgcolor="#6633cc"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1"></td>
	                                                    <td width="130" class="titleBlockNewsW"><a class="titleBlockNewsW" href="#" onClick="javascript: displayNewsBox('Div_LatestBox', 'Div_FocusBox', 'Div_HotBox'); return false;">.:: {L_LATEST_NEWS} ::.</a></td>
	                                                  </tr>
	                                              </table></td>
	                                            </tr>
	                                          </table></td>
	                                        <td width="9" valign="top"><img src="{TEMPLATE_PATH}/images/right_blockblue.gif" width="9" height="16"></td>
										  </tr>
										</table>

										<!-- START: focusArticle -->
										<table width="100%" border="0" cellpadding="10" cellspacing="0" class="mainText">
		                                  <tr> 
		                                    <td align="left">
												{focusArticle:PIC_THUMB}
												{focusArticle:TITLE}<br>
												<span class="date">{focusArticle:DATE}</span><br><br>  
												<div align="justify">{focusArticle:PREVIEW}</div>
											</td>
		                                  </tr>
										</table>
										<!-- END: focusArticle -->

										<!-- START NOIF: focusArticle -->
										&nbsp;
										<!-- END NOIF: focusArticle -->
									</td>
								</tr>
								<!-- START: focusArticle -->
								<tr><td height="8"></td></tr>
								<tr>
									<td>
										<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mainText">
											<!-- START: focusArticle:articlerow -->
			                                 <tr> 
			                                   <td><table width="100%" border="0" cellspacing="2" cellpadding="3">
			                                       <tr>
													<!-- START: focusArticle:articlerow:articlecol --> 
												    <td bgcolor="#D9EFFC" width="{FOCUS_TDWIDTH}" class="ntitle" valign="top" align="left">{focusArticle:articlerow:articlecol:PIC_ICON} {focusArticle:articlerow:articlecol:TITLE}</td>
														<!-- START IF: focusArticle:articlerow:articlecol:middle -->
														<td width="1%" nowrap>&nbsp;</td>
														<!-- END IF: focusArticle:articlerow:articlecol:middle -->
													<!-- END: focusArticle:articlerow:articlecol --> 
			                                       </tr>
			                                     </table></td>
			                                 </tr>
											<!-- END: focusArticle:articlerow -->
			                            </table>
									</td>
								</tr>
								<!-- END: focusArticle -->
								</table>
								</div>


								<div id="Div_HotBox" style="display: none">
						  		<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td bgcolor="#D9EFFC">
									  	<table width="100%" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_blockblue.gif" class="backGX">
	                                      <tr> 
	                                        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
	                                            <tr> 
	                                              <td align="left" valign="top"> 
	                                                <table height="16" border="0" cellpadding="0" cellspacing="0" class="backGX">
	                                                  <tr align="center"> 
	                                                    <td width="110" class="titleBlockNewsW"><a class="titleBlockNewsW" href="#" onClick="javascript: displayNewsBox('Div_FocusBox', 'Div_HotBox', 'Div_LatestBox'); return false;">.:: {L_FOCUS_NEWS} ::.</a></td>
														<td width="1" bgcolor="#6633cc"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1"></td>
	                                                    <td width="110" class="titleBlockNewsM" background="{TEMPLATE_PATH}/images/bg_titleblock.gif"><a class="titleBlockNewsM" href="#" onClick="javascript: displayNewsBox('Div_HotBox', 'Div_FocusBox', 'Div_LatestBox'); return false;">.:: {L_HOT_NEWS} ::.</a></td>
														<td width="1" bgcolor="#6633cc"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1"></td>
	                                                    <td width="130" class="titleBlockNewsW"><a class="titleBlockNewsW" href="#" onClick="javascript: displayNewsBox('Div_LatestBox', 'Div_FocusBox', 'Div_HotBox'); return false;">.:: {L_LATEST_NEWS} ::.</a></td>
	                                                  </tr>
	                                              </table></td>
	                                            </tr>
	                                          </table></td>
	                                        <td width="9" valign="top"><img src="{TEMPLATE_PATH}/images/right_blockblue.gif" width="9" height="16"></td>
										  </tr>
										</table>
	
										<br>
										<table width="96%" border="0" cellspacing="0" cellpadding="5">
											<!-- START: hotarticlerow -->
	                                        <tr><td align="left"><img src="{TEMPLATE_PATH}/images/icon_blue.gif" border="0"> <a class="otherNews" href="{hotarticlerow:U_VIEW}">{hotarticlerow:TITLE}</a></td></tr>
											<!-- END: hotarticlerow -->
	                                    </table>
									</td>
								</tr>
								</table>
								</div>


								<div id="Div_LatestBox" style="display: none;">
						  		<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td bgcolor="#D9EFFC">
									  	<table width="100%" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_blockblue.gif" class="backGX">
	                                      <tr> 
	                                        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
	                                            <tr> 
	                                              <td align="left" valign="top"> 
	                                                <table height="16" border="0" cellpadding="0" cellspacing="0" class="backGX">
	                                                  <tr align="center"> 
	                                                    <td width="110" class="titleBlockNewsW"><a class="titleBlockNewsW" href="#" onClick="javascript: displayNewsBox('Div_FocusBox', 'Div_HotBox', 'Div_LatestBox'); return false;">.:: {L_FOCUS_NEWS} ::.</a></td>
														<td width="1" bgcolor="#6633cc"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1"></td>
	                                                    <td width="110" class="titleBlockNewsW"><a class="titleBlockNewsW" href="#" onClick="javascript: displayNewsBox('Div_HotBox', 'Div_FocusBox', 'Div_LatestBox'); return false;">.:: {L_HOT_NEWS} ::.</a></td>
														<td width="1" bgcolor="#6633cc"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1"></td>
	                                                    <td width="130" class="titleBlockNewsM" background="{TEMPLATE_PATH}/images/bg_titleblock.gif"><a class="titleBlockNewsM" href="#" onClick="javascript: displayNewsBox('Div_LatestBox', 'Div_FocusBox', 'Div_HotBox'); return false;">.:: {L_LATEST_NEWS} ::.</a></td>
	                                                  </tr>
	                                              </table></td>
	                                            </tr>
	                                          </table></td>
	                                        <td width="9" valign="top"><img src="{TEMPLATE_PATH}/images/right_blockblue.gif" width="9" height="16"></td>
										  </tr>
										</table>
	
										<br>
										<table width="96%" border="0" cellspacing="0" cellpadding="5">
											<!-- START: latestarticlerow -->
	                                        <tr><td align="left"><img src="{TEMPLATE_PATH}/images/icon_blue.gif" border="0"> <a class="otherNews" href="{latestarticlerow:U_VIEW}">{latestarticlerow:TITLE}</a></td></tr>
											<!-- END: latestarticlerow -->
	                                    </table>
									</td>
								</tr>
								</table>
								</div>

								<br>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
							    <!-- START: catrow -->
	                            <tr> 
								  <!-- START: catrow:catcol -->
							      <td width="{CAT_TDWIDTH}" valign="top">
								  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	                                  <tr> 
	                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
	                                        <tr> 
	                                          <td>
												<!-- START: catrow:catcol:leftcol -->
											  	<table width="100%" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_blockblue.gif" class="backGX">
	                                              <tr> 
	                                                <td width="15" valign="top"><img src="{TEMPLATE_PATH}/images/left_blockblue.gif" width="15" height="16"></td>
	                                                <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
	                                                    <tr> 
	                                                      <td align="left" valign="top"> 
	                                                        <table height="16" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_titleblock.gif" class="backGX">
	                                                          <tr> 
	                                                            <td width="5"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
	                                                            <td class="titleBlockNewsM">{catrow:catcol:CAT_NAME}</td>
	                                                            <td width="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
	                                                            <td width="5" bgcolor="#FFFFFF"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
	                                                          </tr>
	                                                      </table></td>
	                                                      <td width="45"><a class="more" href="{catrow:catcol:U_VIEW}">{L_VIEW_MORE}</a></td>
	                                                    </tr>
	                                                  </table></td>
	                                                <td width="9" valign="top"><img src="{TEMPLATE_PATH}/images/right_blockblue.gif" width="9" height="16"></td>
	                                              </tr>
	                                            </table>
												<!-- END: catrow:catcol:leftcol -->
	
												<!-- START: catrow:catcol:rightcol -->
												<table width="100%" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_blockorange.gif" class="backGX">
	                                              <tr> 
	                                                <td width="14" valign="top"><img src="{TEMPLATE_PATH}/images/left_blockorange.gif" width="14" height="16"></td>
	                                                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	                                                    <tr> 
	                                                      <td align="left"><table height="16" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_titleblock.gif" class="backGX">
	                                                          <tr> 
	                                                            <td width="5"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
	                                                            <td class="titleBlockNewsM">{catrow:catcol:CAT_NAME}</td>
	                                                            <td width="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
	                                                            <td width="5" bgcolor="#FFFFFF"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
	                                                          </tr>
	                                                        </table></td>
	                                                      <td width="45"><a class="more" href="{catrow:catcol:U_VIEW}">{L_VIEW_MORE}</a></td>
	                                                    </tr>
	                                                  </table></td>
	                                                <td width="9" valign="top"><img src="{TEMPLATE_PATH}/images/right_blockorange.gif" width="9" height="16"></td>
	                                              </tr>
	                                            </table>
												<!-- END: catrow:catcol:rightcol -->
											  </td>
	                                        </tr>
	
											<!-- START IF: catrow:catcol:hotrow -->
										    <!-- START: catrow:catcol:hotrow -->
	                                        <tr> 
	                                          <td><table width="100%" border="0" cellpadding="0" cellspacing="5" class="mainText">
	                                              <tr> 
	                                                <td align="left">
														{catrow:catcol:hotrow:TITLE}<br>
														<span class="date">{catrow:catcol:hotrow:DATE}</span><br>
														<div align="justify">{catrow:catcol:hotrow:THUMB_SMALL} {catrow:catcol:hotrow:PREVIEW}</div>
													</td>
	                                              </tr>
	                                            </table></td>
	                                        </tr>
	                                        <tr> 
	                                          <td height="10" background="{TEMPLATE_PATH}/images/dotline_gray.gif" class="backGXM"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
	                                        </tr>
										    <!-- END: catrow:catcol:hotrow -->
	
	                                        <tr> 
	                                          <td><table width="100%" border="0" cellspacing="5" cellpadding="0">
												  <!-- START: catrow:catcol:articlerow -->
	                                              <tr> 
	                                                <td width="10" align="center" valign="top"><img src="{TEMPLATE_PATH}/images/icon_blue.gif" width="6" height="6" vspace="5"></td>
	                                                <td align="left">{catrow:catcol:articlerow:TITLE}</td>
	                                              </tr>
												  <!-- END: catrow:catcol:articlerow -->
	                                            </table></td>
	                                        </tr>
											<!-- END IF: catrow:catcol:hotrow -->
	                                      </table></td>
	                                  </tr>
	                                </table>
								  <!-- START IF: catrow:catcol:middle -->
								  <td width="2%" nowrap>&nbsp; &nbsp;</td>
								  <!-- END IF: catrow:catcol:middle -->
								  <!-- END: catrow:catcol -->
	                            </tr>
							    <!-- END: catrow -->
	                          </table>

                    <!--//  end middle column //-->
                  </td>

                  <td width="15">&nbsp;</td>
                  <td width="150" valign="top">
                    <!--//  right column //-->
	                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
                    <!--//  end right column //-->
                  </td>
                </tr>
              </table></td>
          </tr>
