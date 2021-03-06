<!DOCTYPE html>
{assign var="appName" value=$app->configuration->appName}
<html>
	<head>
{*may not be needed*}		<meta charset="utf-8">
{*may not be needed*}    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>{if $pageTitle}{$pageTitle|escape} &mdash; {/if}{$appName|escape}</title>
		<base href="{$baseUrl|escape}/">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="assets/less/master.less.css?cache=2018050100">
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-accessibility.css">
		<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link rel="icon" type="image/png" href="assets/images/favicon3.png">
		<script>document.write('<link rel="stylesheet" type="text/css" href="assets/css/app-js.css" media="screen">');</script>

		<meta property="og:title" content="Classroom Technology Database" />
		<meta property="og:description" content="View your classrooms at SF State." />
		<meta name="twitter:title" content="Classroom Technology Database">
		<meta name="twitter:description" content="View your classrooms at SF State.">
		<meta property="og:image" content="assets/images/favicon3.png" />
		<meta name="twitter:image:src" content="assets/images/favicon3.png">
		<link rel="apple-touch-icon" sizes="128x128" href="assets/images/favicon3.png">
		<link rel="icon" sizes="192x192" href="assets/images/favicon3.png">
	</head>

	<body>
		<a href="#content" class="sr-only sr-only-focusable">Skip Navigation</a>
		<header class="at">
	        <nav class="navbar navbar-collapse navbar-static-top" role="navigation">
	          <div class="container">

	            <!-- Brand and toggle get grouped for better mobile display -->
	            <div class="navbar-header">
	              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-at-collapse">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	              </button>
					<a class="navbar-brand" href="{$baseUrl}" style="">
						<img class="img-responsive" src="assets/images/brand.png" style="">
						<span class="text">{$appName}</span>
					</a>
	            </div> 
				<nav class="collapse navbar-collapse" id="navbar-at-collapse">
				<form method="post" action="logout" id="logout-form">
	            <ul class="nav navbar-nav navbar-right">
					{if $viewer}
						<li class="dropdown">
							<a href="#" class="dropdown-toggle navbar-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							{if !$pEdit && !$pSupport}
								Hello, Professor {$viewer->faculty->lastName}
							{else}
								Hello, {$viewer->firstName}
							{/if} 
							<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								{if $pAdmin}
								<li>
									<a class="btn btn-link navbar-link" href="admin"><i class="halflings-icon white cog"></i> Administrate</a>
								</li>
								{/if}
								<li>
									<a class="btn btn-link navbar-link" href="rooms">Rooms</a>
								</li>
								<li>
									<a class="btn btn-link navbar-link" href="schedules">Schedules</a>
								</li>
								{if $pEdit}
								<li>
									<a class="btn btn-link navbar-link" href="software">Software</a>
								</li>
								{/if}
								<li class="text-center">
									<button class="btn btn-link logout navbar-btn" type="submit" name="command[logout]" id="logout-button" value="Logout">Logout</button>
								</li>
							</ul>
						</li>
					{else}
						<li>
							<a class="login-button" href="{$app->baseUrl('login?returnTo=/')}">	
								Login{if $loginToViewOwnRooms} to see your scheduled rooms{/if}
							</a>
						</li>
					{/if} 
	            </ul>
	            </form>
	            </nav>
						
	            <!-- Collect the nav links, forms, and other content for toggling -->
	          </div><!-- /.container-fluid -->
	        </nav>
	        <div class="bc">
				{if $breadcrumbList}
				<div class="container">
					<ol class="at breadcrumb">
						{foreach name="breadcrumbs" item="crumb" from=$breadcrumbList}
						<li{if $smarty.foreach.breadcrumbs.last} class="active"{elseif $smarty.foreach.breadcrumbs.first} class="first"{/if}>
						{l text=$crumb.text href=$crumb.href}
						{if !$smarty.foreach.breadcrumbs.last}{/if}
						</li>
						{/foreach}
					</ol>
				</div>
				{/if}
	        </div>
	    </header>
		{if $app->siteSettings->siteNotice}
		<div class="site-notice action notice">
			{$app->siteSettings->siteNotice}
		</div> 
		{/if}

		{if $flashContent}
		<div id="user-message" class="alert alert-{if $flashClass}{$flashClass}{else}success{/if} alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<div class="primary">{$flashContent}</div>
		</div> 
		{/if}

		{if $userMessageList}
		<div id="user-message" class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			{foreach item="msg" from=$userMessageList}
			<div class="primary">{$msg.primary}</div>
			{foreach item="detail" from=$msg.details}<div class="detail">{$detail}</div>{/foreach}
			{/foreach}
		</div> 
		{/if}

		<div class="container">
			<section class="content">
				{include file=$contentTemplate}
			</section>
		</div>

		{if !$viewer}
		<div id="login-box" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3>Choose Login Method</h3>
					</div>
					<div class="modal-body">
						<p>Loading login options&hellip;</p>
					</div>
				</div>
			</div>
		</div>
		{/if}
    <footer class="sticky-footer">
      <div class="at-footer">
        <div class="container">
          <div class="row">
            <div class="info">
              <h1>Maintained by <a href="http://at.sfsu.edu" class="title">Academic Technology</a></h1>
              <p>Academic Technology supports and advances effective learning, teaching, scholarship, and community service with technology.</p>
            </div>
            <div class="learn-more">
              <div class="row">
                <div class="half">
                  <h2>We Also Work On</h2>
                  <ul>
                    <li><a href="https://ilearn.sfsu.edu/">iLearn</a></li>
                    <li><a href="http://at.sfsu.edu/labspace">Labspace</a></li>
                    <li><a href="http://at.sfsu.edu/coursestream">CourseStream</a></li>
                  </ul>
                </div>
                <div class="half">
                  <h2>Need Help?</h2>
                  <ul>
                    <li>(415) 405-5555</li>
                    <li><a href="mailto:at@sfsu.edu">at@sfsu.edu</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer">
        <div class="container">
          <div id="footer-row" class="row">
            <div id="contact-university" class="col-sm-6">
              <a href="http://www.sfsu.edu/"> <img src="assets/images/logo.png" alt="San Francisco State University Logo" width="50" class="logo"></a>
              <ul class="list-unstyled ">
                <li><a href="http://www.sfsu.edu/">San Francisco State University</a></li>
                <li class="first"><a href="http://www.calstate.edu/">A California State University Campus</a></li>
              </ul>
            </div>
            <div id="contact-local" class="col-sm-6">
              <ul class="list-unstyled">
                <li><strong><a href="https://at.sfsu.edu">Academic Technology</a></strong></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
		<script src="assets/js/app.js?modified=2017021701"></script>
	</body>
</html>
