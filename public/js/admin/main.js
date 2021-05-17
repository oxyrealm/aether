document.addEventListener("DOMContentLoaded", function () {

    fetch('https://raw.githubusercontent.com/oxyrealm/aether/another/.wordpress-org/aether.json')
        .then(response => {
            return response.json()
        })
        .then(sections => {
            let html = "";
            sections.forEach(section => {
                html += `<h3>${section.title}</h3>`;
                html += `<div class="main-grid">`;
                section.products.forEach(product => {
                    html += `<div class="main-box">
                            <img src="${product.picture}" width="300px" height="150px"/>
                            <div class="main-info">
                               <h3>${product.name}</h3>
                               <p>${product.description}</p>
                               <a href="${product.buttonLink}" target="_blank"><button class="button button-primary">${product.buttonText}</button></a>
                            </div>
                         </div>`
                })
                html += `</div>`;
            })
            document.getElementById("aether-main").innerHTML = html;
        })
        .catch(err => {
            console.log(err)
            document.getElementById("aether-main").innerText = "Error!";
        });

});