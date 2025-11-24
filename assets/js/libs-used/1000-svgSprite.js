let ajax = new XMLHttpRequest();
ajax.open('GET', '/build/img/symbol/icons.svg', true);
ajax.send();
ajax.onload = function (e) {
  let div = document.createElement('div');
  div.innerHTML = ajax.responseText;
  div.style.position = 'absolute';
  div.style.right = '100%';
  document.body.insertBefore(div, document.body.childNodes[0]);
};
