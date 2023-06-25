<?php
$amount_now      = $amount_now->amount | 0;
$amount_last_day = $amount_last_day->amount | 0;
$amount_month      = $amount_month->amount | 0;
$amount_last_month = $amount_last_month->amount | 0;

if ($amount_last_day != 0) {
    $persentase_day = (($amount_now - $amount_last_day) / $amount_last_day) * 100;
} else {
    if ($amount_now) {
        $persentase_day = 100;
    } else {
        $persentase_day = 0;
    }
}

// var_dump($amount_last_month);
// die;

if ($amount_last_month != 0) {
    $persentase_month = (($amount_now - $amount_last_month) / $amount_last_month) * 100;
} else {
    if ($amount_month) {
        $persentase_month = 100;
    } else {
        $persentase_month = 0;
    }
}

?>

<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div id='aa'>
                            <span class="fw-semibold d-block mb-1">Penjualan Hari Ini</span>
                            <h3 class="card-title mb-1"><?= "Rp. " . number_format($amount_now, 0, ',', '.'); ?></h3>
                            <small class="fw-semibold">
                                <span>vs Kemarin : </span>
                                <span class="<?php if ($persentase_day == 0) {
                                                    echo "";
                                                } else if ($persentase_day < 0) {
                                                    echo "text-danger";
                                                } else {
                                                    echo "text-green";
                                                } ?>">
                                    <i class="bx <?php if ($persentase_day == 0) {
                                                        echo "";
                                                    } else if ($persentase_day < 0) {
                                                        echo "bx-trending-down";
                                                    } else {
                                                        echo "bx-trending-up";
                                                    } ?>"></i>
                                    <?= round($persentase_day, 2) ?> %
                                </span>
                            </small>
                        </div>
                        <i class='bx bx-wallet-alt' style="font-size:50px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div id='aa'>
                            <span class="fw-semibold d-block mb-1">Penjualan bulan ini</span>
                            <h3 class="card-title mb-1"><?= "Rp. " . number_format($amount_month, 0, ',', '.'); ?></h3>
                            <small class="fw-semibold">
                                <span>vs Bulan Kemarin : </span>
                                <span class="<?php if ($persentase_month == 0) {
                                                    echo "";
                                                } else if ($persentase_month < 0) {
                                                    echo "text-danger";
                                                } else {
                                                    echo "text-green";
                                                } ?>">
                                    <i class="bx <?php if ($persentase_month == 0) {
                                                        echo "";
                                                    } else if ($persentase_month < 0) {
                                                        echo "bx-trending-down";
                                                    } else {
                                                        echo "bx-trending-up";
                                                    } ?>"></i>
                                    <?= round($persentase_month, 2) ?> %
                                </span>
                            </small>
                        </div>
                        <div class="bx bx-wallet" style="font-size:50px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div id='aa'>
                            <span class="fw-semibold d-block mb-1">Produk hampir habis</span>
                            <h3 class="card-title mb-1"><?= $stock_limit->stock_limit ?></h3>
                            <small class="text-danger fw-semibold"> <?= $stock_limit->out_of_stock ?> Produk habis</small>
                        </div>
                        <div class="bx bx-package" style="font-size:50px;"></div>
                    </div>
                </div>
            </div>

            <dic class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="card-title d-flex justify-content-between">
                            <h5 class="mb-0"><i class='bx bx-bar-chart-alt'></i> Statistik Penjualan</h5>
                            <h5 class="fw-bold">2023</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="chart-income">

                        </div>
                    </div>
                </div>
            </dic>
            <dic class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="card-title">
                            <h5 class="mb-0 d-flex align-items-center"><i class='bx bx-basket me-1'></i> Top Produk</h5>
                        </div>
                    </div>
                    <div class="card-body" style="height:355px; overflow:auto">
                        <p class="d-block mb-3" style="font-size:14px">Ini adalah produk dengan jumlah penjualan tertinggi di bulan ini.</p>

                        <?php if (count($top_product_sold)) : ?>
                            <?php foreach ($top_product_sold as $key => $product) : ?>
                                <div class="d-flex align-items-center <?= count($top_product_sold) != $key + 1 ? "mb-2" : "" ?>">
                                    <img class="w-100 h-100 me-2 mb-0 rounded" src="<?= base_url() ?>public/image/products/<?= $product->image ?>" style="max-width:80px;max-height: 55px;object-fit: cover;">
                                    <div>
                                        <small class="line-clamp-2 mb-0"><?= $product->name ?></small>
                                        <small>Terjual : <?= $product->soldout ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="w-100 h-50 border rounded bg-light d-flex justify-content-center align-items-center text-center">
                                Belum ada produk terjual<br> bulan ini 🥹
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </dic>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>public/back/assets/vendor/libs/sweetalert/sweetalert2.js"></script>
<script src="<?= base_url() ?>public/back/assets/vendor/libs/apex-charts/apexcharts.js"></script>

<script>
    $(document).ready(function() {
        const chart = document.getElementById('chart-income')
        const dataIncome = [];

        <?php for ($i = 1; $i <= 12; $i++) : ?>
            <?php foreach ($sales as $item) : ?>
                <?php if ($i == $item->month) : ?>
                    dataIncome.push(<?= $item->total_sales ?>)
                <?php else : ?>
                    dataIncome.push(0)
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endfor; ?>

        const max = dataIncome[dataIncome.indexOf(Math.max(...dataIncome))];

        const chartIncome = {
            series: [{
                name: 'Total pendapatan',
                data: dataIncome,
            }],
            chart: {
                height: 315,
                parentHeightOffset: 0,
                parentWidthOffset: 0,
                toolbar: {
                    show: !1
                },
                type: "area"
            },
            dataLabels: {
                enabled: !1
            },
            stroke: {
                width: 2,
                curve: "smooth"
            },
            legend: {
                show: !1
            },
            markers: {
                size: 6,
                colors: "transparent",
                strokeColors: "transparent",
                strokeWidth: 4,
                discrete: [{
                    fillColor: config.colors.white,
                    seriesIndex: 0,
                    dataPointIndex: new Date().getMonth(),
                    strokeColor: config.colors.primary,
                    strokeWidth: 2,
                    size: 6,
                    radius: 8
                }],
                hover: {
                    size: 7
                }
            },
            colors: [config.colors.primary],
            fill: {
                type: "gradient",
                gradient: {
                    shade: void 0,
                    shadeIntensity: .6,
                    opacityFrom: .5,
                    opacityTo: .25,
                    stops: [0, 95, 100]
                }
            },
            grid: {
                borderColor: config.colors.borderColor,
                strokeDashArray: 3,
                padding: {
                    top: 0,
                    bottom: -10,
                    left: 0,
                    right: 0,
                }
            },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                axisBorder: {
                    show: !1
                },
                axisTicks: {
                    show: !1
                },
                labels: {
                    show: !0,
                    style: {
                        fontSize: "13px",
                        colors: config.colors.axisColor
                    }
                }
            },
            yaxis: {
                labels: {
                    show: !1,
                    formatter: v => formatRupiah(v),
                },
                max: max * 1.5,
                tickAmount: 5,
            }
        };

        if (null !== chart) {
            const p = new ApexCharts(chart, chartIncome);
            p.render()
        }
    });
</script>