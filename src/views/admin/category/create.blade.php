@extends($layout)

@section('content')

<div class="row">

    <h2>Create Category</h2>

        <form action="{{ route('laravel-forum.api.store.category') }}" method="POST">
          <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" class="form-control" />
          </div>

          {{ csrf_field() }}

          <button type="submit" class="btn btn-primary">Submit</button>
        </form>

</div>
@stop
