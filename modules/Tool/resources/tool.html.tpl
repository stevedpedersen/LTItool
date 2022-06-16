<!DOCTYPE html>
{assign var="appName" value=$app->configuration->appName}
<html>
	<head>
		<title>{if $pageTitle}{$pageTitle|escape} &mdash; {/if}{$appName|escape}</title>
		<base href="{$baseUrl|escape}/">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="assets/less/master.less.css?cache=2018050100">
		<link rel="stylesheet" type="text/css" href="assets/css/tool.css">
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-accessibility.css">
		<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link rel="icon" type="image/png" href="assets/images/favicon3.png">
		<script>document.write('<link rel="stylesheet" type="text/css" href="assets/css/app-js.css" media="screen">');</script>

		<link rel="apple-touch-icon" sizes="128x128" href="assets/images/favicon3.png">
		<link rel="icon" sizes="192x192" href="assets/images/favicon3.png">
	</head>

	<body>
		<a href="#mainContent" class="sr-only sr-only-focusable">Skip Navigation</a>

    <div class="wrapper" id="viewTemplate">
        <!-- Page Content  -->
        <div id="content">
			<main role="main" class="col" id="mainContent"> 
				{include file=$contentTemplate}
			</main>
        </div>
    </div>      
		<script src="assets/js/app.js"></script>
	</body>
</html>
