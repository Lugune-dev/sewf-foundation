/* SEWF light/dark theme toggle */
(function () {
	'use strict';

	var STORAGE_KEY = 'sewf-theme';

	function getInitial() {
		var saved = null;
		try { saved = localStorage.getItem(STORAGE_KEY); } catch (e) { /* ignore */ }
		if (saved === 'dark' || saved === 'light') return saved;
		return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
			? 'dark'
			: 'light';
	}

	function apply(theme) {
		document.documentElement.setAttribute('data-theme', theme);
		var sun = document.getElementById('sewf-theme-sun');
		var moon = document.getElementById('sewf-theme-moon');
		if (sun) sun.style.display = theme === 'dark' ? 'block' : 'none';
		if (moon) moon.style.display = theme === 'dark' ? 'none' : 'block';
	}

	function onToggle() {
		var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
		apply(next);
		try { localStorage.setItem(STORAGE_KEY, next); } catch (e) { /* ignore */ }
	}

	function buildButton() {
		var btn = document.createElement('button');
		btn.type = 'button';
		btn.id = 'sewf-theme-toggle';
		btn.setAttribute('aria-label', 'Toggle dark or light theme');
		btn.innerHTML =
			'<svg id="sewf-theme-sun" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>' +
			'<svg id="sewf-theme-moon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>';
		btn.addEventListener('click', onToggle);
		document.body.appendChild(btn);
		return btn;
	}

	function init() {
		apply(getInitial());
		buildButton();
		apply(getInitial());
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
