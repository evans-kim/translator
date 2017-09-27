<div class="form-group{{ $errors->has('translations') ? ' has-error' : '' }}">
    @if(!empty($label))
        <label class="col-md-2 control-label">{{$label}}</label>
        <div class="col-md-10">
            @endif
            <select type="text" name="translations[]" value="" id="translations" multiple style="width:100%">
                @if( !empty($model) && !empty($tags = $model->translations()->get()) )
                    @foreach ($tags as $tag)
                        <option value="{{$tag->locale}}:{{$tag->value}}" selected >{{$tag->locale}}:{{$tag->value}}</option>
                    @endforeach
                @endif
            </select>
            @if ($errors->has('translations'))
                <span class="help-block">
            <strong>{{ $errors->first('translations') }}</strong>
        </span>
            @endif
            @if(!empty($label))
        </div>
    @endif
</div>
@section("scripts")
    @parent
    <script type="text/javascript">

        $("#translations").select2({
            tags: true,
            tokenSeparators: [',',' ','\n'],
            ajax: {
                url: "{{route('translation.list')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * 30) < data.total
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 2,
            theme: "bootstrap"
        });
    </script>

@endsection