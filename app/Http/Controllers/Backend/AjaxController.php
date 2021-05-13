<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\VariationOption;
use Auth;
use DB;

class AjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubCategories($id)
    {
        $subcategories = Category::where('parent_id',$id)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return ['status'=>'success','message'=>'success','data'=>$subcategories];
    }

    public function storeSubCategory(Request $request)
    {
        $category = new Category;
        $category->title     = $request->title;
        $category->parent_id = $request->category_id;
        if($category->save())
          return ['status'=>'success','message'=>__('Successully added sub category')];
        else
          return ['status'=>'failed','message'=>__('Failed to add sub category')];
    }

    public function updateSubCategory(Request $request)
    {
        $category = Category::find($request->id);
        $category->title     = $request->title;
        if($category->update())
          return ['status'=>'success','message'=>__('Successully updated sub category')];
        else
          return ['status'=>'failed','message'=>__('Failed to update sub category')];
    }

    public function deleteSubCategory(Request $request){
        $category = Category::find($request->id);
        if($category->delete())
          return ['status'=>'success','message'=>__('Successully deleted sub category')];
        else
          return ['status'=>'failed','message'=>__('Failed to delete sub category')];
    }

    public function storeVariationOption(Request $request){
      $input = $request->all();
      $storeData = [
         'variation_id'     => $input['variation_id'],
         'variation_option' => $input['variation_option']
      ];
      $variationOptionId = VariationOption::insertGetId($storeData);
      if($variationOptionId)
         return ['status'=>'success','message'=>'Successfully added variation option'];
      else
         return ['status'=>'failed','message'=>'Failed to add variation option'];
    }

    public function variationOptions($id){
      $variationOptions = VariationOption::where('variation_id',$id)->whereNull('deleted_at')->orderBy('id','desc')->get();
      return ['status'=>'success','message'=>'success','data'=>$variationOptions];
    }

    public function updateVariationOption(Request $request){
      $variationOption   = VariationOption::find($request->id);
      $variationOption->variation_option = $request->variation_option;
      if($variationOption->update())
         return ['status'=>'success','message'=>'Successfully updated variation option'];
      else
         return ['status'=>'failed','message'=>'Failed to update '];
    }

    public function deleteVariationOption(Request $request){
        $id = $request->id;
        $variationOption = VariationOption::find($id);
        if($variationOption->delete())
           return ['status'=>'success','message'=>'Successfully deleted variation data'];
        else
           return ['status'=>'failed','message'=>'failed to delete variation data'];
    }

}
