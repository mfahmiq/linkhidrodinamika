// ==================== AUTHENTICATION ====================
// Check if user is authenticated
function checkAuthentication() {
    const session = localStorage.getItem('adminSession');
    const expiry = localStorage.getItem('adminSessionExpiry');

    if (!session || !expiry || Date.now() >= parseInt(expiry)) {
        // Not authenticated or session expired
        window.location.href = 'login.html';
        return false;
    }
    return true;
}

// Logout function
function logout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        localStorage.removeItem('adminSession');
        localStorage.removeItem('adminSessionExpiry');
        window.location.href = 'login.html';
    }
}

// Check authentication on page load
if (!checkAuthentication()) {
    // Will redirect to login if not authenticated
}

// ==================== DATA MANAGEMENT ====================

// Default data (same as app.js)
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

// Available icons
const availableIcons = ['globe', 'linkedin', 'instagram', 'email', 'whatsapp', 'link', 'logo-hdi'];

let currentData = null;
let nextId = 6;

// Load data from localStorage or use defaults
function loadData() {
    const saved = localStorage.getItem('linkProfileData');
    if (saved) {
        currentData = JSON.parse(saved);
    } else {
        currentData = JSON.parse(JSON.stringify(defaultData));
    }

    // Update nextId
    if (currentData.links.length > 0) {
        nextId = Math.max(...currentData.links.map(l => l.id)) + 1;
    }

    return currentData;
}

// Save data to localStorage
function saveData() {
    localStorage.setItem('linkProfileData', JSON.stringify(currentData));
}

// Initialize admin panel
function initAdmin() {
    loadData();
    renderProfile();
    renderLinks();
    setupEventListeners();
}

// Render profile section
function renderProfile() {
    document.getElementById('adminName').value = currentData.profile.name;
    document.getElementById('adminBio').value = currentData.profile.bio;
    document.getElementById('adminProfileImage').src = currentData.profile.image;
    updateCharCount();
}

// Update character count for bio
function updateCharCount() {
    const bio = document.getElementById('adminBio').value;
    document.getElementById('bioCount').textContent = bio.length;
}

// Render links section
function renderLinks() {
    const container = document.getElementById('adminLinksContainer');

    if (currentData.links.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 40px 20px; color: var(--text-secondary);">
                <p>Belum ada link. Klik "Add New Link" untuk menambahkan.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = currentData.links.map((link, index) => `
        <div class="admin-link-item" data-id="${link.id}">
            <div class="admin-link-header">
                <div class="drag-handle">
                    <svg class="drag-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                    <span style="color: var(--text-secondary); font-size: 14px; font-weight: 600;">Link ${index + 1}</span>
                </div>
                <div class="link-status">
                    <div class="toggle-switch ${link.active ? 'active' : ''}" onclick="toggleLinkStatus(${link.id})"></div>
                    <span class="status-label">${link.active ? 'Active' : 'Inactive'}</span>
                    <button class="btn-delete" onclick="deleteLink(${link.id})">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="admin-link-fields">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-input" value="${link.title}" 
                           onchange="updateLinkField(${link.id}, 'title', this.value)" 
                           placeholder="e.g., Website, Instagram">
                </div>
                <div class="form-group">
                    <label>Subtitle (Optional)</label>
                    <input type="text" class="form-input" value="${link.subtitle || ''}" 
                           onchange="updateLinkField(${link.id}, 'subtitle', this.value)" 
                           placeholder="e.g., Visit our official website">
                </div>
                <div class="form-group">
                    <label>URL</label>
                    <input type="url" class="form-input" value="${link.url}" 
                           onchange="updateLinkField(${link.id}, 'url', this.value)" 
                           placeholder="https://example.com">
                </div>
                <div class="form-group">
                    <label>Icon</label>
                    <select class="form-input" onchange="updateLinkField(${link.id}, 'icon', this.value)">
                        ${availableIcons.map(icon => {
        let displayName = icon.charAt(0).toUpperCase() + icon.slice(1);
        if (icon === 'logo-hdi') displayName = '🏢 Logo HDI';
        if (icon === 'whatsapp') displayName = '💬 WhatsApp';
        if (icon === 'linkedin') displayName = '💼 LinkedIn';
        if (icon === 'instagram') displayName = '📸 Instagram';
        if (icon === 'email') displayName = '✉️ Email';
        if (icon === 'globe') displayName = '🌐 Globe';
        if (icon === 'link') displayName = '🔗 Link';
        return `
                                <option value="${icon}" ${link.icon === icon ? 'selected' : ''}>
                                    ${displayName}
                                </option>
                            `;
    }).join('')}
                    </select>
                </div>
            </div>
        </div>
    `).join('');
}

// Setup event listeners
function setupEventListeners() {
    document.getElementById('adminName').addEventListener('input', (e) => {
        currentData.profile.name = e.target.value;
    });

    document.getElementById('adminBio').addEventListener('input', (e) => {
        currentData.profile.bio = e.target.value;
        updateCharCount();
    });
}

// Add new link
function addNewLink() {
    const newLink = {
        id: nextId++,
        title: 'New Link',
        subtitle: '',
        url: 'https://',
        active: true,
        icon: 'link'
    };

    currentData.links.push(newLink);
    renderLinks();

    // Scroll to the new link
    setTimeout(() => {
        const newLinkElement = document.querySelector(`[data-id="${newLink.id}"]`);
        if (newLinkElement) {
            newLinkElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }, 100);
}

// Update link field
function updateLinkField(id, field, value) {
    const link = currentData.links.find(l => l.id === id);
    if (link) {
        link[field] = value;
    }
}

// Toggle link status
function toggleLinkStatus(id) {
    const link = currentData.links.find(l => l.id === id);
    if (link) {
        link.active = !link.active;
        renderLinks();
    }
}

// Delete link
function deleteLink(id) {
    if (confirm('Apakah Anda yakin ingin menghapus link ini?')) {
        currentData.links = currentData.links.filter(l => l.id !== id);
        renderLinks();
    }
}

// Save changes
function saveChanges() {
    // Validate profile data
    if (!currentData.profile.name.trim()) {
        showStatus('error', 'Nama tidak boleh kosong!');
        return;
    }

    // Validate links
    for (const link of currentData.links) {
        if (!link.title.trim()) {
            showStatus('error', 'Semua link harus memiliki title!');
            return;
        }
        if (!link.url.trim() || !isValidUrl(link.url)) {
            showStatus('error', `URL untuk "${link.title}" tidak valid!`);
            return;
        }
    }

    // Save to localStorage
    saveData();
    showStatus('success', 'Perubahan berhasil disimpan! ✓');
}

// Validate URL
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        // Check if it's a mailto or tel link
        return string.startsWith('mailto:') || string.startsWith('tel:');
    }
}

// Show save status
function showStatus(type, message) {
    const statusEl = document.getElementById('saveStatus');
    statusEl.textContent = message;
    statusEl.className = `save-status ${type} show`;

    setTimeout(() => {
        statusEl.classList.remove('show');
    }, 3000);
}

// Reset to defaults
function resetToDefaults() {
    if (confirm('Apakah Anda yakin ingin mereset ke pengaturan default? Semua perubahan akan hilang.')) {
        currentData = JSON.parse(JSON.stringify(defaultData));
        saveData();
        renderProfile();
        renderLinks();
        showStatus('success', 'Data berhasil direset ke default! ✓');
    }
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

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    loadTheme();
    initAdmin();
});
