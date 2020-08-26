<?php

namespace App\Http\Controllers\Ignug;

use App\Http\Controllers\Controller;
use App\Models\Ignug\Catalogue;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $catalogues = Catalogue::where('type', $request->type)->orderBy('name')->get();
        return response()->json([
                'data' => [
                    'catalogues' => $catalogues
                ]]
            , 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Catalogue $catalogue
     * @return \Illuminate\Http\Response
     */
    public function show(Catalogue $catalogue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Catalogue $catalogue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Catalogue $catalogue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Catalogue $catalogue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Catalogue $catalogue)
    {
        //
    }
}
