"use strict";
// let img_count_water_img = 0;
// let img_count_mirine_img = 0;
// let img_count = 0;
// let img_url;
// let unixtime_str;
// let datetime;
// let device_id_list = [-1, -1]; // 船ログの水中カメラ，水上カメラ
// let parameter = {};

// const GetHunelogImgData = (parameter) => {
//   return $.ajax({
//     url: "./api/get_hunelog_img_list.php",
//     type: "POST",
//     data: parameter,
//     catch: false
//   });
// }

// const UnixtimeToDatetime = (unixtime_str) => {
//   datetime = new Date(parseInt(unixtime_str, 10) * 1000); // ユニックスタイムから時間に変更
//   const year = datetime.getFullYear();
//   let month = datetime.getMonth()+1;
//   let date = datetime.getDate();
//   let hour = datetime.getHours();
//   let min = datetime.getMinutes();
//   let sec = datetime.getSeconds();
//   month = ( '00' + month ).slice( -2 );
//   date = ( '00' + date ).slice( -2 );
//   hour = ( '00' + hour ).slice( -2 );
//   min = ( '00' + min ).slice( -2 );
//   sec = ( '00' + sec ).slice( -2 );

//   return `${year}-${month}-${date} ${hour}:${min}:${sec}`;
// }


// const setMarineImg = (img_list, device_list) => {
//   const marine_image_ele = document.getElementById("marine_image");
//   const underwater_image_ele = document.getElementById("underwater_image");
//   const marine_image_time_ele = document.getElementById("marine_image_time");
//   const underwater_image_time_ele = document.getElementById("underwater_image_time");
//   for (let i=0; i<device_id_list.length; i++){
//     if (img_list[i]["device_id"] != -1 && img_list[i]["data"].length != 0){
//       if (img_list[i]["device_id"] == device_list[0])
//         img_count = img_count_water_img;
//       else if (img_list[i]["device_id"] == device_list[1])
//         img_count = img_count_mirine_img;
      
//       img_url = img_list[i]["data"][img_count].replace("/home/users/1/littlestar.jp-ezaki-lab/web/", "http://ezaki-lab.littlestar.jp/");
//       unixtime_str = img_list[i]["data"][img_count].slice(87);
//       unixtime_str = unixtime_str.replace(".jpeg", "");
//       datetime = UnixtimeToDatetime(unixtime_str);
  
//       // 水中カメラの画像セット
//       if (img_list[i]["device_id"] == device_list[0]){
//         underwater_image_ele.src = `./api/small_image.php?file_name=${img_url}`;
//         underwater_image_time_ele.textContent = datetime;
//         if (img_count == img_list[i]["data"].length-1)
//           img_count_water_img = 0;
//         else
//           img_count_water_img += 1;
//       }
//       // 水上カメラの画像セット
//       else if (img_list[i]["device_id"] == device_list[1]){
//         marine_image_ele.src = `./api/small_image.php?file_name=${img_url}`;
//         marine_image_time_ele.textContent = datetime;
//         if (img_count == img_list[i]["data"].length-1)
//           img_count_mirine_img = 0;
//         else
//           img_count_mirine_img += 1;
//           // location.reload(); // 画像が1周したらリロード
//       }
//     }
    
//   }
// }

let count = 0
const temp_ = () => {
  const marin = ["./images/temp_img/b1.jpeg", "./images/temp_img/b2.jpeg", "./images/temp_img/b3.jpeg"];
  const water = ["./images/temp_img/a1.jpeg", "./images/temp_img/a2.jpeg", "./images/temp_img/a3.jpeg"];
  const marin_time = ["2022-03-20 17:05:22", "2022-03-20 17:05:30", "2022-03-20 17:05:35"];
  const watr_time = ["2022-03-20 17:10:20", "2022-03-20 17:10:26", "2022-03-20 17:10:31"];
  // const pre_time = ["2022-03-20 17:02:15", "2022-03-20 17:03:13", "2022-03-20 17:08:20"];
  // const pre_img = ["./images/temp_img/pre_1.jpg", "./images/temp_img/pre_2.jpg", "./images/temp_img/pre_3.jpg"];
  const marine_image_ele = document.getElementById("marine_image");
  const underwater_image_ele = document.getElementById("underwater_image");
  const marine_image_time_ele = document.getElementById("marine_image_time");
  const underwater_image_time_ele = document.getElementById("underwater_image_time");
  // const latest_detection_img_time_ele = document.getElementById("latest_detection_img_time");
  // const latest_detection_img_ele = document.getElementById("latest_detection_img");


  // marine_image_ele.src = marin[count];
  // underwater_image_ele.src = water[count];
  // marine_image_time_ele.textContent = marin_time[count];
  underwater_image_time_ele.textContent = watr_time[count];
  // latest_detection_img_time_ele.textContent = pre_time[count];
  // latest_detection_img_ele.src = pre_img[count];

  count += 1;
  if (count == 3)
    count = 0;
}

const temp = () => {
    temp_();
    setInterval(temp_, 5000); // 5秒ごとに変わる

}

export default temp;