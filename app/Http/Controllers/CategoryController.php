<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryStore;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryStore $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStore $request)
    {
        //
       // dd($request->all());
        $path = $request->file('iconImage')->store('photos');
        $category = new Category($request->all());
        $category->iconImage = $path;
        $category->save();
        return response()->json([
            'data'=>$category,
            'status'=>Response::HTTP_CREATED
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
        $category->imageLink = Storage::url($category->iconImage);
        $category->iconImage = null;
        return response()->json($category,Response::HTTP_FOUND);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
        $path = null;
        //dd($request->all());
        if($request->hasFile('iconImage')){
        Storage::delete($category->iconImage);
        $path = $request->file('iconImage')->store('photos');
        $category->update($request->all());
        $category->iconImage($path);
        $category->update();
        }
        else{
            $category->update($request->all());
        }
        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
        Storage::delete($category->iconImage);
        $category->delete();
        return response()->json([
            'data' => 'Data Deleted Successfully',
            'status'=>Response::HTTP_OK
        ]);
    }
}
