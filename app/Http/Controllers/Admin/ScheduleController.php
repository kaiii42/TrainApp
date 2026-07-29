<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Station;
use App\Models\Train;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()  { return view('admin.schedules.index', ['schedules' => Schedule::with(['train','originStation','destinationStation'])->paginate(20)]); }
    public function create() { return view('admin.schedules.form', ['schedule' => new Schedule, 'trains' => Train::where('is_active',true)->get(), 'stations' => Station::where('is_active',true)->get()]); }
    public function store(Request $request)
    {
        Schedule::create($request->validate(['train_id'=>'required|exists:trains,id','origin_station_id'=>'required|exists:stations,id','destination_station_id'=>'required|exists:stations,id','departure_time'=>'required','arrival_time'=>'required','duration'=>'required','price'=>'required|numeric|min:0','available_days'=>'nullable|array','is_active'=>'boolean']));
        return redirect()->route('admin.schedules.index')->with('success','Jadwal ditambahkan.');
    }
    public function edit(Schedule $schedule) { return view('admin.schedules.form', ['schedule' => $schedule, 'trains' => Train::where('is_active',true)->get(), 'stations' => Station::where('is_active',true)->get()]); }
    public function update(Request $request, Schedule $schedule)
    {
        $schedule->update($request->validate(['train_id'=>'required|exists:trains,id','origin_station_id'=>'required|exists:stations,id','destination_station_id'=>'required|exists:stations,id','departure_time'=>'required','arrival_time'=>'required','duration'=>'required','price'=>'required|numeric|min:0','available_days'=>'nullable|array','is_active'=>'boolean']));
        return redirect()->route('admin.schedules.index')->with('success','Jadwal diperbarui.');
    }
    public function destroy(Schedule $schedule) { $schedule->delete(); return redirect()->route('admin.schedules.index')->with('success','Jadwal dihapus.'); }
}
