<?php

namespace App\Http\Controllers;


use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ComicController extends Controller
{
    //nampilin list komik
    public function index(Request $request){
        $user = Auth::user();
        if ($user->role !== 'publisher') {
            abort(403, 'Unauthorized action.');
        }

        $comics = Comic::orderBy('created_at','desc');
        // search
        if(!empty($request->search)){
            $comics->where('title','like','%'.$request->search.'%');
            $comics->orWhere('genre','like','%'.$request->search.'%');
        }
        $comics = Comic::where('author', $user->name) // nampilin sesuai nama publisher
                  ->when($request->search, function ($query) use ($request) {
                      return $query->where('title', 'like', '%' . $request->search . '%');
                  })
                  ->withSum('reviews','rating')
                  ->paginate(8);

        return view('comic.list', [
            'comics' => $comics
        ]);
    }

    public function create(){
        return view('comic.create');
    }

    public function store(Request $request)
{
    $rules = [
        'title' => 'required|string|min:3|max:255',
        'author' => 'required|string|min:3|max:255',
        'status' => 'required|boolean',
        'genre' => 'required|string',
        'release_date' => 'required|date',
        'image' => 'nullable|image',
        'manga.*' => 'nullable|file|mimes:jpg,png,pdf'  // Allow multiple manga pages
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()->route('comic.create')->withInput()->withErrors($validator);
    }

    // Create the comic entry
    $comic = new Comic();
    $comic->title = $request->title;
    $comic->description = $request->description;
    $comic->author = $request->author;
    $comic->genre = $request->genre;
    $comic->release_date = $request->release_date;
    $comic->status = $request->status;
    $comic->save();

    // Handle the comic cover image
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/comics'), $imageName);
        $comic->image = $imageName;
        $comic->save();
    }

    // Handle the manga pages
    if ($request->hasFile('manga')) {
        foreach ($request->file('manga') as $mangaPage) {
            $mangaPageName = time() . '-' . uniqid() . '.' . $mangaPage->getClientOriginalExtension();
            $mangaPage->move(public_path('uploads/mangas'), $mangaPageName);

            // Save the manga page to the database
            $comic->mangas()->create([
                'file_path' => 'uploads/mangas/' . $mangaPageName
            ]);
        }
    }

    return redirect()->route('comic.index')->with('success', 'Comic added successfully');
}

    // In ComicController
    public function edit($id)
    {
        $comic = Comic::findOrFail($id);
        $mangas = $comic->mangas; // Retrieve associated manga pages

        return view('comic.edit', [
            'comic' => $comic,
            'mangas' => $mangas // Pass mangas to the view
        ]);
    }


    public function update($id, Request $request)
{
    $comic = Comic::findOrFail($id);

    $rules = [
        'title' => 'required|string|min:3|max:255',
        'author' => 'nullable|string|min:3|max:255',
        'status' => 'required|boolean',
        'genre' => 'required|string',
        'release_date' => 'required|date',
        'image' => 'nullable|image',
        'manga.*' => 'nullable|file|mimes:jpg,png,pdf'  // Allow multiple manga pages
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()->route('comic.edit', $comic->id)->withInput()->withErrors($validator);
    }

    // Update the comic entry
    $comic->title = $request->title;
    $comic->description = $request->description;
    $comic->author = $request->author;
    $comic->genre = $request->genre;
    $comic->release_date = $request->release_date;
    $comic->status = $request->status;
    $comic->save();

    // Handle the cover image
    if ($request->hasFile('image')) {
        File::delete(public_path('uploads/comics/' . $comic->image));
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/comics'), $imageName);
        $comic->image = $imageName;
        $comic->save();
    }

    // Handle the manga pages
    if ($request->hasFile('manga')) {
        foreach ($request->file('manga') as $mangaPage) {
            $mangaPageName = time() . '-' . uniqid() . '.' . $mangaPage->getClientOriginalExtension();
            $mangaPage->move(public_path('uploads/mangas'), $mangaPageName);

            // Save the manga page to the database
            $comic->mangas()->create([
                'file_path' => 'uploads/mangas/' . $mangaPageName
            ]);
        }
    }

    return redirect()->route('comic.index')->with('success', 'Comic updated successfully');
}


    public function delete(Request $request){
        $comic = Comic::findOrFail($request->id);

        if($comic ==null){
            session()->flash('error','Comic not found');
            return response()->json([
                'status' => false ,
                'message' => 'Comic not found']);
        }else{
            File::delete(public_path('uploads/comics/'.$comic->image));
            $comic->delete();

            session()->flash('success','Comic deleted successfully');
            return response()->json([
                'status' => true ,
                'message' => 'Comic deleted successfully']);
        }
    }

    public function read($id)
{
    $comic = Comic::findOrFail($id);
    $mangaPages = $comic->mangas; // Get related manga pages

    // Find next comic (optional)
    $nextComic = Comic::where('id', '>', $comic->id)->first();

    return view('comic.read', [
        'comic' => $comic,
        'mangaPages' => $mangaPages,
        'nextComic' => $nextComic
    ]);
}

}
