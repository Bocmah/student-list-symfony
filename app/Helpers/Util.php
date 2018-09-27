<?php

namespace StudentList\Helpers;

class Util
{
    /**
     * Shows arrow near the field which is currently sorted
     *
     * @param string $requiredOrder Order which field implies (i.e. "Exam score" table header will have exam_score order)
     * @param string $currentOrder Order variable passed into view from a query string
     * @param string $direction Sorting direction
     *
     * @return void
     */
    public static function showSortingArrow(string $requiredOrder,
                                            string $currentOrder,
                                            string $direction): void
    {
        if ($requiredOrder === $currentOrder && $direction === "DESC") {
            echo "<span uk-icon='icon: arrow-down'></span>";
        } elseif ($requiredOrder === $currentOrder && $direction === "ASC") {
            echo "<span uk-icon='icon: arrow-up'></span>";
        }
    }
}
