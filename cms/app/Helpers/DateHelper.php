<?php

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'd-m-Y') {
        if (!$date) return '';
        
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        return $date->format($format);
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($date, $format = 'd-m-Y g:i A') {
        if (!$date) return '';
        
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        return $date->format($format);
    }
}