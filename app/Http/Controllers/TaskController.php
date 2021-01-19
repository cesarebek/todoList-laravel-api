<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
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
        //Retrieving all user authenticated tasks
        $tasks = Auth::user()->tasks;
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
    public function store(Request $req){ 
        $taskInput = $req->all();
        $taskInput["user_id"] = Auth::user()->id;
        //Storing task
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
        //Searching for specific task
        $task = Task::where("user_id", Auth::user()->id)
            ->find($id);
        
        if(!is_null($task)){
            //Success Response
            return response()->json([
                'data'=>$task
            ],200); 
        }
        //Failure Response
            return response()->json([
                'message'=>'Task not found'
            ],404); 
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $inputs = $req->all();
        $task = Task::where('user_id', Auth::user()->id)->find($id);

        if(!is_null($task)){
            //Check if task is complete
            $task->completed_at = $req['completed'] ? Carbon::now() : null;
            $task->update($inputs);
            
            //Success Response
            return response()->json([
                'message'=>'Task updated successfully!'
            ],202);
        }
        //Failure Response
        return response()->json([
            'message'=>'Task not Found!'
        ],404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Searching and deleting task
        $task = Task::where('user_id', Auth::user()->id)->find($id);

        if(!is_null($task)){
            $task->delete();
            //Response
            return response()->json([
                'message'=>'Task deleted successfully!'
            ],202);
        }
        //Failure Response
        return response()->json([
            'message'=>'Task not Found!'
        ],404);
    }
}
