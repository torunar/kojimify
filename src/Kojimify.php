<?php

namespace Kojimify;

/**
 * Class Kojimify provides tools to create ingenious texts.
 *
 * @package Kojimify
 */
class Kojimify
{
    /**
     * @var string
     */
    protected $wordsSeparator;

    /**
     * @var string
     */
    protected $charactersGlue;

    /**
     * @var string
     */
    protected $linesGlue;

    /**
     * @var string
     */
    protected $wordsGlue;

    /**
     * @var string
     */
    protected $geniusMark;

    /**
     * Kojimify constructor.
     *
     * @param string $wordSeparator  Character that is used as a words separator
     * @param string $charactersGlue Character that will be put between characters of a kojimified word
     * @param string $linesGlue      Character that will be put between lines of a kojimified word
     * @param string $wordsGlue      Character that will be used to join words of a kojimified text
     * @param string $geniusMark     Character that is used to detect whether the text has increased genius
     */
    public function __construct(
        string $wordSeparator = ' ',
        string $charactersGlue = '  ',
        string $linesGlue = "\n",
        string $wordsGlue = "\n\n",
        string $geniusMark = '!'
    ) {

        $this->wordsSeparator = $wordSeparator;
        $this->charactersGlue = $charactersGlue;
        $this->linesGlue = $linesGlue;
        $this->wordsGlue = $wordsGlue;
        $this->geniusMark = $geniusMark;
    }

    /**
     * Bootstrap method that kojimifies the whole text.
     *
     * @param string $text
     *
     * @return string
     */
    public function processText(string $text): string
    {
        $sourceText = $text;
        $isGenius = $this->isGenius($text);
        if ($isGenius) {
            $sourceText = $this->getCleanText($text);
        }

        $words = $this->splitText($sourceText);
        foreach ($words as &$word) {
            $word = $this->processWord($word, $isGenius);
        }
        unset($word);

        $processedText = $this->buildText($words);

        return $processedText;
    }

    /**
     * Splits source text into separate words.
     *
     * @param string $text
     *
     * @return array
     */
    public function splitText(string $text): array
    {
        $words = explode($this->wordsSeparator, $text);

        $wordsFiltered = array_filter($words);

        return array_values($wordsFiltered);
    }

    /**
     * Kojimifies a single word.
     *
     * @param string $word
     * @param bool   $isGenius
     *
     * @return string
     */
    public function processWord(string $word, bool $isGenius): string
    {
        $characters = [];
        for ($i = 0; $i < mb_strlen($word); $i++) {
            $characters[] = mb_substr($word, $i, 1);
        }

        $processedWord = implode($this->charactersGlue, $characters);
        $wordLength = count($characters);
        for ($i = 1; $i < $wordLength; $i++) {
            $processedWord .= $this->linesGlue . $characters[$i];
            if ($isGenius) {
                $processedWord .= str_repeat($this->charactersGlue, $i) . str_repeat(' ', $i - 1) . $characters[$i];
            }
        }

        return $processedWord;
    }

    /**
     * Detects if the text has increased genius.
     *
     * @param string $text
     *
     * @return bool
     */
    public function isGenius(string $text): bool
    {
        $lastCharacter = mb_substr($text, -1);
        $isGenius = $lastCharacter === $this->geniusMark;

        return $isGenius;
    }

    /**
     * Removes a genius mark from the text.
     *
     * @param string $text
     *
     * @return string
     */
    public function getCleanText(string $text): string
    {
        while ($this->isGenius($text)) {
            $text = mb_substr($text, 0, mb_strlen($text) - 1);
        }

        return $text;
    }

    /**
     * Builds kojimified text from kojimified words.
     *
     * @param array $words
     *
     * @return string
     */
    public function buildText(array $words): string
    {
        $text = implode($this->wordsGlue, $words);

        return $text;
    }
}
