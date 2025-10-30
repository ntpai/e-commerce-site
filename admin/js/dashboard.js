async function fetchDashboardData(getcontent) {
    const url = encodeURI('actions.php?data=' + getcontent);
    try {
        
        const response = await fetch(url);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
        throw error;
    }
}