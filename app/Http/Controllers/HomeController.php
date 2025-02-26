<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index(Request $request){

        $comics = Comic::withCount('reviews')->withSum('reviews','rating')->orderBy('created_at','desc');

        if(!empty($request->search)){
            $comics->where('title','like','%'.$request->search.'%')
            ->orWhere('author','like','%'.$request->search.'%');
        }

        $comics = $comics->where('status',1)->paginate(8);

        return view('home',[
            'comics' => $comics
        ]);
    }

    public function show($id){
        $comic = Comic::with(['reviews.user','reviews' => function($query){
            $query->where('status',1);
        }])->withCount('reviews')->withSum('reviews','rating')->findOrFail($id);

        if($comic->status == 0){
            abort(404);
        }

        $relatedComics = Comic::where('status',1)
        ->withCount('reviews')
        ->withSum('reviews','rating')
        ->take(3)
        ->where('id','!=',$id)
        ->inRandomOrder()
        ->get();


        return view('comic-show',[
            'comic' => $comic,
            'relatedComics' => $relatedComics
        ]);
    }

    public function saveReview(Request $request){
        $validator = Validator::make($request->all(),[
            // 'comic_id' => 'required|exists:comics,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $countReview = Review::where('user_id',Auth::user()->id)
        ->where('comic_id',$request->comic_id)
        ->count();

        if($countReview >= 1){
            session()->flash('error','You have already reviewed this comic');
            return response()->json([
                'status' => false,
            ]);
        }

        $review = new Review();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->user_id = Auth::user()->id;
        $review->comic_id = $request->comic_id;
        $review->save();

        session()->flash('success','comment added successfully');

        return response()->json([
            'status' => true,
        ]);
    }
}
