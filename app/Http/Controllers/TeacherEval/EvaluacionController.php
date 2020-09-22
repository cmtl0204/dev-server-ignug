<?php

namespace App\Http\Controllers\Evaluacion;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Catalogue;
use App\Models\Ignug\State;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index()
    {
        return Catalogue::all();
    }

    public function show($id)
    {
        $catalogue =  Catalogue::findOrFail($id);
//        $catalogue =  Catalogue::where('id',$id)->get();
        return response()->json([
            'data' => [
                'catalogue' => $catalogue
            ]]);
    }

    public function store(Request $request)
    {
       $data = $request->json()->all();
       $dataCatalogue = $data['catalogue'];
       $dataState = $data['state'];

       // Catalogue::create($dataCatalogue);
//        return Catalogue::create([
//            'code'=>$dataCatalogue['code'],
//            'name'=>$dataCatalogue['name'],
//            'icon'=>$dataCatalogue['icon'],
//            'type'=>$dataCatalogue['type'],
//        ]);
        $catalogue = new Catalogue();
        $catalogue->code = $dataCatalogue['code'];
        $catalogue->name = $dataCatalogue['name'];
        $catalogue->icon = $dataCatalogue['icon'];
        $catalogue->type = $dataCatalogue['type'];

        $state = State::findOrFail($dataState['id']);

        $catalogue->state()->associate($state);

        $catalogue->save();

        return response()->json([
        'data' => [
            'catalogues' => $catalogue
        ]
    ], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->json()->all();
        $dataCatalogue = $data['catalogue'];
        $dataState = $data['state'];

        $catalogue = Catalogue::findOrFail($id);
        $catalogue->code = $dataCatalogue['code'];
        $catalogue->name = $dataCatalogue['name'];
        $catalogue->icon = $dataCatalogue['icon'];
        $catalogue->type = $dataCatalogue['type'];

        $state = State::findOrFail($dataState['id']);
        $catalogue->state()->associate($state);
        $catalogue->save();
        return response()->json([
            'data' => [
                'evaluaciones' => $catalogue
            ]
        ], 201);
    }

    public function destroy($id)
    {
        $catalogue = Catalogue::findOrFail($id);
        $catalogue->delete();
//        $catalogue->update([
//            'code'=>'0'
//        ]);

        //$catalogue->code = '0';
//        $catalogue->save();

        return response()->json([
            'data' => [
                'evaluaciones' => $catalogue
            ]
        ], 201);
    }
}

