{include file="header.tpl" pagetitle=$dam->name}
{include file="login.tpl"}
<div id="workarea">
  <h1>{$dam->name} ({$dam->noeea}) {if $dam->valid eq 't'} <img src="{$VALIDICON}" alt="valid" width="15px"/>{else}{/if}</h1>
  <script language="javascript" type="text/javascript">
    EventManager.Add(window, 'load', setupFolders, false);
  </script>
  <form method="post" name="carto_form" id="carto_form" action="">
    <input type="hidden" id="damId" name="cd" value="{$dam->noeea}"/>
	<input type="hidden" id="action" name="action" value="validate"/>
    <table>
      <tr>
        <td width="320px">
		  <h2>{$coordinates}</h2>
            <ul>
              <li>
                {$icoldposition}<br/>
			    <label for="xini">x:</label> <input size="10" type="text" id="xini" name="xini" value="{$dam->x_icold}"/>
                <label for="yini">y:</label> <input size="10" type="text" id="yini" name="yini" value="{$dam->y_icold}"/>
                <input class="SearchButton" type="button" onclick="this.form.x.value={$dam->x_icold};this.form.y.value={$dam->y_icold};" value="{$icoldistrue}"/>
			  </li>
<!-- See https://svn.eionet.europa.eu/projects/Zope/ticket/1300        
              <li>
                {$eeaposition}<br/>
				x: {$dam->x_prop} y: {$dam->y_prop}
				<input class="SearchButton" type="button" onclick="this.form.x.value={$dam->x_prop};this.form.y.value={$dam->y_prop};" value="{$eeaistrue}"/>
              </li>
-->              
              <li>
                {$valposition}<br/>
                <label for="x">x:</label> <input size="10" type="text" id="x" name="x" value="{$x_val}"/>
				<label for="y">y:</label> <input size="10" type="text" id="y" name="y" value="{$y_val}"/>
                <input type="button" name="btnResetSeed" id="btnResetSeed" value="N/A" onclick="javascript:resetSeed('{$outOfRangeX}','{$outOfRangeY}');" />
              </li>
			</ul>
			<input class="SearchButton" type="submit" value="{$saveandvalid}"/>
            <h2>{$comments}</h2>
			<textarea rows="6" cols="30" id="comment" name="comment">{$dam->comments}</textarea><br/>
			<input type="checkbox" id="is_oncanal" name="is_oncanal" {if $dam->is_oncanal eq 't'}checked="checked"{/if}/><label for="is_oncanal">{$is_oncanal}</label> <br/>
			<input type="checkbox" id="is_dyke" name="is_dyke" {if $dam->is_dyke eq 't'}checked="checked"{/if}/><label for="is_dyke">{$is_dyke}</label> <br/>
			<input class="SearchButton" type="submit" value="{$updatecomments}"/>
          </td>
          <td>
            <p> 
              {$score} : 
		      {if $dam->score eq  0} {$s0} 
		        {elseif $dam->score eq  1} {$s1} 
		        {elseif $dam->score eq  2} {$s2} 
		        {elseif $dam->score eq  3} {$s3} 
		        {elseif $dam->score eq  4} {$s4} 
		        {elseif $dam->score eq  5} {$s5} 
		        {elseif $dam->score eq  6} {$s6} 
              {/if}
		      | 
		      {if isset( $first ) && $first neq $dam->noeea}
		        <input onclick="location.replace('dams.php?lang={$langId}&amp;cd={$first}');" type="button" value="&lt;&lt;" class="SearchButton" />
		      {/if}
		      {if isset( $previous ) && $previous neq $dam->noeea}
		        <input onclick="location.replace('dams.php?lang={$langId}&amp;cd={$previous}');" type="button" value="&lt;" class="SearchButton" />
		      {/if}
		      {if isset( $next ) && $next neq $dam->noeea}
		        <input onclick="location.replace('dams.php?lang={$langId}&amp;cd={$next}');" type="button" value="&gt;" class="SearchButton" />
		      {/if}
		      {if isset( $last ) && $last neq $dam->noeea}
		        <input onclick="location.replace('dams.php?lang={$langId}&amp;cd={$last}');" type="button" value="&gt;&gt;" class="SearchButton" />
		      {/if}
		    </p>
			{$clickinfo}
            <br/>
			     <div id="map" style="width: 400px; height: 400px; "></div>
			     <input type="checkbox" checked="checked" id="setWhichPoint" name="setWhichPoint"/>{$setWhichPoint}
          </td>
        </tr>
      </table>
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
		  <p>
            {$alias}: {$dam->alias}
          </p>
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

<div style="display: none;">
  <textarea id="debug_console" name="ajax_console" rows="10" cols="80"></textarea>
  <br />
  <a href="javascript:clearWebConsole();">Clear console</a>
</div>

{include file="footer.tpl"}
{if $googleMap}{$googleMap}{/if}


