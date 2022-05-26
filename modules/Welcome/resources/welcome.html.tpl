<h1>Welcome to the {$appName}</h1>
<br>
<div id="welcome-text">
{if $welcomeText}
{$welcomeText}
{else}
<p>LTI Example Tool & Platform</p>
{/if}
<a href="lti/registrations" class="btn btn-info">LTI Registrations &rarr;</a>
</div>

{if !$userContext->account}
<div class="welcome-module">
    <a href="{$app->baseUrl('login?returnTo=/')}" class="btn btn-primary">Log In</a>
</div>
<br><br>
{/if}

