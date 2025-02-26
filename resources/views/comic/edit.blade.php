@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
            @include('layouts.message')
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Edit Comic
                </div>
                <div class="card-body text-white bg-dark">
                    <form action="{{route('comic.update',$comic->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" value="{{old('title',$comic->title)}}" placeholder="Title" name="title" id="title" />
                            @error('title')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control @error('author') is-invalid @enderror" value="{{old('author',$comic->author)}}" placeholder="Author"  name="author" id="author" readonly/>
                            @error('author')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="genre" class="form-label">Genre</label>
                            <select name="genre" id="genre" class="form-control @error('genre') is-invalid @enderror">
                                <option value="" disabled selected>Choose a genre</option>
                                <option value="action" {{ old('genre',$comic->genre) == 'action' ? 'selected' : '' }}>Action</option>
                                <option value="romance" {{ old('genre',$comic->genre) == 'romance' ? 'selected' : '' }}>Romance</option>
                            </select>
                            @error('genre')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="release_date" class="form-label">Date</label>
                            <input type="date" class="form-control" value="{{old('release_date',$comic->release_date)}}" placeholder="Date"  name="release_date" id="date"/>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Description" cols="30" rows="5">{{old('description',$comic->description)}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="Image" class="form-label">Cover</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"  name="image" id="image"/>
                            @error('image')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror

                            @if(!empty($comic->image))
                                <img class="w-25 my-3" src="{{ asset('uploads/comics/'.$comic->image) }}" alt="">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{$comic->status == 1 ? 'selected' : ''}}>Active</option>
                                <option value="0" {{$comic->status == 0 ? 'selected' : ''}}>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="manga" class="form-label">Manga Pages</label>
                            <input type="file" class="form-control" name="manga[]" id="manga" multiple />
                            @foreach($mangas as $manga)
                                @if(!empty($manga->file_path))
                                    <img class="w-25 my-3" src="{{ asset($manga->file_path) }}" alt="Manga Page">
                                @endif
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
