"use strict";

const get_water_temp = () => {
  return $.ajax({
    // url: "./api/hunelog_api/get_watertemp.php",
    url: "./api/umilog_api/get_watertemp.php",
    type: "POST",
    cache: false
  });
}

const write_chart = () => {
  get_water_temp().then(function (watertemp_data) {
    let time_list = [];
    let top_list = [];
    let middle_list = [];
    let bottom_list = [];
    let datetime;
    let time;
    for (let i=0; i<watertemp_data["time"].length; i++){
      datetime = new Date(watertemp_data["time"][i] * 1000);
      time = datetime.toLocaleTimeString();
      time_list.push(time);
      top_list.push(Number(watertemp_data["top"][i]));
      middle_list.push(Number(watertemp_data["middle"][i]));
      bottom_list.push(Number(watertemp_data["bottom"][i]));
    }
    // console.log(top_list);
    const ctx = document.getElementById("fish_catch_prediction_chart");
    const myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: time_list,
        datasets: [
          {
            label: "表層水温",
            data: top_list,
            tension: 0,
            fill: false,
            borderColor: "#100075",
          },
          {
            label: "中層水温",
            data: middle_list,
            tension: 0,
            fill: false,
            borderColor: "#2b55d4",
          },
          {
            label: "深層水温",
            data: bottom_list,
            tension: 0,
            fill: false,
            borderColor: "#6fc7e4",
          }
        ],
      },
      options: {
        // 
        // elements: {
        //   point: {
        //     radius: 0,
        //   }
        // },
        responsive: false, // canvasサイズ自動設定機能を使わない。HTMLで指定したサイズに固定
        animation: false,
        legend: {
          display: true,
        },
        scales: {
          yAxes: [{
            scaleLabel: {
              display: true, 
              labelString: '水温［℃］',
              fontSize: 17,
              stepSize: 0.1,
            },
            ticks: {
              fontSize: 17,
            }
          }],
          xAxes: [{
            type: 'time',
              time: {
                parser: 'HH:mm',
                unit: 'hour',
                stepSize: 1,
                displayFormats: {
                  'hour': 'HH:mm'
                }
              },
            ticks: {
              min: '00:00',
              max: '23:00',
              fontSize: 17,
            }
          }]
        },
      }
    });
    myChart.update();
  });
}

const temp_chart = () => {
  write_chart();
}

export default temp_chart;