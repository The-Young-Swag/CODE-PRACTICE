<form id="palindromeChecker">
    <input name="input" type="text" placeholder="Enter text" required>
    <button type="submit">Check</button>
</form>

<div id="output"></div>

<script>
    // --- PATTERN: grab elements once at the top, never inside functions ---
    const form   = document.getElementById("palindromeChecker"); // fixed typo
    const output = document.getElementById("output");

    // --- PATTERN: fetch logic in its own function, returns data or throws ---
    async function checkPalindrome(formData) {
    const response = await fetch("ctrl_index.php", {
        method: "POST",
        body: formData
    });

    if (response.ok) {
        const data = await response.json();
        return data.result;
    } else {
        throw new Error(`Server error: ${response.status}`);
    }
}

    // --- PATTERN: listener stays thin, just coordinates the 3 states ---
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        output.textContent = "Checking...";  // loading state

        try {
            const result = await checkPalindrome(new FormData(form)); // fixed: const
            output.textContent = result;                               // success state

        } catch (error) {
            console.error(error);
            output.textContent = "Something went wrong.";              // error state
        }
    });
</script>