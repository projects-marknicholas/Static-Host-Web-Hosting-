const menu = document.querySelector(".menu");
const navRight = document.querySelector("nav > .right");

menu.addEventListener('click', event => {
    navRight.style.display = navRight.style.display == "block" ? "none" : "block";
});

const createNew = document.querySelector(".create-new");
const newModal = document.querySelector("#modal");
const cancelModal = document.querySelector(".cancel");

createNew.addEventListener('click', event => {
    newModal.style.display = newModal.style.display == "flex" ? "none" : "flex";
});
cancelModal.addEventListener('click', event => {
    newModal.style.display = newModal.style.display == "flex" ? "none" : "flex";
});

function copyEndpoint() {
  var endpoint = document.getElementById("endpoint");
  endpoint.select();
  document.execCommand("copy");
  alert("Copied the endpoint: " + endpoint.value);
}