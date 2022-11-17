<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        // Todo return auth user order list
    }

    public function show(Request $request, $id){
        // Todo return auth user specific order
    }

    public function store(Request $request){
        // Todo store order
    }

    public function update(Request $request, $id){
        // Todo update order
    }

    public function destroy(Request $request, $id){
        // Todo delete order
    }

}
