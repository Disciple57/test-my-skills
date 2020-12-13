@inject('image', 'App\Constants\Image')
<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Image upload</title>
    <script src="{{ asset('/app/app.js') }}"></script>
    <script src="{{ asset('/app/common.js') }}"></script>
    <link href="{{ asset('/app/app.css') }}" rel="stylesheet">

</head>
<body>
<div class="d-flex align-items-center bg-dark text-light py-2">
    <button class="btn btn-sm btn-outline-light modal-show" data-bs-toggle="modal" data-bs-target="#modal"
            data-button="store">
        Upload image
    </button>
</div>
<script>
    const apiURL = '{{route('images.index')}}', storageDIR = '{{asset('storage/' . $image::STORAGE_PATCH)}}';
</script>
<div class="container-fluid">
    <div id="list-parent" class="row mt-3 align-items-center"></div>
</div>

<div class="modal fade" id="modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form" method="POST" action="/api/image">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="file" id="image" class="form-control-file" name="image" accept="{{$image::ACCEPT}}">
                        <span class="invalid-feedback" role="alert"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Undo</button>
                    <button id="upload" type="button" class="btn btn-primary" data-action="" data-id="">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<template id="list">
    <div class="col-6 col-sm-4 col-xl-2 mb-1" data-id=">id">
        <div class="list-group h-100 text-center">
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <strong data-item="name" class="fs08 me-auto"></strong>
                    <img class="tool me-2 modal-show" title="update" src="{{asset('/app/img/replace.svg')}}" data-bs-toggle="modal" data-bs-target="#modal"
                    data-button="update" data-id="">
                    <img class="tool" title="delete" data-action="destroy" src="{{asset('/app/img/trash.svg')}}" data-id="">
                </div>
            </div>
            <div class="list-group-item img_parent">
                <div class="img_container">
                    <img class="fill" src="">
                </div>
            </div>
            <div class="list-group-item text-secondary text-left">
                <small class="fs06" data-item="info"></small>
            </div>
        </div>
    </div>
</template>
</body>
</html>