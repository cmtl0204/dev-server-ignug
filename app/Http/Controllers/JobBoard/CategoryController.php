<?php

namespace App\Http\Controllers\JobBoard;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\JobBoard\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //Método para obtener las categorias
    function index(Request $request)
    {
        $categories = Category:: with('children')->where('type', 'father')->get();

        return response()->json([
            'data' => [
                'categories' => $categories
            ]
        ], 200);
    }

}
