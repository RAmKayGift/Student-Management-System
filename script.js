document.getElementById("registrationForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission

    let firstName = document.getElementById("firstName").value.trim();
    let surname = document.getElementById("surname").value.trim();
    let idNumber = document.getElementById("idNumber").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let parentName = document.getElementById("parentName").value.trim();
    let parentNumber = document.getElementById("parentNumber").value.trim();
    let studentIdFile = document.getElementById("studentIdUpload").files[0];
    let parentIdFile = document.getElementById("parentIdUpload").files[0];
    let reportFile = document.getElementById("reportUpload").files[0];
    let message = document.getElementById("registerMessage");

    // Name validation (only letters allowed)
    let nameRegex = /^[A-Za-z\s]+$/;
    if (!nameRegex.test(firstName) || !nameRegex.test(surname)) {
        message.style.color = "red";
        message.textContent = "Error: Name should contain only letters!";
        return;
    }

    // ID Number validation (must be exactly 13 digits)
    let idRegex = /^\d{13}$/;
    if (!idRegex.test(idNumber)) {
        message.style.color = "red";
        message.textContent = "Error: ID Number must be exactly 13 digits!";
        return;
    }

    // Password validation (6+ characters, 1 uppercase letter, 1 number)
    let passwordRegex = /^(?=.*[A-Z])(?=.*\d).{6,}$/;
    if (!passwordRegex.test(password)) {
        message.style.color = "red";
        message.textContent = "Error: Password must be at least 6 characters, include 1 capital letter and 1 number!";
        return;
    }

    // Phone number validation (only digits, 10+ characters)
    let phoneRegex = /^[0-9]{10,}$/;
    if (!phoneRegex.test(phone) || !phoneRegex.test(parentNumber)) {
        message.style.color = "red";
        message.textContent = "Error: Phone number must be at least 10 digits!";
        return;
    }

    // File upload validation (only PDF allowed)
    if (!studentIdFile || !parentIdFile || !reportFile) {
        message.style.color = "red";
        message.textContent = "Error: Please upload all required documents!";
        return;
    }
    if (studentIdFile.type !== "application/pdf" || parentIdFile.type !== "application/pdf" || reportFile.type !== "application/pdf") {
        message.style.color = "red";
        message.textContent = "Error: All uploaded files must be in PDF format!";
        return;
    }

    // Success message
    message.style.color = "green";
    message.textContent = "Registration successful!";
});
