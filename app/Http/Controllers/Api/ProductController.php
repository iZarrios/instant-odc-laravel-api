<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Traits\ApiHelperTrait;

class ProductController extends Controller
{
    use ApiHelperTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return $this->apiResponse(true, 'There Are All Categories!', $products);
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
            'price'         => 'required|numeric',
            'image'         => 'required|file|image|max:5120|mimes:png,jpg,jpeg,svg,gif',
            'category_id'   => 'required|numeric',
            'color_id'      => 'required|numeric',
            'size_id'       => 'required|numeric',
        ]);

        if($validator->fails()) {
            return $this->apiResponse(false, 'There are validation errors', $validator->errors());
        } else {
            // handle image
            $imgName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/products/'), $imgName);

            $product = Product::create($request->all());
            $product->image = $imgName;
            $product->save();

            return $this->apiResponse(true, 'Product Added Successfully', $product);
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
        $product = Product::where('id', '=', $id)->first();
        if($product) {
            return $this->apiResponse(true, 'Product Fetched Successfully', $product);
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
            'name'          => 'string|min:3',
            'description'   => 'string|min:3',
            'price'         => 'numeric',
            'image'         => 'file|image|max:5120|mimes:png,jpg,jpeg,svg,gif',
            'category_id'   => 'numeric',
            'color_id'      => 'numeric',
            'size_id'       => 'numeric',
        ]);


        if($validator->fails()) {
            return $this->apiResponse(false, 'There are validation errors', $validator->errors());
        } else {

            $product = Product::find($id);

            if($request->file('image') && $product) {
                unlink(public_path('images/products/' . $product->image));
                $imgName = time() . '_' . $request->file('image')->getClientOriginalName();
                $request->file('image')->move(public_path('images/products/'), $imgName);

                $product->update($request->all());
                $product->image = $imgName;
                $product->save();

            } elseif($product) {
                $product->update($request->all());
            }

            return $this->apiResponse(true, 'Product Updated Successfully', $product);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::where('id', '=', $id)->first();
        if($product) {
            $product->delete();
            unlink(public_path('images/products/' . $product->image));
            return $this->apiResponse(true, 'Product Deleted Successfully', null);
        }
        return $this->apiResponse(false, 'This id doesn\'t exist in our fields', null);
    }

    public function getOrders ($id)
    {
        $product = Product::find($id);

        if($product) {
            $data = [
                'product' => $product,
                'orders'  => $product->orders,
            ];
            return $this->apiResponse(false, 'product orders fetched successfully', $data);
        }
        return $this->apiResponse(false, 'This id doesn\'t exist in our fields', null);
    }
}
