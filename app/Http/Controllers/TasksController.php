<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$data = [];
        $task = [];
        //if (\Auth::id() === $task->user_id) {
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            return view('tasks.index', [
            'tasks' => $tasks,
        ]);
        }
        else {
            return view('welcome');
        }
            // メッセージ一覧を取得
            //$tasks = Task::all();
        
        //return view('welcome', $data);
    //}
        //
        
    }
        // メッセージ一覧ビューでそれを表示
        //return view('tasks.index', [
        //    'tasks' => $tasks,
        //]);
    //}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //if (\Auth::id() === $task->user_id) {
        if (\Auth::check()) {
            $request->validate([
                'content' => 'required',
                'status' => 'required|max:10',
            ]);
        
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
            'user_id' => $request->user_id,
        ]);
        //
        $task = new Task;
        //$task->user_id = $request->user_id;
        //$task->content = $request->content;
        //$task->status = $request->status;
        //$task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    
        }
        
    else {
        return view('welcome');
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
        return view('tasks.show', [
            'task' => $task,
        ]);
        }
        else {
            return redirect ('/');
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
        //
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
        else {
            return redirect ('/');
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
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        //
        $task = Task::findOrFail($id);
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        
        return redirect('/');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy($id)
    {   
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        return redirect('/');
        //
        //$task = Task::findOrFail($id);
        //$task->delete();
        //return redirect('/');
    }
}
