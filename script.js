window.onload = function() {
    let checkbox = document.getElementById("checkbox");
    let secondDate = document.getElementById("secondDate");

    if (checkbox.checked) {
        secondDate.removeAttribute("disabled");

    } else {
        secondDate.setAttribute("disabled", "");
    };

    checkbox.addEventListener('change', function() {
    if (this.checked) {
        secondDate.removeAttribute("disabled");
    } else {
        secondDate.setAttribute("disabled", "");
    }
    });
};
