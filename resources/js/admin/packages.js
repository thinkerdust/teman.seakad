import Alpine from 'alpinejs';

Alpine.data('packagesManager', (config) => ({
    createModalOpen: config.hasErrors && !config.oldId,
    editModalOpen: config.hasErrors && !!config.oldId,
    deleteModalOpen: false,
    detailModalOpen: false,
    
    loading: false,
    errors: {},
    
    selectedPackage: {
        id: config.oldId || '',
        name: config.oldName || '',
        description: config.oldDescription || '',
        price: config.oldPrice || 0,
        invitation_quota: config.oldInvitationQuota || 1,
        duration_days: config.oldDurationDays || 30,
        status: config.oldStatus || 'active'
    },

    init() {
        // Initialization if needed
    },

    showDetail(pkg) {
        this.errors = {};
        this.selectedPackage = { ...pkg };
        this.detailModalOpen = true;
    },
    
    editPackage(pkg) {
        this.errors = {};
        this.selectedPackage = {
            id: pkg.id,
            name: pkg.name,
            description: pkg.description || '',
            price: pkg.price,
            invitation_quota: pkg.invitation_quota,
            duration_days: pkg.duration_days,
            status: pkg.status
        };
        this.editModalOpen = true;
    },

    closeCreateModal() {
        this.createModalOpen = false;
        this.errors = {};
        const form = document.getElementById('create-package-form');
        if (form) {
            form.reset();
        }
    },

    closeEditModal() {
        this.editModalOpen = false;
        this.errors = {};
        const form = document.getElementById('edit-package-form');
        if (form) {
            form.reset();
        }
    },

    confirmDelete(pkg) {
        this.errors = {};
        this.selectedPackage = pkg;
        this.deleteModalOpen = true;
    },

    submitForm(event, modalType) {
        this.loading = true;
        this.errors = {};
        
        const form = event.target;
        const actionUrl = form.action;
        const formData = new FormData(form);

        window.axios({
            method: 'POST',
            url: actionUrl,
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            this.loading = false;
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(response.data, 'text/html');
            
            // 1. Replace the table contents
            const newTable = doc.getElementById('packages-table-container');
            const currentTable = document.getElementById('packages-table-container');
            if (newTable && currentTable) {
                currentTable.innerHTML = newTable.innerHTML;
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(currentTable);
                }
            }

            // 2. Replace flash alerts
            const newAlerts = doc.getElementById('flash-alerts-container');
            const currentAlerts = document.getElementById('flash-alerts-container');
            if (newAlerts && currentAlerts) {
                currentAlerts.innerHTML = newAlerts.innerHTML;
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(currentAlerts);
                }
            }

            // 3. Close the active modal
            if (modalType === 'create') {
                this.closeCreateModal();
            } else if (modalType === 'edit') {
                this.closeEditModal();
            } else if (modalType === 'delete') {
                this.deleteModalOpen = false;
            }
        })
        .catch(error => {
            this.loading = false;
            if (error.response && error.response.status === 422) {
                this.errors = error.response.data.errors || {};
            } else if (error.response && error.response.status === 403) {
                alert(error.response.data.message || 'Anda tidak memiliki hak akses untuk aksi ini.');
            } else {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan pada sistem. Silakan coba lagi.');
            }
        });
    }
}));
