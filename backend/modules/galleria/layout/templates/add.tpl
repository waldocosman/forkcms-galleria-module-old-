{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblGalleria|ucfirst}: {$lblAddImages}</h2>
</div>

{form:add}
	<div class="ui-tabs">
			<div class="ui-tabs-panel">
				<div class="options">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td id="leftColumn">

						{* Main content *}
						<div class="option horizontal">
							{* Main content *}
							<div class="heading">
								<h3>{$lblImages|ucfirst}</h3>
							</div>

							

					<td id="sidebar">
						<div id="publishOptions" class="box">
							<div class="heading">
								<h3>{$lblAlbum|ucfirst}</h3>
							</div>
							<div class="options">
								{$ddmAlbum}
							</div>
							
						</div>
						
					</td>
				</tr>
			</table>
		</div>
		<div class="fullwidthOptions">
			<div class="buttonHolderRight">
				<input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblSave|ucfirst}" />
			</div>
		</div>
	</div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}