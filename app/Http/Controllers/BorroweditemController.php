<?php

namespace App\Http\Controllers;

use App\Borrowed_item;
use App\Temp_item;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorroweditemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;

        // $today = Carbon::now();

        // checks for existing items that exceeds the return date
        // $fined_items = Borrowed_item::where([['user_id', $user_id], ['status', 'borrowed'], ['return_date', '<', $today]])->exists();

        // if ($fined_items) {
            // reading all existing items that exceeds the return date
            // $existing_items = Borrowed_item::where([['user_id', $user_id], ['status', 'borrowed'], ['return_date', '<', $today]])->get();
            // variable for different days
            // $new_payment = 0;
            // foreach ($existing_items as $existing_item) {

                 // $return_date = Carbon::parse($existing_item->return_date);
                 // getting days difference from today to return date
                 // $days_diff = $today->diffInDays($return_date);
                 /*
                    fines = (fines per day) * (the number of days pass or difference)
                    new payment = current fee + fines
                 */
                 // $new_payment = ($existing_item->fine * $days_diff) + $existing_item->fee;

                // UNFIXED BUG LEFT HERE 4/5/2019 23:10
                // BOTH FEE ITEMS WERE UPDATED BASED ON THE LAST NEW PAYMENT VALUE
        //         Borrowed_item::where([['user_id', $user_id], ['status', 'borrowed'], ['return_date', '<', $today]])
        //         ->update([
        //             'fee' => $new_payment
        //         ]);
        //     }
        // }

        return Borrowed_item::join('items', 'items.id', '=', 'borrowed_items.item_id')
                            ->join('users', 'users.id', '=', 'borrowed_items.user_id')
                            ->select('items.serial', 'items.name', 'items.description', 'borrowed_items.created_at', 'borrowed_items.quantity', 'borrowed_items.id', 'borrowed_items.item_id')
                            ->where([
                            ['user_id', '=', $user_id], 
                            ['status', '=', 'borrowed']
                ])->orderBy('created_at', 'desc')->paginate(5);
    }

    public function userpendingitems()
    {
        $user_id = Auth::user()->id;
        return Borrowed_item::join('items', 'items.id', '=', 'borrowed_items.item_id')
                            ->join('users', 'users.id', '=', 'borrowed_items.user_id')
                            ->select('items.serial', 'items.name', 'items.description', 'borrowed_items.created_at', 'borrowed_items.quantity', 'borrowed_items.id', 'borrowed_items.item_id')
                            ->where([
                            ['user_id', '=', $user_id], 
                            ['status', '=', 'pending']
                ])->orderBy('created_at', 'desc')->paginate(5);
    }

    public function userindexdetails()
    {
        $user_id = Auth::user()->id;

        $total_borrowed_items = Borrowed_item::where([['user_id', $user_id],['status', 'borrowed']])->count();
        $total_pending_items = Borrowed_item::where([['user_id', $user_id],['status', 'pending']])->count();
        
        // $total_payment = Borrowed_item::select('')->where([['user_id', $user_id],['status', 'borrowed']])->sum('fee');
        
        // $total_payment = Borrowed_item::where([['user_id', $user_id],['status', 'borrowed']])->sum('fee');

        return ['tb_items' => $total_borrowed_items, 'tp_items' => $total_pending_items];
    }

    public function adminindex()
    {
        return Borrowed_item::join('items', 'items.id', '=', 'borrowed_items.item_id')
                            ->join('users', 'users.id', '=', 'borrowed_items.user_id')
                            ->select('items.serial',
                                     'items.name',
                                     'items.description',
                                     'users.name AS user_name',
                                     'borrowed_items.created_at',
                                     'borrowed_items.quantity',
                                     'borrowed_items.id',
                                     'borrowed_items.item_id')
                            ->where('status', 'borrowed')->orderBy('created_at', 'desc')->paginate(5);
    }

    public function adminindextwo()
    {
        return Borrowed_item::join('items', 'items.id', '=', 'borrowed_items.item_id')
                            ->join('users', 'users.id', '=', 'borrowed_items.user_id')
                            ->select('items.serial',
                                     'items.name',
                                     'items.description',
                                     'users.name AS user_name',
                                     'borrowed_items.created_at',
                                     'borrowed_items.quantity',
                                     'borrowed_items.id',
                                     'borrowed_items.item_id',
                                     'borrowed_items.status'
                                    )
                            ->orderBy('created_at', 'desc')->paginate(10);
    }

    public function adminitemsindex()
    {
        $total_borrowed_items = Borrowed_item::where('status', 'borrowed')->count();
        $total_pending_items = Borrowed_item::where('status', 'pending')->count();

        // $total_payment = Borrowed_item::select('')->where(['status', 'borrowed'])->sum('fee');

        $stock_in = Item::whereRaw('item_quantity <= (max_quantity)/4')->count();
        
        return ['tb_items' => $total_borrowed_items, 'tp_items' => $total_pending_items, 'stock_in' => $stock_in];
    }

    public function pendingitems()
    {
        $pending_items = Borrowed_item::join('items', 'items.id', '=', 'borrowed_items.item_id')
            ->join('users', 'users.id', '=', 'borrowed_items.user_id')
            ->select('borrowed_items.id', 'items.name', 'items.serial', 'items.description', 'borrowed_items.quantity', 'users.name AS user_name', 'borrowed_items.created_at')
            ->where('status', 'pending')->paginate(5);
        return $pending_items;
    }

    public function approveitem($id)
    {
        Borrowed_item::findOrfail($id)->update([
            'status' => 'borrowed'
        ]);
    }

    public function declineitem($id)
    {
        Borrowed_item::findOrfail($id)->update([
            'status' => 'declined'
        ]);
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
        $temp_items = Temp_item::where('user_id', $user_id)->get();
        foreach ($temp_items as $temp_item) {

            // // finds the item by its id
            // $item = Item::findOrfail($temp_item->item_id);
            // // gets its current quantity
            // $item_quantity = $item->item_quantity;
            // // sets the updated quantity by subtracting
            // $updated_quantity = $item_quantity - $temp_item->quantity;

            // Item::findOrfail($temp_item->item_id)
            //     ->update([
            //         'item_quantity' => $updated_quantity
            // ]);

            Borrowed_item::create([
                'user_id' => $user_id,
                'item_id' => $temp_item->item_id,
                'quantity' => $temp_item->quantity,
            ]);
        }

        Temp_item::where('user_id', $user_id)->delete();
    }

    public function update(Request $request)
    {
        $user_id = Auth::user()->id;
        Borrowed_item::where([['user_id', $user_id], ['item_id', $request->item_id], ['id', $request->id]])->update([
            'status' => 'returned'
        ]);
    }


}
