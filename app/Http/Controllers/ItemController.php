<?php

namespace App\Http\Controllers;

use App\Borrowed_item;
use App\Category;
use App\Item;
use App\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Item::join('categories', 'categories.id', '=', 'items.category_id')
                    ->select('categories.name AS category_name', 'items.name', 'items.serial', 'items.description', 'items.max_quantity', 'items.created_at', 'items.category_id AS category', 'items.id', 'items.availability')
                    ->latest()
                    ->paginate(5);
    }

    public function toBuy()
    {
        $categories = Category::all();
        $items = array();
        foreach ($categories as $category) {
            $items2 = Item::join('categories', 'categories.id', '=', 'items.category_id')->select('items.id', 'items.name', 'items.category_id', 'categories.name AS category_name', 'items.item_quantity')
            ->where([['items.category_id', $category->id], ['availability', 1]])->get();
            foreach ($items2 as $item2) {
                $items[$category->name] = $items2;
            }
        }

        return ['items' => $items, 'categories' => $categories];
        // return [
        //     'category 1' => ['Item 1', 'Item 2'],
        //     'category 2' => ['Item 3'],
        //     'category 3' => ['Item 5', 'Item 6', 'Item 7']
        // ];






        // $items = array();
        // foreach ($categories as $category) {
        //     $items2 = Item::where('category_id', $category->id)->get();
        //     foreach ($items2 as $item2) {
        //         array_push($items, array_push($category_arr, $item2->name));
        //     }
        // }

        // return $items;
    }

    public function updateavail(Request $request, $id)
    {
        return $request;
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
            'serial' => 'required|min:6|max:191|unique:items',
            'category' => 'required|numeric',
            'name' => 'required|max:191|unique:items',
            'description' => 'required|string|max:191',
            'max_quantity' => 'required|numeric|min:1'
        ]);

        Item::create([
            'serial' => $request->serial,
            'category_id' => $request->category,
            'name' => $request->name,
            'description' => $request->description,
            'max_quantity' => $request->max_quantity
        ]);

        Log::create([
            'activity' => 'added new item: ' . $request->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'serial' => 'required|max:191|unique:items,serial,'.$request->id,
            'category' => 'required|numeric',
            'name' => 'required|max:191|unique:items,name,'.$request->id,
            'description' => 'required|max:191',
            'max_quantity' => 'required|numeric|min:1'
        ]);

        $item->update([
            'serial' => $request->serial,
            'category_id' => $request->category,
            'name' => $request->name,
            'description' => $request->description,
            'max_quantity' => $request->max_quantity,
            'availability' => $request->availability
        ]);

        Log::create([
            'activity' => 'updated item id: ' . $item->id,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $exists = Borrowed_item::where('item_id', $item->id)->exists();
        if ($exists) {
            return ['response' => 1];
        } else {
            // $item->delete();
            return ['response' => 0];
        }
    }
}
