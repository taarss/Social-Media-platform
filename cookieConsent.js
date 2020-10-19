const cookieConsentContainer = document.createElement("div");
cookieConsentContainer.classList.add("cookieConsentContainer");
const cookieP = document.createElement("p");
cookieP.innerHTML = `We use cookie on this website to give you the best experience on our site.<br/>To find out more, read our <a href="">privacy policy</a> and <a href="">cookie policy.</a>`;
const buttonContainer = document.createElement("div");
buttonContainer.classList.add("flex");
const cookieBtn = document.createElement("button");
cookieBtn.innerText = "Accept";
cookieBtn.classList.add("cookie-btn");
cookieConsentContainer.appendChild(cookieP);
buttonContainer.appendChild(cookieBtn);
cookieConsentContainer.appendChild(buttonContainer);
const contentContainers = document.querySelectorAll(".content");
contentContainers[0].appendChild(cookieConsentContainer);

const cookieContainer = document.querySelector(".cookieConsentContainer");

cookieBtn.addEventListener("click", (e) => {
	e.preventDefault();
	cookieContainer.classList.remove("active");
	localStorage.setItem("acceptCookieConsent", "true");
});

setTimeout(() => {
	if (!localStorage.getItem("acceptCookieConsent")) {
		cookieContainer.classList.add("active");
	}
}, 1000);
