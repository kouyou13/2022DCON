'use strict';
const today_time_ele = document.getElementById("time");
const place_ele = document.getElementById("place");

const LoadTime = () => {
  const now = new Date();
  // URLを取得
  const url = new URL(window.location.href);
  // URLSearchParamsオブジェクトを取得
  const params = url.searchParams;

  const year = now.getFullYear();
  let month = now.getMonth() + 1;
  let date =  now.getDate();
  let hour = now.getHours();
  let min = now.getMinutes();
  let sec = now.getSeconds();
  month = ( '00' + month ).slice( -2 );
  date = ( '00' + date ).slice( -2 );
  hour = ( '00' + hour ).slice( -2 );
  min = ( '00' + min ).slice( -2 );
  sec = ( '00' + sec ).slice( -2 );
  return `${year}-${month}-${date} ${hour}:${min}:${sec}`;
}

const setTime = () => {
  today_time_ele.innerHTML = LoadTime();
}

const header = (place) => {
  // console.log(LoadTime_temp())
  place_ele.textContent = place;
  setTime();
  //1000ミリ秒（1秒）毎に関数「LoadTime()」を呼び出す
  setInterval(setTime, 1000);
}

export default header;