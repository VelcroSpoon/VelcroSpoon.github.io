function write_answer_days(text_msg){
  let my_p = document.getElementById("p_answer_days");
  if (my_p) my_p.innerHTML = text_msg;
}
function get_dob(){
  const el = document.getElementById("DOB");
  return el ? el.value : "";
}
function write_answer_circle(text_msg){
  let my_p = document.getElementById("p_answer_circle");
  if (my_p) my_p.innerHTML = text_msg;
}
function get_screen_dims(){
  return window.screen;
}

function write_answer_palindrome(text_msg){
  let my_p = document.getElementById("p_answer_palindrome");
  if (my_p) my_p.innerHTML = text_msg;
}
function get_palindrome(){
  const el = document.getElementById("possible_palindrome");
  return el ? el.value : "";
}
function write_answer_fibo(text_msg){
  let my_p = document.getElementById("p_answer_fibo");
  if (my_p) my_p.innerHTML = text_msg;
}
//I took the liberty to slightly modify the js file