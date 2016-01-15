@extend('app_error');

@block('error');
		<p><b>code: </b>{! $code !}</p>
		<p><b>message: </b>{! $msg !}</p>
		<p><b>file: </b> {! $file !}</p>
		<p><b>line: </b>{! $line !}</p>
		<p><b>trace: </b>{! $trace !}</p>
@endblock