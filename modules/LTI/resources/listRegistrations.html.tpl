<h1>LTI Registrations</h1>

<div class="panel panel-default">
	<div class="panel-heading">
		<a href="lti/registrations/create" class="btn btn-success">New Registration &rarr;</a>
	</div>
	<div class="panel-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Tool Provider</th>
					<th>Issuer</th>
					<th>Date Created</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
			{foreach $registrations as $reg}
				<tr>
					<td>{$reg->toolProvider}</td>
					<td>{$reg->issuer}</td>
					<td>{$reg->createdDate->format('Y-m-d')}</td>
					<td>
						<a href="lti/registrations/{$reg->id}" class="btn btn-xs btn-info">edit</a>
						<a href="lti/registrations/{$reg->id}/delete" class="btn btn-xs btn-danger">delete</a>
					</td>
				</tr>
			{foreachelse}
				<tr><td colspan="3">No registrations found</td></tr>
			{/foreach}				
			</tbody>
		</table>
	</div>
</div>