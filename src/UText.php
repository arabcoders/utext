<?php

declare(strict_types=1);

namespace arabcoders\utext;

use arabcoders\utext\UTextOps as StrOps;
use Closure;
use JsonSerializable;
use Stringable;

class UText implements Stringable, JsonSerializable
{
    /**
     * The underlying string value.
     *
     * @var string
     */
    protected string $value = '';

    /**
     * Create a new instance of the class.
     *
     * @param string $value
     *
     * @return void
     */
    public function __construct(string $value = '')
    {
        $this->value = $value;
    }

    /**
     * Return the remainder of a string after the first occurrence of a given value.
     *
     * @param string $search
     *
     * @return static
     */
    public function after(string $search): self
    {
        return new static(StrOps::after($this->value, $search));
    }

    /**
     * Return the remainder of a string after the last occurrence of a given value.
     *
     * @param string $search
     *
     * @return static
     */
    public function afterLast(string $search): self
    {
        return new static(StrOps::afterLast($this->value, $search));
    }

    /**
     * Append the given values to the string.
     *
     * @param array $values
     *
     * @return static
     */
    public function append(...$values): self
    {
        return new static($this->value . implode('', $values));
    }

    /**
     * Get the trailing name component of the path.
     *
     * @param string $suffix
     *
     * @return static
     */
    public function basename(string $suffix = ''): self
    {
        return new static(basename($this->value, $suffix));
    }

    /**
     * Get the portion of a string before the first occurrence of a given value.
     *
     * @param string $search
     *
     * @return static
     */
    public function before(string $search): self
    {
        return new static(StrOps::before($this->value, $search));
    }

    /**
     * Get the portion of a string before the last occurrence of a given value.
     *
     * @param string $search
     *
     * @return static
     */
    public function beforeLast(string $search): self
    {
        return new static(StrOps::beforeLast($this->value, $search));
    }

