<?php

namespace App\Http\Controllers\Attendance;

use App\Models\Ignug\State;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Catalogue;
use App\Models\Ignug\Teacher;
use App\Models\Attendance\Workday;
use App\Models\Attendance\Attendance;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkdayController extends Controller
{
    public function all(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $attendances = $user->attendances()
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

    public function startDay(Request $request)
    {
        $currentDate = Carbon::now()->format('Y/m/d/');
        $currentTime = Carbon::now()->format('H:i:s');

        $data = $request->json()->all();
        $dataAttendance = $data['attendance'];
        $dataWorkday = $data['workday'];
        $user = User::findOrFail($request->user_id);
        $attendance = $user->attendances()->where('date', $currentDate)->first();

        if (!$attendance) {
            $attendance = $this->createAttendance($currentDate, $user, $dataAttendance);
        }
        $dataWorkday['start_time'] = $currentTime;
        $this->createWorkday($dataWorkday, $attendance);
        return response()->json(['workdays' => $attendance->workdays()->where('state_id', '<>', 3)->orderBy('start_time')->get()]);
    }

    public function getCurrenDate(Request $request)
    {
        $currentDate = Carbon::now()->format('Y/m/d/');
        $user = User::findOrFail($request->user_id);
        $attendance = $user->attendances()->where('date', $currentDate)->first();
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

    public function update(Request $request)
    {
        $currentDate = Carbon::now()->format('Y/m/d/ H:i:s');
        $data = $request->json()->all();
        $dataWorkday = $data['workday'];
        $workday = Workday::findOrFail($dataWorkday['id']);
        if (!$workday->observations) {
            $workday->observations = array();
        }
        $user = User::findOrFail($request->user_id);
        array_push($dataWorkday['observations'], 'Motivo: ' . $dataWorkday['observations'][0]);
        array_push($dataWorkday['observations'], 'Hora inicio anterior: ' . $workday->start_time . 'Hora inicio nueva: ' . $dataWorkday['start_time']);
        array_push($dataWorkday['observations'], 'Hora fin anterior: ' . $workday->end_time . 'Hora fin nueva: ' . $dataWorkday['end_time']);
        array_push($dataWorkday['observations'], 'Modificado por: ' . $user->identification
            . ' ' . $user->first_lastname . ' ' . $user->second_lastname
            . ' ' . $user->first_name . ' ' . $user->second_name . ' - '
            . ' ' . $currentDate . ')');
        $observations = array($dataWorkday['observations']);

        if (!Validator::make($dataWorkday, ['start_time' => 'required', 'end_time' => 'required'])->fails()) {
            $workday->update([
                'start_time' => $dataWorkday['start_time'],
                'end_time' => $dataWorkday['end_time'],
                'duration' => $this->calculateDuration($dataWorkday['start_time'], $dataWorkday['end_time']),
                'observations' => array_merge($workday->observations, $observations)
            ]);
        } else if (Validator::make($dataWorkday, ['start_time' => 'required'])->fails()) {
            $workday->update([
                'end_time' => $dataWorkday['end_time'],
                'observations' => array_merge($workday->observations, $observations)
            ]);
            if ($workday->start_time) {
                $workday->update([
                    'duration' => $this->calculateDuration($workday->start_time, $workday->end_time)]);
            }
        } else if (Validator::make($dataWorkday, ['end_time' => 'required'])->fails()) {
            $workday->update([
                'start_time' => $dataWorkday['start_time'],
                'observations' => array_merge($workday->observations, $observations)
            ]);
            if ($workday->end_time) {
                $workday->update(['duration' => $this->calculateDuration($workday->start_time, $workday->end_time)]);
            }
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

    public function endDay(Request $request)
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

    public function destroy($id)
    {
        $workday = Workday::findOrFail($id);
        $state = State::findOrFail($id);
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

    public function createAttendance($currentDate, $user, $attendance)
    {
        $newAttendance = new Attendance([
            'date' => $currentDate,
        ]);

        $state = State::findOrFail(1);
        $type = Catalogue::where('code', $attendance['type'])->first();
        $newAttendance->state()->associate($state);
        $newAttendance->type()->associate($type);
        $newAttendance->attendanceable()->associate($user);
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
