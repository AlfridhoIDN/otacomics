<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Pastikan user adalah publisher
        if ($user->role !== 'publisher') {
            abort(403, 'Unauthorized action.');
        }

        // Ambil semua ID komik yang author-nya adalah user yang login
        $comics = Comic::where('author', $user->name)->pluck('id');

        // Ambil review yang terkait dengan komik yang dibuat oleh author tersebut
        $reviews = Review::with('comic', 'user')
            ->whereIn('comic_id', $comics) // Filter hanya review dari komik si author
            ->orderBy('created_at', 'desc');

        // Jika ada pencarian
        if (!empty($request->search)) {
            $reviews = $reviews->where(function ($query) use ($request) {
                $query->where('review', 'like', '%' . $request->search . '%')
                    ->orWhere('rating', 'like', '%' . $request->search . '%')
                    ->orWhereHas('comic', function ($q) use ($request) {
                        $q->where('title', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Paginate hasilnya
        $reviews = $reviews->paginate(10);

        return view('account.reviews.list', [
            'reviews' => $reviews
        ]);
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('account.reviews.edit', [
            'review' => $review
        ]);
    }

    public function updateReview(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'review' => 'required|string|min:10',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.reviews.edit', $id)->withInput()->withErrors($validator);
        }

        $review->review = $request->review;
        $review->status = $request->status;
        $review->save();

        session()->flash('success', 'Review updated successfully');
        return redirect()->route('account.reviews');
    }


    public function deleteReview(Request $request){

        $id = $request->id;

        $review = Review::find($id);

        if($review == null){
            session()->flash('error','Review not found');
            return response()->json([
                'status' => false
            ]);
        } else{
            $review->delete();
            session()->flash('success','Review deleted successfully');
            return response()->json([
               'status' => false
            ]);
        }
    }
}
