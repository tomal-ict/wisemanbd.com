<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CombinedOrder;
use App\Models\Customer;
use App\Models\IncompleteOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class IncompleteOrderController extends Controller
{
    public function index(Request $request)
    {
        $incomplete_orders = IncompleteOrder::whereNull('status')->orderBy('updated_at', 'desc')->paginate(20);
        return view('backend.sales.incomplete_orders.index', compact('incomplete_orders'));
    }

    public function waiting(Request $request)
    {
        $incomplete_orders = IncompleteOrder::where('status', 'waiting')->orderBy('updated_at', 'desc')->paginate(20);
        return view('backend.sales.incomplete_orders.waiting', compact('incomplete_orders'));
    }

    public function detail(Request $request)
    {
        $incomplete_order = IncompleteOrder::find($request->id);
        if ($incomplete_order == null) {
            return '<div class="text-center p-4">' . translate('No data found') . '</div>';
        }
        if (!$incomplete_order->viewed) {
            $incomplete_order->viewed = 1;
            $incomplete_order->save();
        }
        return view('backend.sales.incomplete_orders.detail', compact('incomplete_order'));
    }

    public function confirm($id)
    {
        $incomplete_order = IncompleteOrder::find($id);
        if ($incomplete_order == null) {
            flash(translate('Order not found'))->error();
            return back();
        }

        $products = json_decode($incomplete_order->products, true);
        if (empty($products)) {
            flash(translate('No products found in this order'))->warning();
            return back();
        }

        // Find or auto-create a customer account from the guest phone number.
        // Username = phone number, default password = 12345678 for new accounts.
        $guest_phone = trim($incomplete_order->phone);
        $guest_user = null;
        if ($guest_phone != '') {
            $search_phones = array();
            $digits = preg_replace('/[^0-9]/', '', $guest_phone);
            $search_phones[] = $guest_phone;
            $search_phones[] = '+' . $guest_phone;
            if (strlen($digits) >= 10) {
                $local_digits = $digits;
                if (substr($digits, 0, 1) == '0') {
                    $local_digits = substr($digits, 1);
                }
                $search_phones[] = '+88' . $local_digits;
                $search_phones[] = '+880' . $local_digits;
            }
            $search_phones = array_values(array_unique($search_phones));

            foreach ($search_phones as $sp) {
                $guest_user = User::where('user_type', 'customer')->where('phone', $sp)->first();
                if ($guest_user != null) {
                    break;
                }
            }

            if ($guest_user == null) {
                $phone_to_save = $guest_phone;
                if (strlen($digits) >= 10) {
                    $local_digits = $digits;
                    if (substr($digits, 0, 1) == '0') {
                        $local_digits = substr($digits, 1);
                    }
                    $phone_to_save = '+88' . $local_digits;
                }

                $guest_user = new User;
                $guest_user->name = $incomplete_order->name != null ? $incomplete_order->name : $guest_phone;
                $guest_user->phone = $phone_to_save;
                if ($incomplete_order->email != null && !User::where('email', trim($incomplete_order->email))->exists()) {
                    $guest_user->email = trim($incomplete_order->email);
                } else {
                    $guest_user->email = null;
                }
                $guest_user->password = Hash::make('12345678');
                $guest_user->email_verified_at = date('Y-m-d H:i:s');
                $guest_user->save();

                $customer = new Customer;
                $customer->user_id = $guest_user->id;
                $customer->save();
            }
        }

        $shippingAddress = array();
        $shippingAddress['name']        = $incomplete_order->name;
        $shippingAddress['email']       = $incomplete_order->email;
        $shippingAddress['address']     = $incomplete_order->address;
        $shippingAddress['country']     = 'Bangladesh';
        $shippingAddress['state']       = 'null';
        $shippingAddress['city']        = $incomplete_order->city;
        $shippingAddress['postal_code'] = 'null';
        $shippingAddress['phone']       = $incomplete_order->phone;
        if ($incomplete_order->delivery_area != null) {
            $shippingAddress['delivery_area'] = $incomplete_order->delivery_area;
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = ($guest_user != null) ? $guest_user->id : 0;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();

        $total_products = count($products);
        $shipping = 0;

        $order = new Order;
        $order->combined_order_id = $combined_order->id;
        $order->user_id = ($guest_user != null) ? $guest_user->id : null;
        $order->shipping_address = $combined_order->shipping_address;
        $order->shipping_type = $incomplete_order->city;
        $order->payment_type = 'cash_on_delivery';
        $order->delivery_status = 'confirmed';
        $order->delivery_viewed = '0';
        $order->payment_status_viewed = '0';
        $order->code = date('Ymd-His') . rand(10, 99);
        $order->date = strtotime('now');
        $order->save();

        $subtotal = 0;
        foreach ($products as $p) {
            $product = Product::find($p['product_id']);
            if ($product == null) {
                continue;
            }

            $subtotal += $p['price'] * $p['qty'];

            $product_stock = $product->stocks->where('variant', isset($p['variation']) ? $p['variation'] : '')->first();
            if ($product_stock == null) {
                $product_stock = $product->stocks->where('variant', '')->first();
            }
            if ($product_stock == null) {
                $product_stock = $product->stocks->first();
            }
            if ($product->digital != 1 && $product_stock != null && $p['qty'] > $product_stock->qty) {
                flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
                $order->delete();
                $combined_order->delete();
                return back();
            } elseif ($product->digital != 1 && $product_stock != null) {
                $product_stock->qty -= $p['qty'];
                $product_stock->save();
            }

            $order_detail = new OrderDetail;
            $order_detail->order_id = $order->id;
            $order_detail->seller_id = $product->user_id;
            $order_detail->product_id = $product->id;
            $order_detail->variation = '';
            if (isset($p['variation']) && $p['variation'] != null && $p['variation'] != '') {
                $order_detail->variation = $p['variation'];
            }
            if (isset($p['size']) && $p['size'] != null && $p['size'] != '') {
                $order_detail->variation = $order_detail->variation != '' ? $order_detail->variation . ' - ' . $p['size'] : $p['size'];
            }
            $order_detail->color = (isset($p['variation']) && $p['variation'] != '' ? $p['variation'] : null);
            $order_detail->size  = (isset($p['size']) && $p['size'] != '' ? $p['size'] : null);
            $order_detail->price = $p['price'] * $p['qty'];
            $order_detail->tax = 0;
            $order_detail->shipping_type = $incomplete_order->city;
            $order_detail->shipping_cost = 0;
            $order_city = strtolower($incomplete_order->city);
            if ($order_city == 'in dhaka city') {
                $order_detail->shipping_cost = 60 / $total_products;
                $shipping += 60 / $total_products;
            } elseif ($order_city == 'sub city of dhaka') {
                $order_detail->shipping_cost = 100 / $total_products;
                $shipping += 100 / $total_products;
            } elseif ($order_city == 'out of dhaka city') {
                $order_detail->shipping_cost = 120 / $total_products;
                $shipping += 120 / $total_products;
            }
            $order_detail->quantity = $p['qty'];
            $order_detail->delivery_status = 'confirmed';
            $order_detail->payment_status = 'unpaid';
            $order_detail->save();

            $product->num_of_sale += $p['qty'];
            $product->save();

            $order->seller_id = $product->user_id;
        }

        $order->grand_total = $subtotal + $shipping;
        $order->save();

        $combined_order->grand_total = $order->grand_total;
        $combined_order->save();

        $io_id = $incomplete_order->id;
        $io_phone = trim($incomplete_order->phone);
        $incomplete_order->delete();

        if ($io_phone != '') {
            IncompleteOrder::where('phone', $io_phone)->where('id', '!=', $io_id)->delete();
        }

        flash(translate('Order has been confirmed and moved to All Orders'))->success();
        return back();
    }

    public function wait($id)
    {
        $incomplete_order = IncompleteOrder::find($id);
        if ($incomplete_order != null) {
            $incomplete_order->status = 'waiting';
            $incomplete_order->save();
            flash(translate('Order has been moved to waiting'))->success();
        }
        return back();
    }

    public function backToIncomplete($id)
    {
        $incomplete_order = IncompleteOrder::find($id);
        if ($incomplete_order != null) {
            $incomplete_order->status = null;
            $incomplete_order->save();
            flash(translate('Order has been moved back to incomplete orders'))->success();
        }
        return back();
    }

    public function store(Request $request)
    {
        $temp_user_id = session('temp_user_id');

        if ($temp_user_id == null) {
            return response()->json(['status' => 0]);
        }

        $phone_digits = strlen(preg_replace('/[^0-9]/', '', $request->phone));

        if ($request->phone == null || trim($request->phone) == '' || $phone_digits < 7) {
            IncompleteOrder::where('temp_user_id', $temp_user_id)->delete();
            return response()->json(['status' => 0]);
        }

        $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        if ($carts->isEmpty()) {
            IncompleteOrder::where('temp_user_id', $temp_user_id)->delete();
            return response()->json(['status' => 0]);
        }

        $products = array();
        $grand_total = 0;
        foreach ($carts as $cartItem) {
            if ($cartItem->product == null) {
                continue;
            }
            $products[] = array(
                'product_id' => $cartItem->product_id,
                'name'       => $cartItem->product->getTranslation('name'),
                'qty'        => $cartItem->quantity,
                'price'      => $cartItem->price,
                'total'      => $cartItem->price * $cartItem->quantity,
                'variation'  => $cartItem->variation,
                'size'       => $cartItem->size,
            );
            $grand_total += $cartItem->price * $cartItem->quantity;
        }

        IncompleteOrder::updateOrCreate(
            ['temp_user_id' => $temp_user_id],
            [
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => trim($request->phone),
                'address'     => $request->address,
                'city'        => $request->city,
                'products'    => json_encode($products),
                'grand_total' => $grand_total,
            ]
        );

        return response()->json(['status' => 1]);
    }

    public function destroy($id)
    {
        $incomplete_order = IncompleteOrder::find($id);
        if ($incomplete_order != null) {
            $incomplete_order->delete();
            flash(translate('Incomplete order has been removed'))->success();
        }
        return back();
    }
}