<div class="{{$viewClass['form-group']}}">

    <div class="{{ $viewClass['label'] }} control-label">
        <span>{!! $label !!}</span>
    </div>
    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <input type="hidden" name="{{$name}}"/>
        <input type="hidden" name="{{$listName}}"/>
        <div class="{{$name}}-infinity-select-container infinity-select-container" style="display: flex;">
            <select class="form-control {{$class}}" style="width: 200px;" {!! $attributes !!} data-index="0">
                <option value=""></option>
            </select>
        </div>
        @include('admin::form.help-block')
    </div>
</div>

@include('dcat-infinity-select::select-script')
