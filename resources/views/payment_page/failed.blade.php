@extends('layout')

@section('content')
    <br>
    <br>
    <br>
    <br>
    <div class="container">
        <div class="alert alert-danger">
            <strong>Payment Failed!</strong> The payment was not successful.
        </div>
    </div>

    <script>
        setTimeout(function(){
            window.history.back(); // Redirect back to the previous page after 5 seconds
        }, 5000);
    </script>
@endsection
