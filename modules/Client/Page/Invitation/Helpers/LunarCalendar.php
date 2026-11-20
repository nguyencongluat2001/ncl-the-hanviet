<?php

namespace Modules\Client\Page\Invitation\Helpers;

/**
 * Đổi ngày dương sang âm lịch Việt Nam (múi giờ +7).
 * Thuật toán của Hồ Ngọc Đức: https://www.informatik.uni-leipzig.de/~duc/amlich/
 */
class LunarCalendar
{
    const TIMEZONE = 7.0;
    const CAN = ['Canh', 'Tân', 'Nhâm', 'Quý', 'Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ'];
    const CHI = ['Thân', 'Dậu', 'Tuất', 'Hợi', 'Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi'];

    /**
     * @return array [ngày, tháng, năm, nhuận(0|1)]
     */
    public static function fromSolar(int $dd, int $mm, int $yy): array
    {
        $dayNumber = self::jdFromDate($dd, $mm, $yy);
        $k = (int) floor(($dayNumber - 2415021.076998695) / 29.530588853);
        $monthStart = self::getNewMoonDay($k + 1);
        if ($monthStart > $dayNumber) {
            $monthStart = self::getNewMoonDay($k);
        }
        $a11 = self::getLunarMonth11($yy);
        $b11 = $a11;
        if ($a11 >= $monthStart) {
            $lunarYear = $yy;
            $a11 = self::getLunarMonth11($yy - 1);
        } else {
            $lunarYear = $yy + 1;
            $b11 = self::getLunarMonth11($yy + 1);
        }
        $lunarDay = $dayNumber - $monthStart + 1;
        $diff = (int) floor(($monthStart - $a11) / 29);
        $lunarLeap = 0;
        $lunarMonth = $diff + 11;
        if ($b11 - $a11 > 365) {
            $leapMonthDiff = self::getLeapMonthOffset($a11);
            if ($diff >= $leapMonthDiff) {
                $lunarMonth = $diff + 10;
                if ($diff == $leapMonthDiff) {
                    $lunarLeap = 1;
                }
            }
        }
        if ($lunarMonth > 12) {
            $lunarMonth -= 12;
        }
        if ($lunarMonth >= 11 && $diff < 4) {
            $lunarYear -= 1;
        }
        return [$lunarDay, $lunarMonth, $lunarYear, $lunarLeap];
    }

    /**
     * Tên năm can chi, ví dụ 2026 => "Bính Ngọ"
     */
    public static function yearName(int $lunarYear): string
    {
        return self::CAN[$lunarYear % 10] . ' ' . self::CHI[$lunarYear % 12];
    }

    /**
     * "ngày 17 tháng 6 năm Bính Ngọ"
     */
    public static function format(int $dd, int $mm, int $yy): string
    {
        [$day, $month, $year, $leap] = self::fromSolar($dd, $mm, $yy);
        return 'ngày ' . $day . ' tháng ' . $month . ($leap ? ' nhuận' : '') . ' năm ' . self::yearName($year);
    }

    private static function jdFromDate(int $dd, int $mm, int $yy): int
    {
        $a = intdiv(14 - $mm, 12);
        $y = $yy + 4800 - $a;
        $m = $mm + 12 * $a - 3;
        $jd = $dd + intdiv(153 * $m + 2, 5) + 365 * $y + intdiv($y, 4) - intdiv($y, 100) + intdiv($y, 400) - 32045;
        if ($jd < 2299161) {
            $jd = $dd + intdiv(153 * $m + 2, 5) + 365 * $y + intdiv($y, 4) - 32083;
        }
        return $jd;
    }

    private static function newMoon(int $k): float
    {
        $T = $k / 1236.85;
        $T2 = $T * $T;
        $T3 = $T2 * $T;
        $dr = M_PI / 180;
        $Jd1 = 2415020.75933 + 29.53058868 * $k + 0.0001178 * $T2 - 0.000000155 * $T3;
        $Jd1 += 0.00033 * sin((166.56 + 132.87 * $T - 0.009173 * $T2) * $dr);
        $M = 359.2242 + 29.10535608 * $k - 0.0000333 * $T2 - 0.00000347 * $T3;
        $Mpr = 306.0253 + 385.81691806 * $k + 0.0107306 * $T2 + 0.00001236 * $T3;
        $F = 21.2964 + 390.67050646 * $k - 0.0016528 * $T2 - 0.00000239 * $T3;
        $C1 = (0.1734 - 0.000393 * $T) * sin($M * $dr) + 0.0021 * sin(2 * $dr * $M);
        $C1 = $C1 - 0.4068 * sin($Mpr * $dr) + 0.0161 * sin($dr * 2 * $Mpr);
        $C1 = $C1 - 0.0004 * sin($dr * 3 * $Mpr);
        $C1 = $C1 + 0.0104 * sin($dr * 2 * $F) - 0.0051 * sin($dr * ($M + $Mpr));
        $C1 = $C1 - 0.0074 * sin($dr * ($M - $Mpr)) + 0.0004 * sin($dr * (2 * $F + $M));
        $C1 = $C1 - 0.0004 * sin($dr * (2 * $F - $M)) - 0.0006 * sin($dr * (2 * $F + $Mpr));
        $C1 = $C1 + 0.0010 * sin($dr * (2 * $F - $Mpr)) + 0.0005 * sin($dr * (2 * $Mpr + $M));
        if ($T < -11) {
            $deltat = 0.001 + 0.000839 * $T + 0.0002261 * $T2 - 0.00000845 * $T3 - 0.000000081 * $T * $T3;
        } else {
            $deltat = -0.000278 + 0.000265 * $T + 0.000262 * $T2;
        }
        return $Jd1 + $C1 - $deltat;
    }

    private static function getNewMoonDay(int $k): int
    {
        return (int) floor(self::newMoon($k) + 0.5 + self::TIMEZONE / 24);
    }

    private static function sunLongitude(float $jdn): float
    {
        $T = ($jdn - 2451545.0) / 36525;
        $T2 = $T * $T;
        $dr = M_PI / 180;
        $M = 357.52910 + 35999.05030 * $T - 0.0001559 * $T2 - 0.00000048 * $T * $T2;
        $L0 = 280.46645 + 36000.76983 * $T + 0.0003032 * $T2;
        $DL = (1.914600 - 0.004817 * $T - 0.000014 * $T2) * sin($dr * $M);
        $DL = $DL + (0.019993 - 0.000101 * $T) * sin($dr * 2 * $M) + 0.000290 * sin($dr * 3 * $M);
        $L = ($L0 + $DL) * $dr;
        return $L - M_PI * 2 * floor($L / (M_PI * 2));
    }

    private static function getSunLongitude(int $dayNumber): int
    {
        return (int) floor(self::sunLongitude($dayNumber - 0.5 - self::TIMEZONE / 24) / M_PI * 6);
    }

    private static function getLunarMonth11(int $yy): int
    {
        $off = self::jdFromDate(31, 12, $yy) - 2415021;
        $k = (int) floor($off / 29.530588853);
        $nm = self::getNewMoonDay($k);
        if (self::getSunLongitude($nm) >= 9) {
            $nm = self::getNewMoonDay($k - 1);
        }
        return $nm;
    }

    private static function getLeapMonthOffset(int $a11): int
    {
        $k = (int) floor(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
        $i = 1;
        $arc = self::getSunLongitude(self::getNewMoonDay($k + $i));
        do {
            $last = $arc;
            $i++;
            $arc = self::getSunLongitude(self::getNewMoonDay($k + $i));
        } while ($arc != $last && $i < 14);
        return $i - 1;
    }
}
