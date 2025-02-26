@extends('layouts.app')

@section('main')
<div class="comic-reader-container bg-dark text-white">
    <!-- Top Navigation Bar -->
    <div class="sticky-top bg-secondary border-bottom border-secondary">
        <div class="container-fluid py-3">
            <div class="row align-items-center">
                <div class="col-4">
                    <a href="{{route('home')}}" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Comics
                    </a>
                </div>
                <div class="col-4 text-center">
                    <h4 class="mb-0 fw-bold comic-title">{{ $comic->title }}</h4>
                </div>
                <div class="col-4 text-end">
                    <div class="dropdown">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" id="readingOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cog"></i> Options
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark text-white border-secondary" aria-labelledby="readingOptionsDropdown">
                            <li><button class="dropdown-item text-white reading-mode" data-mode="continuous">Continuous Scroll</button></li>
                            <li><button class="dropdown-item text-white reading-mode" data-mode="paged">Page by Page</button></li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><button class="dropdown-item text-white" id="toggleFullscreen"><i class="fa fa-expand me-2"></i> Fullscreen</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comic Info Panel -->
    <div class="container-fluid py-4 bg-gradient-to-bottom">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-5 fw-bold mb-3">{{ $comic->title }}</h1>
                <p class="lead mb-4">{{ $comic->description }}</p>
                <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                    @if($comic->genre)
                        <span class="badge bg-primary rounded-pill px-3 py-2">{{ $comic->genre }}</span>
                    @endif
                    @if($comic->author)
                        <span class="badge bg-secondary rounded-pill px-3 py-2">Author: {{ $comic->author }}</span>
                    @endif
                    <span class="badge bg-info rounded-pill px-3 py-2">{{ count($comic->mangas) }} Pages</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Reading Progress Bar -->
    <div class="progress rounded-0" style="height: 5px;">
        <div class="progress-bar bg-primary" role="progressbar" style="width: 0%;" id="readingProgress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <!-- Comic Pages Container -->
    <div class="container-lg py-4 comic-pages" id="comicPagesContainer">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Page Counter -->
                <div class="page-counter text-center mb-4">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-light" id="prevPage" disabled>
                            <i class="fa fa-chevron-left"></i> Previous
                        </button>
                        <span class="btn btn-sm btn-light disabled">
                            Page <span id="currentPage">1</span> of {{ count($comic->mangas) }}
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-light" id="nextPage">
                            Next <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Pages -->
                @foreach($comic->mangas as $index => $manga)
                <div class="manga-page mb-4" data-page="{{ $index + 1 }}">
                    <div class="card bg-black border-0 shadow-lg">
                        <div class="card-header bg-dark bg-opacity-75 text-white text-center border-bottom border-secondary">
                            <small>Page {{ $index + 1 }} of {{ count($comic->mangas) }}</small>
                        </div>
                        <div class="card-body p-0">
                            <img src="{{ asset($manga->file_path) }}" alt="Page {{ $index + 1 }}" class="img-fluid w-100 manga-image" loading="lazy">
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Bottom Navigation -->
                <div class="navigation-buttons my-5 d-flex justify-content-between align-items-center">
                    <a href="{{ route('comic.show', $comic->id) }}" class="btn btn-outline-light">
                        <i class="fa fa-info-circle me-2"></i> Comic Details
                    </a>

                    <div class="text-center">
                        @if($nextComic)
                            <a href="{{ route('comic.read', $nextComic->id) }}" class="btn btn-primary">
                                Next Comic <i class="fa fa-arrow-right ms-2"></i>
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fa fa-flag-checkered me-2"></i> You've Reached the End
                            </button>
                        @endif
                    </div>

                    <button class="btn btn-outline-light" id="scrollToTop">
                        <i class="fa fa-arrow-up me-2"></i> Top
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Reading mode handling
        let readingMode = localStorage.getItem('comicReadingMode') || 'continuous';
        updateReadingMode(readingMode);

        $('.reading-mode').on('click', function() {
            readingMode = $(this).data('mode');
            localStorage.setItem('comicReadingMode', readingMode);
            updateReadingMode(readingMode);
        });

        function updateReadingMode(mode) {
            if (mode === 'paged') {
                $('.manga-page').hide();
                $('.manga-page[data-page="1"]').show();
                $('#prevPage, #nextPage, .page-counter').show();
                $('#comicPagesContainer').removeClass('continuous-mode').addClass('paged-mode');
            } else {
                $('.manga-page').show();
                $('#prevPage, #nextPage').hide();
                $('#comicPagesContainer').removeClass('paged-mode').addClass('continuous-mode');
            }

            // Highlight active mode
            $('.reading-mode').removeClass('active');
            $(`.reading-mode[data-mode="${mode}"]`).addClass('active');
        }

        // Page navigation
        $('#nextPage').on('click', function() {
            const currentPage = parseInt($('#currentPage').text());
            const totalPages = {{ count($comic->mangas) }};

            if (currentPage < totalPages) {
                $('.manga-page').hide();
                $(`.manga-page[data-page="${currentPage + 1}"]`).show();
                $('#currentPage').text(currentPage + 1);

                // Enable/disable buttons as needed
                $('#prevPage').prop('disabled', false);
                if (currentPage + 1 >= totalPages) {
                    $('#nextPage').prop('disabled', true);
                }

                // Update progress
                updateProgress((currentPage + 1) / totalPages * 100);
            }
        });

        $('#prevPage').on('click', function() {
            const currentPage = parseInt($('#currentPage').text());

            if (currentPage > 1) {
                $('.manga-page').hide();
                $(`.manga-page[data-page="${currentPage - 1}"]`).show();
                $('#currentPage').text(currentPage - 1);

                // Enable/disable buttons as needed
                $('#nextPage').prop('disabled', false);
                if (currentPage - 1 <= 1) {
                    $('#prevPage').prop('disabled', true);
                }

                // Update progress
                updateProgress((currentPage - 1) / {{ count($comic->mangas) }} * 100);
            }
        });

        // Scroll to top button
        $('#scrollToTop').on('click', function() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });

        // Fullscreen toggle
        $('#toggleFullscreen').on('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                $(this).html('<i class="fa fa-compress me-2"></i> Exit Fullscreen');
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                    $(this).html('<i class="fa fa-expand me-2"></i> Fullscreen');
                }
            }
        });

        // Reading progress tracking
        $(window).on('scroll', function() {
            if (readingMode === 'continuous') {
                const scrollTop = $(window).scrollTop();
                const docHeight = $(document).height();
                const winHeight = $(window).height();
                const scrollPercent = (scrollTop) / (docHeight - winHeight) * 100;
                updateProgress(scrollPercent);
            }
        });

        function updateProgress(percent) {
            $('#readingProgress').css('width', percent + '%').attr('aria-valuenow', percent);
        }

        // Initialize image click for next/previous
        $('.manga-image').on('click', function(e) {
            if (readingMode === 'paged') {
                const imageWidth = $(this).width();
                const clickX = e.offsetX;

                if (clickX > imageWidth / 2) {
                    $('#nextPage').trigger('click');
                } else {
                    $('#prevPage').trigger('click');
                }
            }
        });

        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if (readingMode === 'paged') {
                if (e.keyCode === 39) { // Right arrow
                    $('#nextPage').trigger('click');
                } else if (e.keyCode === 37) { // Left arrow
                    $('#prevPage').trigger('click');
                }
            }
        });
    });
</script>

<style>
    /* Dark theme base styling */
    body {
        background-color: #121212;
    }

    .bg-dark-subtle {
        background-color: #232323;
    }

    .comic-reader-container {
        min-height: 100vh;
    }

    /* Gradient background for the header */
    .bg-gradient-to-bottom {
        background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
    }

    /* Comic title animation */
    .comic-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Page modes */
    .paged-mode .manga-page {
        animation: fadeIn 0.3s ease-in-out;
    }

    .continuous-mode .manga-page {
        animation: none;
    }

    /* Images */
    .manga-image {
        transition: all 0.2s ease-in-out;
    }

    .paged-mode .manga-image {
        cursor: pointer;
    }

    .paged-mode .manga-image:hover {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
    }

    /* Navigation buttons hover effects */
    .btn-outline-light:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #232323;
    }

    ::-webkit-scrollbar-thumb {
        background: #666;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #888;
    }

    /* Mobile optimizations */
    @media (max-width: 768px) {
        .comic-title {
            font-size: 1rem;
        }

        .navigation-buttons {
            flex-direction: column;
            gap: 1rem;
        }

        .navigation-buttons > * {
            width: 100%;
        }
    }
</style>
@endsection
