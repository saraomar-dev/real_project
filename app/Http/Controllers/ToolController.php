<?php

namespace App\Http\Controllers;
use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index(){
        $tools=Tool::all();
        return view('tools.index',compact('tools'));
        
    }
    public function create(){
        return view('tools.create');
    }
    public function store(Request $request){
        Tool::create(
            [
                'name'=> $request->name,
                'type'=> $request->type,
                'status'=> $request->status,
            ]
        );
        return redirect('/tools');
    }
    public function destroy($id){
        $tool=Tool::findOrFail($id);
        $tool->delete();
        return redirect('/tools');
    }
    public function edit($id){
        $tool=Tool::findOrFail($id);
        return view('tools.edit',compact('tool'));


    }
    public function update(Request $request,$id){
        $tool=Tool::findOrFail($id);
        $tool->update([
            'name'=>$request->name,
            'type'=>$request->type,
            'status'=>$request->status,
        ]);
        return redirect('/tools');
    }
    
}
