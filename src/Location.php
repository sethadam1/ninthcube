<?php

namespace NinthCube;

class Location
{
    /*
     * location_string shortcut
     */
    public static function venue($city, $state = null, $country = null, $linked = 1)
    {
        return self::location_string(['city' => $city, 'state' => $state, 'country' => $country, 'linked' => $linked]);
    }

    /*
     * location_string
     */
    public static function location_string($data = [])
    {
        $street = $data['street'] ?? '';
        $ci     = $data['city'] ?? '';
        $st     = $data['state'] ?? '';
        $co     = $data['country'] ?? '';
        $zip    = $data['zip'] ?? '';
        $lnk    = (1 == ($data['linked'] ?? 0)) ? 1 : 0;
        $nc     = (1 == ($data['no_country'] ?? 0)) ? 1 : 0;

        $use_comma = 0;
        if (!empty($street)) {
            $use_comma = 1;
        }
        $venue = $street;
        if ('' != $ci) {
            $comma = (1 == $use_comma) ? ", " : "";
            $use_comma = 1;
            $venue .= $comma;
            $stl = (!empty($st)) ? '/' . urlencode($st) : '';
            $venue .= (1 == $lnk) ? "<a target=\"_top\" href=\"/venues/city/" . urlencode($ci) . $stl . "\">" . $ci . "</a>" : $ci;
        }
        if ('' != $st) {
            $comma = (1 == $use_comma) ? ", " : "";
            $use_comma = 1;
            $venue .= $comma;
            $col = (!empty($co)) ? '/' . urlencode($co) : '';
            $venue .= (1 == $lnk) ? "<a target=\"_top\" href=\"/venues/state/" . urlencode($st) . $col . "\">" . $st . "</a>" : $st;
            if (1 == $nc) {
                return $venue;
            }
        }
        if ('' != $co) {
            $comma = (1 == $use_comma) ? ", " : "";
            $venue .= $comma;
            $venue .= (1 == $lnk) ? "<a target=\"_top\" href=\"/venues/country/" . urlencode($co) . "\">" . $co . "</a>" : $co;
        }
        if (!empty($zip) && 0 != $zip) {
            $venue .= " " . $zip;
        }
        return $venue;
    }
}
