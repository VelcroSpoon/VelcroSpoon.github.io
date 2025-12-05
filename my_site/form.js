// This function runs on form submit and decides if the quiz is allowed to go through.
// I’m basically doing my own quick client-side validation before the request hits PHP.
function validate(event) {
  // Grab the 2 fields that are mandatory for my quiz result page:
  // I only care about name + email here, the rest can be optional.
  var name  = document.getElementById("name");
  var email = document.getElementById("email");

  // I collect all issues in an array so I can show one alert with multiple lines
  // instead of spamming the user with separate popups.
  var problems = [];
  if (!name || !name.value.trim())  problems.push("Please enter your name.");
  if (!email || !email.value.trim()) problems.push("Please enter your email.");

  // If I found any problems, I manually block the form submit and show them.
  // I call preventDefault here (inside the “has problems” branch) so I only stop
  // the submission when the data is actually invalid.
  if (problems.length > 0) {
    event.preventDefault();                 // cancel the browser’s normal submit behaviour
    alert(problems.join("\n"));             // show all messages in one alert, each on its own line
    return false;                           // extra safety: tells the browser/handler “do not submit”
  }

  // If we get here, both fields passed the checks, so I just let the form submit normally.
  return true;
}
