

"use strict";
const d = document;
d.addEventListener("DOMContentLoaded", function (event) {

  if (d.querySelector('.headroom')) {
    var headroom = new Headroom(document.querySelector("#navbar-main"), {
      offset: 0,
      tolerance: {
        up: 0,
        down: 0
      },
    });
    headroom.init();
  }



  // Popovers
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'))
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
  })

  var scroll = new SmoothScroll('a[href*="#"]', {
    speed: 500,
    speedAsDuration: true
  });

  d.querySelector('.current-year').textContent = new Date().getFullYear();
});

let liItem = document.querySelectorAll('.filter');
let imgItem = document.querySelectorAll('.all');

liItem.forEach(button => {
  button.onclick = function () {

    liItem.forEach(button => {
      button.className = "btn btn-outline-dark filter";
    });
    button.className = "btn btn-outline-dark filter active";

    //Filter
    let value = button.textContent;
    imgItem.forEach(img => {
      img.style.display = 'none';
      if (img.getAttribute('data-filter') == value.toLowerCase() || value == "Hepsi") {
        img.style.display = 'block';
      }
    })
  }
});

function radioButtonGroup(buttonGroup) {
  buttonGroup.addEventListener('click', function (event) {
    if (!matchesSelector(event.target, 'button')) {
      return;
    }
    buttonGroup.querySelector('.active').classList.remove('active');
    event.target.classList.add('active');
  });
}

//hizmet numaraları
const serviceNumbers = document.querySelectorAll('.service-number');
serviceNumbers.forEach((element, index) => {
  const number = String(index + 1).padStart(2, '0');
  element.textContent = number;
});


// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
