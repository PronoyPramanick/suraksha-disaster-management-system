<?php
namespace App\Http\Controllers;
use App\Models\MissingPersonReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class MissingPersonReportController extends Controller {
 public function index(Request $request){$query=MissingPersonReport::query(); if($request->filled('q')){$term=$request->input('q');$query->where(function($q)use($term){$q->where('name','like','%'.$term.'%')->orWhere('last_known_location','like','%'.$term.'%');});}return view('missing-persons.index',['reports'=>$query->latest()->paginate(12)->withQueryString()]);}
 public function create(){return view('missing-persons.create');}
 public function store(Request $request){$data=$request->validate(['name'=>'required|string|max:255','age'=>'nullable|integer|min:0|max:120','last_known_location'=>'required|string|max:255','last_seen_date'=>'required|date|before_or_equal:today','photo'=>'nullable|image|max:2048','contact_method'=>'required|string|max:255','details'=>'nullable|string|max:3000']);if($request->hasFile('photo'))$data['photo_path']=$request->file('photo')->store('missing-persons','public');unset($data['photo']);$data['user_id']=$request->user()->id;MissingPersonReport::create($data);return redirect()->route('missing-persons.index')->with('status','Missing person report submitted.');}
 public function update(Request $request,MissingPersonReport $missingPerson){abort_unless($request->user()->role==='admin'||$missingPerson->user_id===$request->user()->id,403);$data=$request->validate(['status'=>'required|in:Missing,Located,Closed']);$missingPerson->update($data);return back()->with('status','Case status updated.');}
}
