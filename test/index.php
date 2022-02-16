<?php
use Rndoom04\spamdetector;

// Init library
$spamdetector = new \Rndoom04\spamdetector\detector();

// Set the library
$spamdetector->detectLanguage(true); // Detect language? | default false
$spamdetector->setLanguageWhitelist(["cs", "sk", "en"]); // Only cs (czech language), sk (slovak language) and en (english)
$spamdetector->setTestForbiddenWords(true); // Test on forbidden words? | default true
$spamdetector->setTestUrls(true); // Test on URLs? | default true

// One test
echo $spamdetector->test("Is this string ok?");

// Variable test
$testString = "Is this next string ok?";
echo $spamdetector->test($testString);

// Multiple testing
$test = [
    /* NOK */ "Hello! I propose you make a business with crypto token that runs on the Binance Smart Chain. There are 1,000,000 tokens in total. -1% daily ROI of up to 365% with reinvestment and withdrawal -Sustainability through transaction tax -Referral system and incentives for team building: -10% of partner's deposit -5% of partner's recompound -The opportunity to own the first deflationary, fully decentralised income farm. Please, message me at t.me/Drip_Leaders If you are interested in joining!",
    /* NOK */ "Lorem ipsum dolor sit amet.",
    /* NOK */ "Start saving with freelance services today! All for your website - Development, Promotion, Texts, SEO, Backlinks, Audit, Video. Everything from $10 -> is.gd/Rj0wW2",
    /* OK */ "Dobrý den, měl bych zájem o Vaše služby. Můžete mi, prosím, zaslat cenovou nabídku? Děkuji, Karel Novák.",
    /* OK */ "Dobrý deň, mal by som záujem o Vaše služby. Môžete mi, prosím, zaslať cenovú ponuku? Ďakujem, Karel Novák.",
    /* NOK */ "I'll send you my female body with intimate photos. Follow me on is.gd/loveyou69",
    /* NOK */ "ничего особенного _________________ казинолы? супермарке мине-ауксандар - quanto costa il casino di venezia - казинолы? гичардыны? жылды? есебі 2021 ж"
];

foreach($test as $string) {
    $testString = $spamdetector->test($string);
    echo $testString?"OK":"NOK";
    echo "<br>";
}