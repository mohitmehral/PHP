
{include file="header.tpl"}

{include file="login.tpl"}

{include file="legend.tpl"}

<div id="menu">

</div>

<div id="contents">
	<h2>{$dam->name} ({$dam->noeea}) {if $dam->valid eq 't'} <img src="{$VALIDICON}" alt="valid" width="15px"/>{else}{/if}
		</h2><p> {$score} : 
		{if $dam->score eq  0} {$s0} 
		{elseif $dam->score eq  1} {$s1} 
		{elseif $dam->score eq  2} {$s2} 
		{elseif $dam->score eq  3} {$s3} 
		{elseif $dam->score eq  4} {$s4} 
		{elseif $dam->score eq  5} {$s5} 
		{elseif $dam->score eq  6} {$s6} {/if}
		
		| 
		{if $first neq $dam->noeea}
		<input onclick="location.replace('dams.php?lang={$langId}&amp;cd={$first}');" type="button" value="&lt;&lt;" class="SearchButton" />
		{/if}
		{if $previous neq $dam->noeea}
		<input onclick="location.replace('dams.php?lang={$langId}&amp;cd={$previous}');" type="button" value="&lt;" class="SearchButton" />
		{/if}
		{if $next neq $dam->noeea}
		<input onclick="location.replace('dams.php?lang={$langId}&amp;cd={$next}');" type="button" value="&gt;" class="SearchButton" />
		{/if}
		{if $last neq $dam->noeea}
		<input onclick="location.replace('dams.php?lang={$langId}&amp;cd={$last}');" type="button" value="&gt;&gt;" class="SearchButton" />
		{/if}
		</p>
	<script language="javascript" type="text/javascript">
	EventManager.Add(window, 'load', setupFolders, false);
	</script>
	<form method="post" name="carto_form" id="carto_form" action="">
	<input type="hidden" id="damId" name="cd" value="{$dam->noeea}"/>
	<input type="hidden" id="action" name="action" value="validate"/>
	
	<table><tr><td width="300px">
		<h2>{$coordinates}</h2>
			<ul>
				<li>{$icoldposition}
				<ul><li>x: {$dam->x_icold}</li><li>y: {$dam->y_icold} </li></ul>
				<input class="SearchButton" type="button" onclick="this.form.x.value={$dam->x_icold};this.form.y.value={$dam->y_icold};" value="{$icoldistrue}"/>
				</li>
				<li>{$eeaposition}
				<ul><li>x: {$dam->x_prop}</li><li>y: {$dam->y_prop}</li></ul>
				<input class="SearchButton" type="button" onclick="this.form.x.value={$dam->x_prop};this.form.y.value={$dam->y_prop};" value="{$eeaistrue}"/>
				</li>
				<li>{$valposition}
				<ul><li><input size="10" type="text" id="x" name="x" value="{$x_val}"/></li>
					<li><input size="10" type="text" id="y" name="y" value="{$y_val}"/></li></ul>
				</li>
			</ul>
			<input class="SearchButton" type="submit" value="{$saveandvalid}"/>

		<h2>{$comments}</h2>
				<textarea rows="6" cols="30" id="comment" name="comment">
					{$dam->comments}
				</textarea>
				<input type="checkbox" id="is_oncanal" name="is_oncanal" {if $dam->is_oncanal eq 't'}checked="checked"{/if}/>{$is_oncanal} <br/>
				<input type="checkbox" id="is_dyke" name="is_dyke" {if $dam->is_dyke eq 't'}checked="checked"{/if}/>{$is_dyke} <br/>
				<input class="SearchButton" type="submit" value="{$updatecomments}"/>
			
		</td><td>
			{$clickinfo}<br/>
			<div id="map" style="width: 400px; height: 400px; "></div>
		</td>
	</tr></table>

				
	<input type="hidden" name="js_folder_idx" value="1" />
	
	<div id="leftbar">
		<div>
		<ul id="tabnav1">
			<li id="label1"><a href="javascript:ontop(1)">{$localisation}</a></li>
			<li id="label5"><a href="javascript:ontop(5)">{$characteristics}</a></li>
			<li id="label6"><a href="javascript:ontop(6)">{$metadata}</a></li>
			{if $imgTopook eq true}
		   	<li id="label2"><a href="javascript:ontop(2)">{$topographic}</a></li>
			{/if}
			{if $imgSpudok eq true}
			<li id="label3"><a href="javascript:ontop(3)">{$im20001}</a></li>
			{/if}
			{if $imgSpanok eq true}
			<li id="label4"><a href="javascript:ontop(4)">{$im20002}</a></li>
			{/if}
			
		</ul>
   		</div>
  		
		<div id="container">
   		<!-- folder 1 starts here -->
   	<div id="folder1" class="folder">
			<p>{$alias}: {$dam->alias}</p>
			<ul>
				<li>{$city}: {$dam->ic_city}</li>
				<li>{$country}: {$dam->country}</li>
				<li>{$continent}: {$dam->ic_continent}</li>
				<li>{$lake}: {$dam->lake_name}</li>
				<li>{$hydrocode}: {$dam->river_id}</li>
				<li>{$hydroname}: {$dam->river_name}</li>
			</ul>
	</div>
    <!-- end of folder 1 -->

   <!-- folder 2 starts here -->
   {if $imgTopook eq true}
   <div id="folder2" class="folder">
	<p>{$topoinfo}</p>
   <img src="{$imgTopo}" alt="topographic"/>
   </div>
   {/if}
   <!-- end of folder 2 -->
   
   <!-- folder 3 starts here -->
   {if $imgSpudok eq true}
   <div id="folder3" class="folder">
	<p>{$im20001info}</p>
   <img src="{$imgSpud}" alt="image2000"/>
   </div>
   {/if}
   <!-- end of folder 3 -->

   <!-- folder 4 starts here -->
   {if $imgSpanok eq true}
   <div id="folder4" class="folder">
	<p>{$im20002info}</p>
   <img src="{$imgSpan}" alt="image2000"/>
   </div>
   {/if}
   <!-- end of folder 4 -->
 
   <!-- folder 5 starts here -->
   <div id="folder5" class="folder">
			<ul>
				<li>{$area}: {$dam->area}</li>
				<li>{$capacity}: {$dam->cap_total}</li>
				<li>{$height}: {$dam->ic_high}</li>
				<li>{$length}: {$dam->ic_length}</li>
				<li>{$volume}: {$dam->ic_vol}</li>
				<li>{$irrigation}: {$dam->ic_irrigation}</li>
				<li>{$floodstock}: {$dam->ic_floodstock}</li>
				<li>{$settlement}: {$dam->ic_settlement}</li>
			</ul>
   </div>

   <div id="folder6" class="folder">
			<ul>	
				<li>{$purpose}: {$dam->ic_purpose}</li>
				<li>{$owner}: {$dam->ic_owner}</li>
				<li>{$yearic}: {$dam->ic_year}</li>
				<li>{$yearopp}: {$dam->year_opp}</li>
				<li>{$yeardead}: {$dam->year_dead}</li>
				<li>{$note}: {$dam->ic_note}</li>
				<li>{$engineer}: {$dam->ic_engineer}</li>
				<li>{$contractor}: {$dam->ic_contractor}</li>
			</ul>
			<p>
			<input type="checkbox" disabled="disabled"
			{if $dam->is_main eq true}checked="checked"{/if}/>{$main} {if $dam->is_main eq true}{$dam->noeea_m}{/if}<br/>
			<input type="checkbox" disabled="disabled"
			{if $dam->is_icold eq true}checked="checked"{/if}/>{$fromICOLD} 
			</p>
   </div>

   </div>
</div>


</form>


</div>



{include file="footer.tpl"}

{if $googleMap}{$googleMap}{/if}


