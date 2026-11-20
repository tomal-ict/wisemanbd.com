<?php

namespace App\Http\Controllers;

use App\Utility\PayfastUtility;
use Illuminate\Http\Request;
use Auth;
use App\Models\Category;
use App\Models\Cart;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaytmController;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Address;
use App\Models\CombinedOrder;
use Session;
use App\Utility\PayhereUtility;
use App\Utility\NotificationUtility;

class CheckoutController extends Controller
{

    public function __construct()
    {
        //
    }

    public function guestCheckout(Request $request)
    {
        $gatewaySettings = [
            'bkash' => 'bkash',
            'nagad' => 'nagad',
            'sslcommerz' => 'sslcommerz_payment',
            'aamarpay' => 'aamarpay',
            'paypal' => 'paypal_payment',
            'stripe' => 'stripe_payment',
            'mercadopago' => 'mercadopago_payment',
            'instamojo' => 'instamojo_payment',
            'razorpay' => 'razorpay',
            'voguepay' => 'voguepay',
            'payhere' => 'payhere',
            'ngenius' => 'ngenius',
            'iyzico' => 'iyzico',
            'authorizenet' => 'authorizenet',
            'payku' => 'payku',
            'cash_on_delivery' => 'cash_payment',
        ];

        $paymentOption = $request->payment_option;
        if (!isset($gatewaySettings[$paymentOption]) || get_setting($gatewaySettings[$paymentOption]) != 1) {
            flash(translate('Please select an active payment method.'))->warning();
            return back();
        }

        if ($paymentOption == 'cash_on_delivery') {
            $carts = Cart::where('temp_user_id', $request->session()->get('temp_user_id'))->get();
            foreach ($carts as $cart) {
                if ($cart->product == null || $cart->product->digital == 1 || $cart->product->cash_on_delivery == 0) {
                    flash(translate('Cash on Delivery is not available for one or more products in your cart.'))->warning();
                    return back();
                }
            }
        }

        return $this->checkout($request);
    }

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {
        if ($request->payment_option != null) {
            (new OrderController)->store($request);

            $request->session()->put('payment_type', 'cart_payment');
            
            $data['combined_order_id'] = $request->session()->get('combined_order_id');
            $request->session()->put('payment_data', $data);

            if ($request->session()->get('combined_order_id') != null) {
                if ($request->payment_option == 'paypal') {
                    $paypal = new PaypalController;
                    return $paypal->getCheckout();
                } elseif ($request->payment_option == 'stripe') {
                    $stripe = new StripePaymentController;
                    return $stripe->stripe();
                } elseif ($request->payment_option == 'mercadopago') {
                    $mercadopago = new MercadopagoController;
                    return $mercadopago->paybill();
                } elseif ($request->payment_option == 'sslcommerz') {
                    $sslcommerz = new PublicSslCommerzPaymentController;
                    return $sslcommerz->index($request);
                } elseif ($request->payment_option == 'instamojo') {
                    $instamojo = new InstamojoController;
                    return $instamojo->pay($request);
                } elseif ($request->payment_option == 'razorpay') {
                    $razorpay = new RazorpayController;
                    return $razorpay->payWithRazorpay($request);
                } elseif ($request->payment_option == 'payku') {
                    return (new PaykuController)->create($request);
                } elseif ($request->payment_option == 'voguepay') {
                    $voguePay = new VoguePayController;
                    return $voguePay->customer_showForm();
                } elseif ($request->payment_option == 'ngenius') {
                    $ngenius = new NgeniusController();
                    return $ngenius->pay();
                } elseif ($request->payment_option == 'iyzico') {
                    $iyzico = new IyzicoController();
                    return $iyzico->pay();
                } elseif ($request->payment_option == 'nagad') {
                    $nagad = new NagadController;
                    return $nagad->getSession();
                } elseif ($request->payment_option == 'bkash') {
                    $bkash = new BkashController;
                    return $bkash->pay();
                } elseif ($request->payment_option == 'aamarpay') {
                    $aamarpay = new AamarpayController;
                    return $aamarpay->index();
                } elseif ($request->payment_option == 'flutterwave') {
                    $flutterwave = new FlutterwaveController();
                    return $flutterwave->pay();
                } elseif ($request->payment_option == 'mpesa') {
                    $mpesa = new MpesaController();
                    return $mpesa->pay();
                } elseif ($request->payment_option == 'paystack') {
                    if (addon_is_activated('otp_system') && !Auth::user()->email) {
                        flash(translate('Your email should be verified before order'))->warning();
                        return redirect()->route('cart')->send();
                    }
                    $paystack = new PaystackController;
                    return $paystack->redirectToGateway($request);
                } elseif ($request->payment_option == 'payhere') {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));

                    $combined_order_id = $combined_order->id;
                    $amount = $combined_order->grand_total;
                    $first_name = json_decode($combined_order->shipping_address)->name;
                    $last_name = 'X';
                    $phone = json_decode($combined_order->shipping_address)->phone;
                    $email = json_decode($combined_order->shipping_address)->email;
                    $address = json_decode($combined_order->shipping_address)->address;
                    $city = json_decode($combined_order->shipping_address)->city;

                    return PayhereUtility::create_checkout_form($combined_order_id, $amount, $first_name, $last_name, $phone, $email, $address, $city);
                } elseif ($request->payment_option == 'payfast') {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));

