
  <tr>
    <td colspan="5" align="center">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
		  <form name="TEMPLATEFORM" method="POST" action="{S_TEMPLATE_UPDATE}">
		  <tr>
	                        <td align="right">
					<select class="form" name="global_template_id" style="width: 150px" onchange="javascript: changeTemplate();">
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
		  </tr>
		  </form>
		</table>

		<table width="100%" style="height: 70px;" border="0" cellpadding="0" cellspacing="0">
		<form name="SEARCHFORM" method="POST" action="{S_ARTICLE_SEARCH}" onsubmit="javascript: return check_searchForm();">
		<tr>
			<td height="70" style="background-image: url({TEMPLATE_PATH}/images/footer_01.gif);" align="center" valign="top">
			
				<table border="0" cellspacing="0" cellpadding="0">
				<tr><td height="28" class="footer" valign="bottom"><strong>{L_SEARCH_KEYWORD}:</strong> <input class="form" name="s_keyword" value="{SEARCH_KEYWORD}" size="40"> &nbsp;</td>
				<td valign="bottom">
					<div onMouseOver="javascript: this.style.cursor='hand';" onclick="javascript: if (check_searchForm()){ document.SEARCHFORM.submit();}">
					<table width="60" style="height: 20px;" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10"><img src="{TEMPLATE_PATH}/images/button_red_01.gif" width="10" height="20" alt=""></td>
							<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/button_red_02.gif);" class="imgbutton">{L_SEARCH}</td>
							<td width="3"><img src="{TEMPLATE_PATH}/images/button_red_03.gif" width="3" height="20" alt=""></td>
						</tr>
					</table>
					</div>
				</td></tr>
				<tr><td class="footer" align="right" valign="top">
					{L_SEARCH_DAY}:<select class="form" name="s_day" size="1">
					<option value="0">--</option>
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
	
					{L_SEARCH_MONTH}:<select class="form" name="s_month" size="1" onchange="javascript: if (this.selectedIndex == 0){ select_list(0, document.SEARCHFORM.s_day); }">
					<option value="0">--</option>
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
					</select>
					
					{L_SEARCH_YEAR}:<select class="form" name="s_year" onchange="javascript: if (this.selectedIndex == 0){ select_list(0, document.SEARCHFORM.s_day); select_list(0, document.SEARCHFORM.s_month); }">
					<option value="0">----</option>
					<!-- START: syearrow -->
					<option value="{syearrow:YEAR}">{syearrow:YEAR}</option>
					<!-- END: syearrow -->
					</select>
					
					<script language="javascript" type="text/javascript">
					var search_form	= document.SEARCHFORM;
					
//					select_list("{SEARCH_DAY}", search_form.s_day);
//					select_list("{SEARCH_MONTH}", search_form.s_month);
//					select_list("{SEARCH_YEAR}", search_form.s_year);
					
					function check_searchForm(){
						if (search_form.s_keyword.value == ""){
							alert("{L_ERROR_KEYWORD}");
							search_form.s_keyword.focus();
							return false;
						}
						return true;
					}
					</script>
					&nbsp;
				</td>
				<td>&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="64"><img src="{TEMPLATE_PATH}/images/footer_02.gif" width="64" height="70" alt=""></td>
			<td width="36%" height="70" style="background-image: url({TEMPLATE_PATH}/images/footer_03.gif);" class="footer"></td>
		</tr>
		</form>
		</table>
	</td>
    </tr>
</table>

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

</div>
</body>
</html>