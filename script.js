if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

window.onload = function()
{
    let checkbox = document.getElementById("checkbox");
    let checkboxName = document.getElementById("checkboxName");
    let newNameInput = document.querySelector(".newName");
    
    let secondDate = document.getElementById("secondDate");
    let booking = document.querySelectorAll(".booking");
    let changeName = document.querySelectorAll(".changeName");
    let bookingInputs = document.querySelectorAll(".bookingInput");

    booking.forEach(element => {
        if (checkboxName.checked) {
            element.classList.add("displayNone");
        } else {
            element.classList.remove("displayNone");
        };
    });

    bookingInputs.forEach(element => {
        if (checkboxName.checked) {
            element.removeAttribute("required");
            element.setAttribute("disabled", "")
        } else {
            element.required = true;
            element.removeAttribute("disabled");
        };
    });

    changeName.forEach(element => {
        if (checkboxName.checked) {
            element.classList.remove("displayNone");
        } else {
            element.classList.add("displayNone");
        };
    });


    checkboxName.addEventListener('change', function()
    {
        booking.forEach(element => {
            if (this.checked) {
                element.classList.add("displayNone");
            } else {
                element.classList.remove("displayNone");
            }
        });

        changeName.forEach(element => {
            if (this.checked) {
                element.classList.remove("displayNone");
            } else {
                element.classList.add("displayNone");
            }
        });

        bookingInputs.forEach(element => {
            if (this.checked) {
                element.removeAttribute("required");
                element.setAttribute("disabled", "")
            } else {
                element.required = true;
                element.removeAttribute("disabled");
            };
        });

        if (this.checked) {
            newNameInput.required = true;
            newNameInput.removeAttribute("disabled");
        } else {
            newNameInput.setAttribute("disabled", "");
            newNameInput.removeAttribute("required");
        }

        if (this.checked) {
            document.querySelector('.switchSliderOff').classList.add('switchSliderColor');
            document.querySelector('.switchSliderOn').classList.remove('switchSliderColor');
        }else{
            document.querySelector('.switchSliderOn').classList.add('switchSliderColor');
            document.querySelector('.switchSliderOff').classList.remove('switchSliderColor');
        };
    });

    if (checkboxName.checked) {
        secondDate.removeAttribute("disabled");
        document.querySelector('.switchSliderOff').classList.add('switchSliderColor');
        document.querySelector('.switchSliderOn').classList.remove('switchSliderColor');
    } else {
        secondDate.setAttribute("disabled", "");
        document.querySelector('.switchSliderOn').classList.add('switchSliderColor');
        document.querySelector('.switchSliderOff').classList.remove('switchSliderColor');
    };

    checkbox.addEventListener('change', function()
    {
        if (this.checked) {
            secondDate.removeAttribute("disabled");
        } else {
            secondDate.setAttribute("disabled", "");
        }
    });
};
