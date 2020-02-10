<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::check()) {
        $tasks = \Auth::user()->tasks()->orderBy('created_at', 'desc')->paginate(10);
        
        return view('tasks.index', ['tasks' => $tasks]);
        } else {
        return view('welcome');    
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create', ['task' => $task]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['status' => 'required|max:10']);
        
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status
        ]);
        
        return redirect('tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Task::find($id) === null) {
            
            return redirect('/');
            
        } elseif (\Auth::id() === Task::find($id)->user_id) {
        
        $task = Task::find($id);
        
        return view('tasks.show', ['task' => $task]);
        } else {
            return redirect('tasks');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Task::find($id) === null) {
            
        return redirect('/'); 
        
        } elseif (\Auth::id() === Task::find($id)->user_id) {
        
        $task = Task::find($id);
        
        return view('tasks.edit', ['task' => $task]);
        
        } else {
        return redirect('tasks');    
        }
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
        $this->validate($request, ['status' => 'required|max:10']);
        
        $task = Task::find($id);
        
        $task->content = $request->content;
        $task->status = $request->status;
        
        $task->save();
        
        return redirect('tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        
        if (\Auth::id() === Task::find($id)->user_id) {
            $task->delete();
        }
        
        return redirect('tasks');
    }
}
