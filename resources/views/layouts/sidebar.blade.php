<!-- Enhanced Sidebar Navigation -->
<div class="card border-0 shadow-lg">
    <div class="card-header  text-white">
        Welcome, {{Auth::user()->name}}
    </div>
   <div class="card-body bg-dark">
           <div class="text-center mb-3">
                @if(Auth::user()->image != "")
                    <img src="{{ asset('uploads/profile/'.Auth::user()->image) }}"
                        width="150" height="150"
                        style="object-fit: cover; border-radius: 50%;"
                        alt="">
                @endif
            </div>
        <div class="h5 text-center">
            <strong class="text-white">{{Auth::user()->name}}  </strong>
            <p class="text-white h6 mt-2 "> {{ (Auth::user()->reviews->count() > 1) ? Auth::user()->reviews->count().' Reviews' : Auth::user()->reviews->count().' Review' }} </p>
        </div>
    </div>
</div>
<div class="card border-0 shadow-lg mt-3">
    <div class="card-header  text-white">
        Navigation
    </div>
    <div class="card-body sidebar bg-dark">
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                @if (Auth::user()->role == 'publisher' || Auth::user()->role == 'admin')
                    <li class="nav-item">
                        <a href="{{route('comic.index')}}" class="nav-link">
                            <i class="fa-solid fa-book me-3"></i>
                            <span>Comics</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('account.reviews')}}" class="nav-link">
                            <i class="fa-solid fa-star me-3"></i>
                            <span>Comic Reviews</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{route('account.profile')}}" class="nav-link {{ Request::is('account/profile') ? 'active' : '' }}">
                        <i class="fa-solid fa-user me-3"></i>
                        <span>Profile</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('account.myReviews')}}" class="nav-link">
                        <i class="fa-solid fa-comment me-3"></i>
                        <span>My Reviews</span>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="#ganti-pw" class="nav-link">
                        <i class="fa-solid fa-lock me-3"></i>
                        <span>Change Password</span>
                    </a>
                </li> --}}

                <li class="nav-item mt-2">
                    <a href="{{route('account.logout')}}" class="nav-link logout-link">
                        <i class="fa-solid fa-right-from-bracket me-3"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<style>
.sidebar-nav {
    padding: 0.5rem;
}

.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 0.25rem;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    transform: translateX(5px);
}

.nav-link.active {
    background: rgba(var(--bs-primary-rgb), 0.2);
    color: var(--bs-primary);
}

.nav-link i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
    opacity: 0.8;
}

.nav-link span {
    font-weight: 500;
    font-size: 0.95rem;
}

/* Logout Link Special Styling */
.logout-link {
    color: rgba(220, 53, 69, 0.8);
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.logout-link:hover {
    background: rgba(220, 53, 69, 0.1);
    color: rgb(220, 53, 69);
    border-color: rgba(220, 53, 69, 0.3);
}

/* Hover Effects */
.nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    width: 3px;
    height: 0;
    background: var(--bs-primary);
    transition: height 0.2s ease;
}

.nav-link:hover::before {
    height: 100%;
}

/* Active Link Animation */
@keyframes activeLink {
    from {
        background-position: 0% center;
    }
    to {
        background-position: 100% center;
    }
}

.nav-link.active {
    background: linear-gradient(
        90deg,
        rgba(var(--bs-primary-rgb), 0.2) 0%,
        rgba(var(--bs-primary-rgb), 0.1) 100%
    );
    background-size: 200% 100%;
    animation: activeLink 2s linear infinite;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .nav-link {
        padding: 0.5rem 0.75rem;
    }

    .nav-link i {
        font-size: 1rem;
    }

    .nav-link span {
        font-size: 0.9rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add ripple effect to nav links
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const ripple = document.createElement('div');

            ripple.className = 'ripple';
            ripple.style.left = `${e.clientX - rect.left}px`;
            ripple.style.top = `${e.clientY - rect.top}px`;

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Add hover effect
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });

        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});
</script>
