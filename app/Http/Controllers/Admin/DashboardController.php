<?php

namespace App\Http\Controllers\Admin;

use App\Categories;
use App\DriverRestaurant;
use App\Menu;
use App\Order;
use App\Restaurants;
use App\Review;
use App\Types;
use App\User;
use Auth;

class DashboardController extends MainAdminController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->usertype == 'Admin') {
            $types             = Types::count();
            $restaurants_count = Restaurants::count();
            $order             = Order::count();
            $users             = User::where('usertype', 'User')->count();

            return view('admin.pages.dashboard', compact('types', 'restaurants_count', 'order', 'users'));
        }

        if (Auth::user()->usertype == 'Owner' || Auth::user()->usertype == 'Driver') {
            $user_id = Auth::User()->id;

            if (Auth::user()->usertype == 'Driver') {
                $restaurant = DriverRestaurant::where('user_id', $user_id)->first();
            } else {
                $restaurant = Restaurants::where('user_id', $user_id)->first();
            }

            $restaurant_id = $restaurant['id'];

            $categories_count = Categories::where(['restaurant_id' => $restaurant_id])->count();

            $menu_count = Menu::where(['restaurant_id' => $restaurant_id])->count();

            $order_count = Order::where(['restaurant_id' => $restaurant_id])->count();

            $review_count = Review::where(['restaurant_id' => $restaurant_id])->count();

            return view('admin.pages.owner_dashboard', compact('categories_count', 'menu_count', 'order_count', 'review_count'));
        }
    }
}
