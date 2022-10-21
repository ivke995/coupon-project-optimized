@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Crete New Coupon</h1>
@stop

@section('content')

    <form action="{{route('coupons.update', $coupon->id)}}" method="POST">

        @csrf
        @method('PATCH')

        <div>
            <label for="type_id">Type:</label><br>
            <select class="form-control" name="type_id" style="width: 350px"  aria-label="Default select example">
                <option value="">Select type</option>
                @foreach ($types as $type)
                    <option value="{{$type->id}}">{{$type->name}}</option>
                @endforeach
            </select>
        </div>

        <label for="value">Value:</label>
        <input type="number" id="value" class="form-control" style="width: 350px; margin-bottom: 10px" name="value">

        <label for="limit">Limit:</label>
        <input type="number" id="limit" class="form-control" style="width: 350px; margin-bottom: 10px" name="limit">

        <label for="valid_until">Valid until:</label>
        <input type="datetime-local" id="valid_until" class="form-control" style="width: 350px; margin-bottom: 10px" name="expires_at"
               value="2018-07-22"
               min="current" max="2050-12-31">

        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input  name="email" class="form-control" id="email" aria-describedby="emailHelp" style="width: 350px " placeholder="email">
        </div>

        <button type="submit" style="margin-bottom: 10px" class="btn btn-primary">Submit</button>

    </form>
@endsection
