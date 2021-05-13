<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variation;
use App\Models\VariationOption;

class VariationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variations = Variation::whereNull('deleted_at')->orderBy('id','desc')->paginate('10');
        return view('backend.variation.index',compact('variations'));
    }

    public function option($id)
    {   
        $variation = Variation::where('id',$id)->first();
        $options   = VariationOption::where('variation_id',$id)->whereNull('deleted_at')->orderBy('id','desc')->paginate('10');
        return view('backend.variation.option',compact('variation','options'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.variation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $rules = [
            'variation_name' => 'required',
        ];

        $request->validate($rules);

        $insertData = [
            'variation_name'  => $input['variation_name']
        ];

        $variation  = Variation::insertGetId($insertData);

        if($variation){
            return redirect()->route('backend.index.variation')->with('status',true)->with('message',__('Successfully added variation'));
        }else{
            return redirect()->route('backend.index.variation')->with('status',false)->with('message',__('Failed add variation'));
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
       $variation = Variation::find($id);
       return view('backend.variation.edit',compact('variation'));
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
        $input = $request->all();
        $rules = [
            'variation_name' => 'required'
        ];

        $request->validate($rules);

        $variation  = Variation::find($id);
        $variation->variation_name = $request->variation_name;

        if($variation->update()){
            return redirect()->route('backend.index.variation')->with('status',true)->with('message',__('Successfully updated variation'));
        }else{
            return redirect()->route('backend.index.variation')->with('status',false)->with('message',__('Failed update variation'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $variation = Variation::find($request->id);
        if($variation->delete()){
            return redirect()->route('backend.index.variation')->with('status',true)->with('message',__('Successfully deleted variation'));
        }else{
            return redirect()->route('backend.index.variation')->with('status',false)->with('message',__('Failed delete variation'));
        }
            
    }
}
