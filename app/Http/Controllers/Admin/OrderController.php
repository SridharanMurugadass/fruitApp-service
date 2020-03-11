<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Restaurants;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;

class OrderController extends MainAdminController
{
    public function __construct()
    {
        $this->middleware('auth');

        parent::__construct();
    }

    public function orderlist($id)
    {

        $order_list = Order::where("restaurant_id", $id)->orderBy('id', 'desc')->orderBy('created_date', 'desc')->get();

        $drivers = User::select(DB::raw('users.*'))->join('driver_restaurant', 'driver_restaurant.user_id', 'users.id')
            ->where("driver_restaurant.restaurant_id", $id)->orderBy('users.first_name', 'ASC')->get();

        if (Auth::User()->usertype != "Admin") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        $restaurant_id = $id;

        return view('admin.pages.order_list', compact('order_list', 'restaurant_id', 'drivers'));
    }

    public function alluser_order()
    {

        $order_list = Order::orderBy('id', 'desc')->orderBy('created_date', 'desc')->get();

        if (Auth::User()->usertype != "Admin") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        return view('admin.pages.order_list_for_all', compact('order_list'));
    }

    public function order_status($id, $order_id, $status)
    {

        $order = Order::findOrFail($order_id);

        $order->status = $status;

        $order->save();

        \Session::flash('flash_message', 'Status change');

        return \Redirect::back();
    }

    public function delete($id, $order_id)
    {
        if (Auth::User()->usertype != "Admin") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        $order = Order::findOrFail($order_id);
        $order->delete();

        \Session::flash('flash_message', 'Deleted');

        return redirect()->back();
    }

    public function owner_orderlist()
    {

        $user_id = Auth::User()->id;

        $restaurant = Restaurants::where('user_id', $user_id)->first();

        $restaurant_id = $restaurant['id'];

        $order_list = Order::where("restaurant_id", $restaurant_id)->orderBy('created_date')->get();

        $drivers = User::select(DB::raw('users.*'))->join('driver_restaurant', 'driver_restaurant.user_id', 'users.id')
            ->where("driver_restaurant.restaurant_id", $restaurant_id)->orderBy('users.first_name', 'ASC')->get();

        if (Auth::User()->usertype != "Owner") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        return view('admin.pages.owner.order_list', compact('order_list', 'restaurant_id', 'drivers'));
    }

    public function owner_order_status($order_id, $status)
    {

        $order = Order::findOrFail($order_id);

        $order->status = $status;

        $order->save();

        \Session::flash('flash_message', 'Status change');

        return \Redirect::back();
    }

    public function owner_delete($order_id)
    {
        if (Auth::User()->usertype != "Owner") {
            \Session::flash('flash_message', 'Access denied!');

            return redirect('admin/dashboard');
        }

        $order = Order::findOrFail($order_id);
        $order->delete();

        \Session::flash('flash_message', 'Deleted');

        return redirect()->back();
    }

    public function assignDriver($order_id, $assignee)
    {

        $order = Order::findOrFail($order_id);

        $order->assignee = $assignee;

        $order->save();

        \Session::flash('flash_message', 'Driver Assigned');

        return \Redirect::back();
    }

    public function update(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $order->delivery_time = $request->delivery_time;

        $order->save();

        \Session::flash('flash_message', 'Delivery Time updated');

        return \Redirect::back();
    }
}
