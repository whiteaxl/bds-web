
          <tr> 
            <td height="6"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr> 
            <td> 
              <!--  footer -->
			  <br>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="11" background="{TEMPLATE_PATH}/images/bottom_topmenu.gif"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                </tr>
                <tr> 
                  <td height="68" valign="top" background="{TEMPLATE_PATH}/images/bg_footer.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
						<td width="30">&nbsp;</td>
						<td align="left" class="footer">
							:: <a class="footerMenu" href="{U_ARCHIVE}"><strong>{L_ARCHIVE}</strong></a> &nbsp;|&nbsp; <a class="footerMenu" href="{U_ADVANCE_SEARCH}"><strong>{L_SEARCH}</strong></a> :: <br><br>
							Powered by <strong>{SCRIPT_NAME}</strong>
							<br>Copyright &#169; 2005-2008 <strong> [iAG] Nulled</strong>. All rights reserved.
						</td>
                        <td width="270" height="68" align="right" valign="top" background="{TEMPLATE_PATH}/images/bgright_footer.jpg"><table width="88%" border="0" cellpadding="0" cellspacing="0">
                            <tr> 
							  <form name="TEMPLATEFORM" method="POST" action="{S_TEMPLATE_UPDATE}">
                              <td height="30" align="left" class="soso">SOSO NEWS EXPRESS</td>
                              <td height="30" align="right" class="soso">
										<select class="myForm" name="global_template_id" style="width: 100px" onchange="javascript: changeTemplate();">
											<option value="">- {L_TEMPLATE} -</option>
											<!-- START: templaterow -->
											<option value="{templaterow:NAME}">{templaterow:NAME}</option>
											<!-- END: templaterow -->
										</select>
									<script language="javascript">
										var template_form	= document.TEMPLATEFORM;
										select_list("{GLOBAL_TEMPLATE_ID}", template_form.global_template_id);
										function changeTemplate(){
											if (template_form.global_template_id.options[template_form.global_template_id.selectedIndex].value != ""){
												template_form.submit();
											}
										}
									</script>
							  </td>
							  </form>
                            </tr>
                            <tr>
                              <td colspan="2" height="35" align="center" class="soso">
							  		<a href="javascript: gotoTop();" class="more">[{L_TOP_PAGE}]</a>
							  </td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              <!--  end footer -->
            </td>
          </tr>
        </table></td>
    </tr>
  </table>
</center>

<!-- START: ajax_update_statistic -->
<script language="javascript" type="text/javascript">
	var the_location	= window.location + "";
	var statistic_url	= "{ajax_update_statistic:U_STATISTIC}";
	if (the_location.indexOf("http://www.") != -1){ //Found www in the browser address
		if (statistic_url.indexOf("http://www.") == -1){ //Not found www in the ajax link
			statistic_url	= statistic_url.replace("http://", "http://www."); //Add www
		}
	}
	else{ //Not found www in the browser address
		if (statistic_url.indexOf("http://www.") != -1){
			statistic_url	= statistic_url.replace("http://www.", "http://"); //Remove www
		}
	}
	ajaxLoadURL(statistic_url, "GET", "", "", 0, 0);
</script>
<!-- END: ajax_update_statistic -->

</body>
</html>
