import { createApp, h } from 'vue';
import ResidentPortal from './components/resident/ResidentPortal.vue';
import BhwPortal from './components/bhw/BhwPortal.vue';

const appEl = document.getElementById('app');

// BHW portal: data is passed via window.__BHW_DATA__ to avoid HTML attribute escaping issues
if (appEl && window.__BHW_DATA__) {
    const d = window.__BHW_DATA__;
    const app = createApp({
        render() {
            return h(BhwPortal, {
                user: d.user,
                dashboardData: d.dashboardData,
                myAssignedServices: d.myAssignedServices,
                recentActivities: d.recentActivities,
                walkinStoreUrl: d.walkinStoreUrl,
                recentWalkins: d.recentWalkins,
                medicines: d.medicines,
                pendingHomeServices: d.pendingHomeServices,
                acceptedHomeServices: d.acceptedHomeServices,
                assignedHomeServices: d.assignedHomeServices,
                completedHomeServices: d.completedHomeServices,
                reportData: d.reportData,
                notifications: d.notifications,
                notificationSummary: d.notificationSummary,
                markReadUrl: d.markReadUrl,
                bhwConfirmScheduleUrl: d.bhwConfirmScheduleUrl,
                announcements: d.announcements,
                announcementsStoreUrl: d.announcementsStoreUrl,
                medicineRequests: d.medicineRequests,
                medicineReturns: d.medicineReturns,
            });
        }
    });
    app.mount('#app');
} else {
    // Resident portal (and other portals) use the standard component approach
    const app = createApp({});
    app.component('resident-portal', ResidentPortal);
    app.mount('#app');
}
