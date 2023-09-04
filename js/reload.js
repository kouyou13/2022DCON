"use strict";

const reload = () => {
  const timer = 2.5 * 1000 * 60; // 2.5分（2.5*1000*60ミリ秒）
  setInterval('location.reload()',timer);
}

export default reload;