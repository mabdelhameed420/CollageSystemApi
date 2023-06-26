<?php

namespace App\Http\Controllers\Api\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required',
            'rating' => 'required',
        ]);

        $rating = Rating::create($validatedData);
        $rating->save();
        return response()->json([
            'message' => 'Rating created successfully!',
            'data' => $rating
        ], 201);
    }
}
