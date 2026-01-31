/**
 * Golden Z-5 HR System – Company Dashboards Overview (standalone page)
 * Tab switch, grid view toggle (no modal)
 * public/assets/js/dashboards-overview.js
 */
(function () {
  'use strict';

  function onDOMReady(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  onDOMReady(function () {
    var viewTabsBtn = document.getElementById('dashboardsOverviewViewTabs');
    var viewGridBtn = document.getElementById('dashboardsOverviewViewGrid');
    var gridContainer = document.getElementById('dashboardsOverviewGridContainer');
    var tabsContainer = document.getElementById('dashboardsOverviewTabsContainer');
    var previewContainer = document.getElementById('dashboardsOverviewPreviewContainer');
    var tabButtons = document.querySelectorAll('[data-dashboards-role-tab]');
    var roleTilePreviewBtns = document.querySelectorAll('[data-dashboards-role-preview]');

    function showView(view) {
      if (view === 'grid') {
        if (viewGridBtn) viewGridBtn.classList.add('active');
        if (viewTabsBtn) viewTabsBtn.classList.remove('active');
        if (gridContainer) gridContainer.classList.remove('hidden');
        if (tabsContainer) tabsContainer.classList.add('hidden');
        if (previewContainer) previewContainer.classList.add('hidden');
      } else {
        if (viewTabsBtn) viewTabsBtn.classList.add('active');
        if (viewGridBtn) viewGridBtn.classList.remove('active');
        if (gridContainer) gridContainer.classList.add('hidden');
        if (tabsContainer) tabsContainer.classList.remove('hidden');
        if (previewContainer) previewContainer.classList.remove('hidden');
      }
    }

    function setActiveTab(roleId) {
      tabButtons.forEach(function (btn) {
        if (btn.getAttribute('data-dashboards-role-tab') === roleId) {
          btn.classList.add('active');
        } else {
          btn.classList.remove('active');
        }
      });
      var previews = document.querySelectorAll('.dashboards-overview-preview');
      previews.forEach(function (el) {
        if (el.id === 'dashboardsPreview-' + roleId) {
          el.classList.add('active');
        } else {
          el.classList.remove('active');
        }
      });
    }

    if (viewTabsBtn) {
      viewTabsBtn.addEventListener('click', function () {
        showView('tabs');
        setActiveTab('admin');
      });
    }
    if (viewGridBtn) {
      viewGridBtn.addEventListener('click', function () {
        showView('grid');
      });
    }

    tabButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var roleId = btn.getAttribute('data-dashboards-role-tab');
        if (roleId) setActiveTab(roleId);
      });
    });

    roleTilePreviewBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var roleId = btn.getAttribute('data-dashboards-role-preview');
        if (roleId) {
          showView('tabs');
          setActiveTab(roleId);
        }
      });
    });
  });
})();
