const pricePerLiter = 5; // 1 liter costs 5 Riyals

function updateDonation(selectElement) {
    let selectedLiters = parseInt(selectElement.value) || 0;
    let donationInput = selectElement.nextElementSibling;
    let totalPrice = selectedLiters * pricePerLiter;
    donationInput.value = "ريال " + totalPrice.toFixed(2);
}

function submitDonation(button) {
    let card = button.closest('.card');
    let select = card.querySelector('.select-dropdown');
    let donationInput = card.querySelector('.donation-input');

    let liters = parseInt(select.value) || 0;
    let amount = parseFloat(donationInput.value.replace("ريال ", "")) || 0;

    if (liters === 0 || amount === 0) {
        alert("يرجى اختيار كمية الماء قبل التبرع!");
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "donate.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            alert(xhr.responseText);
            location.reload();
        }
    };
    xhr.send(`liters=${liters}&amount=${amount}`);
}
