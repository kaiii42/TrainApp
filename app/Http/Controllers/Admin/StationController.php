<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()    { return view('admin.stations.index', ['stations' => Station::paginate(20)]); }
    public function create()   { return view('admin.stations.form', ['station' => new Station]); }
    public function store(Request $request) { Station::create($request->validate(['name'=>'required','code'=>'required|unique:stations','city'=>'required','latitude'=>'nullable|numeric','longitude'=>'nullable|numeric','is_active'=>'boolean'])); return redirect()->route('admin.stations.index')->with('success','Stasiun ditambahkan.'); }
    public function edit(Station $station)  { return view('admin.stations.form', compact('station')); }
    public function update(Request $request, Station $station) { $station->update($request->validate(['name'=>'required','code'=>'required|unique:stations,code,'.$station->id,'city'=>'required','latitude'=>'nullable|numeric','longitude'=>'nullable|numeric','is_active'=>'boolean'])); return redirect()->route('admin.stations.index')->with('success','Stasiun diperbarui.'); }
    public function destroy(Station $station) { $station->delete(); return redirect()->route('admin.stations.index')->with('success','Stasiun dihapus.'); }
}
