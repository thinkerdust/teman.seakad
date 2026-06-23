import Alpine from 'alpinejs';

Alpine.data('menusManager', (config) => ({
    createMenuModalOpen: config.hasErrors && !config.oldId,
    editMenuModalOpen: config.hasErrors && !!config.oldId,
    deleteMenuModalOpen: false,
    
    loading: false,
    errors: {},
    
    selectedMenu: {
        id: config.oldId || '',
        parent_id: config.oldParentId || '',
        title: config.oldTitle || '',
        icon: config.oldIcon || '',
        route: config.oldRoute || '',
        permission: config.oldPermission || '',
        order: config.oldOrder || 0,
        status: config.oldStatus || 'active'
    },
    
    editMenu(menu) {
        this.errors = {};
        this.selectedMenu = {
            id: menu.id,
            parent_id: menu.parent_id || '',
            title: menu.title,
            icon: menu.icon || '',
            route: menu.route || '',
            permission: menu.permission || '',
            order: menu.order,
            status: menu.status
        };
        this.editMenuModalOpen = true;
    },

    closeCreateModal() {
        this.createMenuModalOpen = false;
        this.errors = {};
        const form = document.getElementById('create-menu-form');
        if (form) {
            form.reset();
        }
    },

    closeEditModal() {
        this.editMenuModalOpen = false;
        this.errors = {};
        const form = document.getElementById('edit-menu-form');
        if (form) {
            form.reset();
        }
    },

    confirmDelete(menu) {
        this.errors = {};
        this.selectedMenu = menu;
        this.deleteMenuModalOpen = true;
    },

    submitForm(event, modalType) {
        this.loading = true;
        this.errors = {};
        
        const form = event.target;
        const actionUrl = form.action;
        const formData = new FormData(form);

        window.axios({
            method: form.method || 'POST',
            url: actionUrl,
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            this.loading = false;
            
            // Parse redirected page contents
            const parser = new DOMParser();
            const doc = parser.parseFromString(response.data, 'text/html');
            
            // 1. Replace dynamic sidebar (to see changes in real-time)
            const newSidebar = doc.querySelector('aside');
            const currentSidebar = document.querySelector('aside');
            if (newSidebar && currentSidebar) {
                currentSidebar.innerHTML = newSidebar.innerHTML;
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(currentSidebar);
                }
            }

            // 2. Replace menus table container
            const newTable = doc.getElementById('menus-table-container');
            const currentTable = document.getElementById('menus-table-container');
            if (newTable && currentTable) {
                currentTable.innerHTML = newTable.innerHTML;
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(currentTable);
                }
            }

            // 3. Replace flash alerts
            const newAlerts = doc.getElementById('flash-alerts-container');
            const currentAlerts = document.getElementById('flash-alerts-container');
            if (newAlerts && currentAlerts) {
                currentAlerts.innerHTML = newAlerts.innerHTML;
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(currentAlerts);
                }
            }

            // 4. Close modals
            if (modalType === 'create') {
                this.closeCreateModal();
            } else if (modalType === 'edit') {
                this.closeEditModal();
            } else if (modalType === 'delete') {
                this.deleteMenuModalOpen = false;
            }
        })
        .catch(error => {
            this.loading = false;
            if (error.response && error.response.status === 422) {
                this.errors = error.response.data.errors || {};
            } else if (error.response && error.response.status === 403) {
                alert(error.response.data.message || 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
            } else {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan pada sistem. Silakan coba lagi.');
            }
        });
    }
}));
