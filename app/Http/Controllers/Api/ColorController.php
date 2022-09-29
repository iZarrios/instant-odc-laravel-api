<?php

namespace App\Http\Controllers\Api;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Traits\ApiHelperTrait;

class ColorController extends Controller
{
    use ApiHelperTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Color::all();
        return $this->apiResponse(true, 'There Are All Colors!', $colors);
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
            'color_value'   => 'required|string',
        ]);

        if($validator->fails()) {
            return $this->apiResponse(false, 'There are validation errors', $validator->errors());
        } else {
            $color = Color::create($request->all());
            return $this->apiResponse(true, 'Color Added Successfully', $color);
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
        $color = Color::where('id', '=', $id)->first();
        if($color) {
            return $this->apiResponse(true, 'Color Fetched Successfully', $color);
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
            'color_value'   => 'required|string',
        ]);
        $color = Color::where('id', '=', $id)->first();

        if($color && !$validator->fails()) {
            $color->update($request->all());
            return $this->apiResponse(true, 'Color Updated Successfully', $color);
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
        $color = Color::where('id', '=', $id)->first();
        if($color) {
            $color->delete();
            return $this->apiResponse(true, 'Color Deleted Successfully', null);
        }
        return $this->apiResponse(false, 'This id doesn\'t exist in our fields', null);
    }
}
