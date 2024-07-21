// Get modal element
var modal = document.getElementById("editRatesModal");

// Get the button that opens the modal
var btns = document.querySelectorAll("button[name='edit_rates']");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btns.forEach(function (btn) {
  btn.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent default action
    var row = this.closest("tr");
    var icuRate = row.querySelector("td:nth-child(1)").innerText;
    var deluxeRate = row.querySelector("td:nth-child(2)").innerText;
    var generalRate = row.querySelector("td:nth-child(3)").innerText;
    var doctorCharges = row.querySelector("td:nth-child(4)").innerText;
    var rmoCharges = row.querySelector("td:nth-child(5)").innerText;
    var nurseCharges = row.querySelector("td:nth-child(6)").innerText;

    document.getElementById("icu_rate").value = icuRate;
    document.getElementById("deluxe_rate").value = deluxeRate;
    document.getElementById("general_rate").value = generalRate;
    document.getElementById("doctor_charges").value = doctorCharges;
    document.getElementById("rmo_charges").value = rmoCharges;
    document.getElementById("nurse_charges").value = nurseCharges;

    modal.style.display = "block";
  });
});

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
  modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

/////////////////////////////////////////////////////////////////////////////////////////

// Get modal element
var expenseModal = document.getElementById("editExpenseModal");

// Get the button that opens the modal
var expenseBtns = document.querySelectorAll(".edit-expense-btn");

// Get the <span> element that closes the modal
var expenseSpan = document.getElementsByClassName("close-expense")[0];

// When the user clicks the button, open the modal
expenseBtns.forEach(function (btn) {
  btn.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent default action
    var row = this.closest("tr");
    var expenseId = row.getAttribute("data-id");
    var expenseDate = row.querySelector("td:nth-child(1)").innerText;
    var expenseType = row.querySelector("td:nth-child(2)").innerText;
    var expenseAmount = row.querySelector("td:nth-child(3)").innerText;

    document.getElementById("expense_id").value = expenseId;
    document.getElementById("expense_date").value = expenseDate;
    document.getElementById("expense_type").value = expenseType;
    document.getElementById("expense_amount").value = expenseAmount;

    expenseModal.style.display = "block";
  });
});

// When the user clicks on <span> (x), close the modal
expenseSpan.onclick = function () {
  expenseModal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == expenseModal) {
    expenseModal.style.display = "none";
  }
};
