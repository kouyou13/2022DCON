'use strict';

const wind_dir_arrow_img_ele = document.getElementById("arrow");
const wind_dir_value_ele = document.getElementById("wind_dir_value");
const wind_vel_value_ele = document.getElementById("wind_vel_value");
const weather_img_ele = document.getElementById("weather_img");

const GetWeatherData = () => {
  return $.ajax({
    url: "./api/get_weather.php",
    type: "POST",
    // data: parameter,
    catch: false
  });
}

// -------------------------------- 風向 -------------------------------- //
const TurnWindDirImg = (wind_deg) => {
  wind_dir_arrow_img_ele.style.transform = `translate(-50%, -50%) rotate(${wind_deg}deg)`;
}

const SetWindDirValue = (wind_deg) => {
  if (wind_deg <= 22.5 || wind_deg > 337.5)
    wind_dir_value_ele.textContent = "北";
  else if(wind_deg > 22.5 && wind_deg <= 67.5)
    wind_dir_value_ele.textContent = "北東";
  else if(wind_deg > 67.5 && wind_deg <= 112.5)
    wind_dir_value_ele.textContent = "東";
  else if(wind_deg > 112.5 && wind_deg <= 157.5)
    wind_dir_value_ele.textContent = "南東";
  else if(wind_deg > 157.5 && wind_deg <= 202.5)
    wind_dir_value_ele.textContent = "南";
  else if(wind_deg > 202.5 && wind_deg <= 247.5)
    wind_dir_value_ele.textContent = "南西";
  else if(wind_deg > 247.5 && wind_deg <= 292.5)
    wind_dir_value_ele.textContent = "西";
  else if(wind_deg > 292.5 && wind_deg <= 337.5)
    wind_dir_value_ele.textContent = "北西";
}

const SetWindDir = (wind_deg) => {
  TurnWindDirImg(wind_deg);
  SetWindDirValue(wind_deg);
}

// -------------------------------- 風速 -------------------------------- //
const SetWindDirImg = (wind_vel) => {
  if (wind_vel == 0) //風速0m/s
    wind_dir_arrow_img_ele.src = "./images/wind_dir_img/velocity_0.png";
  else if (wind_vel < 5) //風速1~4m/s
    wind_dir_arrow_img_ele.src = "./images/wind_dir_img/velocity_1-4.png";
  else if (wind_vel < 8) //風速5~7m/s
    wind_dir_arrow_img_ele.src = "./images/wind_dir_img/velocity_5-7.png";
  else if (wind_vel < 12) //風速8~11m/s
    wind_dir_arrow_img_ele.src = "./images/wind_dir_img/velocity_8-11.png";
  else if (wind_vel < 18) //風速12~17m/s
    wind_dir_arrow_img_ele.src = "./images/wind_dir_img/velocity_12-17.png";
  else //風速18~m/s
    wind_dir_arrow_img_ele.src = "./images/wind_dir_img/velocity_18-.png";
}

const SetWindVel = (wind_vel) => {
  SetWindDirImg(wind_vel);
  wind_vel_value_ele.textContent = wind_vel;
}

// -------------------------------- 風速 -------------------------------- //
const SetWeather = (weather) => {
  if (weather == "clear sky") // 晴れ
    weather_img_ele.src = "./images/weather_img/1.png";

  else if (weather == "few clouds" || weather=="scattered clouds") // 雲少なめ，雲普通
    weather_img_ele.src = "./images/weather_img/2.png";

  else if (weather=="broken clouds" || weather=="overcast clouds" || weather=="mist") // 雲多め，霧
    weather_img_ele.src = "./images/weather_img/3.png";

  else if (weather=="light intensity shower rain" || weather=="shower rain" || weather=="light rain" || weather=="moderate rain") // 小雨のにわか雨，にわか雨，小雨，雨
    weather_img_ele.src = "./images/weather_img/4.png";
  
  else if (weather=="heavy intensity rain" || weather=="very heavy rain" || weather=="heavy intensity shower rain") // 大雨，激しい大雨，大雨のにわか雨
    weather_img_ele.src = "./images/weather_img/5.png";
  
  else if (weather == "snow") // 雪
    weather_img_ele.src = "./images/weather_img/6.png";
  
  else if (weather == "tornado") // 強風
    weather_img_ele.src = "./images/weather_img/7.png";

  else if (weather == "thunderstorm") // 雷雨
    weather_img_ele.src = "./images/weather_img/8.png";
}



const SetWeatherData = () => {
  GetWeatherData().then(function (weather_data) {
    let wind_deg = weather_data[ weather_data.length-1 ]["wind_deg"];
    let wind_vel = weather_data[ weather_data.length-1 ]["wind_velocity"];
    let weather = weather_data[ weather_data.length-1 ]["weather"];
    SetWindDir(wind_deg);
    SetWindVel(wind_vel);
    SetWeather(weather);
  });
} 

export default SetWeatherData;