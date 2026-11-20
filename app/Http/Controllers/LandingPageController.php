<?php

namespace App\Http\Controllers;

use App\Models\IncompleteOrder;
use App\Models\LandingPage;
use App\Models\Product;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $landing_pages = LandingPage::orderBy('created_at', 'desc')->paginate(10);
        return view('backend.landing_pages.index', compact('landing_pages'));
    }

    public function create()
    {
        $products = Product::where('published', 1)->orderBy('created_at', 'desc')->get();
        return view('backend.landing_pages.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount'   => 'required|numeric|min:0|max:100',
            'date_range' => 'required',
        ]);

        try {
            list($start_date, $end_date) = $this->campaignDates($request->date_range);
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['date_range' => $e->getMessage()]);
        }

        LandingPage::create([
            'product_id'  => $request->product_id,
            'title'       => $request->title,
            'discount'    => $request->discount,
            'start_date'  => $start_date,
            'end_date'    => $end_date,
            'header_text' => $request->header_text,
            'footer_text' => $request->footer_text,
            'description' => $request->description,
            'status'      => 1,
        ]);

        flash(translate('Landing page created successfully'))->success();
        return redirect()->route('landing_pages.index');
    }

    public function edit($id)
    {
        $landing_page = LandingPage::findOrFail($id);
        $products = Product::where('published', 1)->orderBy('created_at', 'desc')->get();
        return view('backend.landing_pages.edit', compact('landing_page', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount'   => 'required|numeric|min:0|max:100',
            'date_range' => 'required',
        ]);

        try {
            list($start_date, $end_date) = $this->campaignDates($request->date_range);
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['date_range' => $e->getMessage()]);
        }

        $landing_page = LandingPage::findOrFail($id);
        $landing_page->update([
            'product_id'  => $request->product_id,
            'title'       => $request->title,
            'discount'    => $request->discount,
            'start_date'  => $start_date,
            'end_date'    => $end_date,
            'header_text' => $request->header_text,
            'footer_text' => $request->footer_text,
            'description' => $request->description,
        ]);

        flash(translate('Landing page updated successfully'))->success();
        return redirect()->route('landing_pages.index');
    }

    public function destroy($id)
    {
        LandingPage::findOrFail($id)->delete();
        flash(translate('Landing page deleted successfully'))->success();
        return redirect()->route('landing_pages.index');
    }

    public function update_status(Request $request)
    {
        $landing_page = LandingPage::findOrFail($request->id);
        $landing_page->status = $request->status;
        if ($landing_page->save()) {
            flash(translate('Landing page status updated successfully'))->success();
            return 1;
        }
        return 0;
    }

    public function show($id)
    {
        $landing_page = LandingPage::findOrFail($id);
        $product = $landing_page->product;

        if ($product == null) {
            abort(404);
        }

        $base_price = $product->unit_price;
        $discounted_price = $base_price - ($base_price * $landing_page->discount / 100);
        $start_time = $this->campaignTimestamp($landing_page->start_date);
        $end_time = $this->campaignTimestamp($landing_page->end_date);
        $campaign_open = $landing_page->status == 1
            && ($start_time === false || $start_time <= time())
            && ($end_time === false || $end_time >= time());

        return view('frontend.landing', compact('landing_page', 'product', 'base_price', 'discounted_price', 'campaign_open'));
    }

    public function store_order(Request $request, $id)
    {
        $landing_page = LandingPage::findOrFail($id);
        $product = $landing_page->product;

        $request->validate([
            'name'          => 'required',
            'phone'         => 'required|min:10',
            'address'       => 'required',
            'delivery_area' => 'required|in:In Dhaka City,Sub City Of Dhaka,Out Of Dhaka City',
            'quantity'      => 'required|integer|min:1|max:99',
        ]);

        $colors = json_decode($product->colors);
        $sizes = ($product->sizes != null && is_array(json_decode($product->sizes, true))) ? json_decode($product->sizes, true) : array();

        $variant_rules = array();
        $variant_messages = array();

        if (is_array($colors) && count($colors) > 0) {
            $colorNames = array();
            foreach ($colors as $code) {
                $color = \App\Models\Color::where('code', $code)->first();
                $colorNames[] = $color != null ? $color->name : $code;
            }
            $variant_rules['color'] = 'required|in:' . implode(',', $colorNames);
            $variant_messages['color.required'] = 'আপনাকে কালার সিলেক্ট করতে হবে।';
            $variant_messages['color.in'] = 'নির্বাচিত কালারটি সঠিক নয়।';
        }

        if (count($sizes) > 0) {
            $variant_rules['size'] = 'required|in:' . implode(',', $sizes);
            $variant_messages['size.required'] = 'আপনাকে সাইজ সিলেক্ট করতে হবে।';
            $variant_messages['size.in'] = 'নির্বাচিত সাইজটি সঠিক নয়।';
        }

        if (count($variant_rules) > 0) {
            $request->validate($variant_rules, $variant_messages);
        }

        $start_time = $this->campaignTimestamp($landing_page->start_date);
        $end_time = $this->campaignTimestamp($landing_page->end_date);
        if ($landing_page->status != 1 || ($start_time !== false && $start_time > time()) || ($end_time !== false && $end_time < time())) {
            return back()->with('error', 'এই অফারটি এখন সক্রিয় নেই।');
        }

        if ($product == null) {
            return back()->with('error', translate('Product not found'));
        }

        $price = $product->unit_price - ($product->unit_price * $landing_page->discount / 100);
        $quantity = (int) $request->quantity;
        $total = $price * $quantity;

        IncompleteOrder::create([
            'temp_user_id'  => session('temp_user_id'),
            'name'          => $request->name,
            'email'         => $request->email != null ? $request->email : 'customer@gmail.com',
            'phone'         => trim($request->phone),
            'address'       => $request->address,
            'city'          => $request->delivery_area,
            'delivery_area' => $request->delivery_area,
            'products'      => json_encode(array(array(
                'product_id' => $product->id,
                'name'       => $product->getTranslation('name'),
                'qty'        => $quantity,
                'price'      => $price,
                'total'      => $total,
                'variation'  => $request->color,
                'size'       => $request->size,
            ))),
            'grand_total'   => $total,
        ]);

        if ($request->partial_id) {
            $partial = IncompleteOrder::find((int) $request->partial_id);
            if ($partial != null && $partial->phone == trim($request->phone)) {
                $partial->delete();
            }
        }

        return back()->with('success', 'আপনার অর্ডারটি কনফার্ম হয়েছে। আমাদের প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবে।');
    }

    public function save_partial_order(Request $request, $id)
    {
        $landing_page = LandingPage::findOrFail($id);
        $product = $landing_page->product;

        $phone = preg_replace('/[^0-9]/', '', (string) $request->phone);
        if (strlen($phone) < 11) {
            return response()->json(array('ok' => false), 422);
        }

        $quantity = max(1, min(99, (int) $request->quantity));
        $price = 0;
        $product_name = '';
        $product_id = null;
        if ($product != null) {
            $price = $product->unit_price - ($product->unit_price * $landing_page->discount / 100);
            $product_name = $product->getTranslation('name');
            $product_id = $product->id;
        }
        $total = $price * $quantity;

        $data = array(
            'temp_user_id'  => session('temp_user_id'),
            'name'          => $request->name ?: null,
            'email'         => $request->email != '' ? $request->email : 'customer@gmail.com',
            'phone'         => $phone,
            'address'       => $request->address ?: null,
            'city'          => $request->delivery_area ?: null,
            'delivery_area' => $request->delivery_area ?: null,
            'products'      => json_encode(array(array(
                'product_id' => $product_id,
                'name'       => $product_name,
                'qty'        => $quantity,
                'price'      => $price,
                'total'      => $total,
                'variation'  => $request->color,
                'size'       => $request->size,
            ))),
            'grand_total'   => $total,
        );

        $existing = null;
        if ($request->partial_id) {
            $existing = IncompleteOrder::find((int) $request->partial_id);
            if ($existing != null && $existing->phone != $phone) {
                $existing = null;
            }
        }

        if ($existing != null) {
            $existing->update($data);
            $partial_id = (int) $existing->id;
        } else {
            $partial = IncompleteOrder::create($data);
            $partial_id = (int) $partial->id;
        }

        return response()->json(array('ok' => true, 'id' => $partial_id));
    }

    private function campaignDates($date_range)
    {
        $dates = explode(' to ', (string) $date_range);
        if (count($dates) !== 2) {
            throw new \InvalidArgumentException(translate('Please choose a valid campaign duration'));
        }

        $start_date = $this->parseCampaignDate($dates[0]);
        $end_date = $this->parseCampaignDate($dates[1]);

        if ($end_date < $start_date) {
            throw new \InvalidArgumentException(translate('End date must be after start date'));
        }

        return array(
            $start_date->format('Y-m-d H:i:s'),
            $end_date->format('Y-m-d H:i:s'),
        );
    }

    private function parseCampaignDate($value)
    {
        $date = \DateTime::createFromFormat('!d-m-Y H:i:s', trim($value));
        $errors = \DateTime::getLastErrors();

        if ($date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
            throw new \InvalidArgumentException(translate('Please choose a valid campaign duration'));
        }

        return $date;
    }

    private function campaignTimestamp($value)
    {
        if ($value == null || strpos((string) $value, '0000-00-00') === 0) {
            return false;
        }

        return strtotime($value);
    }
}
