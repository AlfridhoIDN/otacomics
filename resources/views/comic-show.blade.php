@extends('layouts.app')

@section('main')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <!-- Back button with improved styling -->
            <a href="{{route('home')}}" class="btn btn-outline-secondary mb-4">
                <i class="fa fa-arrow-left me-2" aria-hidden="true"></i>Back to Comics
            </a>

            <div class="card bg-dark text-white border-0 shadow-lg">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Comic cover with enhanced presentation -->
                        <div class="col-md-4">
                            <div class="position-relative h-100">
                                @if($comic->image != '')
                                    <img src="{{ asset('uploads/comics/' .$comic->image) }}" alt="{{$comic->title}}" class="img-fluid rounded-start h-100 w-100 object-fit-cover">
                                @else
                                    <img src="https://placehold.co/990x1400" alt="{{$comic->title}}" class="img-fluid rounded-start h-100 w-100 object-fit-cover">
                                @endif
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">{{$comic->genre}}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Comic details with better spacing and organization -->
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                @include('layouts.message')

                                <h1 class="display-5 fw-bold mb-2">{{$comic->title}}</h1>
                                <h2 class="h4 text-light-emphasis mb-4">by {{$comic->author}}</h2>

                                <!-- Rating display with improved visual -->
                                @php
                                    if($comic->reviews_count > 0) {
                                        $avgRating = $comic->reviews_sum_rating/$comic->reviews_count;
                                    } else {
                                        $avgRating = 0;
                                    }
                                    $avgRatingPer = ($avgRating*100)/5;
                                @endphp

                                <div class="d-flex align-items-center mb-4">
                                    <span class="fs-3 fw-bold text-warning me-2">{{ number_format($avgRating, 1) }}</span>
                                    <div class="star-rating d-inline-flex me-3">
                                        <div class="back-stars">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-secondary">
                                        {{ ($comic->reviews_count > 1) ? $comic->reviews_count. ' Reviews' : $comic->reviews_count. ' Review' }}
                                    </span>
                                </div>

                                <!-- CTA button with animation -->
                                <a href="{{ route('comic.read', $comic->id) }}" class="btn btn-primary btn-lg px-4 py-3 mb-4 fw-semibold shadow-sm hover-scale">
                                    <i class="fa-solid fa-book-open me-2"></i> Read Comic
                                </a>

                                <!-- Description with better typography -->
                                <div class="mt-4">
                                    <h3 class="h5 text-light-emphasis mb-2">Description</h3>
                                    <p class="lead">{{$comic->description}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related comics section with improved card styling -->
            <div class="mt-5">
                <h2 class="display-6 mb-4">Readers also enjoyed</h2>

                @if($relatedComics->isNotEmpty())
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($relatedComics as $relatedComic)
                    <div class="col">
                        <div class="card h-100 bg-dark text-white border-0 shadow-sm hover-lift">
                            <a href="{{route('comic.show',$relatedComic->id )}}" class="position-relative">
                                @if($relatedComic->image != '')
                                    <img src="{{ asset('uploads/comics/' .$relatedComic->image) }}" alt="{{ $relatedComic->title }}" class="card-img-top object-fit-cover" style="height: 280px;">
                                @else
                                    <img src="https://placehold.co/990x1400" alt="{{ $relatedComic->title }}" class="card-img-top object-fit-cover" style="height: 280px;">
                                @endif
                                <div class="position-absolute bottom-0 start-0 w-100 bg-gradient-dark p-2">
                                    <span class="badge bg-secondary">{{$relatedComic->genre ?? 'No Genre'}}</span>
                                </div>
                            </a>

                            @php
                                if($relatedComic->reviews_count > 0) {
                                    $avgRating = $relatedComic->reviews_sum_rating / $relatedComic->reviews_count;
                                } else {
                                    $avgRating = 0;
                                }
                                $avgRatingPer = $relatedComic->reviews_count > 0 ? ($avgRating * 100) / 5 : 0;
                            @endphp

                            <div class="card-body">
                                <h3 class="h5 card-title">
                                    <a href="{{route('comic.show' ,$relatedComic->id )}}" class="text-decoration-none text-white hover-text-primary">
                                        {{ $relatedComic->title}}
                                    </a>
                                </h3>
                                <p class="text-secondary">by {{ $relatedComic->author}}</p>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-warning me-2">{{ number_format($avgRating, 1) }}</span>
                                    <div class="star-rating d-inline-flex me-2">
                                        <div class="back-stars">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-secondary">
                                        {{ ($relatedComic->reviews_count > 1) ? $relatedComic->reviews_count. ' Reviews' : $relatedComic->reviews_count. ' Review' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-secondary rounded-3">
                    <i class="fa fa-info-circle me-2"></i> No related comics found
                </div>
                @endif
            </div>

            <!-- Reviews section with better card styling -->
            <div class="mt-5 mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="display-6 m-0">Reviews</h2>
                    <div>
                        @if(Auth::check())
                        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            <i class="fa fa-plus-circle me-2"></i> Add Review
                        </button>
                        @else
                        <a href="{{route ('account.login')}}" class="btn btn-primary rounded-pill px-4">
                            <i class="fa fa-sign-in me-2"></i> Login to Review
                        </a>
                        @endif
                    </div>
                </div>

                @if($comic->reviews->isNotEmpty())
                <div class="review-list">
                    @foreach ($comic->reviews as $review)
                    <div class="card bg-dark border-0 shadow mb-4 rounded-4 overflow-hidden">
                        <div class="card-header bg-dark-subtle d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3 bg-primary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <h5 class="m-0">{{$review->user->name}}</h5>
                            </div>
                            <span class="badge bg-dark text-secondary">
                                {{\Carbon\Carbon::parse($review->created_at)->format('d M, Y')}}
                            </span>
                        </div>
                        <div class="card-body">
                            @php
                            $ratingPer = ($review->rating/5)*100;
                            @endphp

                            <div class="mb-3">
                                <div class="star-rating d-inline-flex" title="">
                                    <div class="back-stars">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <div class="front-stars" style="width: {{$ratingPer}}%">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text text-white">{{$review->review}}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-secondary rounded-3 p-4 text-center">
                    <i class="fa fa-comment-slash fs-4 mb-3 d-block"></i>
                    <p class="m-0">No reviews yet. Be the first to share your thoughts!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Improved Modal for Reviews -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom border-secondary">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                    <i class="fa fa-star text-warning me-2"></i>
                    Rate <strong>{{$comic->title}}</strong>
                </h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="comicReviewForm" name="comicReviewForm">
                <input type="hidden" name="comic_id" value="{{$comic->id}}">
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="rating" class="form-label">Your Rating</label>
                        <div class="rating-select d-flex justify-content-between mb-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating1" value="1">
                                <label class="form-check-label" for="rating1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating2" value="2">
                                <label class="form-check-label" for="rating2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating3" value="3" checked>
                                <label class="form-check-label" for="rating3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating4" value="4">
                                <label class="form-check-label" for="rating4">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating5" value="5">
                                <label class="form-check-label" for="rating5">5</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="review" class="form-label">Your Review</label>
                        <textarea name="review" id="review" class="form-control bg-dark-subtle text-white border-secondary"
                            cols="5" rows="5" placeholder="Share your thoughts about this comic..."></textarea>
                        <div class="invalid-feedback" id="review-error"></div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-paper-plane me-2"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $("#comicReviewForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{route('comic.saveReview')}}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            data: $("#comicReviewForm").serializeArray(),
            success: function(response) {
                if(response.status == false){
                    var errors = response.errors;
                    if(errors.review){
                        $("#review").addClass('is-invalid');
                        $("#review-error").html(errors.review);
                    } else{
                        $("#review").removeClass('is-invalid');
                        $("#review-error").html('');
                    }
                } else {
                    window.location.href='{{ route("comic.show",$comic->id )}}'
                }
            }
        });
    });
</script>

<!-- Add Custom CSS -->
<style>
    /* Hover effects */
    .hover-scale {
        transition: transform 0.3s ease;
    }
    .hover-scale:hover {
        transform: scale(1.05);
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
    }

    .hover-text-primary:hover {
        color: var(--bs-primary) !important;
    }

    /* Background gradient for image overlays */
    .bg-gradient-dark {
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
    }

    /* Star rating improvements */
    .back-stars {
        position: relative;
        color: #444;
    }

    .front-stars {
        position: absolute;
        top: 0;
        left: 0;
        overflow: hidden;
        color: #FFD700;
    }

    /* Custom scrollbar for reviews */
    .review-list {
        max-height: 800px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .review-list::-webkit-scrollbar {
        width: 8px;
    }

    .review-list::-webkit-scrollbar-track {
        background: #333;
        border-radius: 10px;
    }

    .review-list::-webkit-scrollbar-thumb {
        background: #666;
        border-radius: 10px;
    }

    .review-list::-webkit-scrollbar-thumb:hover {
        background: #888;
    }
</style>
@endsection
