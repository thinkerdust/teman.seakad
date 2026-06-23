import Alpine from 'alpinejs';

Alpine.data('invitationContentManager', (config) => ({
    activeTab: sessionStorage.getItem('invitation_content_active_tab') || 'gallery',
    stories: config.stories || [],
    events: config.events || [],
    deletedStoryIds: [],
    deletedEventIds: [],

    // Musik Latar
    allMusic: config.allMusic || [],
    themeSlug: config.themeSlug || '',
    weddingMood: config.weddingMood || '',
    selectedMusicId: config.selectedMusicId || null,
    
    // Search & Filter
    musicSearch: '',
    musicFilterMood: '',
    musicFilterGenre: '',
    musicFilterLanguage: '',
    
    // Audio Player
    previewAudio: null,
    previewPlayingId: null,

    init() {
        this.$watch('activeTab', value => sessionStorage.setItem('invitation_content_active_tab', value));
        
        // Stop audio if tab changes
        this.$watch('activeTab', value => {
            if (value !== 'music') {
                this.stopPreview();
            }
        });
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
    },

    // Musik helper
    togglePreview(music) {
        if (this.previewPlayingId === music.id) {
            this.stopPreview();
        } else {
            this.stopPreview();
            this.previewAudio = new Audio(music.file);
            this.previewAudio.addEventListener('ended', () => {
                this.previewPlayingId = null;
            });
            this.previewAudio.play().catch(err => console.log('Preview failed:', err));
            this.previewPlayingId = music.id;
        }
    },

    stopPreview() {
        if (this.previewAudio) {
            this.previewAudio.pause();
            this.previewAudio = null;
        }
        this.previewPlayingId = null;
    },

    selectMusic(id) {
        this.selectedMusicId = id;
        this.$nextTick(() => {
            document.getElementById('music-select-form').submit();
        });
    },

    get recommendedMusic() {
        // Tentukan mood rekomendasi berdasarkan tema jika weddingMood belum dipilih
        let targetMoods = [];
        const theme = this.themeSlug.toLowerCase();
        
        if (theme === 'floral-elegant' || theme === 'floral') {
            targetMoods = ['Romantic'];
        } else if (theme === 'luxury-gold' || theme === 'luxury') {
            targetMoods = ['Elegant', 'Luxury'];
        } else if (theme === 'islamic-wedding' || theme === 'islamic') {
            targetMoods = ['Islamic'];
        } else if (theme === 'rustic-forest' || theme === 'rustic') {
            targetMoods = ['Acoustic', 'Classic'];
        } else {
            targetMoods = ['Romantic', 'Elegant'];
        }

        // Jika user memilih mood, tambahkan atau prioritaskan mood tersebut
        if (this.weddingMood) {
            targetMoods = [this.weddingMood];
        }

        return this.allMusic.filter(m => targetMoods.some(mood => m.mood.toLowerCase() === mood.toLowerCase()));
    },

    get filteredMusic() {
        return this.allMusic.filter(m => {
            // Search filter
            if (this.musicSearch) {
                const search = this.musicSearch.toLowerCase();
                const matchSearch = m.title.toLowerCase().includes(search) || 
                                    m.artist.toLowerCase().includes(search) || 
                                    (m.album && m.album.toLowerCase().includes(search));
                if (!matchSearch) return false;
            }
            
            // Mood filter
            if (this.musicFilterMood && m.mood.toLowerCase() !== this.musicFilterMood.toLowerCase()) {
                return false;
            }
            
            // Genre filter
            if (this.musicFilterGenre && m.genre.toLowerCase() !== this.musicFilterGenre.toLowerCase()) {
                return false;
            }
            
            // Language filter
            if (this.musicFilterLanguage && m.language.toLowerCase() !== this.musicFilterLanguage.toLowerCase()) {
                return false;
            }
            
            return true;
        });
    }
}));
