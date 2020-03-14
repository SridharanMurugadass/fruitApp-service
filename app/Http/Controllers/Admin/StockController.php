<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use App\MenuStock;
use App\Order;
use App\Restaurants;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Session;

class StockController extends MainAdminController
{
    public function __construct()
    {
        $this->middleware('auth');

        parent::__construct();
    }

    public function menuStocklist($id)
    {

        $stock_items = MenuStock::where("restaurant_id", $id)->orderBy('menu_id')->get();

        if (Auth::User()->usertype != "Admin") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        $restaurant_id = $id;

        return view('admin.pages.stock', compact('stock_items', 'restaurant_id'));
    }

    public function addeditstock($id)
    {

        if (Auth::User()->usertype != "Admin") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        $stock       = MenuStock::where("restaurant_id", $id)->orderBy('menu_id')->get();
        $stock_types = ['Quantity' => 'Quantity', 'Weight' => 'Weight'];

        $restaurant_id = $id;

        return view('admin.pages.addeditstock', compact('stock', 'restaurant_id', 'stock_types'));
    }

    public function addnew(Request $request)
    {

        $data = \Input::except(['_token']);

        $rule = [
            'restaurant_id' => 'required',
            'menu_id'       => 'required',
            'type'          => 'required',
        ];

        if ($request->has('type') && $request->input('type')) {
            $typeField = ('Quantity' == $request->type) ? 'quantity' : 'weight';
            $rule      = array_merge($rule, [$typeField => 'required']);
        }

        $validator = \Validator::make($data, $rule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }
        $inputs = $request->all();

        if (!empty($inputs['id'])) {
            $stock = MenuStock::findOrFail($inputs['id']);
        } else {

            $stock = new MenuStock;
        }

        $stock->restaurant_id = $inputs['restaurant_id'];
        $stock->menu_id       = $inputs['menu_id'];
        $stock->description   = $inputs['description'];
        $stock->type          = $inputs['type'];
        $stock->quantity      = ('Quantity' == $stock->type) ? $inputs['quantity'] : null;
        $stock->weight        = ('Weight' == $stock->type) ? $inputs['weight'] : null;

        $stock->save();

        if (!empty($inputs['id'])) {
            \Session::flash('flash_message', 'Changes Saved');

            return \Redirect::back();
        } else {

            \Session::flash('flash_message', 'Added');

            return \Redirect::back();
        }
    }

    public function editmenu($id, $stock_id)
    {

        if (Auth::User()->usertype != "Admin") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        $stock = MenuStock::findOrFail($stock_id);

        $menu_items  = Menu::where("restaurant_id", $id)->orderBy('menu_id')->get();
        $stock_types = ['Quantity' => 'Quantity', 'Weight' => 'Weight'];

        $restaurant_id = $id;

        return view('admin.pages.addeditstock', compact('stock', 'menu_items', 'restaurant_id', 'stock_types'));
    }

    public function delete($stock_id)
    {

        if (Auth::User()->usertype == "Admin" or Auth::User()->usertype == "Owner") {
            $order_obj = Order::where('item_id', $stock_id)->delete();

            $stock = MenuStock::findOrFail($stock_id);
            $stock->delete();

            \Session::flash('flash_message', 'Deleted');

            return redirect()->back();
        } else {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }
    }

    public function owner_stock()
    {

        $user_id = Auth::User()->id;

        $restaurant = Restaurants::where('user_id', $user_id)->first();

        $restaurant_id = $restaurant['id'];

        $stock_items = MenuStock::where("restaurant_id", $restaurant_id)->orderBy('menu_id')->get();

        if (Auth::User()->usertype != "Owner") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        return view('admin.pages.owner.stock', compact('stock_items', 'restaurant_id'));
    }

    public function owner_addeditstock()
    {

        if (Auth::User()->usertype != "Owner") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        $user_id = Auth::User()->id;

        $restaurant = Restaurants::where('user_id', $user_id)->first();

        $restaurant_id = $restaurant['id'];

        $stock = MenuStock::where("restaurant_id", $restaurant_id)->orderBy('menu_id')->get();

        $menu_items = Menu::where("restaurant_id", $restaurant_id)->get();

        $stock_types = ['Quantity' => 'Quantity', 'Weight' => 'Weight'];

        return view('admin.pages.owner.addeditstock', compact('stock', 'menu_items', 'restaurant_id', 'stock_types'));
    }

    public function owner_editstock($stock_id)
    {

        if (Auth::User()->usertype != "Owner") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        $user_id = Auth::User()->id;

        $restaurant = Restaurants::where('user_id', $user_id)->first();

        $restaurant_id = $restaurant->id;

        $stock = MenuStock::findOrFail($stock_id);

        $menu_items = Menu::where("restaurant_id", $restaurant_id)->get();

        $stock_types = ['Quantity' => 'Quantity', 'Weight' => 'Weight'];

        return view('admin.pages.owner.addeditstock', compact('stock', 'menu_items', 'restaurant_id', 'stock_types'));
    }
}
