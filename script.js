//for quantity button, kalau tak buat tak function butang tu :(

const plus = document.querySelector(".plus"),
minus = document.querySelector(".minus"),
number = document.querySelector(".number");

let a = 0;

plus.addEventListener("click", ()=>{
    a++;
    number.innerText = a;
    console.log(a);
});

minus.addEventListener("click", ()=>{
    if (a > 0){
    a--;
    number.innerText = a;
    }
});