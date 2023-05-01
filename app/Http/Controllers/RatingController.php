<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Rating;
use App\Http\Resources\RatingResource;

class RatingController extends Controller
{
    //
    protected $model;

    public function __construct(Rating $rating)
    {
        $this->model = $rating;
    }
    public function index()
    {
        //
        $items = $this->model->with('books')->get();
        return response(['data' => $items, 'status' => 200]);
    }
    public function store(Request $request)
    {
    

      $request->validate([
        'rating' => 'required',
        // 'books' => 'array',
        'books.*' => 'exists:books,id'
      ]);
      $item = $this->model->create($request->all());

      $books = $request->get('books');
      $item->books()->associate($books);

      return $this->index();
            
        
       
       
    }
}
