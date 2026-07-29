<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Train;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    public function index()   { return view('admin.trains.index', ['trains' => Train::paginate(20)]); }
    public function create()  { return view('admin.trains.form', ['train' => new Train]); }
    public function store(Request $request)  { Train::create($request->validate(['name'=>'required','train_number'=>'required|unique:trains','class'=>'required|in:ekonomi,bisnis,eksekutif','capacity'=>'required|integer|min:1','is_active'=>'boolean'])); return redirect()->route('admin.trains.index')->with('success','Kereta ditambahkan.'); }
    public function edit(Train $train)       { return view('admin.trains.form', compact('train')); }
    public function update(Request $request, Train $train) { $train->update($request->validate(['name'=>'required','train_number'=>'required|unique:trains,train_number,'.$train->id,'class'=>'required|in:ekonomi,bisnis,eksekutif','capacity'=>'required|integer|min:1','is_active'=>'boolean'])); return redirect()->route('admin.trains.index')->with('success','Kereta diperbarui.'); }
    public function destroy(Train $train) { $train->delete(); return redirect()->route('admin.trains.index')->with('success','Kereta dihapus.'); }
}
