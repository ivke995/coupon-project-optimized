@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ request()->query('status') ? ucfirst(request()->query('status')) : 'All' }} Coupons</h1>
@stop

@section('content')

    <table class="table">
        <thead>
        <tr>
            <th scope="col">Type</th>
            <th scope="col">Value</th>
            <th scope="col">Limit</th>
            <th scope="col">Status</th>
            <th scope="col">Used Times</th>
            <th scope="col">Valid Until</th>
            <th scope="col">Used At</th>
            <th scope="col">Options</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($coupons as $coupon)

            <tr>
                <th scope="row">{{$coupon->type->name}}</th>
                <td>@if($coupon->value) {{$coupon->value}} @else <i class="fas fa-times"></i> @endif</td>
                <td>@if($coupon->limit) {{$coupon->limit}} @else <i class="fas fa-times"></i> @endif </td>
                <td>@if($coupon->status) {{$coupon->status}} @else <i class="fas fa-times"></i>@endif </td>
                <td>@if($coupon->times_used) {{$coupon->times_used}} @else <i class="fas fa-times"></i>@endif</td>
                <td>@if($coupon->expires_at) {{$coupon->expires_at}} @else <i class="fas fa-times"></i>@endif</td>
                <td>@if($coupon->used_at) {{$coupon->used_at}} @else <i class="fas fa-times"></i>@endif </td>
{{--                <td> <i class="fa-regular fa-anchor"></i> </td>--}}
                <td>
                    <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit </a>
                    <form style="display: inline" action="{{ route('coupons.destroy', $coupon->id) }}" method="POST">
                        @csrf
                        @method("DELETE")

                        <input type="hidden" value="{{$coupon->id}}" name="id">
                        <button type="submit" class="btn btn-danger text-light">Delete</button>
                    </form>
                </td>
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

