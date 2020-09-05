<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\Attendance\Workday;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function summary(Request $request)
    {
        $attendances = DB::select("
        select
        attendances.id,
        attendances.date,
       authentication.users.identification,
       authentication.users.first_lastname,
       authentication.users.second_lastname,
       authentication.users.first_name,
       authentication.users.second_name,
        min(case when catalogues.code = 'work' then workdays.start_time end) as start_time,
        max(case when catalogues.code = 'work' then workdays.end_time end) as end_time,
       sum(case when catalogues.code = 'work' then workdays.duration end) - sum(case when catalogues.code = 'lunch' then workdays.duration end) as duration,
       sum(case when catalogues.code = 'lunch' then workdays.duration end) as lunch
        from attendance.attendances
         inner join authentication.users on attendances.attendanceable_id = users.id
         inner join attendance.workdays on attendances.id = workdays.attendance_id
         inner join attendance.catalogues on workdays.type_id = catalogues.id
         inner join ignug.states state_users on users.state_id = state_users.id
                 inner join ignug.states state_workdays on workdays.state_id = state_workdays.id
            where
            state_users.code = '1'
            and state_workdays.code = '1'
            and attendances.date between '" . $request->start_date . "' and '" . $request->end_date . "'" .
            "group by attendances.id, attendances.date,users.identification,users.first_lastname,users.second_lastname,users.first_name,users.second_name
            order by attendances.date, authentication.users.first_lastname, start_time;");

        return response()->json([
            'data' => [
                'attendances' => $attendances
            ]
        ], 200);
    }

    public function detail(Request $request)
    {
        $workdays = Workday::
        with('attendance')
            ->with('type')
            ->get();
        return $workdays;
        $attendances = DB::select("
        select attendances.date,
               authentication.users.identification,
               authentication.users.first_lastname,
               authentication.users.second_lastname,
               authentication.users.first_name,
               authentication.users.second_name,
               workdays.start_time,
               workdays.end_time,
               workdays.duration,
               workdays.observations,
               workdays.id as workday_id,
               type_workdays.name as type_workdays
        from    attendance.attendances
                 inner join attendance.workdays on attendances.id = workdays.attendance_id
                 inner join authentication.users on attendances.attendanceable_id = users.id
                 inner join attendance.catalogues type_workdays on workdays.type_id = type_workdays.id
                 inner join ignug.states state_users on users.state_id = state_users.id
                 inner join ignug.states state_workdays on workdays.state_id = state_workdays.id
        where (type_workdays.code = 'work' or type_workdays.code = 'lunch')
                and state_users.code = '1'
                and state_workdays.code = '1'
                and attendances.date between '" . $request->start_date . "' and '" . $request->end_date . "'" .
            "order by attendances.date, authentication.users.first_lastname, workdays.start_time;");

        return response()->json([
            'data' => [
                'attendances' => $attendances
            ]
        ], 200);
    }

    public function all2()
    {
        $users = User::where('state_id', '<>', 3)->with('attendances')->get();
        return $users;
        $teachers = $users->teacher()->where('state_id', '<>', 3)->get();
        return $teachers;
        $attendances = $teachers->attendances()->where('state_id', '<>', 3)->get();
        return $attendances;
        $attendance = Attendance::with(['workdays' => function ($query) {
            $query->where('state_id', '<>', 3);
        }])
            ->with(['tasks' => function ($query) {
                $query->where('state_id', '<>', 3);
            }])
            ->with(['teacher' => function ($query) {
                $query->where('state_id', '<>', 3);
            }])
            ->where('state_id', '<>', 3)
            ->get();
        return response()->json(
            [
                'data' => [
                    'type' => 'attendances',
                    'attributes' => $attendance
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
