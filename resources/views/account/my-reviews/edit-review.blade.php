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
                    Edit My Reviews
                </div>
                <div class="card-body pb-3 text-white">            
                    <form action="{{route('account.myReviews.updateReview',$review->id)}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="review" class="form-label">Comic</label>
                            <div>
                                <strong>{{$review->comic->title}}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Review</label>
                            <textarea class="form-control @error('review') is-invalid @enderror" placeholder="Review" name="review" id="review" rows="3">{{old('review',$review->review)}}</textarea>
                            @error('review')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Rating</label>
                            <select class="form-control @error('rating') is-invalid @enderror" name="rating" id="rating">
                                <option value="1" @selected($review->rating == 1? "selected" : "")>1</option>
                                <option value="2" @selected($review->rating == 2? "selected" : "")>2</option>
                                <option value="3" @selected($review->rating == 3? "selected" : "")>3</option>
                                <option value="4" @selected($review->rating == 4? "selected" : "")>4</option>
                                <option value="5" @selected($review->rating == 5? "selected" : "")>5</option>
                            </select>
                            @error('rating')
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
