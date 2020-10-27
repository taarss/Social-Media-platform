const scriptSource = ["cookieConsent", "navigationCheck"];

scriptSource.forEach((scriptSrc) => {
	let script = document.createElement("script");
	script.src = `${scriptSrc}.js`;
	document.querySelector("body").appendChild(script);
});
