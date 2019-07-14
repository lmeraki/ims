<?php

namespace App\Http\Controllers;

use App\Stock_in;
use App\Item;
use Illuminate\Http\Request;

class StockinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Item::paginate(5);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = Item::findOrfail($request->id);
        $current_quantity = $item->item_quantity;
        $new_quantity = $current_quantity + $request->quantity;
        $max_quantity = $item->max_quantity;

        if ($new_quantity > $max_quantity) {
            
            return response()
                ->json(
                    [
                        'message' => 'Data given is invalid.', 
                        'errors' => 
                            [
                                'quantity' => 'Stock in must not be greater than the max quantity.'
                            ]
                    ], 422);

        } else {
            $request->validate([
                'quantity' => [
                    'required',
                    'numeric',
                    'min:1',
                    'max:'. $item->max_quantity
                ],
            ]);

            Stock_in::create([
                'item_id' => $request->id,
                'quantity' => $request->quantity
            ]);

            Item::findOrfail($request->id)->update([
                'item_quantity' => $new_quantity
            ]);
        }
    }


    public function itemquantity($id)
    {
        $item = Item::findOrfail($id);

        $current_quantity = $item->item_quantity;
        $max_quantity = $item->max_quantity;

        $recommended_quantity = $max_quantity - $current_quantity;

        return ['data' => ['id' => $item->id, 'quantity' => $recommended_quantity]];
    }

}
