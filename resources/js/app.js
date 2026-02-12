import './bootstrap';

// Sidebar toggle for mobile
document.addEventListener('DOMContentLoaded', function () {
	const sidebar = document.getElementById('sidebar');
	const overlay = document.getElementById('sidebar-overlay');
	const openBtn = document.getElementById('sidebar-open');
	const closeBtn = document.getElementById('sidebar-close');

	function openSidebar() {
		if (sidebar) sidebar.classList.remove('hidden');
		if (overlay) overlay.classList.remove('hidden');
		document.body.classList.add('overflow-hidden');
	}

	function closeSidebar() {
		if (sidebar) sidebar.classList.add('hidden');
		if (overlay) overlay.classList.add('hidden');
		document.body.classList.remove('overflow-hidden');
	}

	if (openBtn) openBtn.addEventListener('click', openSidebar);
	if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
	if (overlay) overlay.addEventListener('click', closeSidebar);
});
