@extends('pages.layouts.app')
@section('content')
    <div class="col-md-12 col-sm-12 mt-5">
       <div class="card mt-5">
        <div class="card-header">
            <h3>Select Event And Confirm Your Order</h3>
           
        </div>
        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>    
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            @if (session('error'))
             <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="col-md-6 col-sm-12 float-left">
                @if(isset($data))
                   
                    @if($data > 0)
                        
                    @else
                    
                    @endif
                @else
                    <form action="{{ route('event.search') }}" method="POST" role="search">
                        @csrf
                        <div class="form-group">
                            <label>Select Date: <sup class="text-danger">*</sup></label>
                            <input type="date" name="booking_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Select Event: <sup class="text-danger">*</sup></label>
                            <select class="form-control" name="event_location">
                                <option>---------Select One---------</option>
                                @foreach ($event as $events)
                                    <option value="{{ $events->event_name }}">{{ $events->event_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="search" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </form>

                @endif
            </div>
        </div>
       </div>
    </div>
@endsection