var bandwidthChart1 = function () {
    
    if ($('#kt_chart_bandwidth1').length == 0) {
        return;
    }

    var ctx = document.getElementById("kt_chart_bandwidth1").getContext("2d");

    var gradient = ctx.createLinearGradient(0, 0, 0, 240);
    gradient.addColorStop(0, Chart.helpers.color('#d1f1ec').alpha(1).rgbString());
    gradient.addColorStop(1, Chart.helpers.color('#d1f1ec').alpha(0.3).rgbString());

    var config = {
        type: 'line',
        data: {
            // labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
            labels: JSON.parse(month.replace(/&quot;/g, '"')),
            datasets: [{
                label: "Revenue",
                backgroundColor: gradient,
                borderColor: KTApp.getStateColor('success'),

                pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                pointHoverBackgroundColor: KTApp.getStateColor('danger'),
                pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),

                //fill: 'start',
                data: JSON.parse(value)
            }]
        },
        options: {
            title: {
                display: false,
            },
            tooltips: {
                mode: 'nearest',
                intersect: false,
                position: 'nearest',
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    display: false,
                    gridLines: false,
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: false,
                    gridLines: false,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            elements: {
                line: {
                    tension: 0.0000001
                },
                point: {
                    radius: 4,
                    borderWidth: 12
                }
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 10,
                    bottom: 0
                }
            }
        }
    };

    var chart = new Chart(ctx, config);
}


// Class initialization on page load
jQuery(document).ready(function () {
    bandwidthChart1();

    $('#dateRangePicker').daterangepicker({
        buttonClasses: ' btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary',

        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function (start, end, label) {
        $('input[name="start-date"]').val(start.format('YYYY-MM-DD'))
        $('input[name="end-date"]').val(end.format('YYYY-MM-DD'))
        $('#revenue-form').submit();

    });
});
