async function getDashboardData() {
    try {
        // Abort the request if it takes too long
        const controller = new AbortController();
        const timeout = 10000; // 10s
        const timeoutId = setTimeout(() => controller.abort(), timeout);

        const response = await fetch('actions.php', { cache: 'no-store', signal: controller.signal });
        clearTimeout(timeoutId);

        if (!response.ok) {
            throw new Error(`Network error: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();

        if (data && data.status === 'error') {
            console.error('Error fetching dashboard data:', data.message);
            const errEl = document.getElementById('dashboard-error');
            if (errEl) errEl.textContent = data.message || 'Failed to load dashboard data';
            return;
        }

        // Helper to set text safely if element exists
        const setText = (id, text) => {
            const el = document.getElementById(id);
            if (el) el.textContent = text;
        };

        setText('count', `Product count: ${data?.total_products ?? 0}`);
        setText('active-count', `Active Products: ${data?.active_products ?? 0}`);
        setText('inactive-count', `Inactive Products: ${data?.inactive_products ?? 0}`);
    } catch (err) {
        if (err.name === 'AbortError') {
            console.error('Dashboard fetch aborted (timeout)');
        } else {
            console.error('Error fetching dashboard data:', err);
        }
        const errEl = document.getElementById('dashboard-error');
        if (errEl) errEl.textContent = 'Failed to load dashboard data';
    }
}