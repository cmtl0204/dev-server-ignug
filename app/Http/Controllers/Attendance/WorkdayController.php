<?php

namespace App\Http\Controllers\Attendance;

use App\Models\Ignug\State;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Catalogue;
use App\Models\Ignug\Teacher;
use App\Models\Attendance\Workday;
use App\Models\Attendance\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkdayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        $teacher = Teacher::where('user_id', $request->user_id)->first();
        $attendances = $teacher->attendances()
            ->with(['workdays' => function ($query) {
                $query->where('state_id', '<>', 3);
            }])->where('state_id', '<>', 3)->get();

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
        $currentTime = Carbon::now()->format('H:i:s');

        $data = $request->json()->all();
        $dataAttendance = $data['attendance'];
        $dataWorkday = $data['workday'];
        $teacher = Teacher::where('user_id', $request->user_id)->first();
        $attendance = $teacher->attendances()->where('date', $currentDate)->first();

        if (!$attendance) {
            $attendance = $this->createAttendance($currentDate, $teacher, $dataAttendance);
        }
        $dataWorkday['start_time'] = $currentTime;
        $this->createWorkday($dataWorkday, $attendance);
        return response()->json(['workdays' => $attendance->workdays()->where('state_id', '<>', 3)->orderBy('start_time')->get()]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Teacher $teacher
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrenDate(Request $request)
    {
        $currentDate = Carbon::now()->format('Y/m/d/');
        $teacher = Teacher::where('user_id', $request->user_id)->first();
        $attendance = $teacher->attendances()->where('date', $currentDate)->first();
        if (!$attendance) {
            return response()->json(['data' => null], 200);
        }
        $workdays = $attendance->workdays()->with('type')->where('state_id', '<>', 3)->orderBy('start_time')->get();
        return response()->json([
            'data' => [
                'type' => 'workdays',
                'attributes' => $workdays
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
     * @param \App\Teacher $teacher
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $currentTime = Carbon::now()->format('H:i:s');
        $data = $request->json()->all();
        $dataWorkday = $data['workday'];

        $workday = Workday::findOrFail($dataWorkday['id']);

        if ($workday && !$workday->end_time) {
            $workday->update([
                'end_time' => $currentTime,
                'duration' => $this->calculateDuration($workday->start_time, $currentTime),
                'observations' => $dataWorkday['observations']
            ]);
        }
        $state = State::where('code', '1')->first();
        $workdays = $state->workdays()
            ->where('attendance_id', $workday['attendance_id'])
            ->orderBy('start_time')
            ->get();
        return response()->json([
            'data' => [
                'type' => 'workdays',
                'attributes' => $workdays
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Teacher $teacher
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $workday = Workday::findOrFail($id);
        $state = State::findOrFail(3);
        $workday->state()->associate($state);
        $workday->save();
        $workdays = Workday::where('attendance_id', $workday['attendance_id'])->orderBy('start_time')
            ->where('state_id', '<>', 3)
            ->get();
        return response()->json([
            'data' => [
                'type' => 'workdays',
                'attributes' => $workdays
            ]
        ], 200);
    }

    public function createAttendance($currentDate, $teacher, $attendance)
    {
        $newAttendance = new Attendance([
            'date' => $currentDate,
        ]);

        $state = State::findOrFail(1);
        $type = Catalogue::where('code', $attendance['type'])->first();
        $newAttendance->state()->associate($state);
        $newAttendance->type()->associate($type);
        $newAttendance->attendanceable()->associate($teacher);
        $newAttendance->save();
        return $newAttendance;
    }

    public function createWorkday($data, $attendance)
    {
        $workday = new Workday([
            'start_time' => $data['start_time'],
            'description' => $data['description'],
        ]);
        $type = Catalogue::where('code', $data['type'])->first();
        $state = State::findOrFail(1);
        $workday->attendance()->associate($attendance);
        $workday->type()->associate($type);
        $workday->state()->associate($state);
        $workday->save();
        return $workday;
    }

    public function calculateDuration($startTime, $endTime)
    {
        $startHour = substr($startTime, 0, 2);
        $startMinute = substr($startTime, 3, 2);
        $startSecond = substr($startTime, 6, 2);

        $endHour = substr($endTime, 0, 2);
        $endMinute = substr($endTime, 3, 2);
        $endSecond = substr($endTime, 6, 2);

        $endDate = Carbon::create(1990, 12, 04, $endHour, $endMinute, $endSecond);
        $durationFormat = $startHour . ' hours ' . $startMinute . ' minutes ' . $startSecond . ' seconds';
        return $endDate->sub($durationFormat)->format('H:i:s');
    }
}
