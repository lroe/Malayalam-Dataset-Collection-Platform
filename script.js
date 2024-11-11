// script.js

function navigateTo(page) {
    // Logic to switch between pages, if additional pages are added later
    document.getElementById('write-page').style.display = page === 'write' ? 'block' : 'none';
    // Add more if you implement other pages like review, about, dataset
}

function handleAuth() {
    const authButton = document.getElementById("auth-button");
    // Check if user is logged in (placeholder logic)
    if (authButton.textContent === "Login/Signup") {
        // Redirect to login page or show signup form
        authButton.textContent = "Profile";
    } else {
        // Go to profile or logout functionality
        authButton.textContent = "Login/Signup";
    }
}

function submitTranslation() {
    const monglishInput = document.getElementById('monglish-input').value;
    // Placeholder logic to save translation
    alert("Translation submitted: " + monglishInput);
}

function skipTranslation() {
    // Placeholder logic to skip translation
    alert("Skipped! Loading new text...");
}
