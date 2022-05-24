<h1>Registration: <small class="text-muted">Copy info to/from LMS</small></h1><br>

<form action="{$smarty.server.REQUEST_URI}" method="post" class="form-horizontal">
<div class="panel panel-default">
	<div class="panel-heading">
		<h2>Copy to LMS</h2>
		<p class="">
			The following fields need to be copied exactly as supplied into the configuration page 
			of your LMS.
		</p>
	</div>
	<div class="panel-body">
		
		<div class="form-group">
			<label for="toolProvider" class="col-sm-4 control-label">Tool Provider Name</label>
			<div class="col-sm-8">
				<div class="input-group">
					<input type="text" class="form-control" value="{$toolProvider->getName()}" id="toolProvider">
					<span class="input-group-btn">
						<button class="btn btn-default copy-button" data-target="#toolProvider" type="button" data-toggle="tooltip" title="Copy to Clipboard">
							Copy
						</button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="publicKeySetUrl" class="col-sm-4 control-label">Tool Public Key set URL</label>
			<div class="col-sm-8">
				<div class="input-group">
					<input type="text" class="form-control" value="{$registration->keySet->url}" id="publicKeySetUrl">
					<span class="input-group-btn">
						<button class="btn btn-default copy-button" data-target="#publicKeySetUrl" type="button" data-toggle="tooltip" title="Copy to Clipboard">
							Copy
						</button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="loginUrl" class="col-sm-4 control-label">Login Initiation URL</label>
			<div class="col-sm-8">
				<div class="input-group">
					<input type="text" class="form-control" value="{$loginUrl}" id="loginUrl">
					<span class="input-group-btn">
						<button class="btn btn-default copy-button" data-target="#loginUrl" type="button" data-toggle="tooltip" title="Copy to Clipboard">
							Copy
						</button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="launchUrl" class="col-sm-4 control-label">Launch URL / Redirect URI</label>
			<div class="col-sm-8">
				<div class="input-group">
					<input type="text" class="form-control" value="{$launchUrl}" id="launchUrl">
					<span class="input-group-btn">
						<button class="btn btn-default copy-button" data-target="#launchUrl" type="button" data-toggle="tooltip" title="Copy to Clipboard">
							Copy
						</button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-sm-4 control-label">Description</label>
			<div class="col-sm-8">
				<div class="input-group">
					<input type="text" class="form-control" value="{$toolProvider->getDescription()}" id="description">
					<span class="input-group-btn">
						<button class="btn btn-default copy-button" data-target="#description" type="button" data-toggle="tooltip" title="Copy to Clipboard">
							Copy
						</button>
					</span>
				</div>
			</div>
		</div>

	{if $toolProvider && $toolProvider->getDeepLinks()}
	<br><hr>
	<div class="deeplinks">
		<div class="form-group">
			<div class="col-sm-6 control-label">
				<h3>Deep Links / Content-Item Message</h3>
			</div>
		</div>

	{foreach $toolProvider->getDeepLinks() as $resource => $details}
		{assign var=resourceId value=$details@index}
		<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="">{$resource}</h4>
			</div>
			<div class="panel-body">
				{if $details.placements}
				<div class="form-group">
					<label for="placements{$resourceId}" class="col-sm-2 control-label">Placements</label>
					<div class="col-sm-10">
					{foreach $details.placements as $placement}
						<span class="label label-info" >{$placement}</span>
					{/foreach}
					</div>
				</div>
				{/if}
				<div class="form-group">
					<label for="url{$resourceId}" class="col-sm-2 control-label">URL</label>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="text" class="form-control" value="{$details.url}" id="url{$resourceId}">
							<span class="input-group-btn">
								<button class="btn btn-default copy-button" data-target="#url{$resourceId}" type="button" data-toggle="tooltip" title="Copy to Clipboard">
									Copy
								</button>
							</span>
						</div>
					</div>
					<label for="title{$resourceId}" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="text" class="form-control" value="{$resource}" id="title{$resourceId}">
							<span class="input-group-btn">
								<button class="btn btn-default copy-button" data-target="#title{$resourceId}" type="button" data-toggle="tooltip" title="Copy to Clipboard">
									Copy
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	{/foreach}
	</div>
	{/if}	
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h2>Copy from LMS</h2>
		<p class="">
			The following fields must be filled with information found within your LMS during the configuration process. Be sure to check that you've copied them over correctly!
		</p>
	</div>
	<div class="panel-body">
		
		<div class="form-group">
			<label for="issuer" class="col-sm-4 control-label">Issuer</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="issuer" value="{$registration->issuer}" placeholder="https://sfsu.instructure.com" id="issuer" required>
			</div>
		</div>
		<div class="form-group">
			<label for="client_id" class="col-sm-4 control-label">Client ID</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="client_id" value="{$registration->client_id}" placeholder="21165000..." id="client_id" required>
			</div>
		</div>
		<div class="form-group">
			<label for="deployment_id" class="col-sm-4 control-label">Deployment ID</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="deployment_id" value="{$deployment->id}" placeholder="" id="deployment_id" required>
			</div>
		</div>
		<div class="form-group">
			<label for="customer_id" class="col-sm-4 control-label">Customer ID</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="customer_id" value="{if $deployment->customerId}{$deployment->customerId}{else}{$registration->toolProvider}{/if}" placeholder="https://sfsu.instructure.com" id="customer_id">
			</div>
		</div>
		<div class="form-group">
			<label for="platform_jwks_endpoint" class="col-sm-4 control-label">Platform public keyset <small class="text-muted">(JWKS)</small> URL</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="platform_jwks_endpoint" value="{$registration->platform_jwks_endpoint}" placeholder="https://canvas.instructure.com/api/lti/security/jwks" id="platform_jwks_endpoint" required>
			</div>
		</div>
		<div class="form-group">
			<label for="platform_service_auth_endpoint" class="col-sm-4 control-label">Access token URL</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="platform_service_auth_endpoint" value="{$registration->platform_service_auth_endpoint}" placeholder="https://sfsu.instructure.com/login/oauth2/token" id="platform_service_auth_endpoint" required>
			</div>
		</div>
		<div class="form-group">
			<label for="platform_login_auth_endpoint" class="col-sm-4 control-label">Platform OpenID Connect login endpoint</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="platform_login_auth_endpoint" value="{$registration->platform_login_auth_endpoint}" placeholder="https://canvas.instructure.com/api/lti/authorize_redirect" id="platform_login_auth_endpoint" required>
			</div>
		</div>
		<div class="form-group">
			<label for="platform_auth_provider" class="col-sm-4 control-label">Platform Auth Provider <small class="text-muted">(optional)</small></label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="platform_auth_provider" value="{$registration->platform_auth_provider}" placeholder="" id="platform_auth_provider">
			</div>
		</div>
	</div>
