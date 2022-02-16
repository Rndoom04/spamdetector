SpamDetector
=============

PHP `test()`. Compatible with PHP >= 7.0.

This is a simple library for detect spam from string - good for example contacts forms

## Install

For PHP version **`>= 7.0`**:

```
composer require Rndoom04/spamdetector
```

## How to use it

Firstly init the library by simply "use".

```
use Rndoom04\spamdetector;
```

Then set load the library and set it how you want.
```
$spamdetector = new \Rndoom04\spamdetector\detector();

$spamdetector->detectLanguage(true); // Detect language? Default false
$spamdetector->setLanguageWhitelist(["cs", "sk", "en"]); // Only cs (czech language), sk (slovak language) and en (english)
$spamdetector->setTestForbiddenWords(true); // Test on forbidden words? Default true
$spamdetector->setTestUrls(true); // Test on URLs? Default true
```

Easy! Let's test detecting.
```
// One test
echo $spamdetector->test("Is this string ok?");
```
```
// Variable test
$testString = "Is this next string ok?";
echo $spamdetector->test($testString);
```