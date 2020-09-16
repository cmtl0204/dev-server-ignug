<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\Attendance\Workday;
use App\Models\Ignug\Teacher;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function foo\func;
use Illuminate\Database\Eloquent\Relations\Relation;

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
       users.identification,
       users.first_lastname,
       users.second_lastname,
       users.first_name,
       users.second_name,
        min(case when catalogues.code = 'work' then workdays.start_time end) as start_time,
        max(case when catalogues.code = 'work' then workdays.end_time end) as end_time,
       sum(case when catalogues.code = 'work' then workdays.duration end) - sum(case when catalogues.code = 'lunch' then workdays.duration end) as duration,
       sum(case when catalogues.code = 'lunch' then workdays.duration end) as lunch
        from attendance.attendances
         inner join ignug.teachers on attendances.attendanceable_id = teachers.id
         inner join authentication.users on teachers.user_id = users.id
         inner join attendance.workdays on attendances.id = workdays.attendance_id
         inner join attendance.catalogues on workdays.type_id = catalogues.id
         inner join ignug.states state_teachers on teachers.state_id = state_teachers.id
                 inner join ignug.states state_workdays on workdays.state_id = state_workdays.id
            where
            state_teachers.code = '1'
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
        $alias = Teacher::findOrFail(1)->getMorphClass();

        $attendances = Attendance::with('teacher')
            ->with(['workdays' => function ($query) {
                $query->with(['type' => function ($query) {
                    $query->with(['state' => function ($query) {
                        $query->where('code', '1');
                    }]);
                }])
                    ->with(['state' => function ($query) {
                    $query->where('code', '1');
                }]);
            }])
            ->where('attendanceable_type', $alias)
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->with(['state' => function ($query) {
                $query->where('code', '1');
            }])
            ->get();
        return response()->json([
            'data' => [
                'attendances' => $attendances
            ]
        ], 200);
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Attendance $attendance)
    {
        //
    }


    public function update(Request $request, Attendance $attendance)
    {
        //
    }


    public function destroy(Attendance $attendance)
    {
        //
    }
}
