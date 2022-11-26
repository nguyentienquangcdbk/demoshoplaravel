<?php

namespace App\Http\Controllers;

use App\Models\cateogry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    //

    public function index()
    {
        return cateogry::all();
    }

    public function getId($id)
    {

        return cateogry::find($id);
    }
    public function add(Request $request)
    {
        $category = new cateogry();

        $category->name = $request->name;
        $category->slug = Str::slug($request->name, '-');

        $category->save();

        return $category;
    }

    public function edit(Request $request, $id)
    {
        $category = cateogry::find($id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-')
        ]);

        return $category;
    }
    public function delete($id)
    {

        return cateogry::destroy($id);
    }
}
