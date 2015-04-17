<?php namespace Krumer\Test\Tools\Utils;

class ContentSearch {

    /**
     * Return all the fields of the nested content array based on the given search query.
     *
     * An example query: tags.*.id
     *
     * @param $query
     * @param $content
     * @return array
     */
    public static function findSearchFields($query, $content)
    {
        $levels = explode('.', $query);

        $searchParts = [];
        $searchParts[] = $content;

        foreach ($levels as $level)
        {
            // Wildcard query
            if ($level === '*')
            {
                $searchParts = static::fieldsOfNextLevel($searchParts);
                continue;
            }

            // Specific key name
            $searchParts = static::findFieldInNextLevel($searchParts, $level);
        }

        return $searchParts;
    }

    /**
     * Add all the attributes of the next level because of a wildcard query.
     *
     * @param $parts
     * @return array
     */
    private static function fieldsOfNextLevel($parts)
    {
        $subParts = [];

        foreach ($parts as $part)
        {
            if (is_array($part))
            {
                foreach ($part as $subField)
                {
                    $subParts[] = $subField;
                }
            }
        }

        return $subParts;
    }

    /**
     * Find all the attributes with the given field name in the next level.
     *
     * @param $parts
     * @param $field
     * @return array
     */
    private static function findFieldInNextLevel($parts, $field)
    {
        $resultParts = [];

        foreach ($parts as $part)
        {
            if ( ! is_array($part))
            {
                continue;
            }

            if (array_key_exists($field, $part))
            {
                $resultParts[] = $part[$field];
            }
        }

        return $resultParts;
    }

}