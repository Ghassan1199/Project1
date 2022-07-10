<?php

namespace App\Http\Controllers;

use App\Models\coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\gym;
use App\Models\qualifications;
use App\Models\contract;
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
    public function store(Request $request,coach $coach)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'email' => 'required|unique:users',
            'birthday' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'salary' =>'required'
        ]);

        if ($validator->fails()) {
            $msg = [$validator->errors()->all()];
            return response(['msg' => $msg], 400);
        }
        $coach->name=$request->name;
        $coach->password=$request->password;
        $coach->email=$request->email;
        $coach->birthday=$request->birthday;
        $coach->gym_id=gym::where('admin_id','=',auth('admin-api')->id())->value('admin_id');
        $coach->save();

        $coach->contract()->create([
            'coach_id'=>$coach->id,
            'salary'=>$request->salary,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date
        ]);

        return response([$coach,$coach->contract()->get()]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function show(coach $coach)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function destroy(coach $coach)
    {
        //
    }
}