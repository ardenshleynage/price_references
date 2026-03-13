import "boxicons/dist/boxicons.js";

const allSideMenu = document.querySelectorAll("#sidebar .side-menu.top li a");

allSideMenu.forEach((item) => {
    const li = item.parentElement;

    item.addEventListener("click", function () {
        allSideMenu.forEach((i) => {
            i.parentElement.classList.remove("active");
        });
        li.classList.add("active");
    });
});

// TOGGLE SIDEBAR
const menuBar = document.querySelector("#content nav .bx.bx-menu");
const sidebar = document.getElementById("sidebar");

// Restaurer l'état du sidebar au chargement depuis localStorage
function restoreSidebarState() {
    const savedState = localStorage.getItem("sidebarCollapsed");
    if (savedState === "true") {
        sidebar.classList.add("hide");
        document.documentElement.classList.add("sidebar-collapsed");
    } else {
        sidebar.classList.remove("hide");
        document.documentElement.classList.remove("sidebar-collapsed");
    }
}

// Appeler au chargement de la page
restoreSidebarState();

// Sidebar toggle
menuBar.addEventListener("click", function () {
    sidebar.classList.toggle("hide");
    
    const isCollapsed = sidebar.classList.contains("hide");
    
    if (isCollapsed) {
        document.documentElement.classList.add("sidebar-collapsed");
    } else {
        document.documentElement.classList.remove("sidebar-collapsed");
    }
    
    // Sauvegarder dans localStorage
    localStorage.setItem("sidebarCollapsed", isCollapsed);
});

// Sayfa yüklendiğinde ve boyut değişimlerinde sidebar durumunu ayarlama
function adjustSidebar() {
    const savedState = localStorage.getItem("sidebarCollapsed");
    // Prioriser la valeur sauvegardée si elle existe
    if (savedState === "true") {
        sidebar.classList.add("hide");
    } else if (savedState === "false") {
        sidebar.classList.remove("hide");
    } else {
        // Comportement par défaut pour les petits écrans
        if (window.innerWidth <= 576) {
            sidebar.classList.add("hide");
            sidebar.classList.remove("show");
        } else {
            sidebar.classList.remove("hide");
            sidebar.classList.add("show");
        }
    }
}

// Sayfa yüklendiğinde ve pencere boyutu değiştiğinde sidebar durumunu ayarlama
window.addEventListener("load", adjustSidebar);
window.addEventListener("resize", adjustSidebar);

// Arama butonunu toggle etme
const searchButton = document.querySelector(
    "#content nav form .form-input button",
);
const searchButtonIcon = document.querySelector(
    "#content nav form .form-input button .bx",
);
const searchForm = document.querySelector("#content nav form");

searchButton.addEventListener("click", function (e) {
    // Le comportement toggle est désactivé - la barre de recherche est toujours visible sur mobile
});

// Tabs Glider - Simple transition
function initTabsGlider() {
    const tabsContainers = document.querySelectorAll('.tabs');
    
    tabsContainers.forEach(tabs => {
        const glider = tabs.querySelector('.glider');
        const activeTab = tabs.querySelector('.tab.active');
        
        if (glider && activeTab) {
            glider.style.width = activeTab.offsetWidth + 'px';
            glider.style.transform = 'translateX(' + activeTab.offsetLeft + 'px)';
        }
        
        const tabs_list = tabs.querySelectorAll('.tab');
        tabs_list.forEach(tab => {
            tab.addEventListener('click', function() {
                const glider = tabs.querySelector('.glider');
                if (glider) {
                    glider.style.width = this.offsetWidth + 'px';
                    glider.style.transform = 'translateX(' + this.offsetLeft + 'px)';
                }
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', initTabsGlider);
window.addEventListener('resize', initTabsGlider);

// Dark Mode - Appliquer le thème depuis la session au chargement
document.addEventListener("DOMContentLoaded", function () {
    const savedMode = window.userTheme || "light";
    const isDark = savedMode === "dark";
    if (isDark) {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }
    // Appliquer la couleur du texte de bienvenue
    const welcomeText = document.getElementById("welcome-text");
    if (welcomeText) {
        welcomeText.style.color = isDark ? "#fff" : "#333";
    }
});

// Theme Select (page profil)
document.addEventListener("DOMContentLoaded", function () {
    const themeSelect = document.getElementById("theme-select");
    if (themeSelect) {
        themeSelect.addEventListener("change", function () {
            const isDark = this.value === "dark";
            if (isDark) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
            window.userTheme = this.value;
        });
    }
});

// Profile Menu Toggle
document.addEventListener("DOMContentLoaded", function () {
    const welcomeText = document.getElementById("welcome-text");
    const profileMenu = document.getElementById("profileMenu");
    
    if (welcomeText && profileMenu) {
        welcomeText.style.cursor = "pointer";
        welcomeText.addEventListener("click", function () {
            profileMenu.classList.toggle("show");
        });
    }
});

// Close menus if clicked outside
window.addEventListener("click", function (e) {
    const profileMenu = document.getElementById("profileMenu");
    const welcomeText = document.getElementById("welcome-text");
    if (profileMenu && welcomeText && !e.target.closest("#profileMenu") && !e.target.closest("#welcome-text")) {
        profileMenu.classList.remove("show");
    }
});

// Menülerin açılıp kapanması için fonksiyon
function toggleMenu(menuId) {
    var menu = document.getElementById(menuId);
    var allMenus = document.querySelectorAll(".menu");

    // Diğer tüm menüleri kapat
    allMenus.forEach(function (m) {
        if (m !== menu) {
            m.style.display = "none";
        }
    });

    // Tıklanan menü varsa aç, yoksa kapat
    if (menu.style.display === "none" || menu.style.display === "") {
        menu.style.display = "block";
    } else {
        menu.style.display = "none";
    }
}

// Başlangıçta tüm menüleri kapalı tut
document.addEventListener("DOMContentLoaded", function () {
    var allMenus = document.querySelectorAll(".menu");
    allMenus.forEach(function (menu) {
        menu.style.display = "none";
    });
});
