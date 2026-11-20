<?php $__env->startSection('content'); ?>
<?php if(env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null): ?>
    <div class="">
        <div class="alert alert-danger d-flex align-items-center">
            <?php echo e(translate('Please Configure SMTP Setting to work all email sending functionality')); ?>,
            <a class="alert-link ml-2" href="<?php echo e(route('smtp_settings.index')); ?>"><?php echo e(translate('Configure Now')); ?></a>
        </div>
    </div>
<?php endif; ?>

<?php if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions))): ?>
    <div class="row gutters-10 align-items-center mb-4">
        <div class="col">
            <h5 class="mb-0 fw-700"><?php echo e(translate('Dashboard')); ?></h5>
            <div class="text-muted mt-1 fs-12"><?php echo e(translate('Welcome back')); ?>, <strong class="text-dark"><?php echo e(Auth::user()->name); ?></strong> — <?php echo e(date('l, d F Y')); ?></div>
        </div>
        <div class="col-auto d-none d-lg-flex align-items-center text-muted fs-12">
            <i class="las la-calendar-check la-2x mr-2 text-primary"></i>
            <span><?php echo e(translate('Overview of your store at a glance')); ?></span>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row gutters-10">
        <div class="col-6 col-xl-3">
            <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden position-relative">
                <i class="las la-wallet position-absolute" style="top:12px; right:12px; font-size:34px; opacity:.35;"></i>
                <div class="px-3 pt-3 pb-2">
                    <div class="opacity-50 fs-12"><?php echo e(translate('Today')); ?> <?php echo e(translate('Sales')); ?></div>
                    <div class="h3 fw-700 mb-1"><?php echo e(single_price($today_sales)); ?></div>
                    <div class="opacity-75 fs-12"><i class="las la-shopping-cart mr-1"></i><?php echo e($today_orders); ?> <?php echo e(translate('Orders')); ?></div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                </svg>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden position-relative">
                <i class="las la-calendar position-absolute" style="top:12px; right:12px; font-size:34px; opacity:.35;"></i>
                <div class="px-3 pt-3 pb-3">
                    <div class="opacity-50 fs-12"><?php echo e(translate('Monthly')); ?> <?php echo e(translate('Sales')); ?></div>
                    <div class="h3 fw-700 mb-1"><?php echo e(single_price($month_sales)); ?></div>
                    <div class="opacity-75 fs-12"><i class="las la-receipt mr-1"></i><?php echo e($month_orders); ?> <?php echo e(translate('Orders')); ?></div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,96L34.3,117.3C68.6,139,137,181,206,160C274.3,139,343,53,411,64C480,75,549,160,617,186.7C685.7,213,754,192,823,176C891.4,160,960,117,1029,117.3C1097.1,117,1166,160,1234,181.3C1302.9,203,1371,235,1406,245.3L1440,256L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                </svg>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden position-relative">
                <i class="las la-chart-line position-absolute" style="top:12px; right:12px; font-size:34px; opacity:.35;"></i>
                <div class="px-3 pt-3 pb-3">
                    <div class="opacity-50 fs-12"><?php echo e(translate('Monthly')); ?> <?php echo e(translate('Profit')); ?></div>
                    <div class="h3 fw-700 mb-1"><?php echo e(single_price($month_profit)); ?></div>
                    <div class="opacity-75 fs-12"><i class="las la-check-circle mr-1"></i><?php echo e(translate('On delivered orders')); ?></div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,320L48,277.3C96,235,192,149,288,138.7C384,128,480,192,576,213.3C672,235,768,213,864,192C960,171,1056,149,1152,154.7C1248,160,1344,192,1392,208L1440,224L1440,320Z"></path>
                </svg>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden position-relative">
                <i class="las la-exchange-alt position-absolute" style="top:12px; right:12px; font-size:34px; opacity:.35;"></i>
                <div class="px-3 pt-3 pb-3">
                    <div class="opacity-50 fs-12"><?php echo e(translate('Total')); ?> <?php echo e(translate('Transactions')); ?></div>
                    <div class="h3 fw-700 mb-1"><?php echo e($total_transactions); ?></div>
                    <div class="opacity-75 fs-12"><i class="las la-box mr-1"></i><?php echo e(translate('All time orders')); ?></div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,160L80,181.3C160,203,320,245,480,229.3C640,213.3,800,139,960,112C1120,85.3,1280,107,1360,117.3L1440,128L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Order status overview -->
    <div class="row gutters-10">
        <div class="col-6 col-md-3 col-xl-2">
            <div class="card shadow-none rounded-lg border-soft mb-4">
                <div class="card-body d-flex align-items-start">
                    <span class="h-40px w-40px flex-shrink-0 rounded-circle bg-soft-info d-flex align-items-center justify-content-center mr-2" style="font-size: 18px;"><i class="las la-shopping-bag text-info"></i></span>
                    <div>
                        <div class="h4 fw-700 mb-0" style="line-height:1.1;"><?php echo e($today_orders); ?></div>
                        <div class="fs-11 text-muted"><?php echo e(translate('New Orders')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-2">
            <div class="card shadow-none rounded-4 border-soft mb-4">
                <div class="card-body d-flex align-items-start">
                    <span class="h-40px w-40px flex-shrink-0 bg-soft-warning d-flex align-items-center justify-content-center mr-2" style="font-size: 18px;"><i class="las la-hourglass-half text-warning"></i></span>
                    <div>
                        <div class="fs-4 fw-700 mb-0"><?php echo e($pending_orders); ?></div>
                        <div class="fs-11 text-muted"><?php echo e(translate('Pending')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-2">
            <div class="card shadow-none rounded-4 border-soft mb-4">
                <div class="card-body d-flex align-items-start">
                    <span class="h-40px w-40px flex-shrink-0 bg-soft-primary d-flex align-items-center justify-content-center mr-2" style="font-size: 18px;"><i class="las la-check-double text-primary"></i></span>
                    <div>
                        <div class="fs-4 fw-700 mb-0"><?php echo e($confirmed_orders); ?></div>
                        <div class="fs-11 text-muted"><?php echo e(translate('Confirmed')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-2">
            <div class="card shadow-none rounded-4 border-soft mb-4">
                <div class="card-body d-flex align-items-start">
                    <span class="h-40px w-40px flex-shrink-0 bg-soft-dark d-flex align-items-center justify-content-center mr-2" style="font-size: 18px;"><i class="las la-dolly text-dark"></i></span>
                    <div>
                        <div class="fs-4 fw-700 mb-0"><?php echo e($picked_up_orders); ?></div>
                        <div class="fs-11 text-muted"><?php echo e(translate('Picked Up')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-2">
            <div class="card shadow-none rounded-4 border-soft mb-4">
                <div class="card-body d-flex align-items-start">
                    <span class="h-40px w-40px flex-shrink-0 bg-soft-primary d-flex align-items-center justify-content-center mr-2" style="font-size: 18px;"><i class="las la-truck text-primary"></i></span>
                    <div>
                        <div class="fs-4 fw-700 mb-0"><?php echo e($on_the_way_orders); ?></div>
                        <div class="fs-11 text-muted"><?php echo e(translate('On The Way')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-2">
            <div class="card shadow-none rounded-4 border-soft mb-4">
                <div class="card-body d-flex align-items-start">
                    <span class="h-40px w-40px flex-shrink-0 bg-soft-success d-flex align-items-center justify-content-center mr-2" style="font-size: 18px;"><i class="las la-check-circle text-success"></i></span>
                    <div>
                        <div class="fs-4 fw-700 mb-0"><?php echo e($total_delivered); ?></div>
                        <div class="fs-11 text-muted"><?php echo e(translate('Delivered')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & Order status charts -->
    <div class="row gutters-10">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fs-14 fw-600"><?php echo e(translate('Sales Overview')); ?> <span class="text-muted">(<?php echo e(translate('Last 30 days')); ?>)</span></h6>
                    <div class="text-muted fs-12"><?php echo e(translate('Store revenue per day')); ?></div>
                </div>
                <div class="card-body">
                    <canvas id="sales-overview" class="w-100" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fs-14 fw-600"><?php echo e(translate('Order Status')); ?></h6>
                    <div class="text-muted fs-12"><?php echo e(translate('All orders breakdown')); ?></div>
                </div>
                <div class="card-body">
                    <canvas id="order-status-doughnut" class="w-100" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly + product/category charts -->
    <div class="row gutters-10">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fs-14 fw-600"><?php echo e(translate('Monthly Sales')); ?> <span class="text-muted fs-12">(<?php echo e(translate('Last 6 months')); ?>)</span></h6>
                </div>
                <div class="card-body">
                    <canvas id="monthly-sales" class="w-100" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row gutters-10 h-100">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fs-14"><?php echo e(translate('Products')); ?></h6>
                        </div>
                        <div class="card-body">
                            <canvas id="pie-1" class="w-100" height="305"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fs-14"><?php echo e(translate('Sellers')); ?></h6>
                        </div>
                        <div class="card-body">
                            <canvas id="pie-2" class="w-100" height="305"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gutters-10">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fs-14"><?php echo e(translate('Category wise product sale')); ?></h6>
                </div>
                <div class="card-body">
                    <canvas id="graph-1" class="w-100" height="350"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fs-14"><?php echo e(translate('Category wise product stock')); ?></h6>
                </div>
                <div class="card-body">
                    <canvas id="graph-2" class="w-100" height="350"></canvas>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0"><?php echo e(translate('Top 12 Products')); ?></h6>
    </div>
    <div class="card-body">
        <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-arrows='true'>
            <?php $__currentLoopData = filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(12)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-box">
                    <div class="aiz-card-box border border-light rounded shadow-sm hov-shadow-md mb-2 has-transition bg-white">
                        <div class="position-relative">
                            <a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block">
                                <img
                                    class="img-fit lazyload mx-auto h-210px"
                                    src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                                    data-src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>"
                                    alt="<?php echo e($product->getTranslation('name')); ?>"
                                    onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';"
                                >
                            </a>
                        </div>
                        <div class="p-md-3 p-2 text-left">
                            <div class="fs-15">
                                <?php if(home_base_price($product) != home_discounted_base_price($product)): ?>
                                    <del class="fw-600 opacity-50 mr-1"><?php echo e(home_base_price($product)); ?></del>
                                <?php endif; ?>
                                <span class="fw-700 text-primary"><?php echo e(home_discounted_base_price($product)); ?></span>
                            </div>
                            <div class="rating rating-sm mt-1">
                                <?php echo e(renderStarRating($product->rating)); ?>

                            </div>
                            <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0">
                                <a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block text-reset"><?php echo e($product->getTranslation('name')); ?></a>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    var cur_symbol = '<?php echo e(currency_symbol()); ?>';

    AIZ.plugins.chart('#sales-overview',{
        type: 'line',
        data: {
            labels: [
                <?php $__currentLoopData = $last_30_days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    '<?php echo e(date('d M', strtotime($d['date']))); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            datasets: [{
                label: '<?php echo e(translate('Sales')); ?>',
                data: [
                    <?php $__currentLoopData = $last_30_days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($day['sales']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                fill: true,
                borderWidth: 2.5,
                borderColor: 'rgba(55, 125, 255, 1)',
                backgroundColor: 'rgba(55, 125, 255, 0.12)',
                pointBackgroundColor: '#377dff',
                pointBorderColor: '#ffffff',
                pointRadius: 3,
                pointHoverRadius: 6,
                lineTension: 0.4
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: true,
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true,
                    fontSize: 11
                }
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true,
                        callback: function(value){ return cur_symbol + value; }
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        maxTicksLimit: 10
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data){
                        return data.datasets[tooltipItem.datasetIndex].label + ': ' + cur_symbol + tooltipItem.yLabel;
                    }
                }
            },
            legend: {
                onClick: function () {
                    return '';
                }
            }
        }
    });

    AIZ.plugins.chart('#order-status-doughnut',{
        type: 'doughnut',
        data: {
            labels: [
                '<?php echo e(translate('Pending')); ?>',
                '<?php echo e(translate('Confirmed')); ?>',
                '<?php echo e(translate('Picked Up')); ?>',
                '<?php echo e(translate('On The Way')); ?>',
                '<?php echo e(translate('Delivered')); ?>',
                '<?php echo e(translate('Cancelled')); ?>'
            ],
            datasets: [{
                data: [
                    <?php echo e($pending_orders); ?>,
                    <?php echo e($confirmed_orders); ?>,
                    <?php echo e($picked_up_orders); ?>,
                    <?php echo e($on_the_way_orders); ?>,
                    <?php echo e($total_delivered); ?>,
                    <?php echo e($total_cancelled); ?>

                ],
                backgroundColor: [ '#fdcc0d', "#5d78ff", "#8e44ad", '#00bcd4', "#34bfa3", '#f4516c' ]
            }]
        },
        options: {
            cutoutPercentage: 68,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true,
                    fontSize: 11
                },
                onClick: function() {
                    return '';
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data){
                        var idx = tooltipItem.index;
                        var total = data.datasets[0].data.reduce(function(a,b){ return a+b; }, 0);
                        var val = data.datasets[0].data[idx];
                        var pct = total > 0 ? Math.round(val/total*100) : 0;
                        return data.labels[idx] + ': ' + val + ' (' + pct + '%)';
                    }
                }
            }
        }
    });

    AIZ.plugins.chart('#monthly-sales',{
        type: 'bar',
        data: {
            labels: [
                <?php $__currentLoopData = $monthly_sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    '<?php echo e($ms['month']); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            datasets: [{
                label: '<?php echo e(translate('Sales')); ?>',
                data: [
                    <?php $__currentLoopData = $monthly_sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($ms['sales']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                backgroundColor: [
                    <?php $__currentLoopData = $monthly_sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(34, 191, 163, 0.55)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderColor: [
                    <?php $__currentLoopData = $monthly_sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(34, 191, 163, 1)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderRadius: 4,
                borderSkipped: 'bottom',
                maxBarThickness: 38,
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true,
                        callback: function(value){ return cur_symbol + value; }
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend: {
                display: false,
                onClick: function() {
                    return '';
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data){
                        return data.datasets[0].label + ': ' + cur_symbol + tooltipItem.yLabel;
                    }
                }
            }
        }
    });

    AIZ.plugins.chart('#pie-1',{
        type: 'doughnut',
        data: {
            labels: [
                '<?php echo e(translate('Published')); ?>',
                '<?php echo e(translate('Seller products')); ?>',
                '<?php echo e(translate('Admin products')); ?>'
            ],
            datasets: [
                {
                    data: [
                        <?php echo e(\App\Models\Product::where('published', 1)->count()); ?>,
                        <?php echo e(\App\Models\Product::where('published', 1)->where('added_by', 'seller')->count()); ?>,
                        <?php echo e(\App\Models\Product::where('published', 1)->where('added_by', 'admin')->count()); ?>

                    ],
                    backgroundColor: [
                        "#fd3995",
                        "#34bfa3",
                        "#5d78ff",
                        '#fdcb6e',
                        '#d35400',
                        '#8e44ad',
                        '#006442',
                        '#4D8FAC',
                        '#CA6924',
                        '#C91F37'
                    ]
                }
            ]
        },
        options: {
            cutoutPercentage: 70,
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true,
                    fontSize: 10
                },
                onClick: function () {
                    return '';
                },
                position: 'bottom'
            }
        }
    });

    AIZ.plugins.chart('#pie-2',{
        type: 'doughnut',
        data: {
            labels: [
                '<?php echo e(translate('Total sellers')); ?>',
                '<?php echo e(translate('Approved sellers')); ?>',
                '<?php echo e(translate('Pending sellers')); ?>'
            ],
            datasets: [
                {
                    data: [
                        <?php echo e(\App\Models\Seller::count()); ?>,
                        <?php echo e(\App\Models\Seller::where('verification_status', 1)->count()); ?>,
                        <?php echo e(\App\Models\Seller::where('verification_status', 0)->count()); ?>

                    ],
                    backgroundColor: [
                        "#fd3995",
                        "#34bfa3",
                        "#5d78ff",
                        '#fdcb6e',
                        '#d35400',
                        '#8e44ad',
                        '#006442',
                        '#4D8FAC',
                        '#CA6924',
                        '#C91F37'
                    ]
                }
            ]
        },
        options: {
            cutoutPercentage: 70,
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true,
                    fontSize: 10
                },
                onClick: function () {
                    return '';
                },
                position: 'bottom'
            }
        }
    });
    AIZ.plugins.chart('#graph-1',{
        type: 'bar',
        data: {
            labels: [
                <?php $__currentLoopData = $root_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                '<?php echo e($category->getTranslation('name')); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            datasets: [{
                label: '<?php echo e(translate('Number of sale')); ?>',
                data: [
                    <?php echo e($cached_graph_data['num_of_sale_data']); ?>

                ],
                backgroundColor: [
                    <?php $__currentLoopData = $root_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(55, 125, 255, 0.4)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderColor: [
                    <?php $__currentLoopData = $root_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(55, 125, 255, 1)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                maxBarThickness: 45,
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend:{
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
            }
        }
    });
    AIZ.plugins.chart('#graph-2',{
        type: 'bar',
        data: {
            labels: [
                <?php $__currentLoopData = $root_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                '<?php echo e($category->getTranslation('name')); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            datasets: [{
                label: '<?php echo e(translate('Number of Stock')); ?>',
                data: [
                    <?php echo e($cached_graph_data['qty_data']); ?>

                ],
                backgroundColor: [
                    <?php $__currentLoopData = $root_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(253, 57, 149, 0.4)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderColor: [
                    <?php $__currentLoopData = $root_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(253, 57, 149, 1)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                maxBarThickness: 45,
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend:{
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/backend/dashboard.blade.php ENDPATH**/ ?>