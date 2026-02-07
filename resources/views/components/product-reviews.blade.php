{{-- Product Reviews Component with Filters & Staff Response --}}
@props(['product'])

@php
    $user = auth()->user();
    $userReview = $user ? $product->userReview($user->id) : null;
    
    // Get filter from request
    $ratingFilter = request()->get('rating', 'all');
    
    // Base query with eager loading
    $reviewsQuery = $product->reviews()->with(['user', 'response.user', 'images']);
    
    // Apply rating filter
    if ($ratingFilter === 'photos') {
        $reviewsQuery->has('images'); // Only reviews with photos
    } elseif ($ratingFilter !== 'all' && is_numeric($ratingFilter)) {
        $reviewsQuery->where('rating', $ratingFilter);
    }
    
    // Get reviews with pagination  
    $reviews = $reviewsQuery->paginate(10)->appends(['rating' => $ratingFilter]);
    
    // Calculate counts
    $averageRating = $product->averageRating();
    $reviewsCount = $product->reviewsCount();
    $fiveStarCount = $product->reviews()->where('rating', 5)->count();
    $fourStarCount = $product->reviews()->where('rating', 4)->count();
    $threeStarCount = $product->reviews()->where('rating', 3)->count();
    $withPhotosCount = $product->reviews()->has('images')->count();
@endphp

