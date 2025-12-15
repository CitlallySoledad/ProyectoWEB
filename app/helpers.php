<?php

if (!function_exists('getRedirectRouteByRole')) {
    /**
     * Get redirect route name based on user role
     *
     * @param \App\Models\User $user
     * @return string
     */
    function getRedirectRouteByRole($user)
    {
        if ($user->hasRole('admin')) {
            return 'admin.dashboard';
        }
        
        if ($user->hasRole('judge')) {
            return 'judge.projects.index';
        }
        
        return 'panel.participante';
    }
}
