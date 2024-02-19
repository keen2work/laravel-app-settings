@extends('oxygen::layouts.master-dashboard')

@section('breadcrumbs')
	{{ lotus()->breadcrumbs([
		['Dashboard', route('dashboard')],
		['Settings', route('manage.settings.index')],
		[$pageTitle, null, true]
	]) }}
@stop

@section('pageMainActions')
	@include('oxygen::dashboard.partials.searchField')

	<a href="{{ route('manage.setting-groups.create') }}" class="btn btn-success"><em class="fa fa-plus-circle"></em> Add New Setting Group</a>
@stop

@section('content')
	@include('oxygen::dashboard.partials.table-allItems', [
		'tableHeader' => [
			'Name', 'Description', 'Actions'
		]
	])

	@foreach ($allItems as $item)
		<tr>
			<td>
				@if ($item->is_name_editable)
					<a href="{{ route('manage.setting-groups.edit', ['id' => $item->id]) }}">{{ $item->name }}</a>
				@else
					{{ $item->name }}
				@endif
			</td>
			<td>{{ $item->description }}</td>
			<td>
				@if ($item->is_name_editable)
					<a href="{{ route('manage.setting-groups.edit', ['id' => $item->id]) }}" class="btn btn-success"><em class="fa fa-edit"></em> Edit</a>
					@if ($item->is_key_editable)
						<form action="{{ route('manage.setting-groups.destroy', ['id' => $item->id]) }}" method="POST" class="form form-inline">
							{{ method_field('delete') }}
							{{ csrf_field() }}
							<button class="btn btn-danger js-confirm"><em class="fa fa-times"></em> Delete</button>
						</form>
					@endif
				@else
					<span class="badge badge-warning">GROUP LOCKED</span>
				@endif

			</td>
		</tr>
	@endforeach
@stop
