function validate(event) {
  // Get fields
  var name  = document.getElementById("name");
  var email = document.getElementById("email");

  var problems = [];
  if (!name || !name.value.trim())  problems.push("Please enter your name.");
  if (!email || !email.value.trim()) problems.push("Please enter your email.");

  if (problems.length > 0) {
    event.preventDefault();                 // ← move here
    alert(problems.join("\n"));
    return false;                           // block submit when invalid
  }
  return true;                              // allow submit when valid
}
