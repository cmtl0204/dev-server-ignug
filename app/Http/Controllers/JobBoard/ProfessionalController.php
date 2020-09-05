<?php

namespace App\Http\Controllers\JobBoard;

use App\Models\JobBoard\Ability;
use App\Models\JobBoard\AcademicFormation;
use App\Models\JobBoard\Company;
use App\Models\JobBoard\Course;
use App\Models\JobBoard\Language;
use App\Models\JobBoard\Offer;
use App\Models\JobBoard\Professional;
use App\Models\JobBoard\ProfessionalExperience;
use App\Models\JobBoard\ProfessionalReference;
use Exception;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Controllers\Controller;

class ProfessionalController extends Controller
{

    //Método para filtrar postulantes
    function filterPostulants(Request $request)
    {
        $data = $request->json()->all();
        $dataFilter = $data['filters'];
        $postulants = Professional::
        join('academic_formations', 'academic_formations.professional_id', '=', 'professionals.id')
            ->orWhere($dataFilter['conditions'])
            ->where('professionals.state_id', 1)
            ->where('professionals.about_me', '<>', '')
            ->where('academic_formations.state_id', 1)
            ->orderby('professionals.' . $request->field, $request->order)
            ->paginate($request->limit);
        return response()->json([
            'pagination' => [
                'total' => $postulants->total(),
                'current_page' => $postulants->currentPage(),
                'per_page' => $postulants->perPage(),
                'last_page' => $postulants->lastPage(),
                'from' => $postulants->firstItem(),
                'to' => $postulants->lastItem()
            ], 'postulants' => $postulants], 200);

    }

    //Método para filtrar postulantes segun cambios
    function filterPostulantsFields(Request $request)
    {
        /*
        $postulants = Professional::with([])
        join('academic_formations', 'academic_formations.professional_id', '=', 'professionals.id')
            ->where('professionals.about_me', '<>', '')
            ->where('professionals.state_id', 1)
            ->where('academic_formations.state_id', 1)
        */
        $professionals = Professional::with(['academicFormations' => function ($query) use ($request) {
            $query->with(['professionalDegree' => function ($query1) use ($request) {
                $query1->where('name', 'like', strtoupper($request->filter) . '%');
            }])
            ->where('state_id', 1);
            //->where('name', 'like', strtoupper($request->filter) . '%')
            //->where('name', 'like', strtoupper($request->filter) . '%');
        }])
            ->with(['state' => function ($query) {
                $query->where('code', '1');
            }])
            ->where('about_me', '<>', '')
            //->where('career', 'like', strtoupper($request->filter) . '%')
            //->OrWhere('professional_degree', 'like', '%' . strtoupper($request->filter) . '%')
            ->orderby('professionals.' . $request->field, $request->order)
            ->paginate($request->limit);
        return response()->json([
            'pagination' => [
                'total' => $professionals->total(),
                'current_page' => $professionals->currentPage(),
                'per_page' => $professionals->perPage(),
                'last_page' => $professionals->lastPage(),
                'from' => $professionals->firstItem(),
                'to' => $professionals->lastItem()
            ], 'postulants' => $professionals], 200);

    }

    /* Metodo para obtener todas las ofertas a las que aplico el profesional*/

    /* Metodos para gestionar los datos personales*/
    function getProfessionals(Request $request)
    {
        $professionals = Professional::with(['academicFormations' => function ($query) {
                $query->with('professionalDegree')
                ->where('state_id', 1);
            }])
            ->with(['state' => function ($query) {
                $query->where('code', '1');
            }])
            ->where('about_me', '<>', '')
            ->orderby('professionals.' . $request->field, $request->order)
            ->paginate($request->limit);

        return response()->json([
            'pagination' => [
                'total' => $professionals->total(),
                'current_page' => $professionals->currentPage(),
                'per_page' => $professionals->perPage(),
                'last_page' => $professionals->lastPage(),
                'from' => $professionals->firstItem(),
                'to' => $professionals->lastItem()
            ], 'postulants' => $professionals], 200);

    }

    //Método para obtener un profesional segun el id, con la tabla academicFormations

