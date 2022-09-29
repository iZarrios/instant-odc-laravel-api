<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\ApiHelperTrait;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiHelperTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return $this->apiResponse(true, 'There Are All Categories!', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:3',
            'description'   => 'required|string|min:3',
        ]);

        if($validator->fails()) {
            return $this->apiResponse(false, 'There are validation errors', $validator->errors());
        } else {
            $category = Category::create($request->all());
            return $this->apiResponse(true, 'Category Added Successfully', $category);
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
        $category = Category::where('id', '=', $id)->first();
        if($category) {
            return $this->apiResponse(true, 'Category Fetched Successfully', $category);
        }
        return $this->apiResponse(false, 'This id doesn\'t exist in our fields', null);
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
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:3',
            'description'   => 'required|string|min:3',
        ]);
        $category = Category::where('id', '=', $id)->first();

        if($category && !$validator->fails()) {
            $category->update($request->all());
            return $this->apiResponse(true, 'Category Updated Successfully', $category);
        }
        return $this->apiResponse(false, 'There is an error', $validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('id', '=', $id)->first();
        if($category) {
            $category->delete();
            return $this->apiResponse(true, 'Category Deleted Successfully', null);
        }
        return $this->apiResponse(false, 'This id doesn\'t exist in our fields', null);
    }
}
