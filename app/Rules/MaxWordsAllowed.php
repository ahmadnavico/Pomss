<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxWordsAllowed implements ValidationRule
{
    private $maxWords;
    public function __construct($maxWords)
    {
        $this->maxWords = $maxWords;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
       
        $decodedContent = json_decode($value, true);
        $content = isset($decodedContent['value']) ? $decodedContent['value'] : $value;
        $plainTextContent = strip_tags($content);
     
        $wordCount = str_word_count($plainTextContent);
        // Check if the word count exceeds maxWords
        if ($wordCount > $this->maxWords) {
            $fail("The content may not be greater than {$this->maxWords} words.");
        }
    }

}
