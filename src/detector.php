<?php
    /*
     * SpamDetector by Kollert Slavomír
     * version: 1.0
     * release date: 16.2.2022
     */

    namespace Rndoom04\spamdetector;
    
    use LanguageDetection\Language;

    class detector {
        /* The string where to test */
        private $string = null;
        
        /* Count of errors during spam test */
        private $err;
        
        /* Errors during spam test */
        private $errors = [];
        
        /* Forbidden words */
        private $forbidden_words = [];
        private $forbidden_words_urls = [];
        
        /* Languages */
        private $languageWhitelist = [];
        private $ld; // Language detect library
        
        /* Settings */
        private $testingLanguage = false;
        private $testingForbiddenWords = true;
        private $testingUrls = true;
        
        public function __construct() {
            // Add forbidden words to list from file
            $this->forbidden_words = $this->initForbiddenWords();
            $this->forbidden_words_urls = $this->initForbiddenWordsUrls();
            
            $this->ld = new Language;
        }

        /* Main function to test strings */
        public function test($string) {
            // Set the string to search in
            $this->string = $string;

            // Clear all
            $this->err = 0;
            $this->errors = [];

            // Test it
            if ($this->testingLanguage) { $this->testLanguage(); }
            if ($this->testingForbiddenWords) { $this->testForbiddenWords(); }
            if ($this->testingUrls) { $this->testUrlLinks(); }

            // Return bool
            if ($this->err > 0) {
                // There is an error, pass failed
                return false;
            }

            // Pass 
            return true;
        }

        /* Test string to forbidden words */
        private function testForbiddenWords() {
            foreach ($this->forbidden_words as $word) {
                if (strpos($this->string, $word) !== false) {
                    // Obsahuje
                    $this->addError("Contain forbidden word '" . $word . "' on position '" . strpos($this->string, $word) . "'.");
                    $this->err++;
                }
            }
        }

        /* Test URL link during test */
        private function testUrlLinks() {
            $links = [];
            preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $this->string, $links);

            if (isset($links[0]) && !empty($links[0]) && count($links) > 0) {
                foreach ($links[0] as $link) {
                    $this->addError("URL contains: '" . $link . "'.");
                    $this->err++;
                }
            }
            
            foreach ($this->forbidden_words_urls as $word) {
                if (strpos($this->string, $word) !== false) {
                    // Obsahuje
                    $this->addError("Short url find in: '" . $word . "'.");
                    $this->err++;
                }
            }
        }

        /* Test language */
        private function testLanguage() {
            $ok = false;
            
            $languages = $this->ld->detect($this->string)->close();
            $topLangs = array_slice($languages, 0, 2, true);
            
            foreach($this->languageWhitelist as $lang) {
                if (array_key_exists($lang, $topLangs)) {
                    $ok = true;
                }
            }
           
            if (!$ok) {
                $this->addError("Detected unallowed language. ". join(",", array_keys($topLangs)));
                $this->err++;
            }
        }

        /* Add error during one test */
        private function addError($error) {
            $this->errors[] = $error;
        }
        
        /* Settings */
            /* Add next forbidden words */
            public function addForbiddenWord($phrase) {
                if (is_array($phrase)) {
                    // Array of words
                    foreach($phrase as $word) {
                        $this->forbidden_words[] = $word;
                    }
                } else {
                    // Only one word
                    $this->forbidden_words[] = $phrase;
                }
            }
            
            /* Language whitelist */
            public function setLanguageWhitelist($whitelist) {
                $this->languageWhitelist = $whitelist;
            }
            
            /* Set what to test - test forbidden words */
            public function setTestForbiddenWords($val) {
                $this->testingForbiddenWords = $val;
            }
            
            /* Set what to test - test urls */
            public function setTestUrls($val) {
                $this->testingUrls = $val;
            }
            
            /* Set what to test - test language by whitelist */
            public function detectLanguage($val) {
                $this->testingLanguage = $val;
            }
            
        /* Returns */
            /* Return all errors during one test */
            public function getErrors() {
                return $this->errors;
            }
            
            
        /* Init forbidden words */
        private function initForbiddenWordsUrls() {
            return [
                ".ru",
                "www.",
                "bit.ly",
                "tinyurl.com",
                "is.gs/",
                "is.gd/",
            ];
        }
        private function initForbiddenWords() {
            return [
                "$/month",
                "CLICK HERE",
                "become rich",
                ">>>",
                "female body",
                "erotic",
                " sex", // Space purposely
                "18+",
                " cum", // Space purposely
                "Referral system",
                "intimate photos",
                "odblokování Vaší karty",
                "in PM",
                " limited", // Space purposely
                "next 24 hours",
                "database"
            ];
        }
    }
?>