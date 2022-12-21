'use strict';

/***    Slider Functions    ***/

let img_slider = document.getElementsByClassName("img_slider");

console.log(img_slider);

let etape = 0;

let back = document.querySelector('.back');
let next = document.querySelector('.next');

let nbr_img = img_slider.length;

function switchActiveImages() {
    for(let i = 0; i < nbr_img; i++) {
        img_slider[i].classList.remove('active');
    }
}

next.addEventListener('click', function() {
    etape++;
    if(etape >= nbr_img) {
        etape = 0;
    }
    switchActiveImages();
    img_slider[etape].classList.add('active');
});

back.addEventListener('click', function() {
    etape--;
    if(etape < 0) {
        etape = nbr_img - 1;
    }
    switchActiveImages();
    img_slider[etape].classList.add('active');
});

setInterval(function() {
    etape++;
    if(etape >= nbr_img) {
        etape = 0;
    }
    switchActiveImages();
    img_slider[etape].classList.add('active');
}, 4000);