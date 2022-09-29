<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Traits\ApiHelperTrait;

class OrderController extends Controller
{
    use ApiHelperTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return $this->apiResponse(true, 'There Are All Orders!', $orders);
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
            'customer_message'          => 'string',
            'delivery_due_date'         => 'string',
            'invoice_creation_date'     => 'string',
            'total_price'               => 'required|numeric',
            'products'                  => 'array',
            'customer_id'               => 'required|numeric'
        ]);

        if($validator->fails()) {
            return $this->apiResponse(false, 'There are validation errors', $validator->errors());
        } else {
            $order = Order::create($request->all());
            $order->products()->attach($request->products);

            $data = [
                'order'     => $order,
                'products'  => $order->products,
            ];
            
            return $this->apiResponse(true, 'Order Added Successfully', $data);
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
        $order = Order::where('id', '=', $id)->first();

        if($order) {
            $data = [
                'order'     => $order,
                'products'  => $order->products,
            ];
            return $this->apiResponse(true, 'Order Fetched Successfully', $data);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::where('id', '=', $id)->first();
        
        if($order) {
            $order->delete();
            return $this->apiResponse(true, 'Order Deleted Successfully', null);
        }
        return $this->apiResponse(false, 'This id doesn\'t exist in our fields', null);
    }
}
