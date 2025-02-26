<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;



class AccountController extends Controller
{
    //nampilin halaman register
    public function register(){
        return view('account.register');
    }

    //proses registrasi user
    public function processRegister(Request $request){
        $validator = validator::make($request->all(),[
            'name' =>'required|min:3|max:255',
            'email' =>'required|email|unique:users',
            'password' =>'required|confirmed|min:6',
            'password_confirmation' =>'required',
        ]);


        if($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }

        //kalo validasi berhasil, bakal proses registrasi disini
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.login')->with('success', 'You have been registered successfully');
    }

    //nampilin halaman login
    public function login(){
        return view('account.login');
    }

    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[
            'email' =>'required|email',
            'password' =>'required',
        ]);

        if($validator->fails()) {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }

        //cek kecocokan email dan password

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Mengecek peran pengguna setelah login
            $user = Auth::user();

            if ($user->role === 'admin') {
                // Jika pengguna adalah admin, arahkan ke dashboard admin
                return redirect()->route('admin.dashboard');
            }

            // Jika pengguna adalah user biasa, arahkan ke halaman home
            return redirect()->route('home');
        } else {
            // Jika login gagal, kembalikan ke halaman login dengan pesan error
            return redirect()->route('account.login')->with('error', 'Invalid email or password');
        }

    }
     //nampilin halaman profile user
    public function profile(){

        $user = User::find(Auth::user()->id);

        return view('account.profile', [
            'user' => $user
        ]);
    }
    public function updateProfile(Request $request){

        $rules = [
            'name' =>'required|min:3|max:255',
            'email' =>'required|email|unique:users,email,'.Auth::user()->id.',id',
        ];


        if (!empty($request->image)){
            $rules['image'] = 'image';
        };

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()) {
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        //buat upload image
        if (!empty($request->image)){
            File::delete(public_path('uploads/profile/'.$user->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time(). '.'. $ext;
            $image->move(public_path('uploads/profile'), $imageName);

            $user->image = $imageName;
            $user->save();
        }



        return redirect()->route('account.profile')->with('success', 'Profile changes saved successfully');

    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'You have been logged out successfully');
    }

    public function myReviews(Request  $request){

        $reviews = Review::with('comic')->where('user_id',Auth::user()->id);
        $reviews = $reviews->orderBy('created_at','DESC');

        if (!empty($request->search)){
            $reviews = $reviews->where('review','like','%'.$request->search.'%')
            ->orWhereHas('comic', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        $reviews = $reviews->paginate(8);

        return view('account.my-reviews.my-reviews',[
            'reviews' => $reviews
        ]);
    }

    public function editReview($id){

        $review = Review::where([
            'id' => $id,
            'user_id' => Auth::user()->id
        ])->with('comic')->first();

        return view('account.my-reviews.edit-review',[
            'review' => $review
        ]);

    }

    public function updateReview($id ,Request $request){

        $review = Review::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'review' => 'required',
            'rating' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.myReviews.editReview',$id)->withInput()->withErrors($validator);
        }

        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        session()->flash('success', 'Review updated successfully');
        return redirect()->route('account.myReviews');

    }

    //this method will delete a review
    public function deleteReview(Request $request){

        $id = $request->id;
        $review = Review::find($id);

        if( $review == null ) {
            return response()->json([
                'status' => false
            ]);
        }

        $review->delete();

        session()->flash('success','Review deleted successfulyy');
        return response()->json([
            'status' => true,
            'message' => 'Review deleted successfulyy'
        ]);
    }




}
