@extends('layouts.app')

@section('main')
<div class="container-fluid px-4 py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <!-- Enhanced Header Section with better spacing and hierarchy -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1 class="display-5 text-white fw-bold m-0 mb-2">Comics Collection</h1>
                    <p class="text-white-50 m-0">Discover and explore your favorite comics</p>
                </div>
                <a href="{{route('home')}}" class="btn btn-outline-light btn-sm d-flex align-items-center">
                    <i class="fa-solid fa-xmark me-2"></i>Clear Filters
                </a>
            </div>

            <!-- Improved Search Card with subtle gradient -->
            <div class="search-card mb-5">
                <form action="" method="get">
                    <div class="input-group input-group-lg custom-search">
                        <input
                            type="text"
                            value="{{Request::get('search')}}"
                            class="form-control border-0 search-input"
                            name="search"
                            placeholder="Search comics by title, author..."
                        >
                        <button class="btn btn-primary px-4 search-btn">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Enhanced Comics Grid with improved card design -->
            <div class="row g-4">
                @if ($comics->isNotEmpty())
                    @foreach ($comics as $comic)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="comic-card h-100">
                            <a href="{{route('comic.show', $comic->id)}}" class="text-decoration-none">
                                <div class="comic-cover-wrapper">
                                    @if ($comic->image != '')
                                        <img
                                            src="{{ asset('uploads/comics/'.$comic->image) }}"
                                            alt="{{$comic->title}}"
                                            class="w-100 h-100 object-fit-cover"
                                        >
                                    @else
                                        <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center">
                                            <i class="fa-regular fa-image fa-2x text-white-50"></i>
                                        </div>
                                    @endif
                                    <div class="comic-overlay">
                                        <span class="view-details">View Details</span>
                                    </div>
                                </div>

                                <div class="card-content">
                                    <h3 class="comic-title text-truncate">{{$comic->title}}</h3>
                                    <p class="comic-author">by {{$comic->author}}</p>

                                    @php
                                    if($comic->reviews_count > 0) {
                                        $avgRating = $comic->reviews_sum_rating/$comic->reviews_count;
                                    } else {
                                        $avgRating = 0;
                                    }
                                        $avgRatingPer = ($avgRating*100)/5;
                                    @endphp
                                    
                                    <div class="star-rating d-inline-flex ml-2" title="">

                                        <span class="rating-text theme-font theme-yellow">{{ number_format($avgRating, 1) }}</span>
                                        <div class="star-rating d-inline-flex mx-2" title="">
                                            <div class="back-stars ">
                                                <i class="fa fa-star " aria-hidden="true"></i>
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
                                        <span class="theme-font text-secondary">( {{ ($comic->reviews_count > 1) ? $comic->reviews_count. ' Reviews'
                                            : $comic->reviews_count. ' Review'  }})</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fa-regular fa-face-frown"></i>
                            <h3>No comics found</h3>
                            <p>Try adjusting your search terms</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Styled Pagination -->
            <div class="pagination-wrapper">
                {{$comics->links()}}
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Base Styles */
.search-card {
    background: linear-gradient(to right, rgba(75, 75, 75, 0.2), rgba(100, 100, 100, 0.2));
    border-radius: 16px;
    padding: 8px;
}

.custom-search .search-input {
    background-color: rgba(75, 75, 75, 0.3);
    color: white;
    border-radius: 12px 0 0 12px;
    padding: 12px 20px;
    font-size: 1rem;
}

.custom-search .search-input:focus {
    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
    color: white;
}

.custom-search .search-btn {
    border-radius: 0 12px 12px 0;
    padding: 0 25px;
    transition: all 0.3s ease;
}

.custom-search .search-btn:hover {
    opacity: 0.9;
}

/* Comic Card Styles */
.comic-card {
    background: rgba(75, 75, 75, 0.2);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.comic-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.comic-cover-wrapper {
    aspect-ratio: 2/3;
    position: relative;
    overflow: hidden;
}

.comic-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.comic-card:hover .comic-overlay {
    opacity: 1;
}

.view-details {
    color: white;
    font-weight: 500;
    padding: 8px 16px;
    border: 2px solid white;
    border-radius: 20px;
}

.card-content {
    padding: 1.25rem;
}

.comic-title {
    font-size: 1.1rem;
    color: white;
    margin-bottom: 4px;
    font-weight: 600;
}

.comic-author {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    margin-bottom: 12px;
}

.rating-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stars {
    color: #ffd700;
    font-size: 0.9rem;
}

.rating-info {
    display: flex;
    align-items: center;
    gap: 4px;
}

.rating-value {
    color: #ffd700;
    font-weight: 600;
}

.rating-count {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.85rem;
}

/* Empty State Styling */
.empty-state {
    text-align: center;
    padding: 4rem 0;
}

.empty-state i {
    font-size: 3.5rem;
    color: rgba(255, 255, 255, 0.3);
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: rgba(255, 255, 255, 0.5);
}

/* Pagination Styling */
.pagination-wrapper {
    margin-top: 3rem;
}

/* Force white text in search input */
.search-input::placeholder {
    color: rgba(255, 255, 255, 0.5) !important;
}
</style>
@endsection
