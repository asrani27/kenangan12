<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Kenangan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CanvasJS -->
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Pattern Background */
        .pattern-bg {
            background-color: #0f172a;
            background-image:
                radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(339, 49%, 30%, 1) 0, transparent 50%);
            position: relative;
        }

        .pattern-bg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .card-gradient {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link:hover {
            color: #a78bfa;
        }

        .nav-link.active {
            color: #c4b5fd;
            border-bottom: 2px solid #a78bfa;
        }
    </style>
</head>

<body class="pattern-bg min-h-screen text-white">
    <div class="relative z-10">

        @include('layouts.navigation', ['activePage' => 'dashboard'])

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
                <p class="text-slate-400">Monitoring Realisasi Anggaran Tahun 2026</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Realisasi Keuangan -->
                <div class="card-gradient rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-5xl font-bold text-green-400 mb-2">0<span class="text-2xl">%</span></h3>
                        <p class="text-slate-300 font-semibold text-lg">REALISASI KEUANGAN</p>
                    </div>
                </div>

                <!-- Realisasi Fisik -->
                <div class="card-gradient rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-red-400 to-rose-600 mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-5xl font-bold text-red-400 mb-2">0<span class="text-2xl">%</span></h3>
                        <p class="text-slate-300 font-semibold text-lg">REALISASI FISIK</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Line Chart -->
                <div class="card-gradient rounded-2xl p-6">
                    <h3 class="text-xl font-semibold mb-4 text-slate-200">Realisasi Fisik Dan Keuangan 2026</h3>
                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                </div>

                <!-- Column Chart -->
                <div class="card-gradient rounded-2xl p-6">
                    <h3 class="text-xl font-semibold mb-4 text-slate-200">Periode Januari 2026</h3>
                    <div id="chartContainer2" style="height: 300px; width: 100%;"></div>
                </div>
            </div>

            <!-- Pie Chart and Progress -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Pie Chart -->
                <div class="card-gradient rounded-2xl p-6">
                    <h3 class="text-xl font-semibold mb-4 text-slate-200">Kategori Capaian SKPD Triwulan I</h3>
                    <div id="chartContainer3" style="height: 320px; width: 100%;"></div>
                </div>

                <!-- Target & Progress -->
                <div class="card-gradient rounded-2xl p-6">
                    <h3 class="text-xl font-semibold mb-6 text-slate-200">Target Penyerapan Anggaran</h3>

                    <!-- Table -->
                    <div class="overflow-hidden rounded-lg mb-6">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-800/50">
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-300">Triwulan</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-slate-300">Target</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700">
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-sm text-slate-300">Triwulan I</td>
                                    <td class="px-4 py-3 text-right text-sm text-slate-300">25 %</td>
                                </tr>
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-sm text-slate-300">Triwulan II</td>
                                    <td class="px-4 py-3 text-right text-sm text-slate-300">50 %</td>
                                </tr>
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-sm text-slate-300">Triwulan III</td>
                                    <td class="px-4 py-3 text-right text-sm text-slate-300">75 %</td>
                                </tr>
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-sm text-slate-300">Triwulan IV</td>
                                    <td class="px-4 py-3 text-right text-sm text-slate-300">100 %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Progress Bars -->
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-slate-300">Realisasi Fisik</span>
                                <span class="text-sm font-medium text-red-400">75%</span>
                            </div>
                            <div class="h-3 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-red-500 to-rose-600 rounded-full transition-all duration-1000"
                                    style="width: 75%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-slate-300">Realisasi Keuangan</span>
                                <span class="text-sm font-medium text-blue-400">50%</span>
                            </div>
                            <div class="h-3 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-1000"
                                    style="width: 50%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SKPD Table -->
            <div class="card-gradient rounded-2xl p-6">
                <h3 class="text-xl font-semibold mb-6 text-slate-200">Data SKPD</h3>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-600/20 to-indigo-600/20">
                                <th class="px-4 py-4 text-left text-sm font-semibold text-slate-200">No</th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-slate-200">Kode</th>
                                <th class="px-4 py-4 text-left text-sm font-semibold text-slate-200">SKPD</th>
                                <th class="px-4 py-4 text-center text-sm font-semibold text-slate-200">Keuangan</th>
                                <th class="px-4 py-4 text-center text-sm font-semibold text-slate-200">Fisik</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            {{-- @foreach ($skpd as $key => $item)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-300">{{ $key + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-slate-300">{{ $item->kode_skpd }}</td>
                                <td class="px-4 py-3 text-sm text-slate-300">{{ $item->nama }}</td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400">
                                        50 %
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">
                                        75 %
                                    </span>
                                </td>
                            </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        @include('layouts.footer')

    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Chart initialization
        window.onload = function() {
            // Line Chart
            var chart = new CanvasJS.Chart("chartContainer", {
                theme: "dark2",
                title: {
                    text: "Realisasi Fisik Dan Keuangan 2026",
                    fontColor: "#ffffff"
                },
                axisX: {
                    interval: 1,
                    intervalType: "month",
                    valueFormatString: "MMM",
                    labelFontColor: "#94a3b8"
                },
                axisY: {
                    labelFontColor: "#94a3b8"
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor: "pointer",
                    verticalAlign: "bottom",
                    horizontalAlign: "center",
                    dockInsidePlotArea: true,
                    itemclick: toogleDataSeries,
                    fontColor: "#ffffff"
                },
                data: [{
                        type: "line",
                        name: "Keuangan",
                        showInLegend: true,
                        markerSize: 0,
                        yValueFormatString: "Rp,#,###k",
                        color: "#3b82f6",
                        dataPoints: [{
                                x: new Date(2014, 00, 01),
                                y: 850
                            },
                            {
                                x: new Date(2014, 01, 01),
                                y: 889
                            },
                            {
                                x: new Date(2014, 02, 01),
                                y: 890
                            },
                            {
                                x: new Date(2014, 03, 01),
                                y: 899
                            },
                            {
                                x: new Date(2014, 04, 01),
                                y: 903
                            },
                            {
                                x: new Date(2014, 05, 01),
                                y: 925
                            },
                            {
                                x: new Date(2014, 06, 01),
                                y: 899
                            },
                            {
                                x: new Date(2014, 07, 01),
                                y: 875
                            },
                            {
                                x: new Date(2014, 08, 01),
                                y: 927
                            },
                            {
                                x: new Date(2014, 09, 01),
                                y: 949
                            },
                            {
                                x: new Date(2014, 10, 01),
                                y: 946
                            },
                            {
                                x: new Date(2014, 11, 01),
                                y: 927
                            },
                        ]
                    },
                    {
                        type: "line",
                        name: "Fisik",
                        showInLegend: true,
                        markerSize: 0,
                        yValueFormatString: "Rp,#,###k",
                        color: "#ef4444",
                        dataPoints: [{
                                x: new Date(2014, 00, 01),
                                y: 1200
                            },
                            {
                                x: new Date(2014, 01, 01),
                                y: 1200
                            },
                            {
                                x: new Date(2014, 02, 01),
                                y: 1200
                            },
                            {
                                x: new Date(2014, 03, 01),
                                y: 1180
                            },
                            {
                                x: new Date(2014, 04, 01),
                                y: 1250
                            },
                            {
                                x: new Date(2014, 05, 01),
                                y: 1270
                            },
                            {
                                x: new Date(2014, 06, 01),
                                y: 1300
                            },
                            {
                                x: new Date(2014, 07, 01),
                                y: 1300
                            },
                            {
                                x: new Date(2014, 08, 01),
                                y: 1358
                            },
                            {
                                x: new Date(2014, 09, 01),
                                y: 1410
                            },
                            {
                                x: new Date(2014, 10, 01),
                                y: 1480
                            },
                            {
                                x: new Date(2014, 11, 01),
                                y: 1500
                            }
                        ]
                    }
                ]
            });
            chart.render();

            // Column Chart
            var chart2 = new CanvasJS.Chart("chartContainer2", {
                animationEnabled: true,
                theme: "dark2",
                title: {
                    text: "Periode Maret 2026",
                    fontColor: "#ffffff"
                },
                axisY: {
                    labelFontColor: "#94a3b8"
                },
                axisX: {
                    labelFontColor: "#94a3b8"
                },
                data: [{
                    type: "column",
                    indexLabel: "{y}",
                    legendMarkerColor: "grey",
                    indexLabelFontColor: "#ffffff",
                    dataPoints: [{
                            y: 0,
                            label: "Pagu",
                            color: "#8b5cf6"
                        },
                        {
                            y: 0,
                            label: "Rencana",
                            color: "#a78bfa"
                        },
                        {
                            y: 0,
                            label: "Realisasi",
                            color: "#c4b5fd"
                        },
                    ]
                }]
            });
            chart2.render();

            // Pie Chart
            var chart3 = new CanvasJS.Chart("chartContainer3", {
                animationEnabled: true,
                theme: "dark2",
                title: {
                    text: "Kategori Capaian SKPD Triwulan I",
                    fontColor: "#ffffff"
                },
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie,
                    fontColor: "#ffffff"
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{name}: <strong>{y} SKPD </strong>",
                    indexLabel: "{name} - {y} SKPD ",
                    indexLabelFontColor: "#ffffff",
                    dataPoints: [{
                            y: 6,
                            name: "SANGAT BAIK",
                            color: "#22c55e"
                        },
                        {
                            y: 20,
                            name: "BAIK",
                            color: "#3b82f6"
                        },
                        {
                            y: 5,
                            name: "KURANG",
                            color: "#f97316"
                        },
                        {
                            y: 3,
                            name: "CUKUP",
                            color: "#eab308"
                        }
                    ]
                }]
            });
            chart3.render();

            function explodePie(e) {
                if (typeof(e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
                } else {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
                }
                e.chart.render();
            }

            function toogleDataSeries(e) {
                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                chart.render();
            }
        }
    </script>

</body>

</html>