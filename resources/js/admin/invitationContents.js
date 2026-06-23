import Alpine from 'alpinejs';

Alpine.data('invitationContentManager', (config) => ({
    activeTab: sessionStorage.getItem('invitation_content_active_tab') || 'gallery',
    stories: config.stories || [],
    events: config.events || [],
    deletedStoryIds: [],
    deletedEventIds: [],

    init() {
        this.$watch('activeTab', value => sessionStorage.setItem('invitation_content_active_tab', value));
    },

    addStory() {
        this.stories.push({ id: null, title: '', date: '', description: '' });
    },

    removeStory(index) {
        const id = this.stories[index].id;
        if (id) {
            this.deletedStoryIds.push(id);
        }
        this.stories.splice(index, 1);
    },

    addEvent() {
        this.events.push({ id: null, name: '', date: '', time: '', location: '' });
    },

    removeEvent(index) {
        const id = this.events[index].id;
        if (id) {
            this.deletedEventIds.push(id);
        }
        this.events.splice(index, 1);
    }
}));
