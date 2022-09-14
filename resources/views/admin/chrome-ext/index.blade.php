@extends('admin.layouts.app')

@section('title', 'View Jobs Table ')
@push('style')
<style>
    .word-break {
        word-break: break-all;
    }
</style>
@endpush
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title pb-3">
                    <h5>Chrome Extension</h5>
                    <div class="ibox-tools align-items-center d-flex">
                    </div>
                </div>
                <div class="ibox-content">
                    <form class="m-t" role="form" method="POST" action="{{ url('admin/chrome-ext/upload-image') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="custom-file">
                            <input id="logo" type="file" name="file" class="custom-file-input" required>
                            <label for="logo" class="custom-file-label">Choose file...</label>
                        </div>
                        <div class="d-flex justify-content-center py-5">
                            <img id="previewImg" src="{{url('/storage/chrome-ext/white.gif').'?'.now()}}" onerror="this.src='{{asset('/admin-assets/img/placeholder.jpeg')}}'" class="border w-100">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        $('.custom-file-input').on('change', function() {
            readURL(this);
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#previewImg').attr('src', e.target.result);
                }
                return reader.readAsDataURL(input.files[0]);
            }
        }
    });

</script>
@endpush