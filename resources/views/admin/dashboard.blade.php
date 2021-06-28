@extends('layouts.master')

@section('title','DESForm Dashboard')

@section('db')
    <a class="navbar-brand" href="#pablo">Dashboard</a>
@endsection

@section('dashboardpanel')
    <div class="panel-header panel-header-lg">
        <canvas id="bigDashboardChart"></canvas>
      </div>
@endsection

@section('content')
<div class="row">
    {{-- <div class="col-lg-4 col-md-6">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category"><strong>Komposisi Jenis Pengguna DESForm</strong></h5>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="canvas" ></canvas>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="col-lg-4 col-md-6">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category"><strong>Jumlah Form yang Sudah Dibuat</strong></h5>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="canvas2"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category"><strong>Status Seluruh Form</strong></h5>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="canvas3" ></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
$(function() {
  chartColor = "#FFFFFF";

        // General configuration for the charts with Line gradientStroke
        gradientChartOptionsConfiguration = {
            maintainAspectRatio: false,
            legend: {
                display: false,
            },
            tooltips: {
                bodySpacing: 4,
                mode: "nearest",
                intersect: 0,
                position: "nearest",
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10,
            },
            responsive: 1,
            scales: {
                yAxes: [
                    {
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false,
                            drawBorder: false,
                        },
                    },
                ],
                xAxes: [
                    {
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false,
                            drawBorder: false,
                        },
                    },
                ],
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 15,
                    bottom: 15,
                },
            },
        };

        gradientChartOptionsConfigurationWithNumbersAndGrid = {
            maintainAspectRatio: false,
            legend: {
                display: false,
            },
            tooltips: {
                bodySpacing: 4,
                mode: "nearest",
                intersect: 0,
                position: "nearest",
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10,
            },
            responsive: true,
            scales: {
                yAxes: [
                    {
                        gridLines: 0,
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawBorder: false,
                        },
                    },
                ],
                xAxes: [
                    {
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false,
                            drawBorder: false,
                        },
                    },
                ],
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 15,
                    bottom: 15,
                },
            },
        };

        var ctx = document.getElementById("bigDashboardChart").getContext("2d");

        var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
        gradientStroke.addColorStop(0, "#80b6f4");
        gradientStroke.addColorStop(1, chartColor);

        var gradientFill = ctx.createLinearGradient(0, 200, 0, 50);
        gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
        gradientFill.addColorStop(1, "rgba(255, 255, 255, 0.24)");

        var myChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ['Jenis Pengguna'],
                datasets: [
                    {
                        label: "Pengguna",
                        borderColor: chartColor,
                        pointBorderColor: chartColor,
                        pointBackgroundColor: "#1e3d60",
                        pointHoverBackgroundColor: "#1e3d60",
                        pointHoverBorderColor: chartColor,
                        pointBorderWidth: 1,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 2,
                        pointRadius: 5,
                        fill: true,
                        backgroundColor: gradientFill,
                        borderWidth: 2,
                        data: [{!!$men_learning!!}],
                    },
                    {
                        label: 'Admin',
                        data: [{!!$women_learning!!}],
                        borderColor: chartColor,
                        pointBorderColor: chartColor,
                        pointBackgroundColor: "#1e3d60",
                        pointHoverBackgroundColor: "#1e3d60",
                        pointHoverBorderColor: chartColor,
                        pointBorderWidth: 1,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 2,
                        pointRadius: 5,
                        fill: true,
                        backgroundColor: gradientFill,
                        borderWidth: 2,
                    }

                ],
            },
            options: {
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 0,
                        bottom: 0,
                    },
                },
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "#fff",
                    titleFontColor: "#333",
                    bodyFontColor: "#666",
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                },
                legend: {
                    position: "bottom",
                    fillStyle: "#FFF",
                    display: true,
                },
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                fontColor: "rgba(255,255,255,0.4)",
                                fontStyle: "bold",
                                beginAtZero: true,
                                maxTicksLimit: 5,
                                padding: 10,
                            },
                            gridLines: {
                                drawTicks: true,
                                drawBorder: true,
                                display: true,
                                color: "rgba(255,255,255,0.1)",
                                zeroLineColor: "transparent",
                            },
                        },
                    ],
                    xAxes: [
                        {
                            gridLines: {
                                zeroLineColor: "transparent",
                                display: true,
                            },
                            ticks: {
                                padding: 10,
                                fontColor: "rgba(255,255,255,0.4)",
                                fontStyle: "bold",
                            },
                        },
                    ],
                },
            },
        });
    });

//   $(function(){
//     var ctx = document.getElementById("canvas").getContext('2d');

//     var myChart = new Chart(ctx, {
//       type: 'bar',
//       data: {
//         labels: ['Jenis Pengguna'],
//         datasets: [{
//           label: 'Pengguna',
//           data: [{!!$men_learning!!}],
//           backgroundColor: 'rgba(40,167,69,1)',
//           borderColor: 'transparent',
//         },{
//           label: 'Admin',
//           data: [{!!$women_learning!!}],
//            backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)'],
//            borderColor: ['rgb(255, 99, 132)'],
//         }]
//       },
//       options: {
//         legend: {
//           display: false
//         },
//         scales: {
//           yAxes: [{
//             gridLines: {
//               display: true,
//               drawBorder: false,
//               color: '#f2f2f2',
//             },
//             ticks: {
//               beginAtZero: true,
//               callback: function(value, index, values) {
//                 return value;
//               }
//             }
//           }],
//           xAxes: [{
//             gridLines: {
//               display: false,
//               tickMarkLength: 15,
//             }
//           }]
//         },
//       }
//     });
// });

$(function(){
    var ctx = document.getElementById("canvas2").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Jumlah Form'],
        datasets: [{
          data: [{!!$form!!}],
          backgroundColor: 'rgba(40,167,69,1)',
          borderColor: 'transparent',
        }]
      },
      options: {
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              display: true,
              drawBorder: false,
              color: '#f2f2f2',
            },
            ticks: {
              beginAtZero: true,
              callback: function(value, index, values) {
                return value;
              }
            }
          }],
          xAxes: [{
            gridLines: {
              display: false,
              tickMarkLength: 15,
            }
          }]
        },
      }
    });
});

$(function(){
    var ctx = document.getElementById("canvas3").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Status Form'],
        datasets: [{
          label: 'Terbuka',
          data: [{!!$statusopen!!}],
          backgroundColor: 'rgba(40,167,69,1)',
          borderColor: 'transparent',
        },{
          label: 'Draft',
          data: [{!!$statusdraft!!}],
           backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)'],
           borderColor: ['rgb(255, 99, 132)'],
        },{
          label: 'Pending',
          data: [{!!$statuspending!!}],
           backgroundColor: ['rgb(55, 99, 132)', 'rgba(216, 216, 55, 0.87)', 'rgb(60, 79, 213)', 'rgb(75, 138, 239)'],
           borderColor: ['rgb(255, 99, 132)'],
        },{
          label: 'Tertutup',
          data: [{!!$statusclosed!!}],
           backgroundColor: ['rgb(25, 99, 132)', 'rgba(156, 186, 255, 0.87)', 'rgb(160, 179, 13)', 'rgb(15, 238, 239)'],
           borderColor: ['rgb(155, 199, 132)'],
        }]
      },
      options: {
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              display: true,
              drawBorder: false,
              color: '#f2f2f2',
            },
            ticks: {
              beginAtZero: true,
              callback: function(value, index, values) {
                return value;
              }
            }
          }],
          xAxes: [{
            gridLines: {
              display: false,
              tickMarkLength: 15,
            }
          }]
        },
      }
    });
});

</script>


@endsection
