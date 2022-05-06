<h1>Welcome to the {$appName}</h1>
<br>
<div id="welcome-text">
{if $welcomeText}
{$welcomeText}
{else}
<p>LTI Example Tool & Platform</p>
{/if}
</div>

{if !$userContext->account}
<div class="welcome-module">
    <a href="{$app->baseUrl('login?returnTo=/')}" class="btn btn-primary">Log In</a>
</div>
<br><br>
{/if}

