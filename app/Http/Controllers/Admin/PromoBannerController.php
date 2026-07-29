<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use Illuminate\Http\Request;

class PromoBannerController extends Controller
{
    public function index()  { return view('admin.banners.index', ['banners' => PromoBanner::orderBy('order')->paginate(20)]); }
    public function create() { return view('admin.banners.form', ['banner' => new PromoBanner]); }
    public function store(Request $request)
    {
        PromoBanner::create($request->validate(['title'=>'required','description'=>'nullable','image'=>'nullable|url','link'=>'nullable|url','discount_percentage'=>'nullable|integer|min:0|max:100','start_date'=>'nullable|date','end_date'=>'nullable|date|after_or_equal:start_date','is_active'=>'boolean','order'=>'nullable|integer']));
        return redirect()->route('admin.banners.index')->with('success','Banner ditambahkan.');
    }
    public function edit(PromoBanner $banner) { return view('admin.banners.form', compact('banner')); }
    public function update(Request $request, PromoBanner $banner)
    {
        $banner->update($request->validate(['title'=>'required','description'=>'nullable','image'=>'nullable|url','link'=>'nullable|url','discount_percentage'=>'nullable|integer|min:0|max:100','start_date'=>'nullable|date','end_date'=>'nullable|date|after_or_equal:start_date','is_active'=>'boolean','order'=>'nullable|integer']));
        return redirect()->route('admin.banners.index')->with('success','Banner diperbarui.');
    }
    public function destroy(PromoBanner $banner) { $banner->delete(); return redirect()->route('admin.banners.index')->with('success','Banner dihapus.'); }
}
