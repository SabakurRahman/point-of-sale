@extends('layout')

@section('content')
<br>
<br>
<br>
    <div class="container mt-5">
        <div class="alert alert-success">
            <strong>Success!</strong> Payment was successful.
        </div>
    </div>

<script>
    setTimeout(function(){
        window.history.back(); // Redirect back to the previous page after 5 seconds
    }, 5000);
</script>
@endsection
