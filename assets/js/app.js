

document.addEventListener('DOMContentLoaded', function () {
    var myCarousel = document.querySelector('#myCarousel')
    new bootstrap.Carousel(myCarousel, {
        interval: 2000,
        wrap: false
    })
});


// Highlight current page links
const navLinks = document.querySelectorAll('nav ul li');
// console.log(navLinks);
const url = window.location.href;

for (let navLink of navLinks) {

    if (url === navLink.children[0].href) {
        console.log(navLink.children[0].href);
        navLink.children[0].classList.add('active');

        // If it is dropdown
        if (navLink.closest('ul').classList.contains('dropdown-menu')) {
            const parentLi = navLink.parentElement.closest('li');
            parentLi.children[0].classList.add('active');
        }

    } else if (url.slice(0, -1) === navLink.children[0].href) {
        navLink.children[0].classList.add('active');
    }
}