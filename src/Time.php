<?php

namespace NinthCube;

class Time
{
    /*
     * convert date to relative time string (e.g., "3 days ago")
     */
    public static function time_ago($date): string
    {
        $timestamp = strtotime($date);
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'just now';
        }

        $minutes = round($diff / 60);
        if ($minutes < 60) {
            return $minutes . ' minute' . ($minutes != 1 ? 's' : '') . ' ago';
        }

        $hours = round($minutes / 60);
        if ($hours < 24) {
            return $hours . ' hour' . ($hours != 1 ? 's' : '') . ' ago';
        }

        $days = round($hours / 24);
        if ($days < 7) {
            return $days . ' day' . ($days != 1 ? 's' : '') . ' ago';
        }

        if ($days < 30) {
            $weeks = round($days / 7);
            return $weeks . ' week' . ($weeks != 1 ? 's' : '') . ' ago';
        }

        if ($days < 365) {
            $months = round($days / 30);
            return $months . ' month' . ($months != 1 ? 's' : '') . ' ago';
        }

        $years = round($days / 365);
        if ($years == 1) {
            return 'about a year ago';
        } else {
            return $years . ' year' . ($years != 1 ? 's' : '') . ' ago';
        }
    }

    public static function time_until($date): string
    {
        $timestamp = strtotime($date);
        $diff = $timestamp - time();

        if ($diff < 60) {
            return 'in a moment';
        }

        $minutes = round($diff / 60);
        if ($minutes < 60) {
            return 'in ' . $minutes . ' minute' . ($minutes != 1 ? 's' : '');
        }

        $hours = round($minutes / 60);
        if ($hours < 24) {
            return 'in ' . $hours . ' hour' . ($hours != 1 ? 's' : '');
        }

        $days = round($hours / 24);
        if ($days < 7) {
            return 'in ' . $days . ' day' . ($days != 1 ? 's' : '');
        }

        if ($days < 30) {
            $weeks = round($days / 7);
            return 'in ' . $weeks . ' week' . ($weeks != 1 ? 's' : '');
        }

        if ($days < 60) {
            return 'in about a month';
        }

        if ($days < 365) {
            $months = round($days / 30);
            if ($months < 2) {
                return 'in about a month';
            } elseif ($months < 12) {
                return 'in about ' . $months . ' months';
            }
        }

        $years = round($days / 365);
        if ($years == 1) {
            return 'in about a year';
        } elseif ($years < 2) {
            return 'in over a year';
        } else {
            return 'in over ' . $years . ' years';
        }
    }

    public static function time_elapsed($date): string
    {
        $timestamp = strtotime($date);
        $now = time();

        if ($timestamp > $now) {
            return self::time_until($date);
        } else {
            return self::time_ago($date);
        }
    }
}
