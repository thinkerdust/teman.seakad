import Alpine from 'alpinejs';

Alpine.data('usersManager', (config) => ({
    createUserModalOpen: config.hasErrors && !config.oldId,
    editUserModalOpen: config.hasErrors && !!config.oldId,
    deleteUserModalOpen: false,
    resetPasswordModalOpen: false,
    
    loading: false,
    errors: {},
    
    selectedUser: {
        id: config.oldId || '',
        name: config.oldName || '',
        email: config.oldEmail || '',
        phone: config.oldPhone || '',
        status: config.oldStatus || 'active',
        avatar_url: ''
    },
    
    editUser(user) {
        this.errors = {};
        this.selectedUser = {
            id: user.id,
            name: user.name,
            email: user.email,
            phone: user.phone || '',
            status: user.status,
            avatar_url: user.avatar ? '/storage/' + user.avatar : ''
        };
        this.editUserModalOpen = true;
    },

    closeCreateModal() {
        this.createUserModalOpen = false;
        this.errors = {};
        const form = document.getElementById('create-user-form');
        if (form) {
            form.reset();
        }
        window.dispatchEvent(new CustomEvent('reset-avatar'));
    },

    closeEditModal() {
        this.editUserModalOpen = false;
        this.errors = {};
        const form = document.getElementById('edit-user-form');
        if (form) {
            form.reset();
        }
    },

    confirmDelete(user) {
        this.errors = {};
        this.selectedUser = user;
        this.deleteUserModalOpen = true;
    },

    openResetPassword(user) {
        this.errors = {};
        this.selectedUser = user;
        this.resetPasswordModalOpen = true;
    },

    submitForm(event, modalType) {
        this.loading = true;
        this.errors = {};
        
        const form = event.target;
        const actionUrl = form.action;
        const formData = new FormData(form);

        // Axios request to form action
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
            
            // The response HTML contains the updated redirected page
            const parser = new DOMParser();
            const doc = parser.parseFromString(response.data, 'text/html');
            
            // 1. Replace the table contents
            const newTable = doc.getElementById('users-table-container');
            const currentTable = document.getElementById('users-table-container');
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

            // 3. Close the modal
            if (modalType === 'create') {
                this.closeCreateModal();
            } else if (modalType === 'edit') {
                this.closeEditModal();
            } else if (modalType === 'reset') {
                this.resetPasswordModalOpen = false;
                const resetForm = document.getElementById('reset-password-form');
                if (resetForm) resetForm.reset();
            } else if (modalType === 'delete') {
                this.deleteUserModalOpen = false;
            }
        })
        .catch(error => {
            this.loading = false;
            if (error.response && error.response.status === 422) {
                // Validation errors from Laravel Form Requests
                this.errors = error.response.data.errors || {};
            } else {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan pada sistem. Silakan coba lagi.');
            }
        });
    }
}));
