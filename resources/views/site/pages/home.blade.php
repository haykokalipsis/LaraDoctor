@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('images/site/banner.jpg') }}" class="img-fluid" alt="banner">
            </div>

            <div class="col-md-6">
                <h2>Create an account & Book your appointment</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab amet animi, consequuntur cum dolor dolorum, excepturi fugit maiores minima obcaecati, officiis pariatur quis quod rerum sint soluta sunt temporibus ullam? Accusamus assumenda at cupiditate debitis earum esse eveniet fugiat in labore molestiae natus, odit possimus provident qui quo quod vero!</p>
                <div class="mt-5">
                    <a href="{{url('/register')}}"> <button class="btn btn-success">Register as Patient</button></a>
                    <a href="{{url('/login')}}"><button class="btn btn-secondary">Login</button></a>
                </div>
            </div>
        </div>

        <hr>

        <!-- Search doctor -->
        <form action="{{route('home')}}" method="GET">
            <div class="card">
                <div class="card-header">Find doctors</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" name="date" id="datepicker" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit">Find doctors</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Display doctors -->
        <div class="card">
            <div class="card-header">Doctors</div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Expertise</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($appointments_today as $appointment)
                                <tr>
                                    <th scope="row">1</th>
                                    <td>
                                        <img src="{{asset('images/doctors')}}/{{$appointment->doctor->image}}" width="100px" style="border-radius: 50%;">
                                    </td>
                                    <td>
                                        {{$appointment->doctor->name}}
                                    </td>
                                    <td>
                                        {{$appointment->doctor->department}}
                                    </td>
                                    <td>
                                        <a href="{{route('site.appointments.create', [$appointment->user_id, $appointment->date])}}">
                                            <button class="btn btn-success">Book Appointment</button>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <td>No doctors available today</td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        body {
            background: #fff;
        }

        .ui-corner-all {
            background: red;
            color: #fff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function () {
            $('#datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                showButtonPanel: true,
                numberOfMonths: 2,
                minDate: new Date()
            });
        });
    </script>
@endpush
