// Simple form validation
document.addEventListener("submit", function(e) {
    if (e.target.classList.contains("needs-validation")) {
        let inputs = e.target.querySelectorAll("input, select");
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].value.trim() === "") {
                alert("Please fill all fields");
                e.preventDefault();
                break;
            }
        }
    }
});
