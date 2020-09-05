<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Catalogue;
use App\Models\Ignug\State;
use App\Models\Attendance\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function allCatalogues(Request $request)
    {
        $tasks = Catalogue::with('tasks')->where('type', 'tasks.process')->get();
        return response()->json([
            'data' => [
                'type' => 'attendances',
                'attributes' => $tasks
            ]
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $attendances = $user->attendances()
            ->with(['tasks' => function ($query) {
                $query->where('state_id', '<>', '3');
            }])->where('state_id', '<>', '3')->get();

        return response()->json([
            'data' => [
                'type' => 'attendances',
                'attributes' => $attendances
            ]
        ], 200);
    }

    public function getHistory(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $attendances = $user->attendances()
            ->with(['tasks' => function ($query) {
                $query->with('type')->where('state_id', '<>', 3);
            }])
            ->with('type')
            ->where('state_id', '<>', 3)
            ->whereBetween('date', array($request->start_date, $request->end_date))
            ->get();

        return response()->json([
            'data' => [
                'type' => 'attendances',
                'attributes' => $attendances
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentDate = Carbon::now()->format('Y/m/d/');
        $data = $request->json()->all();
        $dataTask = $data['task'];

        $user = User::findOrFail($request->user_id);
        $attendance = $user->attendances()->where('date', $currentDate)->first();
        if ($attendance) {
            $this->createTask($dataTask, $attendance);
        } else {
            return response()->json([
                'errorr' => [
                    'status' => 404,
                    'title' => 'Attendance not found',
                    'detail' => ''
                ]
            ], 404);
        }

        return response()->json([
            'data' => [
                'attributes' => $attendance->tasks()->with('type')->where('state_id', '<>', '3')->get(),
                'type' => 'tasks'
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrenDate(Request $request)
    {
        $currentDate = Carbon::now()->format('Y/m/d/');
        $user = User::findOrFail($request->user_id);
        $attendance = $user->attendances()->where('date', $currentDate)->first();
        if (!$attendance) {
            return response()->json(['data' => null], 200);
        }
        $tasks = $attendance->tasks()->with('type')->where('state_id', '<>', '3')->get();
        return response()->json([
            'data' => [
                'type' => 'tasks',
                'attributes' => $tasks
            ],
            'meta' => [
                'current_day' => $currentDate
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request

     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $data = $request->json()->all();
        $dataTask = $data['task'];

        $task = Task::findOrFail($dataTask['id']);
        $task->update([
            'percentage_advance' => $dataTask['percentage_advance'],
            'observations' => $dataTask['observations']
        ]);
        $tasks = Task::where('attendance_id', $task['attendance_id'])
            ->where('state_id', '<>', '3')
            ->get();
        return response()->json([
            'data' => [
                'type' => 'tasks',
                'attributes' => $tasks
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $state = State::findOrFail(3);
        $task->state()->associate($state);
        $task->save();
        $tasks = Task::where('attendance_id', $task['attendance_id'])
            ->where('state_id', '<>', '3')
            ->get();
        return response()->json([
            'data' => [
                'type' => 'tasks',
                'attributes' => $tasks
            ]
        ], 200);
    }

    public function createTask($data, $attendance)
    {
        $task = $attendance->tasks()->where('type_id', $data['type_id'])->first();
        if (!$task) {
            $task = new Task([
                'percentage_advance' => $data['percentage_advance'],
                'description' => $data['description'],
            ]);
        } else {
            $task->update([
                'percentage_advance' => $data['percentage_advance'],
                'description' => $data['description'],
            ]);
        }

        $type = Catalogue::findOrFail($data['type_id']);
        $state = State::findOrFail(1);
        $task->attendance()->associate($attendance);
        $task->type()->associate($type);
        $task->state()->associate($state);
        $task->save();
        return $task;
    }
}
