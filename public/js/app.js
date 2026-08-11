/* Alpine.js helpers & JS utilities */

// Navbar scroll effect + mobile toggle
document.addEventListener('DOMContentLoaded', () => {
    // ─── Navbar Scroll ───────────────────────────
    const navbar = document.getElementById('navbar');
    if (navbar) {
        const onScroll = () => navbar.classList.toggle('scrolled', window.scrollY > 20);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // ─── Mobile Nav Toggle ───────────────────────
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('open');
            navMenu.classList.toggle('open');
        });
        // Close on link click
        navMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('open');
                navMenu.classList.remove('open');
            });
        });
    }

    // ─── Sidebar Toggle (Dashboard) ──────────────
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay?.classList.toggle('open');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });
    }

    // ─── Dropdown Menus ──────────────────────────
    document.querySelectorAll('[data-dropdown]').forEach(trigger => {
        const menuId = trigger.dataset.dropdown;
        const menu = document.getElementById(menuId);
        if (!menu) return;
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('open');
        });
        document.addEventListener('click', () => menu.classList.remove('open'));
    });

    // ─── Auto-dismiss Alerts ─────────────────────
    document.querySelectorAll('.alert-auto-dismiss').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // ─── Toast Notifications ─────────────────────
    window.showToast = (message, type = 'info', duration = 3500) => {
        const container = document.getElementById('toast-container') || (() => {
            const el = document.createElement('div');
            el.id = 'toast-container';
            el.className = 'toast-container';
            document.body.appendChild(el);
            return el;
        })();
        const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `<span>${icons[type] || ''}</span><span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    };

    // ─── Smooth Counter Animation ─────────────────
    const animateCounter = (el) => {
        const target = parseInt(el.dataset.count);
        const duration = 1500;
        const start = performance.now();
        const update = (time) => {
            const progress = Math.min((time - start) / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(ease * target).toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(update);
        };
        requestAnimationFrame(update);
    };
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    document.querySelectorAll('[data-count]').forEach(el => counterObserver.observe(el));

    // ─── Scroll Reveal ────────────────────────────
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-up');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    document.querySelectorAll('[data-reveal]').forEach(el => revealObserver.observe(el));
});

// ─── BMI Calculator (Alpine.js-compatible) ───────────
window.bmiApp = () => ({
    weight: '',
    height: '',
    age: '',
    gender: 'male',
    bmi: null,
    category: '',
    color: '',
    calculating: false,
    calculate() {
        if (!this.weight || !this.height) return;
        this.calculating = true;
        setTimeout(() => {
            const h = this.height / 100;
            this.bmi = +(this.weight / (h * h)).toFixed(1);
            if      (this.bmi < 18.5) { this.category = 'Kurus (Underweight)'; this.color = '#F59E0B'; }
            else if (this.bmi < 25.0) { this.category = 'Normal';              this.color = '#10B981'; }
            else if (this.bmi < 30.0) { this.category = 'Gemuk (Overweight)';  this.color = '#F97316'; }
            else                       { this.category = 'Obesitas';             this.color = '#EF4444'; }
            this.calculating = false;
        }, 600);
    },
    reset() { this.weight=''; this.height=''; this.bmi=null; this.category=''; }
});

// ─── Symptom Checker State ────────────────────────────
window.symptomApp = () => ({
    step: 1,
    selectedSymptoms: [],
    duration: '',
    severity: '',
    symptoms: [
        { id: 'demam', label: 'Demam', icon: '🌡️' },
        { id: 'batuk', label: 'Batuk', icon: '😮‍💨' },
        { id: 'pilek', label: 'Pilek', icon: '🤧' },
        { id: 'sakit_kepala', label: 'Sakit Kepala', icon: '🤕' },
        { id: 'mual', label: 'Mual/Muntah', icon: '🤢' },
        { id: 'nyeri_dada', label: 'Nyeri Dada', icon: '💔' },
        { id: 'sesak_napas', label: 'Sesak Napas', icon: '😮' },
        { id: 'diare', label: 'Diare', icon: '🚽' },
        { id: 'nyeri_perut', label: 'Nyeri Perut', icon: '🫃' },
        { id: 'pusing', label: 'Pusing', icon: '😵' },
        { id: 'lemas', label: 'Lemas/Lelah', icon: '😴' },
        { id: 'nyeri_sendi', label: 'Nyeri Sendi', icon: '🦵' },
    ],
    toggleSymptom(id) {
        if (this.selectedSymptoms.includes(id)) {
            this.selectedSymptoms = this.selectedSymptoms.filter(s => s !== id);
        } else {
            this.selectedSymptoms.push(id);
        }
    },
    hasSymptom(id) { return this.selectedSymptoms.includes(id); },
    nextStep() { if (this.step < 3 && this.selectedSymptoms.length > 0) this.step++; },
    prevStep() { if (this.step > 1) this.step--; },
});

// ─── Cart Quantity Helper ─────────────────────────────
window.cartItem = (max) => ({
    qty: 1,
    max: max,
    inc() { if (this.qty < this.max) this.qty++; },
    dec() { if (this.qty > 1) this.qty--; },
});

// ─── Smooth Delete Animation Helper ──────────────────
window.confirmDelete = function(event, message = 'Apakah Anda yakin ingin menghapus data ini?') {
    if (event) event.preventDefault();
    const target = event ? event.currentTarget : null;
    const form = target ? (target.tagName === 'FORM' ? target : target.closest('form')) : null;
    if (!form) return false;

    if (!confirm(message)) {
        return false;
    }

    const row = form.closest('tr') || form.closest('.card') || form.closest('.cart-item') || form.closest('.list-item');
    if (row) {
        row.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
        row.style.opacity = '0';
        row.style.transform = 'scale(0.96) translateY(-4px)';
        
        setTimeout(() => {
            row.style.maxHeight = '0px';
            row.style.paddingTop = '0px';
            row.style.paddingBottom = '0px';
            row.style.marginTop = '0px';
            row.style.marginBottom = '0px';
            row.style.overflow = 'hidden';
        }, 120);

        setTimeout(() => {
            form.submit();
        }, 360);
    } else {
        form.submit();
    }
    return false;
};

// ─── Chat Polling ─────────────────────────────────────
window.chatApp = (consultationId) => ({
    messages: [],
    message: '',
    loading: false,
    init() { this.fetchMessages(); setInterval(() => this.fetchMessages(), 3000); },
    async fetchMessages() {
        try {
            const res = await fetch(`/konsultasi/${consultationId}/pesan`);
            const data = await res.json();
            this.messages = data;
            this.$nextTick(() => {
                const el = document.getElementById('chat-scroll');
                if (el) el.scrollTop = el.scrollHeight;
            });
        } catch(e) {}
    },
    async send() {
        if (!this.message.trim() || this.loading) return;
        this.loading = true;
        const body = new FormData();
        body.append('message', this.message);
        body.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        try {
            await fetch(`/konsultasi/${consultationId}/pesan`, { method: 'POST', body });
            this.message = '';
            await this.fetchMessages();
        } catch(e) {}
        this.loading = false;
    }
});
