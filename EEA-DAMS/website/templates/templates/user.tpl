{include file="header.tpl" pagetitle=$profile}
{include file="login.tpl"}

<!-- OnChange JS activate update your profil -->
<script type="text/javascript" src="js/script.js"></script>

<div id="workarea">

{if $action eq 'upd' or $action eq 'new'}
	{if $action eq 'upd'}<form method="post" action="user.php" name="user" id="user"><input type="hidden" name="action" value="sav"/><input type="hidden" name="id" value="{$user->id}"/>{/if}
	{if $action eq 'new'}<form method="post" action="user.php" name="user" id="user"><input type="hidden" name="action" value="cre"/>{/if}
	<input type="hidden" name="lang" value="{$langId}">
			
	<h1>{$profile}</h1>
	<ul>
		<!-- user profile -->
		<li><b>{$username}: </b><input type="text" id="userlogin" name="userlogin" value="{$user->login}"/></li>
		<li><b>{$password}: </b><input type="password" name="userpassword1" id="userpassword1" size="6" value=""/> (<b>{$confirm} </b><input type="password" name="userpassword2" id="userpassword2" size="6" value=""/>)</li>
		<li>{$surname}: <input type="text" name="usersurname" size="6" value="{$user->surname}"/></li>
		<li>{$firstname}: <input type="text" name="userfirstname" value="{$user->firstname}"/></li>
		<li>{$mail}: <input type="mail" name="useremail" value="{$user->email}"/></li>
		<li>{$phone}: <input type="phone" name="userphone" value="{$user->phone}"/></li>
		<li>{$address}: <textarea name="useraddress" >{$user->address}</textarea></li>
	</ul>
	<h2>{$roles}</h2>
	<ul>
		<!-- ADM or VAL -->
		<li><input type="checkbox" {if $roleAdm eq 'f'}disabled="disabled"{/if} name="userroleadm" {if $user->roleadm eq 't'}checked="checked"{/if}/>{$roleadm}</li>
		<li><input type="checkbox" {if $roleAdm eq 'f'}disabled="disabled"{/if} name="userroledam" {if $user->roledam eq 't'}checked="checked"{/if}/>{$roledam}</li>
	</ul>
	<input type="button" onclick="validformuser(this.form);" value="{$saveuserprofile}" class="SearchButton">
	</form>


{if $action neq 'new'}
	{if $roleAdm eq 't' && $user->roledam eq 't'}
	<h2>{$dams}</h2>
	<table>
	<tr>
		<td>
		<form method="POST" action="#" name="filter_form" id="filter_form">
			{$addFilter}:
			<input type="hidden" name="lang" value="{$langId}">
			<ul>
				<li>{$code}: <input type="text" name="cd" size="5"/></li>
				<li>{$name}: <input type="text" name="srcName" size="5"/></li>
				<li>{$country}:
				  <select name="srcCountry"">
				  <option value="">*</option>
				  {html_options values=$damCountryFilter output=$damCountryFilter}
				  </select>			
				 <!--<input type="text" name="srcCountry" size="5"/>--></li>
			</ul>
			<input class="SearchButton" type="button" onclick="applyFilter();" value="{$applyfilter}"/>
		</form>
		</td>
		<form method="POST" action="user.php" name="dams_form" id="dams_form">
			<td>
				<input type="hidden" name="action" value="savedams">
				<input type="hidden" name="lang" value="{$langId}">
				<input type="hidden" name="id" value="{$user->id}">
				{$availableDams}<br/>
				<select name="listunselect"  size="16" CLASS="ctrl" multiple OnDblClick="javascript:affecte(this.form.elements['listselect[]'],this.form.listunselect)" style="WIDTH: 250px"> 
					{foreach name=outer item=da from=$allDams}
						<option value="{$da.noeea}">{$da.name} ({$da.noeea})</option>
					{/foreach}
				</select>
				</td>
				<td valign="middle">
					<input value=">" type="button" {if $user->roleadm neq true}disabled{/if} 
						OnClick="javascript:affectetout(this.form.elements['listselect[]'],this.form.listunselect)"  class="SearchButton"><br/>
					<input value="<" type="button" {if $user->roleadm neq true}disabled{/if} 
						OnClick="javascript:affectetout(this.form.listunselect,this.form.elements['listselect[]'])"  class="SearchButton">
				</td>
				<td>{$selectedDams} <input value="X" type="button" {if $user->roleadm neq true}disabled{/if} 
						OnClick="javascript:removeAllOptions(this.form.elements['listselect[]'])" class="SearchButton"><br/>
				<select name="listselect[]" size="16" CLASS="ctrl" {if $roleadm eq false}disabled="disabled"{/if}  multiple="multiple" OnDblClick="javascript:affecte(this.form.listunselect,this.form.elements['listselect[]'])" style="width: 250px" > 
					{foreach name=outer item=da from=$userDams}
						<option value="{$da.noeea}">{$da.name} ({$da.noeea})</option>
					{/foreach}
				</select>
				<input type="button" value="{$saveSelectedDams}" class="SearchButton" onclick="javascript:saveDams(this.form, this.form.elements['listselect[]'])">
			</td>
		</form>
	</tr>
	</table>
	{elseif $user->roledam eq 't'}
	<h2>{$dams}</h2>
	<table>
	<tr>
			<td>{$selectedDams}<br/>
				<select name="listselect[]" size="16" CLASS="ctrl" {if $roleadm eq false}disabled{/if}  multiple OnDblClick="javascript:affecte(this.form.listunselect,this.form.elements['listselect[]'])" style="WIDTH: 250px"> 
					{foreach name=outer item=da from=$userDams}
						<option value="{$da.noeea}">{$da.name} ({$da.noeea})</option>
					{/foreach}
				</select>
			</td>
	</tr>
	</table>
	{/if}
{/if}	
	

{elseif $action eq 'del'}
User {$user->login} deleted.
{elseif $action eq 'cre'}
User {$user->login} created.
{elseif $action eq 'sav'}
User {$user->login} updated.
{elseif $action eq 'savedams'}
User {$user->login} updated.
{/if}
</div>

{include file="footer.tpl"}
