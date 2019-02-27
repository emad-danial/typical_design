<?php
namespace App\Http\Controllers;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AjaxController extends Controller
{
    public function index() {
        $catParentList = DB::table('categories')->select('id','cat_name')->where('parent_category_id','=','0')->get();
        if ($catParentList->count() >0)
        return view('category',compact('catParentList'));
        else
            return view('category');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3']);
        $category = new Category();
        $category->parent_category_id = $request->parentID;
        $category->cat_name = $request->name;
        $category->save();
        return response()->json(['success'=>'Data is successfully added']);
    }
    public function catParentList($id) {
        $catParentList = DB::table('categories')->select('id','cat_name')->where('parent_category_id',$id)->get();
        return response()->json(['success'=>$catParentList]);
    }
}
