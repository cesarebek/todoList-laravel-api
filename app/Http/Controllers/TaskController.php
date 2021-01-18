<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //Check logged user
        $user = Auth::user();
        //Conditional task retrieving
        if(is_null($user)){
            return response()->json([
                'message'=>"Please Log-in"
            ]);
        }
        $tasks = Task::where('user_id', $user->id)->get();
        return response()->json([
            'data'=>$tasks
        ],200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //Check logged user
        $user = Auth::user();
        //Task creation
        $taskInput = $request->all();
        $taskInput["user_id"] = $user->id;
        //Storin task
        $task = Task::create($taskInput);
        //Response
        return response()->json([
            'message'=>'Task created successfuly!',
            'data'=>$task
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Check logged user
        $user = Auth::user();
        //Retrieving specific task
        $task = Task::where("user_id", $user->id)->where("id", $id)->first();
        //Response
        return response()->json([
            'data'=>$task
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Check logged user
        $user = Auth::user();
        //Searching for task
        $task = Task::where("user_id", $user->id)->where("id", $id);
        //Update post
        $updatedTask = $task->update($request->all());
        //Response
        return response()->json([
            'message'=>'Task updated successfully!',
            'data'=>$updatedTask
        ],202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Check logged user
        $user = Auth::user();
        //Searching and deleting task
        $task = Task::where('user_id', $user->id)->where("id", $id)->delete();
        //Response
        return response()->json([
            'message'=>'Task deleted successfully!'
        ],202);
    }
}
