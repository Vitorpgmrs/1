document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  });
  
  const contatoBtn = document.querySelector("#contato button");
  if (contatoBtn) {
    contatoBtn.addEventListener("click", () => {
      alert("Mensagem enviada com sucesso! Em breve entraremos em contato.");
    });
  }
  document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("userMenuBtn");
    const toggle = document.getElementById("userMenuToggle");
    const menu = document.getElementById("userDropdown");
  
    const openMenu = () => {
      menu.style.display = menu.style.display === "block" ? "none" : "block";
    };
  
    btn.addEventListener("click", openMenu);
    toggle.addEventListener("click", openMenu);
  
    document.addEventListener("click", function (e) {
      if (!menu.contains(e.target) && !btn.contains(e.target) && !toggle.contains(e.target)) {
        menu.style.display = "none";
      }
    });
  });
  