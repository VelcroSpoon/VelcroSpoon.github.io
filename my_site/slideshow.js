// I keep track of which slide is currently visible using a simple index.
// This variable is shared by next()/previous() and showSlide().
let current_slide = 0;

function showSlide(n) {
  // I grab all the slide elements inside the slideshow container.
  // Each .slideshow_img is one image in the hero carousel.
  const slides = document.querySelectorAll(".slideshow .slideshow_img");
  if (slides.length === 0) return; // If there are no slides, there’s nothing to show.

  const total = slides.length;
  
  // I wrap the slide index using modulo so the slideshow loops:
  // going “past” the last slide brings you back to the first, and
  // going below zero wraps to the last slide.
  current_slide = (n + total) % total;

  // I hide every slide except the one whose index matches current_slide.
  // This keeps the markup simple: only one slide shows at a time by toggling display.
  slides.forEach((slide, i) => {
    slide.style.display = (i === current_slide) ? "block" : "none";
  });
}

function next() {
  // Moving forward simply means asking showSlide for the next index.
  showSlide(current_slide + 1);
}

function previous() {
  // Same idea for going backward: just subtract one and let showSlide
  // handle the wrapping logic.
  showSlide(current_slide - 1);
}

document.addEventListener("DOMContentLoaded", function () {
  // As soon as the DOM is ready, I initialize the slideshow by showing
  // the first slide. This avoids a flash of all images stacked on top
  // of each other before the JS runs.
  showSlide(0);
});
