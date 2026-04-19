document.getElementById("check-btn").addEventListener("click", function(){
    const input = document.getElementById("text-input").value;
    const result = document.getElementById("result");

    if(input === ""){
        alert("Please input a value");
        return;
    }

    const clean = input.toLowerCase().replace(/[^a-z0-9]/g, "");

    const reversed = clean.split("").reverse().join("");

    if(clean === reversed){
        result.textContent = `${input} is a palindrome`;
    } else {
        result.textContent = `${input} is not a palindrome`;
    }
});