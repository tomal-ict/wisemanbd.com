@extends('backend.layouts.app')

@section('content')
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0 fw-600">{{ translate('Create Purchase') }}</h6></div>
    <div class="card-body">
        <form action="{{ route('pos.purchases.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-xl-8">
                    <div class="card mb-3">
                        <div class="card-header"><h6 class="mb-0 fw-600"><i class="las la-cart-plus la-2x opacity-60 mr-2"></i>{{ translate('Purchase Items') }}</h6></div>
                        <div class="card-body">
                            <div class="form-group mb-2">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-lg" id="purchase-product-search" placeholder="{{ translate('Search product by name / barcode / SKU...') }}" autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary px-4" id="purchase-add-product"><i class="las la-search"></i> {{ translate('Search') }}</button>
                                    </div>
                                </div>
                                <div class="mt-2" id="purchase-search-results"></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="purchase-items-table">
                                    <thead>
                                        <tr class="fs-12 text-uppercase">
                                            <th class="text-center" style="width:45px">#</th>
                                            <th>{{ translate('Product') }}</th>
                                            <th style="width:130px">{{ translate('Size') }}</th>
                                            <th style="width:130px">{{ translate('Color') }}</th>
                                            <th style="width:90px">{{ translate('Qty') }}</th>
                                            <th style="width:130px">{{ translate('Unit Cost') }}</th>
                                            <th class="text-right" style="width:130px">{{ translate('Total') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <p class="fs-12 text-muted mb-0"><i class="las la-info-circle"></i> {{ translate('Choose size / color, set unit cost (purchase price). Marking Received adds this to stock and updates the product cost price — sellable from POS and website.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card mb-3">
                        <div class="card-header"><h6 class="mb-0 fw-600"><i class="las la-truck opacity-60 mr-2"></i>{{ translate('Supplier') }}</h6></div>
                        <div class="card-body">
                            <div class="form-group">
                                <select name="supplier_id" class="form-control" required>
                                    <option value="">{{ translate('Select supplier...') }}</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header"><h6 class="mb-0 fw-600"><i class="las la-wallet opacity-60 mr-2"></i>{{ translate('Status & Payment') }}</h6></div>
                        <div class="card-body">
                            <div class="form-group mb-2">
                                <label class="fs-12 mb-1">{{ translate('Status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="Pending">{{ translate('Pending') }}</option>
                                    <option value="Received" selected>{{ translate('Received') }}</option>
                                    <option value="Cancelled">{{ translate('Cancelled') }}</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="fs-12 mb-1">{{ translate('Purchase Date') }}</label>
                                <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="fs-12 mb-1">{{ translate('Payment Method') }}</label>
                                <select name="payment_method" class="form-control">
                                    @foreach(\App\Http\Controllers\PosPurchaseController::PAYMENT_METHODS as $method)
                                        <option value="{{ $method }}">{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="fs-12 mb-1">{{ translate('Paid Amount') }}</label>
                                <input type="number" name="paid_amount" class="form-control" id="purchase-paid" min="0" step="0.01" value="0">
                            </div>
                            <div class="form-group mb-0">
                                <label class="fs-12 mb-1">{{ translate('Note') }}</label>
                                <textarea name="note" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h6 class="mb-0 fw-600"><i class="las la-file-invoice-dollar opacity-60 mr-2"></i>{{ translate('Summary') }}</h6></div>
                        <div class="card-body">
                            <div class="fs-14">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ translate('Subtotal') }}</span>
                                    <strong id="purchase-subtotal">0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2 align-items-center">
                                    <span>{{ translate('Discount') }}</span>
                                    <input type="number" class="form-control form-control-sm text-right" name="discount_amount" id="purchase-discount" min="0" step="0.01" value="0" style="width:110px">
                                </div>
                                <div class="d-flex justify-content-between mb-2 align-items-center">
                                    <span>{{ translate('Other Charges') }}</span>
                                    <input type="number" class="form-control form-control-sm text-right" name="other_charges" id="purchase-charges" min="0" step="0.01" value="0" style="width:110px">
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="fw-600">{{ translate('Grand Total') }}</span>
                                    <strong id="purchase-grand" class="text-primary fs-18">0.00</strong>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block"><i class="las la-save"></i> {{ translate('Save Purchase') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
(function () {
    var items = [];
    var currency = {!! json_encode(currency_symbol()) !!};
    var resultsBox = document.getElementById('purchase-search-results');
    var searchInput = document.getElementById('purchase-product-search');
    var debounceTimer = null;
    var lastQuery = '';
    var csrf = '{{ csrf_token() }}';

    function fmt(n) { return currency + Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

    function escapeHtml(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function renderSuggestions(products) {
        resultsBox.innerHTML = '';
        if (!products.length) {
            resultsBox.innerHTML = '<div class="fs-12 text-muted">{{ translate('No product found') }}</div>';
            return;
        }
        var group = document.createElement('div');
        group.className = 'list-group';
        products.forEach(function (p) {
            var img = p.image ? '<img src="' + escapeHtml(p.image) + '" class="img-fit" style="width:34px;height:34px;border-radius:4px;">' : '<div class="size-34px bg-soft-secondary rounded d-flex align-items-center justify-content-center"><i class="las la-box las la-2x"></i></div>';
            var tag = '';
            if (p.colors && p.colors.length) tag += '<span class="badge badge-soft-primary mr-1">{{ translate('Color') }}</span>';
            if (p.sizes && p.sizes.length) tag += '<span class="badge badge-soft-info">{{ translate('Size') }}</span>';
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'list-group-item list-group-item-action d-flex align-items-center text-left';
            button.innerHTML = img
                + '<div class="ml-3 flex-grow-1"><div class="fs-13 fw-600">' + escapeHtml(p.name) + '</div>'
                + '<div class="fs-12 text-muted">' + escapeHtml(p.barcode) + ' ' + tag + '</div></div>'
                + '<i class="las la-plus-circle text-primary las-2x"></i>';
            button.addEventListener('click', function () {
                addItem(p);
                searchInput.value = '';
                lastQuery = '';
                resultsBox.innerHTML = '';
            });
            group.appendChild(button);
        });
        resultsBox.appendChild(group);
    }

    function td2(html) {
        var c = document.createElement('td');
        c.innerHTML = html;
        return c;
    }

    function doSearch() {
        var q = searchInput.value.trim();
        if (q === lastQuery) return;
        lastQuery = q;
        if (!q) { resultsBox.innerHTML = ''; return; }
        var body = new FormData();
        body.append('_token', csrf);
        body.append('q', q);
        fetch('{{ route('pos.purchases.products.search') }}', { method: 'POST', body: body })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                renderSuggestions(data.products || []);
            })
            .catch(function () { resultsBox.innerHTML = ''; });
    }

    function addItem(product) {
        var colors = (product.colors || []).map(function (c) { return c.name; });
        var sizes = (product.sizes || []).slice();
        // Each selected product is a separate purchase line for manual entry.
        items.push({
            product_id: product.product_id,
            name: product.name,
            barcode: product.barcode || '',
            colors: colors,
            sizes: sizes,
            color: '',
            size: '',
            qty: '',
            unit_cost: '',
            total: 0
        });
        render();
    }

    function colorOptions(item) {
        var opts = '<option value="" disabled' + (item.color === '' ? ' selected' : '') + '>{{ translate('Select color') }}</option>';
        (item.colors || []).forEach(function (c) {
            opts += '<option value="' + escapeHtml(c) + '"' + (item.color == c ? ' selected' : '') + '>' + escapeHtml(c) + '</option>';
        });
        return opts;
    }

    function sizeOptions(item) {
        var opts = '<option value="" disabled' + (item.size === '' ? ' selected' : '') + '>{{ translate('Select size') }}</option>';
        (item.sizes || []).forEach(function (s) {
            opts += '<option value="' + escapeHtml(s) + '"' + (item.size == s ? ' selected' : '') + '>' + escapeHtml(s) + '</option>';
        });
        return opts;
    }

    function render() {
        var tbody = document.querySelector('#purchase-items-table tbody');
        tbody.innerHTML = '';
        var subtotal = 0;
        items.forEach(function (item, index) {
            item.total = (parseFloat(item.qty) || 0) * (parseFloat(item.unit_cost) || 0);
            subtotal += item.total;
            var colorSelect = item.colors.length ? '<select class="form-control form-control-sm purchase-variant-color" data-index="' + index + '">' + colorOptions(item) + '</select>' : '';
            var sizeSelect = item.sizes.length ? '<select class="form-control form-control-sm purchase-variant-size" data-index="' + index + '">' + sizeOptions(item) + '</select>' : '';
            var hidden = '<input type="hidden" name="items[' + index + '][product_id]" value="' + item.product_id + '">'
                + '<input type="hidden" name="items[' + index + '][color]" value="' + escapeHtml(item.color) + '">'
                + '<input type="hidden" name="items[' + index + '][size]" value="' + escapeHtml(item.size) + '">';
            var tr = document.createElement('tr');
            tr.appendChild(td2(hidden)); // 0: hidden
            tr.cells[0].style.display = 'none';
            var sl = document.createElement('td'); // 1: SL
            sl.className = 'text-center text-secondary';
            sl.textContent = index + 1;
            tr.appendChild(sl);
            var nameCell = document.createElement('td'); // 2: Product
            nameCell.className = 'fs-13';
            nameCell.innerHTML = '<div class="fw-600">' + escapeHtml(item.name) + '</div>'
                + '<div class="fs-12 text-muted">' + (item.barcode ? escapeHtml(item.barcode) : 'SKU-' + item.product_id) + '</div>';
            tr.appendChild(nameCell);
            tr.appendChild(td2(sizeSelect)); // 3: Size
            tr.appendChild(td2(colorSelect)); // 4: Color
            tr.appendChild(td2('<input type="number" class="form-control form-control-sm purchase-qty" name="items[' + index + '][qty]" value="' + item.qty + '" min="0.01" step="0.01" data-index="' + index + '">')); // 5: Qty
            tr.appendChild(td2('<input type="number" class="form-control form-control-sm purchase-cost" name="items[' + index + '][unit_cost]" value="' + item.unit_cost + '" min="0" step="0.01" data-index="' + index + '">')); // 6: Unit Cost
            var totalCell = document.createElement('td'); // 7: Total
            totalCell.className = 'text-right fs-13 fw-600';
            totalCell.textContent = fmt(item.total);
            tr.appendChild(totalCell);
            var actionCell = document.createElement('td'); // 8: action
            actionCell.className = 'text-right';
            actionCell.innerHTML = '<button type="button" class="btn btn-soft-danger btn-icon btn-xs purchase-remove" data-index="' + index + '"><i class="las la-trash"></i></button>';
            tr.appendChild(actionCell);
            tbody.appendChild(tr);
        });
        var discount = Math.min(subtotal, parseFloat(document.getElementById('purchase-discount').value) || 0);
        var charges = parseFloat(document.getElementById('purchase-charges').value) || 0;
        var grand = Math.round(subtotal - discount + charges);
        document.getElementById('purchase-subtotal').textContent = fmt(subtotal);
        document.getElementById('purchase-grand').textContent = fmt(grand);
        document.getElementById('purchase-paid').max = grand;
    }

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(doSearch, 300);
    });
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
    });

    document.getElementById('purchase-add-product').addEventListener('click', doSearch);

    // Color / size dropdown change
    document.querySelector('#purchase-items-table tbody').addEventListener('change', function (e) {
        var el = e.target;
        var idx = parseInt(el.getAttribute('data-index'));
        if (el.classList.contains('purchase-variant-color')) { items[idx].color = el.value; }
        if (el.classList.contains('purchase-variant-size')) { items[idx].size = el.value; }
        if (el.classList.contains('purchase-variant-color') || el.classList.contains('purchase-variant-size')) {
            render();
        }
    });

    document.querySelector('#purchase-items-table tbody').addEventListener('input', function (e) {
        var el = e.target;
        if (el.classList.contains('purchase-qty')) { items[parseInt(el.getAttribute('data-index'))].qty = el.value === '' ? '' : (parseFloat(el.value) || 0); render(); }
        if (el.classList.contains('purchase-cost')) { items[parseInt(el.getAttribute('data-index'))].unit_cost = el.value === '' ? '' : (parseFloat(el.value) || 0); render(); }
    });

    document.querySelector('#purchase-items-table tbody').addEventListener('click', function (e) {
        var rm = e.target.closest('.purchase-remove');
        if (rm) { items.splice(parseInt(rm.getAttribute('data-index')), 1); render(); }
    });

    document.getElementById('purchase-discount').addEventListener('input', render);
    document.getElementById('purchase-charges').addEventListener('input', render);
    render();
})();
</script>
@endsection
