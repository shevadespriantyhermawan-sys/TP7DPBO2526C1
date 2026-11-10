document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", function(e) {
            const inputs = form.querySelectorAll("[required]");
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    alert("Please fill in all required fields!");
                    e.preventDefault();
                }
            });
        });
    });
});