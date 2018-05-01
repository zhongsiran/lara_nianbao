@foreach ($corps as $corp)
	{{ $corp->designated_person}} - {{$corp->type}} <br>
	{{ $corp->Division}} <br>
	未报：{{ $corp->not_submitted}} <br />
	已报：{{ $corp->submitted}} <br />
	------------------- <br>
@endforeach
