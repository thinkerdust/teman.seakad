import Alpine from 'alpinejs';

Alpine.data('invitationsManager', (config) => ({
    createModalOpen: config.hasErrors && !config.oldId,
    editModalOpen: config.hasErrors && !!config.oldId,
    deleteModalOpen: false,
    
    loading: false,
    errors: {},
    
    selectedInvitation: {
        id: config.oldId || '',
        theme_id: config.oldThemeId || '',
        title: config.oldTitle || '',
        slug: config.oldSlug || '',
        groom_name: config.oldGroomName || '',
        bride_name: config.oldBrideName || '',
        akad_date: config.oldAkadDate || '',
        reception_date: config.oldReceptionDate || '',
        venue: config.oldVenue || '',
        address: config.oldAddress || '',
        maps_url: config.oldMapsUrl || '',
        description: config.oldDescription || '',
        status: config.oldStatus || 'draft'
    },
    
    init() {
        // Otomatis men-generate slug dari judul undangan di form pembuatan baru
        this.$watch('selectedInvitation.title', (value) => {
            if (!this.selectedInvitation.id) { // Hanya auto-slug untuk undangan baru
                this.selectedInvitation.slug = this.slugify(value);
            }
        });
    },

    slugify(text) {
        return text
            .toString()
            .toLowerCase()
            .replace(/\s+/g, '-')           // Ganti spasi dengan -
            .replace(/[^\w\-]+/g, '')       // Hapus karakter non-word
            .replace(/\-\-+/g, '-')         // Ganti multi - dengan satu -
            .replace(/^-+/, '')             // Trim - dari awal
            .replace(/-+$/, '');            // Trim - dari akhir
    },
    
    editInvitation(invitation) {
        this.errors = {};
        
        // Helper to format datetime-local input string
        const formatDatetime = (dtStr) => {
            if (!dtStr) return '';
            const d = new Date(dtStr);
            if (isNaN(d.getTime())) return '';
            
            // Format to YYYY-MM-DDTHH:MM
            const pad = (n) => String(n).padStart(2, '0');
            return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        };

        this.selectedInvitation = {
            id: invitation.id,
            theme_id: invitation.theme_id,
            title: invitation.title,
            slug: invitation.slug,
            groom_name: invitation.groom_name,
            bride_name: invitation.bride_name,
            akad_date: formatDatetime(invitation.akad_date),
            reception_date: formatDatetime(invitation.reception_date),
            venue: invitation.venue,
            address: invitation.address || '',
            maps_url: invitation.maps_url || '',
            description: invitation.description || '',
            status: invitation.status
        };
        this.editModalOpen = true;
    },

    closeCreateModal() {
        this.createModalOpen = false;
        this.errors = {};
        const form = document.getElementById('create-invitation-form');
        if (form) {
            form.reset();
        }
        this.selectedInvitation = {
            id: '',
            theme_id: '',
            title: '',
            slug: '',
            groom_name: '',
            bride_name: '',
            akad_date: '',
            reception_date: '',
            venue: '',
            address: '',
            maps_url: '',
            description: '',
            status: 'draft'
        };
    },

    closeEditModal() {
        this.editModalOpen = false;
        this.errors = {};
        const form = document.getElementById('edit-invitation-form');
        if (form) {
            form.reset();
        }
        this.selectedInvitation = {
            id: '',
            theme_id: '',
            title: '',
            slug: '',
            groom_name: '',
            bride_name: '',
            akad_date: '',
            reception_date: '',
            venue: '',
            address: '',
            maps_url: '',
            description: '',
            status: 'draft'
        };
    },

    confirmDelete(invitation) {
        this.errors = {};
        this.selectedInvitation = invitation;
        this.deleteModalOpen = true;
    },

    toggleStatusAjax(actionUrl) {
        this.loading = true;
        
        window.axios({
            method: 'POST',
            url: actionUrl,
            data: {
                _method: 'PUT'
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            this.loading = false;
            this.updateDom(response.data);
        })
        .catch(error => {
            this.loading = false;
            console.error('Terjadi kesalahan:', error);
            alert('Terjadi kesalahan sistem saat memperbarui status.');
        });
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
            
            this.updateDom(response.data);

            // Tutup modal
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
                alert(error.response.data.message || 'Anda tidak memiliki hak akses.');
            } else {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan pada sistem. Silakan coba lagi.');
            }
        });
    },

    updateDom(htmlContent) {
        // Parse halaman baru
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlContent, 'text/html');
        
        // Hotswap tabel/grid kontainer undangan
        const newTable = doc.getElementById('invitations-table-container');
        const currentTable = document.getElementById('invitations-table-container');
        if (newTable && currentTable) {
            currentTable.innerHTML = newTable.innerHTML;
            if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                window.Alpine.initTree(currentTable);
            }
        }

        // Hotswap flash alerts
        const newAlerts = doc.getElementById('flash-alerts-container');
        const currentAlerts = document.getElementById('flash-alerts-container');
        if (newAlerts && currentAlerts) {
            currentAlerts.innerHTML = newAlerts.innerHTML;
            if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                window.Alpine.initTree(currentAlerts);
            }
        }
    }
}));
