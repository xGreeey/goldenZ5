/**
 * =============================================================================
 * PORTAL — CLIENT-SIDE BEHAVIOR
 * =============================================================================
 * Theme (light/dark), sidebar collapse, mobile menu, user dropdown popup.
 * =============================================================================
 */

(function () {
    'use strict';

    const STORAGE_THEME = 'portal-theme';
    const STORAGE_SIDEBAR = 'portal-sidebar-collapsed';

    const sidebar = document.getElementById('portal-sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const userMenuTrigger = document.getElementById('userMenuTrigger');
    const userMenuPopup = document.getElementById('userMenuPopup');
    const notificationTrigger = document.getElementById('notificationTrigger');
    const notificationPopup = document.getElementById('notificationPopup');
    const notificationBadge = document.getElementById('notificationBadge');
    const markAllRead = document.getElementById('markAllRead');
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;

    /* -------------------------------------------------------------------------
       THEME (light / dark)
       ------------------------------------------------------------------------- */
    function getStoredTheme() {
        try {
            return localStorage.getItem(STORAGE_THEME) || 'light';
        } catch (_) {
            return 'light';
        }
    }

    function setTheme(theme) {
        theme = theme === 'dark' ? 'dark' : 'light';
        html.setAttribute('data-theme', theme);
        try {
            localStorage.setItem(STORAGE_THEME, theme);
        } catch (_) {}
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            if (icon) icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
    }

    setTheme(getStoredTheme());

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const current = html.getAttribute('data-theme') || 'light';
            setTheme(current === 'dark' ? 'light' : 'dark');
        });
    }

    /* -------------------------------------------------------------------------
       SIDEBAR COLLAPSE (green status dot = toggle)
       ------------------------------------------------------------------------- */
    function getSidebarCollapsed() {
        try {
            return localStorage.getItem(STORAGE_SIDEBAR) === '1';
        } catch (_) {
            return false;
        }
    }

    function setSidebarCollapsed(collapsed) {
        if (sidebar) {
            sidebar.classList.toggle('collapsed', !!collapsed);
            document.body.classList.toggle('sidebar-collapsed', !!collapsed);
            var icon = sidebarToggle && sidebarToggle.querySelector('i');
            if (icon) {
                icon.className = collapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
            }
        }
        try {
            localStorage.setItem(STORAGE_SIDEBAR, collapsed ? '1' : '0');
        } catch (_) {}
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth <= 1024) {
                sidebar.classList.toggle('open');
                sidebarOverlay && sidebarOverlay.classList.toggle('visible', sidebar.classList.contains('open'));
            } else {
                setSidebarCollapsed(!getSidebarCollapsed());
            }
        });
    }

    setSidebarCollapsed(getSidebarCollapsed());

    /* -------------------------------------------------------------------------
       MY TEAM SECTION COLLAPSE (section menu button toggles list visibility)
       ------------------------------------------------------------------------- */
    const STORAGE_SECTION_TEAM = 'portal-section-team-collapsed';
    const sectionMenuBtn = document.getElementById('sectionMenuBtn');
    const sectionTeam = document.getElementById('portal-sidebar-section-team');
    const sectionTeamList = document.getElementById('portal-sidebar-section-team-list');

    function getSectionTeamCollapsed() {
        try {
            return localStorage.getItem(STORAGE_SECTION_TEAM) === '1';
        } catch (_) {
            return false;
        }
    }

    function setSectionTeamCollapsed(collapsed) {
        if (sectionTeam && sectionTeamList) {
            sectionTeam.classList.toggle('collapsed', !!collapsed);
            sectionTeam.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            if (sectionMenuBtn) {
                sectionMenuBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                const icon = sectionMenuBtn.querySelector('i');
                if (icon) {
                    icon.className = collapsed ? 'fas fa-chevron-right' : 'fas fa-ellipsis-v';
                }
            }
            try {
                localStorage.setItem(STORAGE_SECTION_TEAM, collapsed ? '1' : '0');
            } catch (_) {}
        }
    }

    if (sectionMenuBtn && sectionTeam) {
        sectionMenuBtn.addEventListener('click', function () {
            const collapsed = sectionTeam.classList.toggle('collapsed');
            sectionTeam.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            sectionMenuBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            const icon = sectionMenuBtn.querySelector('i');
            if (icon) {
                icon.className = collapsed ? 'fas fa-chevron-right' : 'fas fa-ellipsis-v';
            }
            try {
                localStorage.setItem(STORAGE_SECTION_TEAM, collapsed ? '1' : '0');
            } catch (_) {}
        });
        setSectionTeamCollapsed(getSectionTeamCollapsed());
    }

    /* -------------------------------------------------------------------------
       SIDEBAR BOTTOM: SYSTEM STATUS (Active, Do not disturb, etc.)
       ------------------------------------------------------------------------- */
    const STORAGE_STATUS = 'portal-status';
    const statusTrigger = document.getElementById('statusTrigger');
    const statusPopup = document.getElementById('statusPopup');
    const statusDot = document.getElementById('statusDot');
    const statusLabel = document.getElementById('statusLabel');

    var STATUS_CONFIG = {
        active: { label: 'Active', dotClass: 'portal-sidebar-status-dot-active' },
        busy: { label: 'Busy', dotClass: 'portal-sidebar-status-dot-busy' },
        dnd: { label: 'Do not disturb', dotClass: 'portal-sidebar-status-dot-dnd' },
        brb: { label: 'Be right back', dotClass: 'portal-sidebar-status-dot-brb' },
        away: { label: 'Appear away', dotClass: 'portal-sidebar-status-dot-away' },
        offline: { label: 'Appear offline', dotClass: 'portal-sidebar-status-dot-offline' }
    };

    function getStoredStatus() {
        try {
            var s = localStorage.getItem(STORAGE_STATUS) || 'active';
            return STATUS_CONFIG[s] ? s : 'active';
        } catch (_) {
            return 'active';
        }
    }

    function setStatus(statusKey) {
        if (statusKey === 'reset') statusKey = 'active';
        var config = STATUS_CONFIG[statusKey];
        if (!config) return;
        try {
            localStorage.setItem(STORAGE_STATUS, statusKey);
        } catch (_) {}
        if (statusDot) {
            statusDot.className = 'portal-sidebar-status-dot ' + config.dotClass;
        }
        if (statusLabel) {
            statusLabel.textContent = config.label;
        }
        var options = statusPopup && statusPopup.querySelectorAll('.portal-sidebar-status-option[data-status]');
        if (options) {
            options.forEach(function (opt) {
                opt.classList.toggle('active', opt.getAttribute('data-status') === statusKey);
            });
        }
    }

    function positionStatusPopup() {
        if (!statusTrigger || !statusPopup || statusPopup.hasAttribute('hidden')) return;
        var triggerRect = statusTrigger.getBoundingClientRect();
        statusPopup.style.left = triggerRect.left + 'px';
        statusPopup.style.bottom = (window.innerHeight - triggerRect.top + 8) + 'px';
    }

    if (statusTrigger && statusPopup) {
        var statusWrap = statusTrigger.closest('.portal-sidebar-status-wrap');
        setStatus(getStoredStatus());

        statusTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            var isHidden = statusPopup.hasAttribute('hidden');
            if (isHidden) {
                statusPopup.removeAttribute('hidden');
                statusTrigger.setAttribute('aria-expanded', 'true');
                if (statusWrap) statusWrap.classList.add('is-open');
                positionStatusPopup();
            } else {
                statusPopup.setAttribute('hidden', '');
                statusTrigger.setAttribute('aria-expanded', 'false');
                if (statusWrap) statusWrap.classList.remove('is-open');
            }
        });

        statusPopup.querySelectorAll('.portal-sidebar-status-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var status = btn.getAttribute('data-status');
                setStatus(status);
                statusPopup.setAttribute('hidden', '');
                statusTrigger.setAttribute('aria-expanded', 'false');
                if (statusWrap) statusWrap.classList.remove('is-open');
            });
        });

        document.addEventListener('click', function (e) {
            if (!statusPopup.hasAttribute('hidden') && !statusPopup.contains(e.target) && !statusTrigger.contains(e.target)) {
                statusPopup.setAttribute('hidden', '');
                statusTrigger.setAttribute('aria-expanded', 'false');
                if (statusWrap) statusWrap.classList.remove('is-open');
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !statusPopup.hasAttribute('hidden')) {
                statusPopup.setAttribute('hidden', '');
                statusTrigger.setAttribute('aria-expanded', 'false');
                if (statusWrap) statusWrap.classList.remove('is-open');
                statusTrigger.focus();
            }
        });
        window.addEventListener('resize', positionStatusPopup);
        window.addEventListener('scroll', positionStatusPopup, true);
    }

    /* -------------------------------------------------------------------------
       USER MENU POPUP (profile click opens dropdown, positioned beside sidebar)
       ------------------------------------------------------------------------- */
    if (userMenuTrigger && userMenuPopup) {
        const footer = userMenuTrigger.closest('.portal-sidebar-footer');
        const POPUP_GAP = 8;

        function positionPopup() {
            if (sidebar && !userMenuPopup.hasAttribute('hidden')) {
                const sidebarRect = sidebar.getBoundingClientRect();
                const triggerRect = userMenuTrigger.getBoundingClientRect();
                userMenuPopup.style.left = (sidebarRect.right + POPUP_GAP) + 'px';
                userMenuPopup.style.bottom = (window.innerHeight - triggerRect.bottom) + 'px';
            }
        }

        function openMenu() {
            userMenuPopup.removeAttribute('hidden');
            userMenuTrigger.setAttribute('aria-expanded', 'true');
            footer && footer.classList.add('is-open');
            positionPopup();
            closeAccountSettingsPopup();
        }
        function closeMenu() {
            userMenuPopup.setAttribute('hidden', '');
            userMenuTrigger.setAttribute('aria-expanded', 'false');
            footer && footer.classList.remove('is-open');
            closeAccountSettingsPopup();
        }
        function toggleMenu() {
            if (userMenuPopup.hasAttribute('hidden')) {
                openMenu();
            } else {
                closeMenu();
            }
        }
        userMenuTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleMenu();
        });
        document.addEventListener('click', function (e) {
            var target = e.target;
            var accPop = document.getElementById('accountSettingsPopup');
            var accBtn = document.getElementById('accountSettingsToggle');
            if (userMenuPopup.hasAttribute('hidden')) return;
            if (!userMenuPopup.contains(target) && !userMenuTrigger.contains(target)) {
                closeMenu();
            } else if (accPop && !accPop.hasAttribute('hidden') && !accPop.contains(target) && accBtn && !accBtn.contains(target)) {
                closeAccountSettingsPopup();
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            var accPopup = document.getElementById('accountSettingsPopup');
            if (accPopup && !accPopup.hasAttribute('hidden')) {
                closeAccountSettingsPopup();
                e.preventDefault();
                return;
            }
            if (!userMenuPopup.hasAttribute('hidden')) {
                closeMenu();
                userMenuTrigger.focus();
            }
        });
        window.addEventListener('resize', positionPopup);
        window.addEventListener('scroll', positionPopup, true);
    }

    /* -------------------------------------------------------------------------
       ACCOUNT SETTINGS POPUP (opens to the right of user menu)
       ------------------------------------------------------------------------- */
    const accountSettingsToggle = document.getElementById('accountSettingsToggle');
    const accountSettingsPopup = document.getElementById('accountSettingsPopup');
    const accountSettingsClose = document.getElementById('accountSettingsClose');

    function positionAccountSettingsPopup() {
        if (!userMenuPopup || !accountSettingsPopup || accountSettingsPopup.hasAttribute('hidden')) return;
        var userRect = userMenuPopup.getBoundingClientRect();
        var gap = 8;
        accountSettingsPopup.style.left = (userRect.right + gap) + 'px';
        accountSettingsPopup.style.bottom = (window.innerHeight - userRect.bottom) + 'px';
    }

    function closeAccountSettingsPopup() {
        if (accountSettingsPopup) accountSettingsPopup.setAttribute('hidden', '');
        if (accountSettingsToggle) accountSettingsToggle.setAttribute('aria-expanded', 'false');
    }

    function openAccountSettingsPopup() {
        if (!accountSettingsPopup || !userMenuPopup) return;
        accountSettingsPopup.removeAttribute('hidden');
        accountSettingsToggle.setAttribute('aria-expanded', 'true');
        positionAccountSettingsPopup();
    }

    if (accountSettingsToggle && accountSettingsPopup) {
        accountSettingsToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (accountSettingsPopup.hasAttribute('hidden')) {
                openAccountSettingsPopup();
            } else {
                closeAccountSettingsPopup();
            }
        });
    }
    if (accountSettingsClose && accountSettingsPopup) {
        accountSettingsClose.addEventListener('click', function () {
            closeAccountSettingsPopup();
        });
    }
    window.addEventListener('resize', positionAccountSettingsPopup);
    window.addEventListener('scroll', positionAccountSettingsPopup, true);

    /* -------------------------------------------------------------------------
       MOBILE MENU
       ------------------------------------------------------------------------- */
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function () {
            sidebar && sidebar.classList.add('open');
            sidebarOverlay && sidebarOverlay.classList.add('visible');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            sidebar && sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('visible');
        });
    }

    /* -------------------------------------------------------------------------
       NOTIFICATION POPUP (header bell icon opens dropdown)
       ------------------------------------------------------------------------- */
    if (notificationTrigger && notificationPopup) {
        function openNotificationPopup() {
            notificationPopup.removeAttribute('hidden');
            notificationTrigger.setAttribute('aria-expanded', 'true');
        }

        function closeNotificationPopup() {
            notificationPopup.setAttribute('hidden', '');
            notificationTrigger.setAttribute('aria-expanded', 'false');
        }

        // Toggle popup on trigger click
        notificationTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            if (notificationPopup.hasAttribute('hidden')) {
                openNotificationPopup();
            } else {
                closeNotificationPopup();
            }
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            const target = e.target;
            if (!notificationPopup.hasAttribute('hidden') && 
                !notificationPopup.contains(target) && 
                !notificationTrigger.contains(target)) {
                closeNotificationPopup();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !notificationPopup.hasAttribute('hidden')) {
                closeNotificationPopup();
                notificationTrigger.focus();
            }
        });

        // Mark all as read handler
        if (markAllRead) {
            markAllRead.addEventListener('click', function (e) {
                e.stopPropagation();
                markNotificationRead([]); // Empty array means mark all as read
            });
        }

        // Load notifications from database
        function loadNotifications() {
            const notificationList = document.getElementById('notificationList');
            if (!notificationList) return;

            // Get CSRF token
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

            // Determine API URL based on current path
            let apiUrl = '/admin/notifications-api.php';
            if (window.location.pathname.includes('/human-resource')) {
                apiUrl = '/human-resource/notifications-api.php';
            } else if (window.location.pathname.includes('/super-admin') || window.location.pathname.includes('/super_admin')) {
                apiUrl = '/super-admin/notifications-api.php';
            }

            fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                credentials: 'same-origin'
            })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                }).catch(function () {
                    return { ok: false, data: null };
                });
            })
            .then(function (result) {
                const emptyState = notificationList.querySelector('.portal-notification-empty');
                if (!result.ok || !result.data || !result.data.success) {
                    if (emptyState) emptyState.style.display = 'flex';
                    if (notificationBadge) notificationBadge.textContent = '';
                    return;
                }

                const notifications = result.data.notifications || [];
                const unreadCount = result.data.unread_count || 0;

                // Clear existing items (except empty state)
                const existingItems = notificationList.querySelectorAll('.portal-notification-item');
                existingItems.forEach(function (item) {
                    item.remove();
                });

                if (notifications.length === 0) {
                    if (emptyState) emptyState.style.display = 'flex';
                } else {
                    if (emptyState) emptyState.style.display = 'none';

                    notifications.forEach(function (notif) {
                        const item = document.createElement('a');
                        item.className = 'portal-notification-item' + (notif.unread ? ' unread' : '');
                        item.href = notif.url || '#';
                        item.setAttribute('data-notification-id', notif.id);
                        item.innerHTML = '<div class="portal-notification-item-icon"><i class="fas ' + (notif.icon || 'fa-bell') + '" aria-hidden="true"></i></div>' +
                            '<div class="portal-notification-item-content">' +
                            '<div class="portal-notification-item-title">' + (notif.title || 'Notification') + '</div>' +
                            '<div class="portal-notification-item-message">' + (notif.message || '') + '</div>' +
                            '<div class="portal-notification-item-time">' + (notif.time_ago || '') + '</div>' +
                            '</div>';

                        // Mark as read on click
                        item.addEventListener('click', function (e) {
                            if (notif.unread) {
                                markNotificationRead([notif.id]);
                            }
                        });

                        notificationList.appendChild(item);
                    });
                }

                // Update badge count
                if (notificationBadge) {
                    notificationBadge.textContent = unreadCount > 0 ? unreadCount : '';
                }
            })
            .catch(function (error) {
                console.error('Failed to load notifications:', error);
                const emptyState = notificationList.querySelector('.portal-notification-empty');
                if (emptyState) emptyState.style.display = 'flex';
                if (notificationBadge) notificationBadge.textContent = '';
            });
        }

        // Mark notification(s) as read
        function markNotificationRead(notificationIds) {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

            let apiUrl = '/admin/notifications-mark-read.php';
            if (window.location.pathname.includes('/human-resource')) {
                apiUrl = '/human-resource/notifications-mark-read.php';
            } else if (window.location.pathname.includes('/super-admin') || window.location.pathname.includes('/super_admin')) {
                apiUrl = '/super-admin/notifications-mark-read.php';
            }

            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    action: notificationIds.length === 0 ? 'mark_all_read' : 'mark_read',
                    notification_ids: notificationIds
                })
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    // Reload notifications to update UI
                    loadNotifications();
                }
            })
            .catch(function (error) {
                console.error('Failed to mark notifications as read:', error);
            });
        }

        // Load notifications on page load
        loadNotifications();

        // Refresh notifications every 60 seconds
        setInterval(loadNotifications, 60000);
    }

    /* -------------------------------------------------------------------------
       RESIZE
       ------------------------------------------------------------------------- */
    window.addEventListener('resize', function () {
        if (window.innerWidth > 1024) {
            sidebar && sidebar.classList.remove('open');
            sidebarOverlay && sidebarOverlay.classList.remove('visible');
        }
    });

    /* -------------------------------------------------------------------------
       MY TASKS: tab switching (ARIA tablist/tab/tabpanel)
       Clicking a tab switches content without full page reload; URL updated.
       Keyboard: Arrow Left/Right (move and activate), Home/End (first/last).
       ------------------------------------------------------------------------- */
    (function initTasksTabs() {
        var wrap = document.querySelector('.portal-tasks-tabs-wrap[data-tablist="tasks"]');
        if (!wrap) return;

        var tablist = wrap.querySelector('[role="tablist"]');
        var tabs = wrap.querySelectorAll('[role="tab"]');
        var panelsContainer = document.querySelector('.portal-tasks-panels');
        var panels = panelsContainer ? panelsContainer.querySelectorAll('[role="tabpanel"]') : [];

        function getViewFromUrl() {
            var params = new URLSearchParams(window.location.search);
            return params.get('view') || 'overview';
        }

        function setActiveTab(viewKey) {
            tabs.forEach(function (tab) {
                var key = tab.getAttribute('data-tab');
                var isActive = key === viewKey;
                tab.classList.toggle('active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.setAttribute('tabindex', isActive ? '0' : '-1');
            });
            panels.forEach(function (panel) {
                var key = panel.getAttribute('data-panel');
                var isActive = key === viewKey;
                panel.classList.toggle('is-active', isActive);
                panel.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });
        }

        function updateUrl(viewKey) {
            var url = new URL(window.location.href);
            url.searchParams.set('view', viewKey);
            if (window.history && window.history.replaceState) {
                window.history.replaceState({ view: viewKey }, '', url.toString());
            }
        }

        function activateTab(tabEl) {
            var key = tabEl.getAttribute('data-tab');
            if (!key) return;
            setActiveTab(key);
            updateUrl(key);
        }

        function focusTab(index) {
            var i = Math.max(0, Math.min(index, tabs.length - 1));
            if (tabs[i]) tabs[i].focus();
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function (e) {
                if (tab.getAttribute('href') && tab.getAttribute('href').indexOf('page=tasks') !== -1) {
                    e.preventDefault();
                    activateTab(tab);
                }
            });
        });

        if (tablist) {
            tablist.addEventListener('keydown', function (e) {
                var currentIndex = -1;
                for (var i = 0; i < tabs.length; i++) {
                    if (document.activeElement === tabs[i]) {
                        currentIndex = i;
                        break;
                    }
                }
                if (currentIndex === -1) return;

                switch (e.key) {
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        e.preventDefault();
                        focusTab(currentIndex - 1);
                        activateTab(tabs[currentIndex - 1] || tabs[currentIndex]);
                        break;
                    case 'ArrowRight':
                    case 'ArrowDown':
                        e.preventDefault();
                        focusTab(currentIndex + 1);
                        activateTab(tabs[currentIndex + 1] || tabs[currentIndex]);
                        break;
                    case 'Home':
                        e.preventDefault();
                        focusTab(0);
                        activateTab(tabs[0]);
                        break;
                    case 'End':
                        e.preventDefault();
                        focusTab(tabs.length - 1);
                        activateTab(tabs[tabs.length - 1]);
                        break;
                }
            });
        }

        setActiveTab(getViewFromUrl());
    })();

    /* -------------------------------------------------------------------------
       SETTINGS PAGE: slider value display (items per page, notification volume)
       ------------------------------------------------------------------------- */
    (function initSettingsSliders() {
        var itemsEl = document.getElementById('itemsPerPage');
        var itemsVal = document.getElementById('itemsPerPageValue');
        if (itemsEl && itemsVal) {
            function updateItems() {
                itemsVal.textContent = itemsEl.value + ' items';
            }
            itemsEl.addEventListener('input', updateItems);
            updateItems();
        }
        var volEl = document.getElementById('notifyVolume');
        var volVal = document.getElementById('notifyVolumeValue');
        if (volEl && volVal) {
            function updateVol() {
                volVal.textContent = volEl.value + '%';
            }
            volEl.addEventListener('input', updateVol);
            updateVol();
        }
    })();
})();
