
@extends('layout')

@section('content')
<br>
<br>
<br>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Payment Details</h5>
                </div>
                <div class="card-body">
                    <form action="/process-payment" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="card-number">Appointment Id  </label>
                            <input type="text" class="form-control" value="{{$appointmentId}}" id="card-number" name="appointment_id" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" id="amount" value="{{$amount}}" name="amount" required>
                        </div>
                        @if ($appointDetails?->due == 0 || $appointDetails?->due<0)
                            <button type="submit" class="btn btn-primary mt-2" disabled>Pay Now</button>
                        @else
                            <button type="submit" class="btn btn-primary mt-2">Pay Now</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div  class="col-md-4">
                 <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h5>Appointment Details</h5>
                        </div>
                    </div>
                     <div class="card-body">
                         <p><strong>Name: </strong> {{$appointDetails?->name}}</p>
                         <p><strong>Category Name: </strong> {{$appointDetails?->category?->name}}</p>
                         <p><strong>Service Category Name: </strong> {{$appointDetails?->category?->serviceCategory?->name}}</p>
                         <p><strong>Date Time: </strong> {{ \Carbon\Carbon::parse($appointDetails?->date_time)->format('M d Y, H:i') }}

                         </p>
                         <p><strong>Amount: </strong>{{$appointDetails?->amount}}</p>
                         <p><strong>Due: </strong>{{$appointDetails?->due < 0 ? 0: $appointDetails?->due}}</p>
                         <!-- Add more appointment details as needed -->
                     </div>
                 </div>
        </div>
    </div>
</div>
@endsection
