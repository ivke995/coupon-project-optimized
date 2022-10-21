@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">All Coupons</h1>
@stop

@section('content')

    <table class="table">
        <thead>
        <tr>
            <th scope="col">Email</th>
            <th scope="col">First Coupon Used</th>
            <th scope="col">Last Coupon Used</th>
            <th scope="col">Coupons Used</th>
        </tr>
        </thead>
        <tbody>

        @foreach($emails as $email)
            <tr>
                <th>{{$email->email}}</th>
                <th>{{$email->first_coupon_used_at}}</th>
                <th>{{$email->last_coupon_used_at}}</th>
                <th>{{$email->coupons_used}}</th>
            </tr>

        @endforeach
        </tbody>
    </table>

@stop

@section('js')
    <script>
        $(document).ready( function () {
            $('.table').DataTable();
        } );
    </script>

@endsection
