import Alpine from 'alpinejs';

Alpine.data('guestsManager', (config) => ({
    createModalOpen: config.hasErrors && !config.oldId,
    editModalOpen: config.hasErrors && !!config.oldId,
    deleteModalOpen: false,
    importModalOpen: false,
    
    loading: false,
    errors: {},
    
    selectedGuest: {
        id: config.oldId || '',
        name: config.oldName || '',
        phone: config.oldPhone || '',
        attendance: config.oldAttendance || 'hadir',
        message: config.oldMessage || ''
    },
    
    invitationSlug: config.invitationSlug || '',
    baseUrl: window.location.origin,

    editGuest(guest) {
        this.errors = {};
        this.selectedGuest = {
            id: guest.id,
            name: guest.name,
            phone: guest.phone || '',
            attendance: guest.attendance,
            message: guest.message || ''
        };
        this.editModalOpen = true;
    },

    closeCreateModal() {
        this.createModalOpen = false;
        this.errors = {};
        const form = document.getElementById('create-guest-form');
        if (form) {
            form.reset();
        }
        this.selectedGuest = {
            id: '',
            name: '',
            phone: '',
            attendance: 'hadir',
            message: ''
        };
    },

    closeEditModal() {
        this.editModalOpen = false;
        this.errors = {};
        const form = document.getElementById('edit-guest-form');
        if (form) {
            form.reset();
        }
        this.selectedGuest = {
            id: '',
            name: '',
            phone: '',
            attendance: 'hadir',
            message: ''
        };
    },

    confirmDelete(guest) {
        this.errors = {};
        this.selectedGuest = guest;
        this.deleteModalOpen = true;
    },

    getPersonalLink(guestName) {
        const encodedName = encodeURIComponent(guestName).replace(/%20/g, '+');
        return `${this.baseUrl}/${this.invitationSlug}?to=${encodedName}`;
    },

    copyPersonalLink(guestName) {
        const link = this.getPersonalLink(guestName);
        navigator.clipboard.writeText(link).then(() => {
            alert('Tautan personal tamu berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin:', err);
            // Fallback jika clipboard API ditolak / tidak didukung di http non-secure
            const tempInput = document.createElement('input');
            tempInput.value = link;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('Tautan personal tamu berhasil disalin!');
        });
    },

    submitForm(event, modalType) {
        this.loading = true;
        this.errors = {};
        
        const form = event.target;
        const actionUrl = form.action;
        const formData = new FormData(form);

        window.axios({
            method: 'POST', // Send as POST for multipart CSV / _method PUT parsing
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
            
            // Replace guests table container
            const newTable = doc.getElementById('guests-table-container');
            const currentTable = document.getElementById('guests-table-container');
            if (newTable && currentTable) {
                currentTable.innerHTML = newTable.innerHTML;
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(currentTable);
                }
            }

            // Replace flash alerts
            const newAlerts = doc.getElementById('flash-alerts-container');
            const currentAlerts = document.getElementById('flash-alerts-container');
            if (newAlerts && currentAlerts) {
                currentAlerts.innerHTML = newAlerts.innerHTML;
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(currentAlerts);
                }
            }

            // Close modals
            if (modalType === 'create') {
                this.closeCreateModal();
            } else if (modalType === 'edit') {
                this.closeEditModal();
            } else if (modalType === 'delete') {
                this.deleteModalOpen = false;
            } else if (modalType === 'import') {
                this.importModalOpen = false;
                const importForm = document.getElementById('import-guests-form');
                if (importForm) {
                    importForm.reset();
                }
            }
        })
        .catch(error => {
            this.loading = false;
            if (error.response && error.response.status === 422) {
                this.errors = error.response.data.errors || {};
            } else if (error.response && error.response.status === 403) {
                alert(error.response.data.message || 'Anda tidak memiliki hak akses.');
            } else {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan pada sistem. Silakan coba lagi.');
            }
        });
    }
}));