<div class="space-y-8" id="reviews-section">
    {{-- Reviews Summary Header --}}
    <div class="border-t border-gray-200 pt-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold tracking-tight">Customer Reviews</h2>
        </div>

        {{-- Rating Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            {{-- Average Rating Display --}}
            <div class="flex flex-col items-center justify-center p-8 bg-gray-50 rounded-lg">
                <div class="text-6xl font-bold mb-2" id="average-rating">{{ number_format($averageRating, 1) }}</div>
                <x-star-rating :rating="round($averageRating)" size="lg" />
                <p class="text-sm text-gray-600 mt-2" id="reviews-count-text">{{ $reviewsCount }} {{ Str::plural('review', $reviewsCount) }}</p>
            </div>

            {{-- Rating Distribution --}}
            <div class="space-y-2">
                @foreach([5, 4, 3, 2, 1] as $star)
                    @php
                        $count = $product->reviews()->where('rating', $star)->count();
                        $percentage = $reviewsCount > 0 ? ($count / $reviewsCount) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium w-8">{{ $star }} ★</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-black transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Rating Filter Tabs --}}
        <div class="flex flex-wrap gap-2 mt-6">
            <a href="?rating=all#all-reviews" 
               class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $ratingFilter === 'all' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All ratings
            </a>
            @if($withPhotosCount > 0)
            <a href="?rating=photos#all-reviews"
               class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $ratingFilter === 'photos' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                With photos ({{ $withPhotosCount }})
            </a>
            @endif
            @if($fiveStarCount > 0)
            <a href="?rating=5#all-reviews"
               class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $ratingFilter === '5' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                5 stars ({{ $fiveStarCount }})
            </a>
            @endif
            @if($fourStarCount > 0)
            <a href="?rating=4#all-reviews"
               class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $ratingFilter === '4' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                4 stars ({{ $fourStarCount }})
            </a>
            @endif
            @if($threeStarCount > 0)
            <a href="?rating=3#all-reviews"
               class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $ratingFilter === '3' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                3 stars ({{ $threeStarCount }})
            </a>
            @endif
        </div>
    </div>

    {{-- Write Review Form or User's Review --}}
    @auth
        <div class="border-t border-gray-200 pt-8">
            @if($userReview)
                {{-- User's Existing Review --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm font-bold text-blue-800 mb-2">Your Review</p>
                            <x-star-rating :rating="$userReview->rating" size="md" />
                        </div>
                        <button
                            onclick="deleteReview({{ $userReview->id }})"
                            class="text-sm text-red-600 hover:text-red-800 font-medium"
                        >
                            Delete
                        </button>
                    </div>
                    @if($userReview->title)
                        <h4 class="font-bold mb-2">{{ $userReview->title }}</h4>
                    @endif
                    <p class="text-gray-700">{{ $userReview->comment }}</p>
                    <p class="text-xs text-gray-500 mt-2">Posted {{ $userReview->timeAgo }}</p>
                </div>
            @else
                {{-- Write Review Form --}}
                <div>
                    <h3 class="text-lg font-bold mb-4">Write a Review</h3>
                    <form id="review-form" class="space-y-4">
                        @csrf
                        
                        {{-- Rating Input --}}
                        <div>
                            <label class="block text-sm font-bold mb-2">Rating *</label>
                            <x-star-rating :rating="0" :interactive="true" name="rating" size="xl" />
                        </div>

                        {{-- Title Input --}}
                        <div>
                            <label for="review-title" class="block text-sm font-bold mb-2">Review Title (Optional)</label>
                            <input
                                type="text"
                                id="review-title"
                                name="title"
                                maxlength="100"
                                placeholder="Sum up your experience"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                            >
                        </div>

                        {{-- Comment Input --}}
                        <div>
                            <label for="review-comment" class="block text-sm font-bold mb-2">Your Review *</label>
                            <textarea
                                id="review-comment"
                                name="comment"
                                rows="5"
                                minlength="10"
                                maxlength="1000"
                                placeholder="Tell us what you think about this product..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black resize-none"
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="char-count">0</span>/1000 characters (minimum 10)
                            </p>
                        </div>

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            class="w-full md:w-auto px-8 py-3 bg-black text-white font-bold rounded-full hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Submit Review
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @else
        {{-- Login Prompt --}}
        <div class="border-t border-gray-200 pt-8">
            <div class="bg-gray-50 rounded-lg p-6 text-center">
                <p class="text-gray-700 mb-4">Please log in to write a review</p>
                <a href="/login" class="inline-block px-6 py-2 bg-black text-white font-bold rounded-full hover:bg-gray-800 transition-colors">
                    Log In
                </a>
            </div>
        </div>
    @endauth

    {{-- Reviews List --}}
    <div class="border-t border-gray-200 pt-8" id="all-reviews">
        <h3 class="text-lg font-bold mb-6">All Reviews</h3>
        
        <div id="reviews-list" class="space-y-6">
            @forelse($reviews as $review)
                <div class="border-b border-gray-200 pb-6 last:border-0">
                    <div class="flex items-start gap-4">
                        {{-- User Avatar --}}
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-black text-white flex items-center justify-center font-bold">
                            {{ $review->userInitial }}
                        </div>

                        {{-- Review Content --}}
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="font-bold">{{ $review->user->name }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <x-star-rating :rating="$review->rating" size="sm" />
                                        @if($review->verified_purchase)
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full font-medium">
                                                Verified Purchase
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $review->timeAgo }}</span>
                            </div>

                            @if($review->title)
                                <h4 class="font-bold mb-2">{{ $review->title }}</h4>
                            @endif

                            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>

                            {{-- Review Images --}}
                            @if($review->images->isNotEmpty())
                                <div class="grid grid-cols-4 gap-2 mt-4">
                                    @foreach($review->images as $image)
                                        <img 
                                            src="{{ image_url($image->image_path) }}" 
                                            alt="Review image" 
                                            class="w-full h-24 object-cover rounded-lg border border-gray-200 hover:opacity-75 transition-opacity cursor-pointer"
                                        >
                                    @endforeach
                                </div>
                            @endif

                            {{-- Staff Response --}}
                            @if($review->response)
                                <div class="mt-4 bg-gray-50 rounded-lg p-4 border-l-4 border-black">
                                    <p class="text-xs font-bold text-gray-900 mb-2">Staff Response</p>
                                    <p class="text-sm text-gray-700">{{ $review->response->response }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">No reviews yet. Be the first to review this product!</p>
            @endforelse
        </div>

        {{-- Numbered Pagination with Softer Styling --}}
        @if($reviews->hasPages())
            <div class="mt-8">
                <nav class="flex items-center justify-center gap-1">
                    {{-- Previous Button --}}
                    @if ($reviews->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 cursor-not-allowed">« Previous</span>
                    @else
                        <a href="{{ $reviews->previousPageUrl() }}#all-reviews" class="px-3 py-2 text-gray-700 hover:text-black font-medium">« Previous</a>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $start = max(1, $reviews->currentPage() - 2);
                        $end = min($reviews->lastPage(), $reviews->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $reviews->url(1) }}#all-reviews" class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-full font-medium">1</a>
                        @if($start > 2)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                    @endif

                    @for($page = $start; $page <= $end; $page++)
                        @if ($page == $reviews->currentPage())
                            <span class="px-4 py-2 bg-gray-600 text-white rounded-full font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $reviews->url($page) }}#all-reviews" class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-full font-medium">{{ $page }}</a>
                        @endif
                    @endfor

                    @if($end < $reviews->lastPage())
                        @if($end < $reviews->lastPage() - 1)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                        <a href="{{ $reviews->url($reviews->lastPage()) }}#all-reviews" class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-full font-medium">{{ $reviews->lastPage() }}</a>
                    @endif

                    {{-- Next Button --}}
                    @if ($reviews->hasMorePages())
                        <a href="{{ $reviews->nextPageUrl() }}#all-reviews" class="px-3 py-2 text-gray-700 hover:text-black font-medium">Next »</a>
                    @else
                        <span class="px-3 py-2 text-gray-400 cursor-not-allowed">Next »</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</div>

{{-- JavaScript for Review Form --}}
@auth
@if(!$userReview)
<script>
// Character counter
const commentInput = document.getElementById('review-comment');
const charCount = document.getElementById('char-count');

if (commentInput) {
    commentInput.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
}

// Review form submission
const reviewForm = document.getElementById('review-form');
if (reviewForm) {
    reviewForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        
        try {
            const response = await fetch('{{ route("reviews.store", $product) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
showToast(data.message, 'success', {
                description: 'Your review has been submitted successfully',
                duration: 3000
            });
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast(data.message || 'Something went wrong', 'error', {
                description: 'Please check your input and try again',
                duration: 4000
            });
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Review';
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Failed to submit review. Please try again.', 'error', {
                description: 'Check your connection and review content',
                duration: 4000
            });
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Review';
        }
    });
}
</script>
@endif

@if($userReview)
<script>
async function deleteReview(reviewId) {
    if (!confirm('Are you sure you want to delete your review?')) {
        return;
    }
    
    try {
        const response = await fetch(`/reviews/${reviewId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success', {
                description: 'Your review has been deleted',
                duration: 3000
            });
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast(data.message || 'Failed to delete review', 'error', {
                description: 'Please try again or contact support',
                duration: 4000
            });
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Failed to delete review. Please try again.', 'error', {
                description: 'Please check your connection and try again',
                duration: 4000
            });
    }
}
</script>
@endif
@endauth
