<h1>Delete Registration: <small class="text-muted">{$registration->id}</small></h1><br>
<form method="post" class="form-horizontal">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Are you sure you want to delete this registration?</h2>
		</div>
		<div class="panel-body">
			<dl class="dl-horizontal">
				<dt>Tool Provider</dt>
				<dd>{$registration->toolExtension->getName()}</dd>
				<dt>Issuer</dt>
				<dd>{$registration->issuer}</dd>
				<dt>Client ID</dt>
				<dd>{$registration->client_id}</dd>
				<dt>Deployment ID</dt>
				<dd>{$deployment->id}</dd>
			</dl>
		</div>
		<div class="panel-footer">
			<div class="commands">
			    {generate_form_post_key}
			    <a class="btn btn-default" href="lti/registrations">Cancel</a>
			    <button class="btn btn-danger pull-right" type="submit" name="command[delete]">Delete</button>
			</div>
		</div>
	</div>
</form>