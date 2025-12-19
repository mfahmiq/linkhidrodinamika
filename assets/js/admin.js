/**
 * Admin Panel JavaScript
 */

// Theme toggle
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    html.setAttribute('data-theme', newTheme);
    document.cookie = `theme=${newTheme}; path=/; max-age=31536000`;
}

// Update bio character count
document.getElementById('profileBio')?.addEventListener('input', function () {
    document.getElementById('bioCount').textContent = this.value.length;
});

// Profile form submission
document.getElementById('profileForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('action', 'update_profile');

    try {
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        showStatus(result.success ? 'success' : 'error', result.message);

        if (result.success) {
            setTimeout(() => location.reload(), 1500);
        }
    } catch (error) {
        showStatus('error', 'Failed to update profile');
    }
});

// Update link
async function updateLink(id) {
    const container = document.querySelector(`[data-id="${id}"]`);
    const formData = new FormData();

    formData.append('action', 'update_link');
    formData.append('id', id);
    formData.append('title', container.querySelector('[name="title"]').value);
    formData.append('subtitle', container.querySelector('[name="subtitle"]').value);
    formData.append('url', container.querySelector('[name="url"]').value);
    formData.append('icon', container.querySelector('[name="icon"]').value);

    try {
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        showStatus(result.success ? 'success' : 'error', result.message);
    } catch (error) {
        showStatus('error', 'Failed to update link');
    }
}

// Delete link
async function deleteLink(id) {
    if (!confirm('Are you sure you want to delete this link?')) return;

    const formData = new FormData();
    formData.append('action', 'delete_link');
    formData.append('id', id);

    try {
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            document.querySelector(`[data-id="${id}"]`).remove();
            showStatus('success', result.message);
        } else {
            showStatus('error', result.message);
        }
    } catch (error) {
        showStatus('error', 'Failed to delete link');
    }
}

// Toggle link status
async function toggleLink(id) {
    const formData = new FormData();
    formData.append('action', 'toggle_link');
    formData.append('id', id);

    try {
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            location.reload();
        }
    } catch (error) {
        showStatus('error', 'Failed to toggle link status');
    }
}

// Add new link
function addNewLink() {
    const container = document.getElementById('linksContainer');
    const newId = 'new_' + Date.now();

    const html = `
        <div class="admin-link-item" data-id="${newId}">
            <div class="admin-link-header">
                <div class="drag-handle">
                    <svg class="drag-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                    <span style="color: var(--text-secondary); font-size: 14px; font-weight: 600;">New Link</span>
                </div>
                <div class="link-status">
                    <button class="btn-delete" onclick="this.closest('.admin-link-item').remove()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="admin-link-fields">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-input" placeholder="Link Title" required>
                </div>
                <div class="form-group">
                    <label>Subtitle (Optional)</label>
                    <input type="text" name="subtitle" class="form-input" placeholder="Link Description">
                </div>
                <div class="form-group">
                    <label>URL</label>
                    <input type="url" name="url" class="form-input" placeholder="https://example.com" required>
                </div>
                <div class="form-group">
                    <label>Icon</label>
                    <select name="icon" class="form-input">
                        <option value="globe">🌐 Globe</option>
                        <option value="linkedin">💼 LinkedIn</option>
                        <option value="instagram">📸 Instagram</option>
                        <option value="email">✉️ Email</option>
                        <option value="whatsapp">💬 WhatsApp</option>
                        <option value="link">🔗 Link</option>
                        <option value="logo-hdi">🏢 Logo HDI</option>
                    </select>
                </div>
                <button type="button" class="btn-save" onclick="createLink('${newId}')">Create Link</button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    container.lastElementChild.scrollIntoView({ behavior: 'smooth' });
}

// Create new link
async function createLink(tempId) {
    const container = document.querySelector(`[data-id="${tempId}"]`);
    const formData = new FormData();

    formData.append('action', 'create_link');
    formData.append('title', container.querySelector('[name="title"]').value);
    formData.append('subtitle', container.querySelector('[name="subtitle"]').value);
    formData.append('url', container.querySelector('[name="url"]').value);
    formData.append('icon', container.querySelector('[name="icon"]').value);
    formData.append('is_active', true);

    try {
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showStatus('success', result.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showStatus('error', result.message);
        }
    } catch (error) {
        showStatus('error', 'Failed to create link');
    }
}

// Show status message
function showStatus(type, message) {
    const statusEl = document.getElementById('saveStatus');
    statusEl.textContent = message;
    statusEl.className = `save-status ${type} show`;

    setTimeout(() => {
        statusEl.classList.remove('show');
    }, 3000);
}
