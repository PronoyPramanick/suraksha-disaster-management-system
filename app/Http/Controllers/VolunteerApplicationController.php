<?php
namespace App\Http\Controllers;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class VolunteerApplicationController extends Controller {
 public function index(Request $request){if($request->user()->role==='admin')return view('volunteers.index',['applications'=>VolunteerApplication::with('user')->latest()->paginate(15),'admin'=>true]);return view('volunteers.index',['applications'=>VolunteerApplication::where('user_id',$request->user()->id)->latest()->paginate(15),'admin'=>false]);}
 public function create(Request $request){abort_if($request->user()->role==='admin',403);abort_if(VolunteerApplication::where('user_id',$request->user()->id)->exists(),403,'You have already applied.');return view('volunteers.create');}
 public function store(Request $request){abort_if($request->user()->role==='admin',403);abort_if(VolunteerApplication::where('user_id',$request->user()->id)->exists(),403);$data=$request->validate(['phone'=>'required|string|max:30','skills'=>'required|string|max:2000','availability'=>'required|string|max:255','experience'=>'nullable|string|max:2000']);$data['user_id']=$request->user()->id;VolunteerApplication::create($data);return redirect()->route('volunteers.index')->with('status','Application submitted for administrator review.');}
 public function review(Request $request,VolunteerApplication $application){abort_unless($request->user()->role==='admin',403);$data=$request->validate(['status'=>'required|in:Approved,Rejected']);DB::transaction(function()use($application,$request,$data){$application=VolunteerApplication::whereKey($application->id)->lockForUpdate()->firstOrFail();abort_unless($application->status==='Pending',422,'Application already reviewed.');$application->update(['status'=>$data['status'],'reviewed_by'=>$request->user()->id]);if($data['status']==='Approved')$application->user()->update(['role'=>'volunteer']);});return back()->with('status','Application reviewed.');}
}
