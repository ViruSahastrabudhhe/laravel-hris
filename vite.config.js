import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/admin/adminTrainings.css',
                'resources/css/admin/adminDashboard.css',
                'resources/css/admin/adminRecruitment.css',
                'resources/css/admin/adminPersonnel.css',
                'resources/css/admin/adminAttendance.css',
                'resources/css/admin/adminLeaveandBenefits.css',
                'resources/css/admin/adminPerformance.css',
                'resources/css/datatable.css',
                'resources/css/jquery.dataTables.min.css',
                'resources/css/users/usersLanding.css',
                'resources/css/users/usersLogin.css',
                'resources/css/users/usersRegister.css',
                'resources/css/users/usersChatbot.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
