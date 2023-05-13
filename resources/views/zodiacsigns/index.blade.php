@extends('layouts.app')

@section('content')

	<div class="d-flex justify-content-end mb-3"><a href="{{ route('zodiacsigns.create') }}" class="btn btn-info">Create</a></div>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>id</th>
				<th>Aries</th>
				<th>Taurus</th>
				<th>Gemini</th>
				<th>Cancer</th>
				<th>Leo</th>
				<th>Virgo</th>
				<th>Libra</th>
				<th>Scorpio</th>
				<th>Sagittarius</th>
				<th>Capricorn</th>
				<th>Aquarius</th>
				<th>Pisces</th>

				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($zodiacsigns as $zodiacsign)

				<tr>
					<td>{{ $zodiacsign->id }}</td>
					<td>{{ $zodiacsign->Aries }}</td>
					<td>{{ $zodiacsign->Taurus }}</td>
					<td>{{ $zodiacsign->Gemini }}</td>
					<td>{{ $zodiacsign->Cancer }}</td>
					<td>{{ $zodiacsign->Leo }}</td>
					<td>{{ $zodiacsign->Virgo }}</td>
					<td>{{ $zodiacsign->Libra }}</td>
					<td>{{ $zodiacsign->Scorpio }}</td>
					<td>{{ $zodiacsign->Sagittarius }}</td>
					<td>{{ $zodiacsign->Capricorn }}</td>
					<td>{{ $zodiacsign->Aquarius }}</td>
					<td>{{ $zodiacsign->Pisces }}</td>

					<td>
						<div class="d-flex gap-2">
                            <a href="{{ route('zodiacsigns.show', [$zodiacsign->id]) }}" class="btn btn-info">Show</a>
                            <a href="{{ route('zodiacsigns.edit', [$zodiacsign->id]) }}" class="btn btn-primary">Edit</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['zodiacsigns.destroy', $zodiacsign->id]]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </div>
					</td>
				</tr>

			@endforeach
		</tbody>
	</table>

@stop
