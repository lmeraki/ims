<?php

namespace App\Http\Controllers;

use App\Temp_item;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TempitemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        return Temp_item::join('items', 'items.id', '=', 'temp_items.item_id')
                        ->select('items.name', 'items.description', 'temp_items.quantity', 'temp_items.created_at', 'temp_items.id AS id', 'temp_items.item_id AS item')
                        ->where('user_id', $user_id)
                        ->latest()
                        ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        // $today = date("Y/m/d");
        // $seven_days = date("Y/m/d", strtotime("+7 days"));

        $it_em = Item::findOrfail($request->item);
        $request->validate([
            'item' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:'.$it_em->item_quantity,
            // 'return_date' => 'required|date|after_or_equal:'. $today .'|before_or_equal:'. $seven_days
        ]);

        // $tem_pitems = Temp_item::where([['item_id', $request->item], ['user_id', $user_id]])->get();
        // $total_quantity = 0;
        // foreach ($tem_pitems as $tem_pitem) {
        //     $tem_pitem_current_quantity = $tem_pitem->quantity;
        //     $total_quantity = $tem_pitem_current_quantity + $request->quantity;
        // }

        // if ($total_quantity > $it_em->item_quantity) {
        //     return response()->json([
        //                 'message' => 'Data given is invalid.', 
        //                 'errors' => 
        //                     [
        //                         'quantity' => 'Your allowed quantity must be lesser than or equal to ' .$it_em->item_quantity. '.'
        //                     ]
        //             ], 422);
        // } else {
            
        // }

        $items = Item::findOrfail($request->item);

        $exists = Temp_item::where([['item_id', $request->item], ['user_id', $user_id]])->exists();

        if ($exists) {
            $get_existing = Temp_item::where([['item_id', $request->item], ['user_id', $user_id]])->get();
            $existing_item = 0;
            foreach ($get_existing as $item) {
                $existing_item = $item->quantity;
            }
            $current_quantity = $existing_item;
            $new_quantity = $request->quantity;

            $update_quantity = $current_quantity + $new_quantity;

            Temp_item::where([['item_id', $request->item], ['user_id', $user_id]])
                    ->update([
                        'quantity' => $update_quantity,
            ]);
        } else {
            $total_fee = $items->fee * $request->quantity;
            Temp_item::create([
                    'user_id' => $user_id,
                    'item_id' => $request->item,
                    'quantity' => $request->quantity
                ]);
        }

        $items->update([
            'item_quantity' => $items->item_quantity - $request->quantity
        ]);
    }

        

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Temp_item  $tempitem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Temp_item $tempitem)
    {
        $user_id = Auth::user()->id;
        // $today = date("Y/m/d");
        // $seven_days = date("Y/m/d", strtotime("+7 days"));

        $request->validate([
            'item' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            // 'return_date' => 'required|date|after_or_equal:'. $today .'|before_or_equal:'. $seven_days
        ]);

        $items = Item::findOrfail($request->item);

        // $tem_pitems = Temp_item::where([['item_id', $request->item], ['user_id', $user_id]])->get();
        // $total_quantity = 0;
        // foreach ($tem_pitems as $tem_pitem) {
        //     $tem_pitem_current_quantity = $tem_pitem->quantity;
        //     $total_quantity = $tem_pitem_current_quantity + $request->quantity;
        // }

        $total_quantity = $items->item_quantity + $tempitem->quantity;

        if ($request->quantity > $total_quantity) {
            return response()->json([
                        'message' => 'Data given is invalid.', 
                        'errors' => 
                            [
                                'quantity' => 'Your allowed quantity must be lesser than or equal to ' .$total_quantity. '.'
                            ]
                    ], 422);
        } else {
            $current_quantity = $tempitem->quantity;

            $quantity = 0;

            if ($request->quantity < $current_quantity) {
                $quantity = $current_quantity - $request->quantity;

                $items->update([
                    'item_quantity' => $items->item_quantity + $quantity
                ]);

            } else {
                $quantity = $request->quantity - $current_quantity;

                $items->update([
                    'item_quantity' => $items->item_quantity - $quantity
                ]);
            }

            $tempitem->update([
                'item_id' => $request->item,
                'quantity' => $request->quantity,
            ]);
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Temp_item  $tempitem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Temp_item $tempitem)
    {
        $items = Item::findOrfail($tempitem->item_id);

        $items->update([
            'item_quantity' => $items->item_quantity + $tempitem->quantity
        ]);

        $tempitem->delete();
    }
}
