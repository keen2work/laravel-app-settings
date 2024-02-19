@extends('oxygen::layouts.master-dashboard')

@php
	$form = $form ?? new \EMedia\Formation\Builder\Formation();
	if ($entity) {
		$form->setModel($entity);
	}
@endphp

@section('content')
    {{ lotus()->pageHeadline($pageTitle) }}

    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        ['Manage Settings', route('manage.settings.index')],
        [$pageTitle, null, true]
    ]) }}

    <div class="card">
        <div class="card-header">
            {{ $pageTitle }}
        </div>
        <div class="card-body">
            @if ($entity->id)
                @if (!$entity->is_key_editable)
                    <div class="alert alert-warning">
                        <strong>NOTE</strong>
                        <div>This key is locked. Any changes to the key will be ignored.</div>
                    </div>
                @endif
            @endif

            <form action="{{ entity_resource_path() }}" method="post" class="form-horizontal">
                @if ($entity->id)
                    {{ method_field('put') }}
                @endif

                {{ csrf_field() }}

                @if ($entity->is_key_editable)
                    {{ $form->render('setting_key') }}
                @else
                    {{ $form->render('setting_key', null, ['attributes' => 'readonly']) }}
                @endif

                @switch ($entity->setting_data_type)
                    @case (\EMedia\AppSettings\Entities\Settings\Setting::DATA_TYPE_TEXT)
                    {{ $form->render('setting_value', null, ['type' => 'textarea']) }}
                    @break
                    @default
                    {{ $form->render('setting_value') }}
                @endswitch

                {{ $form->render(null, ['setting_key', 'setting_value']) }}

                <div class="form-group row">
                    <div class="col-sm-8 offset-4">
                        <button type="submit" class="btn btn btn-success btn-wide">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
