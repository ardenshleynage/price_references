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

// SIDEBAR TOGGLE
const menuToggle = document.querySelector("#content nav .bx.bx-menu");
const sidebar = document.getElementById("sidebar");
const html = document.documentElement;

// Créer l'overlay pour mobile
function getSidebarOverlay() {
    let overlay = document.getElementById("sidebar-overlay");
    if (!overlay) {
        overlay = document.createElement("div");
        overlay.id = "sidebar-overlay";
        document.body.appendChild(overlay);
        overlay.addEventListener("click", function() {
            toggleSidebar();
        });
    }
    return overlay;
}

// Toggle sidebar
function toggleSidebar() {
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        const isActive = sidebar.classList.contains("active");
        const overlay = getSidebarOverlay();
        
        if (isActive) {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
            document.body.style.overflow = "";
        } else {
            sidebar.classList.add("active");
            overlay.classList.add("active");
            document.body.style.overflow = "hidden";
        }
    } else {
        const isHidden = sidebar.classList.contains("hide");
        
        if (isHidden) {
            sidebar.classList.remove("hide");
            html.classList.remove("sidebar-collapsed");
            localStorage.setItem("sidebarCollapsed", "false");
        } else {
            sidebar.classList.add("hide");
            html.classList.add("sidebar-collapsed");
            localStorage.setItem("sidebarCollapsed", "true");
        }
    }
}

// Restaurer l'état du sidebar
function restoreSidebarState() {
    const savedState = localStorage.getItem("sidebarCollapsed");
    const isMobile = window.innerWidth <= 768;
    const overlay = getSidebarOverlay();
    
    if (isMobile) {
        sidebar.classList.remove("active");
        sidebar.classList.remove("hide");
        overlay.classList.remove("active");
        document.body.style.overflow = "";
    } else {
        if (savedState === "true") {
            sidebar.classList.add("hide");
            html.classList.add("sidebar-collapsed");
        } else {
            sidebar.classList.remove("hide");
            html.classList.remove("sidebar-collapsed");
        }
        overlay.classList.remove("active");
    }
}

// Event listener
if (menuToggle) {
    menuToggle.addEventListener("click", toggleSidebar);
}

// Initialisation
document.addEventListener("DOMContentLoaded", restoreSidebarState);

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
