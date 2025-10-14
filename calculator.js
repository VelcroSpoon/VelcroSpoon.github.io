function compute_days() {
  const dobStr = get_dob();                 
  const dob = new Date(dobStr);
  const today = new Date();

  if (isNaN(dob.getTime())) {
    write_answer_days("Please enter a valid date (YYYY-MM-DD).");
    return;
  }

  
  const d0 = new Date(dob.getFullYear(), dob.getMonth(), dob.getDate());
  const d1 = new Date(today.getFullYear(), today.getMonth(), today.getDate());

  const msPerDay = 1000 * 60 * 60 * 24;
  const days = Math.floor((d1 - d0) / msPerDay);

  write_answer_days("You are about <b>" + days + "</b> days old.");
}

function compute_circle() {
  const screen = get_screen_dims(); 
  const diameter = Math.min(screen.width, screen.height);
  const radius = diameter / 2;
  const area = Math.PI * radius * radius;

  write_answer_circle(
    "Using your screen (w×h = " + screen.width + "×" + screen.height + "):<br>" +
    "Radius ≈ <b>" + radius.toFixed(1) + " px</b><br>" +
    "Area A = πr² ≈ <b>" + area.toFixed(1) + " px²</b>"
  );
}


function check_palindrome() {
  const raw = get_palindrome(); // from utils
  const cleaned = (raw || "").toLowerCase().replace(/[^a-z0-9]/g, "");

  let isPal = true;
  for (let i = 0; i < Math.floor(cleaned.length / 2); i++) {
    if (cleaned[i] !== cleaned[cleaned.length - 1 - i]) {
      isPal = false;
      break;
    }
  }

  if (!cleaned) {
    write_answer_palindrome("Type something first 😊");
  } else if (isPal) {
    write_answer_palindrome("✅ Yes! <b>" + raw + "</b> is a palindrome.");
  } else {
    write_answer_palindrome("❌ No. <b>" + raw + "</b> is not a palindrome.");
  }
}

function create_fibo() {
  const el = document.getElementById("fibo_length");
  const n = parseInt(el ? el.value : "", 10);

  if (isNaN(n)) {
    write_answer_fibo("Please enter a number (e.g., 10).");
    return;
  }
  if (n <= 0) {
    write_answer_fibo("[]");
    return;
  }

  const seq = [];
  if (n >= 1) seq.push(0);
  if (n >= 2) seq.push(1);

  for (let i = 2; i < n; i++) {
    seq.push(seq[i - 1] + seq[i - 2]);
  }

  write_answer_fibo("Fibonacci (" + n + "): " + seq.join(", "));
}
//I took the liberty to slightly modify the js file