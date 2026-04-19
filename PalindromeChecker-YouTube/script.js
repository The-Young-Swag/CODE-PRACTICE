const input = document.getElementById("input")
const button = document.getElementById("btn")



function reverseString(str){
    return str.split("").reverse().join("")
}

function check(){


    const value = input.value
    const reverse = reverseString(value)
    
    if (value === ""){
    alert("it's empty")    
    }


    else if(value === reverse){
    alert("is Palindrome")    
    } else {
        alert("Not Palindrome")
    }

    input.value = ""
}

button.addEventListener("click",check);


