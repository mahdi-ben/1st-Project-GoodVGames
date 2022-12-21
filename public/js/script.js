'use strict';


function delAlert() {
    let result = confirm('Voulez-vous vraiment supprimer ?');
    if(result == false) {
        event.preventDefault();
    }
}

document.addEventListener('DOMContentLoaded', function () {

console.log("ok");

let input = document.querySelector("#search");

input.addEventListener('keyup',() => {
    
    // Get id tape in the input
    let textFind = document.querySelector('#search').value;
    
    console.log(textFind);
    
    // Doing an object type request
    let req = new Request('index.php?road=searchAjax', {
        method: 'POST',
        body : JSON.stringify({ textToFind : textFind })
    });  
    
    // Exploit the answer of ajax request
        fetch(req)
            .then(result => result.text())
            .then(result => { document.querySelector('#target').innerHTML = result;
        });
});

});











 
    
    