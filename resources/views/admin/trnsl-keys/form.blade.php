<div class="ibox">
    <div class="ibox-title">
        <h5>Translation Key</h5>
    </div>
    <div class="ibox-content">
        {{-- @if(count($errors))
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <br />
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif --}}
        <form>
            <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                {{-- <label class="col-lg-2 col-form-label">Name</label> --}}
                {!! Form::label('name', 'Name:', ['class' => 'col-lg-2 col-form-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('name', null, ['class' => 'form-control','id' => 'name', 'placeholder' => 'Name']) !!}
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                </div>
            </div>

            <div class="form-group row {{ $errors->has('api_key_type') ? 'has-error' : '' }}">
                {!! Form::label('api_key_type', 'API Key Type:', ['class' => 'col-lg-2 col-form-label']) !!}
                <div class="col-lg-10">
                    {!! Form::select('api_key_type', [null => 'Select API key type'] + $selectData, null, ['class' => 'form-control apiKeyType','id' =>'']) !!}
                    <span class="text-danger">{{ $errors->first('api_key_type') }}</span>
                    <span class="form-text m-b-none"> Select API key type here.</span>
                </div>

            </div>

            <div class="form-group row {{ $errors->has('key') ? 'has-error' : '' }}">
                {!! Form::label('key', 'Key:', ['class' => 'col-lg-2 col-form-label']) !!}
                <div class="col-lg-10">
                     {!! Form::text('key', null, ['class' => 'form-control','id' => 'key', 'placeholder' => 'Key']) !!}
                     <span class="text-danger">{{ $errors->first('key') }}</span>
                    <span class="form-text m-b-none" id="spanText">  Yandex translation API key here.</span>
                </div>
            </div>

            <div class="form-group row ibm_url {{ $errors->has('url_key') ? 'has-error' : '' }}">
                {!! Form::label('url_key', 'Url Key:', ['class' => 'col-lg-2 col-form-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('url_key',$key->transIbmUrlKey->url_key ?? null, ['class' => 'form-control','id' => 'url_key', 'placeholder' => 'Url Key']) !!}
                    <span class="text-danger">{{ $errors->first('url_key') }}</span>
                    <span class="form-text m-b-none"> IBM translation Url key here.</span>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-offset-2 col-lg-10">
                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function(){
        $('.apiKeyType').val() === 'ibm' ? $('.ibm_url').show() : $('.ibm_url').hide();
        $('#spanText').html('');
        $('.apiKeyType').change(function(e) {
            $('#spanText').html($(this).val() +' translation API key here.');
            if ($(this).val()== "ibm") {
                $('.ibm_url').show();
            } else {
                $('.ibm_url').hide();
            }
        });
    })
</script>
@endpush