                    $combined_order_id = $combined_order->id;
                    $amount = $combined_order->grand_total;

                    return PayfastUtility::create_checkout_form($combined_order_id, $amount);
                } elseif ($request->payment_option == 'paytm') {
                    if (Auth::user()->phone == null) {
                        flash('Please add phone number to your profile')->warning();
                        return redirect()->route('profile');
                    }

                    $paytm = new PaytmController;
                    return $paytm->index();
                } elseif ($request->payment_option == 'toyyibpay') {
                    return (new ToyyibpayController)->createbill();
                } else if ($request->payment_option == 'authorizenet') {
                    $authorize_net = new AuthorizeNetController();
                    return $authorize_net->pay();
                } elseif ($request->payment_option == 'cash_on_delivery') {
                    flash(translate("Your order has been placed successfully"))->success();
                    return redirect()->route('order_confirmed');
                } elseif ($request->payment_option == 'wallet') {
                    $user = Auth::user();
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    if ($user->balance >= $combined_order->grand_total) {
                        $user->balance -= $combined_order->grand_total;
                        $user->save();
                        return $this->checkout_done($request->session()->get('combined_order_id'), null);
                    }
                } else {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    foreach ($combined_order->orders as $order) {
                        $order->manual_payment = 1;
                        $order->save();
                    }
                    flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();
                    return redirect()->route('order_confirmed');
                }
            }
        } else {
            flash(translate('Select Payment Option.'))->warning();
            return back();
        }
    }

    //redirects to this method after a successfull checkout
    public function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);

        foreach ($combined_order->orders as $key => $order) {
            $order = Order::findOrFail($order->id);
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            calculateCommissionAffilationClubPoint($order);
        }

        Session::put('combined_order_id', $combined_order_id);
        return redirect()->route('order_confirmed');
    }

    public function get_shipping_info(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
//        if (Session::has('cart') && count(Session::get('cart')) > 0) {
        if ($carts && count($carts) > 0) {
            $categories = Category::all();
            return view('frontend.shipping_info', compact('categories', 'carts'));
        }
        flash(translate('Your cart is empty'))->success();
        return back();
    }

    public function store_shipping_info(Request $request)
    {
        if ($request->address_id == null) {
            flash(translate("Please add shipping address"))->warning();
            return back();
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($carts as $key => $cartItem) {
            $cartItem->address_id = $request->address_id;
            $cartItem->save();
        }

        return view('frontend.delivery_info', compact('carts'));
        // return view('frontend.payment_select', compact('total'));
    }

    public function store_delivery_info(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();

        if($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;

        if ($carts && count($carts) > 0) {
            foreach ($carts as $key => $cartItem) {
                $product = \App\Models\Product::find($cartItem['product_id']);
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $subtotal += $cartItem['price'] * $cartItem['quantity'];

                if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    $cartItem['shipping_type'] = 'pickup_point';
                    $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                } else {
                    $cartItem['shipping_type'] = 'home_delivery';
                }
                $cartItem['shipping_cost'] = 0;
                if ($cartItem['shipping_type'] == 'home_delivery') {
                    $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                }

                if(isset($cartItem['shipping_cost']) && is_array(json_decode($cartItem['shipping_cost'], true))) {

                    foreach(json_decode($cartItem['shipping_cost'], true) as $shipping_region => $val) {
                        if($shipping_info['city'] == $shipping_region) {
                            $cartItem['shipping_cost'] = (double)($val);
                            break;
                        } else {
                            $cartItem['shipping_cost'] = 0;
                        }
                    }
                } else {
                    if (!$cartItem['shipping_cost'] ||
                            $cartItem['shipping_cost'] == null ||
                            $cartItem['shipping_cost'] == 'null') {

                        $cartItem['shipping_cost'] = 0;
                    }
                }

                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();

            }
            $total = $subtotal + $tax + $shipping;
            return view('frontend.payment_select', compact('carts', 'shipping_info', 'total'));

        } else {
            flash(translate('Your Cart was empty'))->warning();
            return redirect()->route('home');
        }
    }

    private function couponScopeQuery()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::user()->id);
        }
        return Cart::where('temp_user_id', Session::get('temp_user_id'));
    }

    public function apply_coupon_code(Request $request)
    {
        $carts = $this->couponScopeQuery()->get();
        $response_message = array();

        if ($carts->count() > 0) {
            $coupon = Coupon::where('code', $request->code)->first();

            if ($coupon != null) {
                if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                    $coupon_usage = null;
                    if (Auth::check()) {
                        $coupon_usage = CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first();
                    }
                    if ($coupon_usage == null) {
                        $coupon_details = json_decode($coupon->details);

                        $coupon_carts = $this->couponScopeQuery()
                                        ->where('owner_id', $coupon->user_id)
                                        ->get();

                        $coupon_discount = 0;

                        if ($coupon->type == 'cart_base') {
                            $subtotal = 0;
                            $tax = 0;
                            $shipping = 0;
                            foreach ($coupon_carts as $key => $cartItem) {
                                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                                $tax += $cartItem['tax'] * $cartItem['quantity'];
                                $shipping += $cartItem['shipping_cost'];
                            }
                            $sum = $subtotal + $tax + $shipping;

                            if ($sum >= $coupon_details->min_buy) {
                                if ($coupon->discount_type == 'percent') {
                                    $coupon_discount = ($sum * $coupon->discount) / 100;
                                    if ($coupon_discount > $coupon_details->max_discount) {
                                        $coupon_discount = $coupon_details->max_discount;
                                    }
                                } elseif ($coupon->discount_type == 'amount') {
                                    $coupon_discount = $coupon->discount;
                                }
                            } else {
                                $response_message['response'] = 'warning';
                                $response_message['message'] = translate('Minimum order amount is').' '.single_price($coupon_details->min_buy);
                            }

                        } elseif ($coupon->type == 'product_base') {
                            foreach ($coupon_carts as $key => $cartItem) {
                                foreach ($coupon_details as $key => $coupon_detail) {
                                    if ($coupon_detail->product_id == $cartItem['product_id']) {
                                        if ($coupon->discount_type == 'percent') {
                                            $coupon_discount += ($cartItem['price'] * $coupon->discount / 100) * $cartItem['quantity'];
                                        } elseif ($coupon->discount_type == 'amount') {
                                            $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                        }
                                    }
                                }
                            }
                        }

                        if ($coupon_discount > 0) {
                            $this->couponScopeQuery()
                                    ->where('owner_id', $coupon->user_id)
                                    ->update(
                                            [
                                                'discount' => $coupon_carts->count() > 0 ? $coupon_discount / $coupon_carts->count() : 0,
                                                'coupon_code' => $request->code,
                                                'coupon_applied' => 1
                                            ]
                            );

                            if ($coupon->discount_type == 'percent') {
                                $response_message['message'] = translate('Coupon has been applied').' - '.translate('You saved').' '.single_price($coupon_discount).' ('.$coupon->discount.'% '.translate('off').')';
                            } else {
                                $response_message['message'] = translate('Coupon has been applied').' - '.translate('You saved').' '.single_price($coupon_discount);
                            }
                            $response_message['response'] = 'success';
                        } elseif (empty($response_message['response'])) {
                            $response_message['response'] = 'warning';
                            $response_message['message'] = translate('Coupon is not valid for this cart');
                        }
                    } else {
                        $response_message['response'] = 'warning';
                        $response_message['message'] = translate('You already used this coupon!');
                    }
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('Coupon expired!');
                }
            } else {
                $response_message['response'] = 'danger';
                $response_message['message'] = translate('Invalid coupon!');
            }
        } else {
            $response_message['response'] = 'warning';
            $response_message['message'] = translate('Your Cart was empty');
        }

        $carts = $this->couponScopeQuery()->get();
        $returnHTML = '';

        if (Auth::check() && $carts->count() > 0 && $carts[0]['address_id'] != null) {
            $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
            $returnHTML = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        }

        $boxHTML = view('frontend.partials.cart_coupon_box', ['carts' => $carts, 'prefix' => 'cart'])->render();
        $guestBoxHTML = view('frontend.partials.cart_coupon_box', ['carts' => $carts, 'prefix' => 'guest'])->render();

        return response()->json(array('response_message' => $response_message, 'html' => $returnHTML, 'box_html' => $boxHTML, 'guest_box_html' => $guestBoxHTML));
    }

    public function remove_coupon_code(Request $request)
    {
        $this->couponScopeQuery()
                ->update(
                        [
                            'discount' => 0.00,
                            'coupon_code' => '',
                            'coupon_applied' => 0
                        ]
                );

        $coupon = Coupon::where('code', $request->code)->first();
        $carts = $this->couponScopeQuery()->get();
        $returnHTML = '';

        if (Auth::check() && $carts->count() > 0 && $carts[0]['address_id'] != null) {
            $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
            $returnHTML = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        }

        $boxHTML = view('frontend.partials.cart_coupon_box', ['carts' => $carts, 'prefix' => 'cart'])->render();
        $guestBoxHTML = view('frontend.partials.cart_coupon_box', ['carts' => $carts, 'prefix' => 'guest'])->render();

        return response()->json(array(
            'response_message' => array('response' => 'success', 'message' => translate('Coupon has been removed')),
            'html' => $returnHTML,
            'box_html' => $boxHTML,
            'guest_box_html' => $guestBoxHTML
        ));
    }

    public function apply_club_point(Request $request) {
        if (addon_is_activated('club_point')){

            $point = $request->point;

            if(Auth::user()->point_balance >= $point) {
                $request->session()->put('club_point', $point);
                flash(translate('Point has been redeemed'))->success();
            }
            else {
                flash(translate('Invalid point!'))->warning();
            }
        }
        return back();
    }

    public function remove_club_point(Request $request) {
        $request->session()->forget('club_point');
        return back();
    }

    public function order_confirmed(Request $request)
    {
        $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
   if(Auth::user()){
        Cart::where('user_id', $combined_order->user_id)
                ->delete();
}else{
     Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                ->delete();
}
        //Session::forget('club_point');
        //Session::forget('combined_order_id');
        if(Auth::user()){
        
        foreach($combined_order->orders as $order){
            NotificationUtility::sendOrderPlacedNotification($order);
        }
        
        }

        return view('frontend.order_confirmed', compact('combined_order'));
    }
}
