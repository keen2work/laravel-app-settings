@extends('oxygen::layouts.master-dashboard')

@section('breadcrumbs')
    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        [$pageTitle, null, true]
    ]) }}
@stop

@section('pageMainActions')
    @include('oxygen::dashboard.partials.searchField')

    <a href="{{ route('manage.settings.create') }}" class="btn btn-success"><em class="fa fa-plus-circle"></em> Add New Setting</a>
	<a href="{{ route('manage.setting-groups.create') }}" class="btn btn-success"><em class="fa fa-plus-circle"></em> Add New Setting Group</a>
@stop

@section('content')
    @include('oxygen::dashboard.partials.table-allItems', [
        'tableHeader' => [
            'Key', 'Value', 'Data Type', 'Description', 'Group', 'Actions'
        ]
    ])

    @foreach ($allItems as $item)
        <tr>
            <td>
                @if ($item->is_value_editable)
                    <a href="{{ route('manage.settings.edit', ['id' => $item->id]) }}">{{ $item->setting_key }}</a>
                @else
                    {{ $item->setting_key }}
                @endif
            </td>
            <td>{{ substr($item->setting_value, 0, 100) }}</td>
            <td>{{ strtoupper($item->setting_data_type) }}</td>
            <td>{{ $item->description }}</td>
            <td>
				@if ($item->group)
					{{ $item->group->name }}
				@endif
			</td>
            <td>
                @if ($item->is_value_editable)
                    <a href="{{ route('manage.settings.edit', ['id' => $item->id]) }}" class="btn btn-success"><em class="fa fa-edit"></em> Edit</a>
                    @if ($item->is_key_editable)
                        <form action="{{ route('manage.settings.destroy', ['id' => $item->id]) }}" method="POST" class="form form-inline">
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                            <button class="btn btn-danger js-confirm"><em class="fa fa-times"></em> Delete</button>
                        </form>
                    @endif
                @else
                    <span class="badge badge-warning">LOCKED</span>
                @endif

            </td>
        </tr>
    @endforeach
@stop
