<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()

    {

        $tasks = Task::all();

        return view('tasks.index', compact('tasks'));

    }

    /*public function store(Request $request)

    {

        Task::create([

            'title' => $request->title,

            'difficulty' => $request->difficulty,

        ]);

        return redirect()->back();

    }*/
       public function store(Request $request)
{
    if(auth()->user()->role !== 'admin'){
        abort(403);
    }

    Task::create([
        'title' => $request->title,
        'difficulty' => $request->difficulty,
    ]);

    return back();
} 

}

