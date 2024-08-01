/**
 * Dashboard Analytics
 */

'use strict';

(function () {
  let cardColor, headingColor, axisColor, shadeColor, borderColor;

  cardColor = config.colors.white;
  headingColor = config.colors.headingColor;
  axisColor = config.colors.axisColor;
  borderColor = config.colors.borderColor;

  $.ajax({
    url: 'trafik.php', 
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      let aylar = [];
      let trafik = [];

      data.forEach(function (item) {
        aylar.push(item.month);
        trafik.push(item.count);
      });

      console.log(aylar)
      console.log(trafik)

      const incomeChartEl = document.querySelector('#incomeChart');
      const incomeChartConfig = {
        series: [
          {
            data: trafik
          }
        ],
        chart: {
          height: 215,
          parentHeightOffset: 0,
          parentWidthOffset: 0,
          toolbar: {
            show: false
          },
          type: 'area'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: 2,
          curve: 'smooth'
        },
        legend: {
          show: false
        },
        markers: {
          size: 6,
          colors: 'transparent',
          strokeColors: 'transparent',
          strokeWidth: 4,
          discrete: [
            {
              fillColor: config.colors.white,
              seriesIndex: 0,
              dataPointIndex: 7,
              strokeColor: config.colors.primary,
              strokeWidth: 2,
              size: 6,
              radius: 8
            }
          ],
          hover: {
            size: 7
          }
        },
        colors: [config.colors.primary],
        fill: {
          type: 'gradient',
          gradient: {
            shade: shadeColor,
            shadeIntensity: 0.6,
            opacityFrom: 0.5,
            opacityTo: 0.25,
            stops: [0, 95, 100]
          }
        },
        grid: {
          borderColor: borderColor,
          strokeDashArray: 3,
          padding: {
            top: -20,
            bottom: -8,
            left: -10,
            right: 8
          }
        },
        xaxis: {
          categories: aylar,
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          labels: {
            show: true,
            style: {
              fontSize: '13px',
              colors: axisColor
            }
          }
        },
        yaxis: {
          labels: {
            show: false
          },
          min: 10,
          max: 50,
          tickAmount: 4
        }
      };

      if (typeof incomeChartEl !== undefined && incomeChartEl !== null) {
        const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
        incomeChart.render();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error('Veri alınırken bir hata oluştu:', textStatus, errorThrown);
    }
  });

})();
