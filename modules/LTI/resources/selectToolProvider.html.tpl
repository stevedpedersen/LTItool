<h1>Registration: <small>Select an LTI Tool Provider</small></h1>
<br>
<form action="lti/registrations/new" method="post" class="form">
	<div class="form-group">
		<label for="tool">Available Tools</label>
		<select name="tool" id="tool" class="form-control">
			<option value="">Select a tool...</option>
		{foreach $toolProviders as $tool}
			<option value="{$tool::getExtensionName()}">
				<strong>[{$tool::getExtensionName()}]</strong> {$tool->getName()} - {$tool->getDescription()}
			</option>
		{/foreach}
		</select>
	</div>
	{generate_form_post_key}
	<br>
	<button type="submit" name="command[new]" class="btn btn-primary">Next Step &rarr;</button>
</form>