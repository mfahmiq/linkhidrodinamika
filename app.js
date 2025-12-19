// Default data
const defaultData = {
    profile: {
        name: 'Hidrodinamika',
        bio: 'Connecting Innovation & Excellence',
        image: 'logo-hdi.png'
    },
    links: [
        {
            id: 1,
            title: 'Website',
            subtitle: 'Visit our official website',
            url: 'https://hidrodinamika.com',
            active: true,
            icon: 'globe'
        },
        {
            id: 2,
            title: 'LinkedIn',
            subtitle: 'Connect professionally',
            url: 'https://linkedin.com/company/hidrodinamika',
            active: true,
            icon: 'linkedin'
        },
        {
            id: 3,
            title: 'Instagram',
            subtitle: 'Follow our journey',
            url: 'https://instagram.com/hidrodinamika',
            active: true,
            icon: 'instagram'
        },
        {
            id: 4,
            title: 'Email',
            subtitle: 'Get in touch',
            url: 'mailto:info@hidrodinamika.com',
            active: true,
            icon: 'email'
        },
        {
            id: 5,
            title: 'WhatsApp',
            subtitle: 'Chat with us',
            url: 'https://wa.me/6281234567890',
            active: true,
            icon: 'whatsapp'
        }
    ]
};

// Icon SVG paths
const icons = {
    globe: '<path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>',
    linkedin: '<path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/>',
    instagram: '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>',
    email: '<path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
    whatsapp: '<path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>',
    link: '<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>',
    arrow: '<path d="M5 12h14M12 5l7 7-7 7"/>',
    'logo-hdi': 'IMAGE' // Special marker for image icon
};

// Get icon SVG or image
function getIconSVG(iconName) {
    // Check if it's the logo-hdi icon
    if (iconName === 'logo-hdi') {
        return `<img src="logo-hdi.png" alt="HDI Logo" style="width: 20px; height: 20px; object-fit: contain;">`;
    }
    return `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${icons[iconName] || icons.link}</svg>`;
}

// Load data from localStorage or use defaults
function loadData() {
    const saved = localStorage.getItem('linkProfileData');
    return saved ? JSON.parse(saved) : defaultData;
}

// Render profile
function renderProfile() {
    const data = loadData();

    document.getElementById('displayName').textContent = data.profile.name;
    document.getElementById('displayBio').textContent = data.profile.bio;
    document.getElementById('profileImage').src = data.profile.image;
}

// Render links
function renderLinks() {
    const data = loadData();
    const container = document.getElementById('linksContainer');

    // Filter only active links
    const activeLinks = data.links.filter(link => link.active);

    container.innerHTML = activeLinks.map(link => `
        <a href="${link.url}" class="link-item" target="_blank" rel="noopener noreferrer">
            <div class="link-content">
                <div class="link-icon">
                    ${getIconSVG(link.icon)}
                </div>
                <div class="link-text">
                    <div class="link-title">${link.title}</div>
                    ${link.subtitle ? `<div class="link-subtitle">${link.subtitle}</div>` : ''}
                </div>
            </div>
            <div class="link-arrow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    ${icons.arrow}
                </svg>
            </div>
        </a>
    `).join('');
}

// Theme toggle functionality
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

// Load saved theme
function loadTheme() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
}

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
    loadTheme();
    renderProfile();
    renderLinks();
});
