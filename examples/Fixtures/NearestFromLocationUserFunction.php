<?php

/**
 * Class NearestFromLocationUserFunction
 */
class NearestFromLocationUserFunction
{
    /**
     * @var float
     */
    protected static $latitude = 38.631913;

    /**
     * @var float
     */
    protected static $longitude = -121.434879;

    /**
     * This UserFunction removes all the coordinates that are too far from our values.
     * The distance must be greater than 0.1 unit.
     *
     * @param array $configuration
     * @param \Flamingo\Core\TaskRuntime $taskRuntime
     */
    public static function run(array $configuration, \Flamingo\Core\TaskRuntime $taskRuntime)
    {
        $source = $taskRuntime->getFirstTable();

        foreach ($source as $index => &$row) {
            if (self::getDistanceFromLocation($row['latitude'], $row['longitude']) > 0.1) {
                $source->offsetUnset($index);
            }
        }
    }

    /**
     * Returns the "distance" value between two coordinates.
     *
     * @param float $latitude
     * @param float $longitude
     * @return float
     */
    protected static function getDistanceFromLocation($latitude, $longitude)
    {
        return sqrt(pow($latitude - self::$latitude, 2) + pow($longitude - self::$longitude, 2));
    }
}