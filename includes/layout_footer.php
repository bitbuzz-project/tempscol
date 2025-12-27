</main>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
const sidebar = document.getElementById('sidebar');
const toggleButton = document.getElementById('toggleSidebar');
const closeButton = document.getElementById('closeSidebar');

if (toggleButton) {
    toggleButton.addEventListener('click', () => {
        sidebar.classList.add('show');
    });
}

if (closeButton) {
    closeButton.addEventListener('click', () => {
        sidebar.classList.remove('show');
    });
}

// Close sidebar on outside click (mobile)
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(e.target) && !toggleButton?.contains(e.target)) {
            sidebar.classList.remove('show');
        }
    }
});
</script>
</body>
</html>