<?php

namespace App\Http\Controllers;

use App\Models\coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\gym;
use App\Models\qualifications;
use App\Models\contract;
use App\Models\subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'email' => 'required|unique:users',
            'birthday' => 'required',
        ]);

        if ($validator->fails()) {
            $msg = [$validator->errors()->all()];
            return response()->json(['msg' => $msg], 400);
        }

        $coach=new coach;
        $coach->first_name = $request->first_name;
        $coach->last_name = $request->last_name;
        $coach->password = Hash::make($request->password);
        $coach->email = $request->email;
        $coach->birthday = $request->birthday;
        $coach->gym_id = gym::where('admin_id', '=', auth('admin-api')->id())->value('admin_id');
        $coach->save();

        return response()->json($coach, 200);
    }

    public function create_qual(Request $request)
    {
        $qual = $request;

        $coach = coach::find($request->id);
        $coach->qualifications()->create([
            'title' => $qual->title,
        ]);

        return response()->json($qual, 200);
    }

    public function create_cont(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'salary' => 'required',
            'coach_id' => 'required'
        ]);

        if ($validator->fails()) {
            $msg = [$validator->errors()->all()];
            return response()->json(['msg' => $msg], 400);
        }

        $coach = coach::find($request->coach_id);

        $cont = [
            'coach_id' => $request->coach_id,
            'salary' => $request->salary,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];

        $coach->contract()->create($cont);

        return response()->json($cont, 200);
    }

    //we need the $id for the coach
    public function showAllUsers($id){
        $coach=coach::find($id);
        $users=$coach->Users()->get();
        $res['users']=$users;

        return response()->json($res,200);
    }

    //we need the $id for the coach
    public function showPrivateUsers($id){
        $coach=coach::find($id);
        $users=$coach->subscription()->where('private','=','1')->get();
        $res['users']=$users;

        return response()->json($res,200);
    }

    public function showAvailableCoaches($id){
        $coaches=coach::where('gym_id' ,'=' ,$id)->get();
        $available=[];
        foreach($coaches as $coach){
            if($coach->contract()->value('end_date') > Carbon::now()) {
                $available[]=$coach;
            }
        } 
        $res['Available_coaches']=$available;
        return response()->json($res,200);
    }

    public function showUnAvailableCoaches($id){
        $coaches=coach::where('gym_id' ,'=' ,$id)->get();
        $Unavailable=[];
        foreach($coaches as $coach){
            if($coach->contract()->value('end_date') < Carbon::now()) {
                $Unavailable[]=$coach;
            }
        } 
        $res['UnAvailable_coaches']=$Unavailable;
        return response()->json($res,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $coach = coach::find($id);
        return response()->json($coach,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, coach $coach)
    {
        //
    }

}
