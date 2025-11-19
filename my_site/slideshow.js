
let current_slide = 0;

function showSlide(n) {
  const slides = document.querySelectorAll(".slideshow .slideshow_img");
  if (slides.length === 0) return;

  const total = slides.length;
  
  current_slide = (n + total) % total;

  slides.forEach((slide, i) => {
    slide.style.display = (i === current_slide) ? "block" : "none";
  });
}

function next() {
  showSlide(current_slide + 1);
}

function previous() {
  showSlide(current_slide - 1);
}

document.addEventListener("DOMContentLoaded", function () {
  showSlide(0);
});
