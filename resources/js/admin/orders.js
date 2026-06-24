import Alpine from 'alpinejs';

Alpine.data('ordersManager', (config) => ({
    createModalOpen: config.hasErrors && !config.oldId,
    editModalOpen: config.hasErrors && !!config.oldId,
    deleteModalOpen: false,
    detailModalOpen: false,
    activateModalOpen: false,
    
    packages: config.packages || [],
    
    loading: false,
    errors: {},
    
    selectedOrder: {
        id: config.oldId || '',
        order_number: config.oldOrderNumber || '',
        customer_name: config.oldCustomerName || '',
        phone: config.oldPhone || '',
        email: config.oldEmail || '',
        package_id: config.oldPackageId || '',
        quota: config.oldQuota || 1,
        price: config.oldPrice || 0,
        status: config.oldStatus || 'pending',
        start_date: config.oldStartDate || '',
        end_date: config.oldEndDate || '',
        notes: config.oldNotes || '',
        user_id: null,
        user: null
    },

    init() {
        this.$watch('selectedOrder.package_id', (value) => {
            if (!value) return;
            const pkg = this.packages.find(p => p.id == value);
            if (pkg) {
                this.selectedOrder.price = Math.round(pkg.price);
                this.selectedOrder.quota = pkg.invitation_quota;
            }
        });
    },

    showDetail(order) {
        this.errors = {};
        this.selectedOrder = { ...order };
        this.detailModalOpen = true;
    },
    
    editOrder(order) {
        this.errors = {};
        this.selectedOrder = {
            id: order.id,
            order_number: order.order_number,
            customer_name: order.customer_name,
            phone: order.phone,
            email: order.email,
            package_id: order.package_id || '',
            quota: order.quota,
            price: order.price,
            status: order.status,
            start_date: order.start_date ? this.formatDate(order.start_date) : '',
            end_date: order.end_date ? this.formatDate(order.end_date) : '',
            notes: order.notes || '',
            user_id: order.user_id
        };
        this.editModalOpen = true;
    },

    openActivate(order) {
        this.errors = {};
        
        // Default start date = today
        const today = new Date();
        const todayStr = this.formatDate(today);

        // Default end date = today + 30 days
        const expiry = new Date();
        expiry.setDate(today.getDate() + 30);
        const expiryStr = this.formatDate(expiry);

        this.selectedOrder = {
            id: order.id,
            order_number: order.order_number,
            customer_name: order.customer_name,
            start_date: order.start_date ? this.formatDate(order.start_date) : todayStr,
            end_date: order.end_date ? this.formatDate(order.end_date) : expiryStr
        };
        this.activateModalOpen = true;
    },

    closeCreateModal() {
        this.createModalOpen = false;
        this.errors = {};
        const form = document.getElementById('create-order-form');
        if (form) {
            form.reset();
        }
    },

    closeEditModal() {
        this.editModalOpen = false;
        this.errors = {};
        const form = document.getElementById('edit-order-form');
        if (form) {
            form.reset();
        }
    },

    confirmDelete(order) {
        this.errors = {};
        this.selectedOrder = order;
        this.deleteModalOpen = true;
    },

    formatDate(dateVal) {
        if (!dateVal) return '';
        const d = new Date(dateVal);
        if (isNaN(d.getTime())) return '';
        
        const year = d.getFullYear();
        let month = '' + (d.getMonth() + 1);
        let day = '' + d.getDate();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    },

    submitForm(event, modalType) {
        this.loading = true;
        this.errors = {};
        
        const form = event.target;
        const actionUrl = form.action;
        const formData = new FormData(form);

        window.axios({
            method: 'POST', // Use POST (with _method if PUT/PATCH)
            url: actionUrl,
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            this.loading = false;
            
            // Follow standard redirect behavior: parse response page
            const parser = new DOMParser();
            const doc = parser.parseFromString(response.data, 'text/html');
            
            // 1. Replace the table contents
            const newTable = doc.getElementById('orders-table-container');
            const currentTable = document.getElementById('orders-table-container');
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
            } else if (modalType === 'activate') {
                this.activateModalOpen = false;
            } else if (modalType === 'delete') {
                this.deleteModalOpen = false;
            }
        })
        .catch(error => {
            this.loading = false;
            if (error.response && error.response.status === 422) {
                // Validation error
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
