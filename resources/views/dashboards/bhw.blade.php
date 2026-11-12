<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BHW Portal — MEDLINK</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style type="text/tailwindcss">
        body { font-family: 'Inter', sans-serif; }
    </style>
    @vite(['resources/js/medlink.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen">
    <script>
        window.__BHW_DATA__ = {
            user: {!! json_encode(auth()->user()) !!},
            dashboardData: {!! json_encode($dashboardData) !!},
            myAssignedServices: {!! json_encode($myAssignedServices) !!},
            recentActivities: {!! json_encode($recentActivities) !!},
            walkinStoreUrl: {!! json_encode($walkinStoreUrl) !!},
            recentWalkins: {!! json_encode($recentWalkins) !!},
            medicines: {!! json_encode($medicines) !!},
            pendingHomeServices: {!! json_encode($pendingHomeServices) !!},
            acceptedHomeServices: {!! json_encode($acceptedHomeServices) !!},
            assignedHomeServices: {!! json_encode($assignedHomeServices) !!},
            completedHomeServices: {!! json_encode($completedHomeServices) !!},
            reportData: {!! json_encode($reportData) !!},
            notifications: {!! json_encode($notifications) !!},
            notificationSummary: {!! json_encode($notificationSummary ?? []) !!},
            markReadUrl: {!! json_encode(route('bhw.notifications.read')) !!},
            bhwConfirmScheduleUrl: {!! json_encode(url('/bhw/scheduling')) !!},
            announcements: {!! json_encode($announcements) !!},
            announcementsStoreUrl: {!! json_encode(route('announcements.store')) !!},
            medicineRequests: {!! json_encode($medicineDeliveryRequests ?? []) !!},
            medicineReturns: {!! json_encode($medicineReturns ?? []) !!},
        };
    </script>
    <div id="app"></div>
</body>
</html>
