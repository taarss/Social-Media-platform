const activeLinks = () => {
	const links = document.querySelectorAll(".profileAside ul a");
	links.forEach((link) => {
		if (
			window.location.pathname.replace("/", "") == link.getAttribute("href")
		) {
			link.classList.add("activeLink");
		}
	});
};

activeLinks();
