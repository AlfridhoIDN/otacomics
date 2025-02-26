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
                    Comics
                </div>
                <div class="card-body bg-dark pb-0">
                    <div class="d-flex justify-content-between">
                        <a href="{{route('comic.create')}}" class="btn btn-primary">Add Comic</a>
                        <form action="" method="get">
                            <div class="d-flex">
                                <input type="text" class="form-control" name="search" value="{{Request::get('search')}}" placeholder="Search" />
                                <button type="submit" class="btn btn-primary ms-2">Search</button>
                                <a href="{{route('comic.index')}}" class="btn btn-secondary ms-2">Clear</a>
                            </div>
                        </form>
                    </div>
                    <table class="table  table-striped mt-3">
                        <thead class="table-dark ">
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Release</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                            <tbody>
                                @if ($comics->isNotEmpty())
                                    @foreach ($comics as $comic)
                                    @php
                                        if($comic->reviews_count > 0) {
                                            $avgRating = $comic->reviews_sum_rating/$comic->reviews_count;
                                            } else {
                                                $avgRating = 0;
                                            }
                                        $avgRatingPer = ($avgRating*100)/5;
                                    @endphp
                                        <tr>
                                            <td>{{$comic->title}}</td>
                                            <td>{{$comic->author}}</td>
                                            <td>{{$comic->genre}}</td>
                                            <td>{{$comic->release_date}}</td>
                                            <td>{{ number_format($avgRating, 1) }} Star ({{ ($comic->reviews_count > 1) ? $comic->reviews_count. ' Reviews'
                                            : $comic->reviews_count. ' Review'  }})</td>
                                            <td>
                                                @if($comic->status == 1)
                                                    <span class="text-success">Active</span> {{--text-success--}}
                                                @else
                                                    <span class="text-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-sm"><i class="fa-regular fa-star"></i></a>
                                                <a href="{{route('comic.edit',$comic->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" onclick="deleteComic({{$comic->id}})" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">No data available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </thead>
                    </table>
                    <div class="bg-light pt-3 rounded">
                        <div class="flex px-10">
                            @if ($comics->isNotEmpty())
                            {{$comics->links()}}
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function deleteComic(id){
        if(confirm('Are you sure you want to delete this comic?')){
            $.ajax({
                url: '{{route('comic.delete')}}',
                type: 'DELETE',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response){
                    if(response.status){
                        window.location.href = '{{route('comic.index')}}';
                    }else{
                        alert(response.message);
                    }
                },
                error: function(){
                    alert('An error occurred. Please try again.');
                }
            });
        }
    }
</script>
@endsection
