@if ($errors->any())
    <div class="alert alert-dismissable alert-danger">
        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