    /**
     * Get the portion of a string between two given values.
     *
     * @param string $from
     * @param string $to
     *
     * @return static
     */
    public function between(string $from, string $to): self
    {
        return new static(StrOps::between($this->value, $from, $to));
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param string|string[] $needles
     *
     * @return bool
     */
    public function contains(array|string $needles): bool
    {
        return StrOps::contains($this->value, $needles);
    }

    /**
     * Determine if a given string contains all array values.
     *
     * @param array $needles
     *
     * @return bool
     */
    public function containsAll(array $needles): bool
    {
        return StrOps::containsAll($this->value, $needles);
    }

    /**
     * Get the parent directory's path.
     *
     * @param int $levels
     *
     * @return static
     */
    public function dirname(int $levels = 1): self
    {
        return new static(dirname($this->value, $levels));
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string|array $needles
     *
     * @return bool
     */
    public function endsWith(string|array $needles): bool
    {
        return StrOps::endsWith($this->value, $needles);
    }

    /**
     * Determine if the string is an exact match with the given value.
     *
     * @param string $value
     *
     * @return bool
     */
    public function exactly(string $value): bool
    {
        return $this->value === $value;
    }

    /**
     * Explode the string into an array.
     *
     * @param string $delimiter
     * @param int $limit
     *
     * @return array
     */
    public function explode(string $delimiter, int $limit = PHP_INT_MAX): array
    {
        return explode($delimiter, $this->value, $limit);
    }

    /**
     * Cap a string with a single instance of a given value.
     *
     * @param string $cap
     *
     * @return static
     */
    public function finish(string $cap): self
    {
        return new static(StrOps::finish($this->value, $cap));
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param string|array $pattern
     *
     * @return bool
     */
    public function is(string|array $pattern): bool
    {
        return StrOps::is($pattern, $this->value);
    }

    /**
     * Determine if the given string is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    /**
     * Return the length of the given string.
     *
     * @return int
     */
    public function length(): int
    {
        return StrOps::length($this->value);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param int $limit
     * @param string|null $end
     *
     * @return static
     */
    public function limit(int $limit = 100, ?string $end = '...'): self
    {
        return new static(StrOps::limit($this->value, $limit, $end));
    }

    /**
     * Convert the given string to lower-case.
     *
     * @return static
     */
    public function lower(): self
    {
        return new static(StrOps::lower($this->value));
    }

    /**
     * Get the string matching the given pattern.
     *
     * @param string $pattern
     *
     * @return static
     */
    public function match(string $pattern): self
    {
        preg_match($pattern, $this->value, $matches);

        return new static($matches[1] ?? $matches[0] ?? '');
    }

    /**
     * Does the string match the given pattern
     *
     * @param string $pattern
     *
     * @return bool
     */
    public function isMatch(string $pattern): bool
    {
        return StrOps::isMatch($pattern, $this->value);
    }

    /**
     * Prepend the given values to the string.
     *
     * @param array $values
     *
     * @return static
     */
    public function prepend(...$values): self
    {
        return new static(implode('', $values) . $this->value);
    }

    /**
     * Replace the given value in the given string.
     *
     * @param string|array $search
     * @param string|array $replace
     *
     * @return static
     */
    public function replace(string|array $search, string|array $replace): self
    {
        return new static(str_replace($search, $replace, $this->value));
    }

    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param string $search
     * @param array $replace
     *
     * @return static
     */
    public function replaceArray(string $search, array $replace): self
    {
        return new static(StrOps::replaceArray($search, $replace, $this->value));
    }

    /**
     * Replace the first occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     *
     * @return static
     */
    public function replaceFirst(string $search, string $replace): self
    {
        return new static(StrOps::replaceFirst($search, $replace, $this->value));
    }

    /**
     * Replace the last occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     *
     * @return static
     */
    public function replaceLast(string $search, string $replace): self
    {
        return new static(StrOps::replaceLast($search, $replace, $this->value));
    }

    /**
     * Replace the patterns matching the given regular expression.
     *
     * @param string $pattern
     * @param string|Closure $replace
     * @param int $limit
     *
     * @return static
     */
    public function replaceMatches(string $pattern, string|Closure $replace, int $limit = -1): self
    {
        if ($replace instanceof Closure) {
            return new static(preg_replace_callback($pattern, $replace, $this->value, $limit));
        }

        return new static(preg_replace($pattern, $replace, $this->value, $limit));
    }

    /**
     * Begin a string with a single instance of a given value.
     *
     * @param string $prefix
     *
     * @return static
     */
    public function start(string $prefix): self
    {
        return new static(StrOps::start($this->value, $prefix));
    }

    /**
     * Convert the given string to upper-case.
     *
     * @return static
     */
    public function upper(): self
    {
        return new static(StrOps::upper($this->value));
    }

    /**
     * Convert the given string to title case.
     *
     * @return static
     */
    public function title(): self
    {
        return new static(StrOps::title($this->value));
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param array|string $needles
     *
     * @return bool
     */
    public function startsWith(array|string $needles): bool
    {
        return StrOps::startsWith($this->value, $needles);
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     *
     * @param int $start
     * @param int|null $length
     *
     * @return static
     */
    public function substr(int $start, ?int $length = null): self
    {
        return new static(StrOps::substr($this->value, $start, $length));
    }

    /**
     * Trim the string of the given characters.
     *
     * @param string|null $characters
     *
     * @return static
     */
    public function trim(?string $characters = null): self
    {
        return new static(trim(...array_merge([$this->value], func_get_args())));
    }

    /**
     * Execute the given callback if the string is empty.
     *
     * @param Closure $callback
     *
     * @return mixed|static
     */
    public function whenEmpty(Closure $callback)
    {
        if ($this->isEmpty()) {
            return $callback($this) ?? $this;
        }

        return $this;
    }

    /**
     * Limit the number of words in a string.
     *
     * @param int $words
     * @param string|null $end
     *
     * @return static
     */
    public function words(int $words = 100, ?string $end = '...'): self
    {
        return new static(StrOps::words($this->value, $words, $end));
    }

    /**
     * Get the raw string value.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Print raw string in var_dump.
     *
     * @return array
     */
    public function __debugInfo(): array
    {
        return ['string' => $this->value];
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
