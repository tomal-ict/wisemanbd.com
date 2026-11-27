<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\ProductStock;
use App\Models\SizeStock;
use Auth;
use Session;
use Cookie;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->forget('buy_now_cart_id');
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            if($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                        ->update(
                                [
                                    'user_id' => $user_id,
                                    'temp_user_id' => null
                                ]
                );

                Session::forget('temp_user_id');
            }
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            // $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->get() : [] ;
        }

        return view('frontend.view_cart', compact('carts'));
    }


    public function cart_update(Request $request)
    {

        $carts = Cart::where('id', $request->cardid)->first();
        $stock = SizeStock::where('product_id', $carts->product_id)->where('variant', $carts->variation ?? '')->first();
        if (!$stock) {
            $stock = SizeStock::where('product_id', $carts->product_id)->where('size', $carts->size ?? '')->first();
        }
        if (!$stock) {
            $stock = ProductStock::where('product_id', $carts->product_id)->first();
        }
        if ($stock && $stock->qty >= $request->qty) {
            $carts->quantity = $request->qty;
            $carts->save();

            $this->refreshCouponDiscount($carts);

            $price = $carts->price;
            $total = $price * $request->qty;

return response()->json([
            "price" => $price,
            "total" => $total,
            "box" => view('frontend.partials.cart_coupon_box', [
                        'carts' => Cart::where('temp_user_id', Session()->get('temp_user_id'))->get(),
                        'prefix' => 'guest'
                    ])->render()
        ]);

        }else{

             return response()->json([
                "status" => 1,
                "stock" => $stock->qty
            ]);

        }

    }


    public function cart_delete(Request $request)
    {
        if($request->id){
            $carts = Cart::where('id', $request->id)->delete();
        }
        
    }


    private function scopeCartsQuery(Cart $cart)
    {
        if (auth()->user() != null) {
            return Cart::where('user_id', Auth::user()->id)->where('owner_id', $cart->owner_id)->get();
        }
        $temp_user_id = Session()->get('temp_user_id');
        return Cart::where('temp_user_id', $temp_user_id)->where('owner_id', $cart->owner_id)->get();
    }


    public function refreshCouponDiscount(Cart $updatedCart)
    {
        if ($updatedCart['coupon_applied'] != 1 || $updatedCart['coupon_code'] == null) {
            return;
        }
        $coupon = \App\Models\Coupon::where('code', $updatedCart['coupon_code'])->first();
        if ($coupon == null) {
            return;
        }
        if (strtotime(date('d-m-Y')) < $coupon->start_date || strtotime(date('d-m-Y')) > $coupon->end_date) {
            return;
        }

        $coupon_details = json_decode($coupon->details);
        $coupon_carts = $this->scopeCartsQuery($updatedCart);
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
            Cart::where('id', $updatedCart->id)
                    ->where('owner_id', $updatedCart->owner_id)
                    ->update(['discount' => $coupon_carts->count() > 0 ? $coupon_discount / $coupon_carts->count() : 0]);
        } else {
            Cart::where('id', $updatedCart->id)
                    ->where('owner_id', $updatedCart->owner_id)
                    ->update([
                        'discount' => 0.00,
                        'coupon_code' => null,
                        'coupon_applied' => 0
                    ]);
        }
    }


    public function getguest_card(Request $request)
    {
        $array = [];

        $temp_user_id = Session()->get('temp_user_id');
        $user_id = Auth::check() ? Auth::user()->id : null;
        $buy_now_cart_id = $request->get('buy_now_cart_id') ?: Session()->get('buy_now_cart_id');

        if ($buy_now_cart_id) {
            $cart = \App\Models\Cart::where('id', $buy_now_cart_id)->get();
        } elseif ($temp_user_id) {
            $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
        } elseif ($user_id) {
            $cart = \App\Models\Cart::where('user_id', $user_id)->get();
        } else {
            $cart = collect();
        }

        foreach($cart as $value){
            if(!$value->product) continue;
            $variation = $value->variation;
            $variationDisplay = $variation;
            if ($value->size && $variationDisplay) {
                if (stripos($variationDisplay, $value->size) === false) {
                    $variationDisplay = rtrim($variationDisplay, '-') . '-' . $value->size;
                }
            } elseif ($value->size) {
                $variationDisplay = $value->size;
            }
            $array[] = [
                "cart_id" => $value->id,
                "icon"  => uploaded_asset($value->product->thumbnail_img),
                "title"  => $value->product->name,
                "variation" => $variationDisplay,
                "price" => $value->price,
                "total" => $value->price * $value->quantity,
                "qty" => $value->quantity
            ];
        }

        return response()->json([
            "guest_data" => $array
        ]);
    }





    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        $buyNow = $request->boolean('buy_now');

        return view('frontend.partials.addToCart', compact('product', 'buyNow'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);
        $carts = array();
        $data = array();

        // Quick add (home/card buttons): skip heavy view rendering, return a light JSON
        $quick = $request->boolean('quick');
        $errorResponse = function ($status, $carts, $view) use ($quick) {
            $response = array(
                'status' => $status,
                'cart_count' => count($carts),
            );
            if (!$quick) {
                $response['modal_view'] = view($view)->render();
                $response['nav_cart_view'] = view('frontend.partials.cart')->render();
            }
            return $response;
        };

        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = bin2hex(random_bytes(10));
                $request->session()->put('temp_user_id', $temp_user_id);
            }
            $data['temp_user_id'] = $temp_user_id;
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        $data['product_id'] = $product->id;
        $data['owner_id'] = $product->user_id;

        $str = '';
        $tax = 0;
        if($product->auction_product == 0){
            if($product->digital != 1 && $request->quantity < $product->min_qty) {
                return $errorResponse(0, $carts, 'frontend.partials.minQtyNotSatisfied');
            }

            //check the color enabled or disabled for the product
            if($request->has('color')) {
                $str = $request['color'];
            }

            if ($product->digital != 1) {
                //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
                $missingAttributes = false;
                foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                    $attributeValue = $request['attribute_id_'.$choice->attribute_id] ?? null;
                    if ($attributeValue == null) {
                        $missingAttributes = true;
                        break;
                    }
                    if ($str != null){
                        $str .= '-'.str_replace(' ', '', $attributeValue);
                    }
                    else{
                        $str .= str_replace(' ', '', $attributeValue);
                    }
                }
                // if attributes are not selected (quick add to cart), use the first available stock
                if ($missingAttributes) {
                    $firstStock = $product->stocks->first();
                    if ($firstStock != null) {
                        $str = $firstStock->variant;
                    }
                }
            }

            $data['variation'] = $str;
            $data['size'] = $request->has('size') ? $request['size'] : null;

            if ($request->has('size') && $request['size']) {
                $str .= '-'.str_replace(' ', '', $request['size']);
            }

            $requiredColor = $product->colors != null && is_array(json_decode($product->colors)) && count(json_decode($product->colors)) > 0;
            $requiredSize = $product->sizes != null && is_array(json_decode($product->sizes, true)) && count(json_decode($product->sizes, true)) > 0;
            if ($requiredColor && (!$request->has('color') || $request['color'] == null || $request['color'] == '')) {
                return response()->json(['status' => 0, 'message' => 'আপনাকে কালার সিলেক্ট করতে হবে']);
            }
            if ($requiredSize && (!$request->has('size') || $request['size'] == null || $request['size'] == '')) {
                return response()->json(['status' => 0, 'message' => 'আপনাকে সাইজ সিলেক্ট করতে হবে']);
            }

            $colorName = $request->has('color') ? $request['color'] : '';
            $sizeName = $request->has('size') ? $request['size'] : '';

            $product_stock = SizeStock::where('product_id', $product->id)->where('variant', $str)->first();
            if ($product_stock == null) {
                $product_stock = SizeStock::where('product_id', $product->id)
                    ->where('color', $colorName)->where('size', $sizeName)->first();
            }
            if ($product_stock == null) {
                $product_stock = $product->stocks->where('variant', $str)->first();
            }
            if ($product_stock == null) {
                $product_stock = $product->stocks->where('variant', '')->first() ?: $product->stocks->first();
            }
            if ($product_stock == null && $product->current_stock > 0) {
                $product_stock = (object)[
                    'price' => $product->unit_price,
                    'qty' => $product->current_stock,
                    'sku' => $product->sku ?? ''
                ];
            }
            if ($product_stock == null) {
                return $errorResponse(0, $carts, 'frontend.partials.outOfStockCart');
            }
            $price = $product_stock->price;
            $quantity = $product_stock->qty;

            if($quantity < $request['quantity']){
                return $errorResponse(0, $carts, 'frontend.partials.outOfStockCart');
            }

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            }
            elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if($product->discount_type == 'percent'){
                    $price -= ($price*$product->discount)/100;
                }
                elseif($product->discount_type == 'amount'){
                    $price -= $product->discount;
                }
            }

            //calculation of taxes
            foreach ($product->taxes as $product_tax) {
                if($product_tax->tax_type == 'percent'){
                    $tax += ($price * $product_tax->tax) / 100;
                }
                elseif($product_tax->tax_type == 'amount'){
                    $tax += $product_tax->tax;
                }
            }

            $data['quantity'] = $request['quantity'];
            $data['price'] = $price;
            $data['tax'] = $tax;
            //$data['shipping'] = 0;
            $data['shipping_cost'] = 0;
            $data['product_referral_code'] = null;
            $data['cash_on_delivery'] = $product->cash_on_delivery;
            $data['digital'] = $product->digital;

            if ($request['quantity'] == null){
                $data['quantity'] = 1;
            }

            if(Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
                $data['product_referral_code'] = Cookie::get('product_referral_code');
            }

            if($carts && count($carts) > 0){
                $foundInCart = false;

                foreach ($carts as $key => $cartItem){
                    $cart_product = Product::where('id', $cartItem['product_id'])->first();
                    if($cart_product->auction_product == 1){
                        return $errorResponse(0, $carts, 'frontend.partials.auctionProductAlredayAddedCart');
                    }

                    if($cartItem['product_id'] == $request->id) {
                        $product_stock = $cart_product->stocks->where('variant', $str)->first();
                        if ($product_stock == null) {
                            $product_stock = $cart_product->stocks->where('variant', '')->first();
                        }
                        $quantity = $product_stock->qty;
                        if($quantity < $cartItem['quantity'] + $request['quantity']){
                            return $errorResponse(0, $carts, 'frontend.partials.outOfStockCart');
                        }
                        $sameSize = ($request->has('size') && $request['size'] != '')
                            ? (($cartItem['size'] ?? null) == $request['size'])
                            : (($cartItem['size'] ?? null) == '' || ($cartItem['size'] ?? null) == null);
                        if((($str != null && $cartItem['variation'] == $str) && $sameSize) || $str == null){
                            $foundInCart = true;

                            if ($request->boolean('buy_now')) {
                                $cartItem['quantity'] = $request['quantity'] ?: 1;
                            } else {
                                $cartItem['quantity'] += $request['quantity'];
                            }

                            if($cart_product->wholesale_product){
                                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                                if($wholesalePrice){
                                    $price = $wholesalePrice->price;
                                }
                            }

                            $cartItem['price'] = $price;

                            $cartItem->save();
                            $savedCartItem = $cartItem;
                        }
                    }
                }
                if (!$foundInCart) {
                    $savedCartItem = Cart::create($data);
                }
            }
            else{
                $savedCartItem = Cart::create($data);
            }

            if ($request->boolean('buy_now') && isset($savedCartItem) && $savedCartItem) {
                $request->session()->put('buy_now_cart_id', $savedCartItem->id);
            }

            if(auth()->user() != null) {
                $user_id = Auth::user()->id;
                $carts = Cart::where('user_id', $user_id)->get();
            } else {
                $temp_user_id = $request->session()->get('temp_user_id');
                $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            }
			
            $response = array(
                'status' => 1,
                'cart_count' => count($carts),
            );
            if ($request->boolean('buy_now') && isset($savedCartItem) && $savedCartItem) {
                $response['buy_now_cart_id'] = $savedCartItem->id;
            }
            if (!$quick) {
                $response['modal_view'] = view('frontend.partials.addedToCart', compact('product', 'data'))->render();
                $response['nav_cart_view'] = view('frontend.partials.cart')->render();
            }
            return $response;
        }
        else{
            $price = $product->bids->max('amount');

            foreach ($product->taxes as $product_tax) {
                if($product_tax->tax_type == 'percent'){
                    $tax += ($price * $product_tax->tax) / 100;
                }
                elseif($product_tax->tax_type == 'amount'){
                    $tax += $product_tax->tax;
                }
            }

            $data['quantity'] = 1;
            $data['price'] = $price;
            $data['tax'] = $tax;
            $data['shipping_cost'] = 0;
            $data['product_referral_code'] = null;
            $data['cash_on_delivery'] = $product->cash_on_delivery;
            $data['digital'] = $product->digital;

            if(count($carts) == 0){
                Cart::create($data);
            }
            if(auth()->user() != null) {
                $user_id = Auth::user()->id;
                $carts = Cart::where('user_id', $user_id)->get();
            } else {
                $temp_user_id = $request->session()->get('temp_user_id');
                $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            }

            $response = array(
                'status' => 1,
                'cart_count' => count($carts),
            );
            if (!$quick) {
                $response['modal_view'] = view('frontend.partials.addedToCart', compact('product', 'data'))->render();
                $response['nav_cart_view'] = view('frontend.partials.cart')->render();
            }
            return $response;
        }

    }

    //returns the nav cart section (badge count + mini cart) only
    public function nav(Request $request)
    {
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->get() : collect();
        }

        return array(
            'cart_count' => count($carts),
            'cart_total' => single_price($carts->sum(function ($cart) {
                return $cart->price * $cart->quantity;
            })),
            'nav_cart_view' => view('frontend.partials.cart')->render(),
        );
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::destroy($request->id);
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);

        if($cartItem['id'] == $request->id){
            $product = Product::find($cartItem['product_id']);
            $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
            if ($product_stock == null) {
                $product_stock = $product->stocks->where('variant', '')->first();
            }
            $quantity = $product_stock->qty;
            $price = $product_stock->price;
			
			//discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            }
            elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if($product->discount_type == 'percent'){
                    $price -= ($price*$product->discount)/100;
                }
                elseif($product->discount_type == 'amount'){
                    $price -= $product->discount;
                }
            }

            if($quantity >= $request->quantity) {
                if($request->quantity >= $product->min_qty){
                    $cartItem['quantity'] = $request->quantity;
                }
            }

            if($product->wholesale_product){
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if($wholesalePrice){
                    $price = $wholesalePrice->price;
                }
            }

            $cartItem['price'] = $price;
            $cartItem->save();

            $this->refreshCouponDiscount($cartItem);
        }

        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart_details', compact('carts'))->render(),
            'page_box' => count($carts) > 0 ? view('frontend.partials.cart_page_box', [
                            'carts' => $carts,
                            'variant' => auth()->user() != null ? 'logged' : 'guest'
                        ])->render() : '',
            'nav_cart_view' => view('frontend.partials.cart')->render(),
        );
    }
}
