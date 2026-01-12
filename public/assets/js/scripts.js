const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');

if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');

        const icon = mobileMenuBtn.querySelector('i');
        if (mobileMenu.classList.contains('hidden')) {
            icon.classList.remove('fa-xmark');
            icon.classList.add('fa-bars-staggered');
        } else {
            icon.classList.remove('fa-bars-staggered');
            icon.classList.add('fa-xmark');
        }
    });
}


function searchComponent() {
    return {
        query: '',
        results: [],
        show: false,
        init() {
            this.$watch('query', value => {
                if (!value) this.show = false;
            });
        },
        search() {
            if (this.query.length < 2) {
                this.results = [];
                this.show = false;
                return;
            }

            fetch(`/search-suggestions?q=${encodeURIComponent(this.query)}`)
                .then(res => res.json())
                .then(data => {
                    this.results = data;
                    this.show = data.length > 0;
                });
        },
        goToSearch() {
            if (this.query.trim().length > 0) {
                window.location.href = `/products?search=${encodeURIComponent(this.query)}`;
            }
        }
    }
}
