<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxLinksAllowed implements ValidationRule
{
    private $maxLinks;
    private $isAdmin;

    public function __construct($maxLinks)
    {
        $this->maxLinks = $maxLinks;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Count the number of links in the content
        $linkCount = substr_count($value, '<a href='); 
        // Check if the link count exceeds the maximum allowed
        if ($linkCount > $this->maxLinks) {
            // Trigger validation failure
            $fail("Maximum {$this->maxLinks} links allowed in content.");
        }
    }
}
