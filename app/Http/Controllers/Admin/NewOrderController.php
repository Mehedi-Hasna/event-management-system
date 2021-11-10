<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\assignstackholder;
use App\Models\orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_name = "New Order List";
        $orders = orders::all();

        return view('admin.orders.index', compact('page_name','orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_name = "Order Details";
        $orders    = orders::find($id);
        $invoice_number = $orders->invoice_number;
        
        $package = DB::select(DB::raw("SELECT carts.package_id, carts.invoice_number, packages.id, packages.package_name FROM carts, packages WHERE carts.invoice_number = $invoice_number GROUP By carts.package_id"));
      
        $sub_cat = DB::table('carts')
            ->join('subcategories', 'subcategories.id', '=', 'carts.sub_category_id')
            ->where('carts.invoice_number','=', $invoice_number)
            ->get();

        $main_cat = DB::table('carts')
            ->join('categories', 'categories.id', '=', 'carts.category_id')
            ->where('carts.invoice_number','=', $invoice_number)
            ->groupBy('carts.category_id')
            ->get();

        foreach ($main_cat as $main_price){
            $main_cat_price = $main_price->category_price;
        } 
    
        $total_price = $main_cat_price;
        foreach ($sub_cat as $order){
            $total_price    += $order->price;
        } 

       
       $stake_holder = DB::table('assignstackholders')
                ->join('orders','orders.id', '=', 'assignstackholders.order_id')
                ->join('users','users.id', '=', 'assignstackholders.stackholder_id')
                ->where('assignstackholders.order_id','=', $orders->id)
                ->get();
        return view('admin.orders.invoice', compact('orders','page_name','main_cat','sub_cat', 'package', 'total_price', 'stake_holder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        $this->validate($request,[
            'process' => 'required'
        ]);

        $update_process = orders::find($id);
        $update_process->process = $request->process;

        $update_process->save();
        return back()->with('message','Update Successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
