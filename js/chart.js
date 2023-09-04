'use strict';

const mu = 0; // 平均
const sigma = 3.5; // 標準偏差
const xmin = -14; // x軸の最低
const xmax = 14; // x軸の最高
const nx = 28; // 点の数



const UnixtimeToDatetime = (unixtime) => {
  const datetime = new Date(parseInt(unixtime, 10) * 1000); // ユニックスタイムから時間に変更
  const year = datetime.getFullYear();
  let month = datetime.getMonth()+1;
  let date = datetime.getDate();
  month = ( '00' + month ).slice( -2 );
  date = ( '00' + date ).slice( -2 );

  return `${month}-${date}`;
}

const TodayDate = () => {
  const datetime = new Date(); // 現在時刻取得
  const year = datetime.getFullYear();
  let month = datetime.getMonth()+1;
  let date = datetime.getDate();
  month = ( '00' + month ).slice( -2 );
  date = ( '00' + date ).slice( -2 );

  return `${month}-${date}`;
}


const setMonth = (new_moon_date) => {
  const new_moon_unix = Date.parse(new_moon_date) / 1000;
  let month_list = [];
  for(let i=0; i<29; i++){
    let minus_day = i - 14;
    let unix = new_moon_unix + minus_day * 24 * 60 * 60;
    month_list.push(UnixtimeToDatetime(unix));
  }
  return month_list;
}


const calc_func = (max_fish_catch) => {
  
  const data = [];
  const result_data = [];
  let y_max = -1;
  for(let i=0; i<nx+1; i++) {
    let x = xmin+(xmax-xmin)/nx*i;
    let y = 1.0/Math.sqrt(2.0*Math.PI*sigma*sigma)*Math.exp(-1.0*(x-mu)*(x-mu)/(2.0*sigma*sigma));
    data.push(y);
    if(y > y_max)
      y_max = y;
  }

  for(let i=0; i<nx+1; i++) {
    result_data.push(data[i] / y_max * max_fish_catch);
  }
  return result_data;
}

const plotChart = () => {
  const ctx = document.getElementById("fish_catch_prediction_chart");
  const new_moon_date = "2022-05-01";
  const max_fish_catch = 30; // 30[t]
  const month_list = setMonth(new_moon_date);
  const data_list = calc_func(max_fish_catch);
  const today_index = month_list.indexOf(TodayDate());
  // console.log(today_index);
  // console.log(data_list);

  const myChart = new Chart(ctx, {
    type: 'line',
    data: {
      lineAtIndex: today_index,
      labels: month_list,
      datasets: [
        {
          data: data_list,
          borderColor: "#63AFF4",
          backgroundColor: "rgba(0,0,0,0)",
        },
      ],
      
    },
    options: {
      elements: {
        point: {
          radius: 0,
        }
      },
      responsive: false, // canvasサイズ自動設定機能を使わない。HTMLで指定したサイズに固定
      animation: false,
      legend: {
        display: false,
      },
      scales: {
        yAxes: [{
          scaleLabel: {
            display: true, 
            labelString: '漁獲量［t］',
            fontSize: 17,
          },
          ticks: {
            fontSize: 17,
          }
        }],
        xAxes: [{
          ticks: {
            min: xmin,
            max: xmax,
            fontSize: 17,
          }
        }]
      },
    }
  });
  myChart.update();
}

/********************* 任意の縦線を引く為の処理 ***********************/
var originalLineDraw = Chart.controllers.line.prototype.draw;
Chart.helpers.extend(Chart.controllers.line.prototype, {
  draw: function(){
    originalLineDraw.apply(this, arguments);
    const chart = this.chart;
    const ctx = chart.chart.ctx;
    const index = chart.config.data.lineAtIndex;
    if (index){
      const xaxis = chart.scales['x-axis-0'];
      const yaxis = chart.scales['y-axis-0'];
      ctx.save();
      ctx.beginPath();
      ctx.moveTo(xaxis.getPixelForValue(undefined, index), yaxis.top);
      ctx.strokeStyle = '#ff0000';
      ctx.lineWidth = 2;
      ctx.lineTo(xaxis.getPixelForValue(undefined, index), yaxis.bottom);
      ctx.stroke();
      ctx.restore();
    }
  }
});


export default plotChart;
