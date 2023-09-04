"use strict";
let img_count = 0;
let sonner_img_list = [];
let img_name;

const GetSonnerImg = () => {
  return $.ajax({
    url: "./api/get_sonner_img.php",
    type: "POST",
    // data: parameter,
    catch: false
  });
}


const getToday = () => {
  const now = new Date();
  const year = now.getFullYear();
  let month = now.getMonth()+1;
  let date = now.getDate();
  // let hour = datetime.getHours();
  // let min = datetime.getMinutes();
  // let sec = datetime.getSeconds();
  month = ( '00' + month ).slice( -2 );
  date = ( '00' + date ).slice( -2 );
  // hour = ( '00' + hour ).slice( -2 );
  // min = ( '00' + min ).slice( -2 );
  // sec = ( '00' + sec ).slice( -2 );

  return `${year}-${month}-${date}`;
}


// 20220314_131313 → 2022-03-14 13:13:13に変える
const Convert = (img_url) => {
  const today = getToday();
  img_url = img_url.replace(`http://ezaki-lab.littlestar.jp/2022DCON/sonner_images/${today}/`, "");
  img_url = img_url.replace(".png", "");
  let year = img_url.slice(0, 4);
  let month = img_url.slice(4, 6);
  let date = img_url.slice(6, 8);
  let hour = img_url.slice(9, 11);
  let min = img_url.slice(11, 13);
  let sec = img_url.slice(13, 15);
  return `${year}-${month}-${date} ${hour}:${min}:${sec}`;
}

const SetSonnerImg = (sonner_img_list) => {
  const sonner_img_ele = document.getElementById("sonner_img");
  const sonner_img_time_ele = document.getElementById("sonner_img_time");
  img_name = sonner_img_list[img_count].replace("/home/users/1/littlestar.jp-ezaki-lab/web/", "http://ezaki-lab.littlestar.jp/");
  sonner_img_ele.src = `./api/small_image.php?file_name=${img_name}`;
  img_name = Convert(img_name);
  sonner_img_time_ele.textContent = img_name;
  if (img_count == 9)
    img_count = 0;
  else
    img_count += 1;
}


const Sonner = () => {
  GetSonnerImg().then(function (sonner_img_list_temp) {
    // console.log(sonner_img_list_temp.slice(-10));
    sonner_img_list = sonner_img_list_temp.slice(-10);
    SetSonnerImg(sonner_img_list)
    setInterval(function(){SetSonnerImg(sonner_img_list)}, 5000);
  });
}


Sonner();