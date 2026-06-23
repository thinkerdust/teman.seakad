import Alpine from 'alpinejs';

Alpine.data('themesManager', (config) => ({
    createModalOpen: config.hasErrors && !config.oldId,
    editModalOpen: config.hasErrors && !!config.oldId,
    deleteModalOpen: false,
    
    loading: false,
    errors: {},
    
    selectedTheme: {
        id: config.oldId || '',
        name: config.oldName || '',
        slug: config.oldSlug || '',
        folder: config.oldFolder || '',
        description: config.oldDescription || '',
        status: config.oldStatus || 'active',
        thumbnail_url: ''
    },
    
    init() {
        // Automatically generate slug from name in create form
        this.$watch('selectedTheme.name', (value) => {
            if (!this.selectedTheme.id) { // Only auto-slug for new themes
                this.selectedTheme.slug = this.slugify(value);
            }
        });
    },

    slugify(text) {
        return text
            .toString()
            .toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start
            .replace(/-+$/, '');            // Trim - from end
    },
    
    editTheme(theme) {
        this.errors = {};
        this.selectedTheme = {
            id: theme.id,
            name: theme.name,
            slug: theme.slug,
            folder: theme.folder,
            description: theme.description || '',
            status: theme.status,
            thumbnail_url: theme.thumbnail || ''
        };
        this.editModalOpen = true;
    },

    closeCreateModal() {
        this.createModalOpen = false;
        this.errors = {};
        const form = document.getElementById('create-theme-form');
        if (form) {
            form.reset();
        }
        this.selectedTheme = {
            id: '',
            name: '',
            slug: '',
            folder: '',
            description: '',
            status: 'active',
            thumbnail_url: ''
        };
    },

    closeEditModal() {
        this.editModalOpen = false;
        this.errors = {};
        const form = document.getElementById('edit-theme-form');
        if (form) {
            form.reset();
        }
        this.selectedTheme = {
            id: '',
            name: '',
            slug: '',
            folder: '',
            description: '',
            status: 'active',
            thumbnail_url: ''
        };
    },

    confirmDelete(theme) {
        this.errors = {};
        this.selectedTheme = theme;
        this.deleteModalOpen = true;
    },

    submitForm(event, modalType) {
        this.loading = true;
        this.errors = {};
        
        const form = event.target;
        const actionUrl = form.action;
        const formData = new FormData(form);

        window.axios({
            method: 'POST', // Send as POST so PHP can parse multipart file data
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
            
            // Replace themes table container
            const newTable = doc.getElementById('themes-table-container');
            const currentTable = document.getElementById('themes-table-container');
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
