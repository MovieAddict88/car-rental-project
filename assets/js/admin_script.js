/* globals Chart:false */

(() => {
  'use strict'

  // Revenue Chart
  const revenueChartCanvas = document.getElementById('revenueChart');
  if (revenueChartCanvas) {
    const ctx = revenueChartCanvas.getContext('2d');

    // The data is passed from PHP via script tags in the dashboard file
    // We need to make sure the global variables `chartLabels` and `chartValues` exist
    if (typeof chartLabels !== 'undefined' && typeof chartValues !== 'undefined') {
      const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: chartLabels,
          datasets: [{
            label: 'Revenue (PHP)',
            data: chartValues,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                // Format the ticks to include '₱'
                callback: function(value, index, values) {
                  return '₱' + new Intl.NumberFormat().format(value);
                }
              }
            }
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.dataset.label || '';
                  if (label) {
                    label += ': ';
                  }
                  if (context.parsed.y !== null) {
                    label += '₱' + new Intl.NumberFormat().format(context.parsed.y);
                  }
                  return label;
                }
              }
            }
          }
        }
      });
    }
  }
})()