<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Http\Controllers\API\CarController;

class CartController extends Controller
{
    /**
     * Create JSON response with cart data.
     *
     * @return JSON
     */
    public function formatJSON($cart_item)
    {
        return response()->json([
            'car' => CarController::createJson($cart_item->car),
            'client' => $cart_item->client,
            'seller' => $cart_item->car->seller,
            'added_at' => $cart_item->added_at,
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $cart = Cart::with(['car', 'client'])->where('client_id', $id)->get();
        return response(
            [
                'message' => 'Successfully retrieved cart items',
                'data' => $this->formatJSON($cart)
            ],
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer',
            'seller_id' => 'required|integer',
            'car_id' => 'required|integer'
        ]);

        $newCartItem = new Cart([
            'client_id' => $request->get('client_id'),
            'seller_id' => $request->get('seller_id'),
            'car_id' => $request->get('car_id'),
            'added_at' => now(),
        ]);

        $newCartItem->save();

        $cart_item_data = Cart::with(['car', 'client'])->find($newCartItem->id);

        return response(
            [
                'message' => 'Successfully added cart item',
                'data' => $this->formatJSON($cart_item_data),
                'statusCode' => '200'
            ],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cart = Cart::with(['car', 'client'])->find($id);
        if($cart){
            return response(
                [
                    'message' => 'Successfully retrieved cart item',
                    'data' => $this->formatJSON($cart),
                ],
            );
        }
        return response(
            [
                'message' => 'Cart item not found',
            ], 404
        );
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
        $request->validate([
            'client_id' => 'required|integer',
            'seller_id' => 'required|integer',
            'car_id' => 'required|integer'
        ]);

        $cart = Cart::find($id);
        if($cart){
            $cart->client_id = $request->get('client_id');
            $cart->seller_id = $request->get('seller_id');
            $cart->car_id = $request->get('car_id');
            $cart->added_at = now();
            $cart->save();

            $cart_item_data = Cart::with(['car', 'client'])->find($id);

            return response(
                [
                    'message' => 'Successfully updated cart item',
                    'data' => $this->formatJSON($cart_item_data),
                ],
            );
        }
        return response(
            [
                'message' => 'Cart item not found',
            ], 404
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);
        if($cart){
            $cart->delete();
            return response(
                [
                    'message' => 'Successfully deleted cart item',
                ],
            );
        }
        return response(
            [
                'message' => 'Cart item not found',
            ], 404
        );
    }
}
