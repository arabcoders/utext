<?php

declare(strict_types=1);

namespace arabcoders\utext;

class UTextOps
{
    /**
     * @var string set default encoding.
     */
    private static string $encoding = 'UTF-8';

    /**
     * Set Internal Class mb_* encoding.
     *
     * @param string $encoding
     */
    public static function setEncoding(string $encoding): void
    {
        static::$encoding = $encoding;
    }

    /**
     * Get a new stringable object from the given string.
     *
     * @param string $string
     *
     * @return UText
     */
    public static function of(string $string): UText
    {
        return new UText($string);
    }

    /**
     * Return the remainder of a string after the first occurrence of a given value.
     *
     * @param string $subject
     * @param string $search
     *
     * @return string
     */
    public static function after(string $subject, string $search): string
    {
        return empty($search) ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }

    /**
     * Return the remainder of a string after the last occurrence of a given value.
     *
     * @param string $subject
     * @param string $search
     *
     * @return string
     */
    public static function afterLast(string $subject, string $search): string
    {
        if (empty($search)) {
            return $subject;
        }

        $position = mb_strrpos($subject, $search, 0, static::$encoding);

        if (false === $position) {
            return $subject;
        }

        return static::substr($subject, $position + static::length($search));
    }

    /**
     * Get the portion of a string before the first occurrence of a given value.
     *
     * @param string $subject
     * @param string $search
     *
     * @return string
     */
    public static function before(string $subject, string $search): string
    {
        return $search === '' ? $subject : explode($search, $subject)[0];
    }

    /**
     * Get the portion of a string before the last occurrence of a given value.
     *
     * @param string $subject
     * @param string $search
     *
     * @return string
     */
    public static function beforeLast(string $subject, string $search): string
    {
        if (empty($search)) {
            return $subject;
        }

        $pos = mb_strrpos($subject, $search, 0, static::$encoding);

        if (false === $pos) {
            return $subject;
        }

        return static::substr($subject, 0, $pos);
    }

    /**
     * Get the portion of a string between two given values.
     *
     * @param string $subject
     * @param string $from
     * @param string $to
     *
     * @return string
     */
    public static function between(string $subject, string $from, string $to): string
    {
        if (empty($from) || empty($to)) {
            return $subject;
        }

        return static::beforeLast(static::after($subject, $from), $to);
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     *
     * @return bool
     */
    public static function contains(string $haystack, string|array $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if (!empty($needle) && false !== mb_strpos($haystack, $needle, 0, static::$encoding)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string contains all array values.
     *
     * @param string $haystack
     * @param string[] $needles
     *
     * @return bool
     */
    public static function containsAll(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (!static::contains($haystack, $needle)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     *
     * @return bool
     */
    public static function endsWith(string $haystack, string|array $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if (static::substr($haystack, -static::length($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cap a string with a single instance of a given value.
     *
     * @param string $value
     * @param string $cap
     *
     * @return string
     */
    public static function finish(string $value, string $cap): string
    {
        $quoted = preg_quote($cap, '/');

        return preg_replace('/(?:' . $quoted . ')+$/u', '', $value) . $cap;
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param string|array $pattern
     * @param string $value
     *
     * @return bool
     */
    public static function is(string|array $pattern, string $value): bool
    {
        $fn = static function ($value) {
            if ($value === null) {
                return [];
            }

            return is_array($value) ? $value : [$value];
        };

        $patterns = $fn($pattern);

        if (empty($patterns)) {
            return false;
        }

        foreach ($patterns as $match) {
            // If the given value is an exact match we can of course return true right
            // from the beginning. Otherwise, we will translate asterisks and do an
            // actual pattern match against the two strings to see if they match.
            if ($value === $match) {
                return true;
            }

            $match = preg_quote($match, '#');

            // Asterisks are translated into zero-or-more regular expression wildcards
            // to make it convenient to check if the strings starts with the given
            // pattern such as "library/*", making any string check convenient.
            $match = str_replace('\*', '.*', $match);

            if (1 === preg_match('#^' . $match . '\z#u', $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return the length of the given string.
     *
     * @param string $value
     *
     * @return int
     */
    public static function length(string $value): int
    {
        return mb_strlen($value, static::$encoding);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param string $value
     * @param int $limit
     * @param string|null $end
     *
     * @return string
     */
    public static function limit(string $value, int $limit = 100, ?string $end = '...'): string
    {
        if (mb_strwidth($value, static::$encoding) <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', static::$encoding)) . $end;
    }

    /**
     * Convert the given string to lower-case.
     *
     * @param string $value
     *
     * @return string
     */
    public static function lower(string $value): string
    {
        return mb_strtolower($value, static::$encoding);
    }

    /**
     * Does string match pattern.
     *
     * @param string $pattern regex pattern.
     * @param string $text
     *
     * @return bool
     */
    public static function isMatch(string $pattern, string $text): bool
    {
        return (bool)preg_match($pattern, $text);
    }

    /**
     * Limit the number of words in a string.
     *
     * @param string $value
     * @param int $words
     * @param string|null $end
     *
     * @return string
     */
    public static function words(string $value, int $words = 100, ?string $end = '...'): string
    {
        preg_match('/^\s*+(?:\S++\s*+){1,' . $words . '}/u', $value, $matches);

        if (!isset($matches[0]) || static::length($value) === static::length($matches[0])) {
            return $value;
        }

        return rtrim($matches[0]) . $end;
    }

    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param string $search
     * @param array<int|string, string> $replace
     * @param string $subject
     *
     * @return string
     */
    public static function replaceArray(string $search, array $replace, string $subject): string
    {
        $segments = explode($search, $subject);

        $result = array_shift($segments);

        foreach ($segments as $segment) {
            $result .= (array_shift($replace) ?? $search) . $segment;
        }

        return $result;
    }

    /**
     * Replace the first occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     * @param string $subject
     *
     * @return string
     */
    public static function replaceFirst(string $search, string $replace, string $subject): string
    {
        if (empty($search)) {
            return $subject;
        }

        $position = strpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    /**
     * Replace the last occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     * @param string $subject
     *
     * @return string
     */
    public static function replaceLast(string $search, string $replace, string $subject): string
    {
        $position = strrpos($subject, $search);

        if (false !== $position) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    /**
     * Begin a string with a single instance of a given value.
     *
     * @param string $value
     * @param string $prefix
     *
     * @return string
     */
    public static function start(string $value, string $prefix): string
    {
        $quoted = preg_quote($prefix, '/');

        return $prefix . preg_replace('/^(?:' . $quoted . ')+/u', '', $value);
    }

    /**
     * Convert the given string to upper-case.
     *
     * @param string $value
     *
     * @return string
     */
    public static function upper(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Convert the given string to title case.
     *
     * @param string $value
     *
     * @return string
     */
    public static function title(string $value): string
    {
        return mb_convert_case($value, MB_CASE_TITLE, static::$encoding);
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     *
     * @return bool
     */
    public static function startsWith(string $haystack, string|array $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ((string)$needle !== '' && static::substr($haystack, 0, static::length($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     *
     * @param string $string
     * @param int $start
     * @param int|null $length
     *
     * @return string
     */
    public static function substr(string $string, int $start, ?int $length = null): string
    {
        return mb_substr($string, $start, $length, static::$encoding);
    }
}
