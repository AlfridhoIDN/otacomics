@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
                
            <div class="card border-0 shadow bg-dark">
                <div class="card-header  text-white">
                    Edit Reviews
                </div>
                <div class="card-body pb-3">            
                    <form action="{{route('account.reviews.updateReview',$review->id)}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="review" class="form-label">Review</label>
                            <textarea class="form-control @error('review') is-invalid @enderror" placeholder="Review" name="review" id="review" rows="3" readonly>{{old('review',$review->review)}}</textarea>
                            @error('review')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                <option value="0" @selected($review->status == 0? "selected" : "")>Pending</option>
                                <option value="1" @selected($review->status == 1? "selected" : "")>Active</option>
                                <option value="2" @selected($review->status == 2? "selected" : "")>Denied</option>
                            </select>
                            @error('status')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-primary mt-2">save</button>
                    </form>
                </div>
            </div>   
        </div>
    </div>
</div>
@endsection