</div>

	<br>
	<div class="commands">
	    {generate_form_post_key}
	    <button class="btn btn-primary" type="submit" name="command[save]">Save</button>
	    <a class="btn btn-secondary" href="lti/registrations">cancel</a>
	</div>
</form>

<br><br>
<div class="containers">
	<h4>Documentation</h4>
	<ul class="list-unstyled">
		<li><a href="https://microsoft.github.io/Learn-LTI/docs/CONFIGURATION_GUIDE.html" target="_blank">
			Microsoft's LTI Configuration Guide &nearr;</a> <small> (Moodle/Canvas/Blackboard)</small>
		</li>
		<li><a href="https://canvas.instructure.com/doc/api/file.lti_dev_key_config.html" target="_blank">
			Configuring LTI Advantage Tools &nearr;</a> <small> (Canvas)</small>
		</li>
		<li><a href="https://help.feedbackfruits.com/en/articles/4780657-how-to-configure-lti-1-3-for-moodle#configuration" target="_blank">
			Example of LTI Configuration &nearr;</a><small> (Moodle)</small>
		</li>
	</ul>
</div>

<script type="text/javascript">
(function () {
	for (let btn of document.getElementsByClassName('copy-button')) {
		btn.addEventListener('click', () => {
			const input = document.querySelector(btn.getAttribute('data-target'));
			input.select();

			if (navigator.clipboard) {
				navigator.clipboard.writeText(input.value);
			} else {
				try {
					input.setSelectionRange(0, input.value.length);
					const success = document.execCommand('copy');
				} catch (err) {
					console.log('error copying to clipboard:', err);
				}				
			}
		});
	}
})();
</script>