    function show($id)
    {
        try {
            $professional = Professional::where('user_id', $id)->with('academicFormations')->first();
            return response()->json(['professional' => $professional], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException  $e) {
            return response()->json($e, 405);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }

    //Método para validar aplicación de un profesional a una empresa
    function validateAppliedPostulant(Request $request)
    {
        try {
            $company = Company::where('user_id', $request->user_id)->first();
            if ($company) {
                $appliedOffer = DB::table('company_professional')
                    ->where('professional_id', $request->professional_id)
                    ->where('company_id', $company->id)
                    ->where('state', 'ACTIVE')
                    ->first();
                if ($appliedOffer) {
                    return response()->json(true, 200);
                } else {
                    return response()->json(false, 200);
                }
            } else {
                return response()->json(null, 404);
            }

        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 409);
        } catch (\PDOException $e) {
            return response()->json($e, 409);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }

    //Método para separar a un profesional de la empresa
    function detachCompany(Request $request)
    {
        try {
            $data = $request->json()->all();
            $user = $data['user'];
            $company = $data['company'];
            $professional = Professional::where('user_id', $user['id'])->first();
            if ($professional) {
                $response = $professional->companies()->detach($company['company_id']);
                if ($response == 0) {
                    return response()->json($response, 404);
                } else {
                    return response()->json($response, 201);
                }

            } else {
                return response()->json(0, 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 409);
        } catch (\PDOException $e) {
            return response()->json($e, 409);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }

    }

    /*
     * Grupo 3
     */
    // comparar con codido de la tabla estado, no con el id

    /* Metodo para obtener todas las ofertas a las que aplico el profesional*/
    function getAppliedOffers(Request $request)
    {
        try {
            $professional = Professional::with(['offers' => function ($query) {
                $query->with(['state' => function ($queryDos) {
                    $queryDos->where('code', '1');
                }]);
            }])->with(['state' => function ($queryTres) {
                $queryTres->where('code', '1');
            }])->where('user_id', $request->user_id)->get();

            $oportunidades = [];

            foreach ($professional as $profesion) {
                array_push($oportunidades, $profesion->offers);
            }

            return response()->json([
                'data' => [
                    'opportunities' => $oportunidades
                ]
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 400);
        } catch (Exception $e) {
            return response()->json($e, 501);
        } catch (Error $e) {
            return response()->json($e, 502);
        }

    }

    function getInterestedCompanies(Request $request)
    {
        try {
            $companies = Professional::with(['companies' => function ($query) {
                $query->with(['state' => function ($queryDos) {
                    $queryDos->where('code', '1');
                }]);
            }])->with(['state' => function ($queryTres) {
                $queryTres->where('code', '1');
            }])->where('user_id', $request->user_id)->get();

//            $companiesArray = [];
//
//            foreach ($professional as $profesion) {
//                array_push($oportunidades, $profesion->offers);
//            }

            return response()->json([
                'data' => [
                    'companies' => $companies
                ]
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 400);
        } catch (Exception $e) {
            return response()->json($e, 501);
        } catch (Error $e) {
            return response()->json($e, 502);
        }
    }

    public function getAllprofessionalsTesteo()
    {
        return Professional::with('offers')->get();
    }

    function getAllProfessionals()
    {
        $professionals = Professional::join('academic_formations', 'academic_formations.professional_id', 'professionals.id')
            ->with('academicFormations')
            ->where('professionals.about_me', '<>', '')
            ->where('professionals.state', 'ACTIVE')->get();
        return response()->json(['professionals' => $professionals], 200);

    }

    /*
     * FinGrupo3
     */

    /*
     * Grupo4
     */
    /*Actualiza datos del professional*/
    function update(Request $request)
    {
        try {
            $data = $request->json()->all();
            $dataProfessional = $data['professional'];

            $user = User::findOrFail($dataProfessional['user_id']);

            $user->update([
                'identity' => trim($dataProfessional['identity']),
                'first_name' => strtoupper(trim($dataProfessional['first_name'])),
                'last_name' => strtoupper(trim($dataProfessional['last_name'])),
                'email' => strtolower(trim($dataProfessional['email'])),
                'nationality' => strtoupper($dataProfessional['nationality']),
                'civil_state' => strtoupper($dataProfessional['civil_state']),
                'birthdate' => $dataProfessional['birthdate'],
                'gender' => strtoupper($dataProfessional['gender']),
                'phone' => trim($dataProfessional['phone']),
                'address' => strtoupper(trim($dataProfessional['address'])),
                'about_me' => strtoupper(trim($dataProfessional['about_me'])),
            ]);
            $professional->user()->update(['email' => strtolower(trim($dataProfessional['email']))]);
            return response()->json('asd', 201);
        } catch (ModelNotFoundException $e) {
            return response()->json('ModelNotFound', 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json('NotFoundHttp', 405);
        } catch (QueryException $e) {
            return response()->json($e, 500);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }

    /*
     * FinGrupo4
     */
}
