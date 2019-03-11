<?php

use Kojimify\Kojimify;
use PHPUnit\Framework\TestCase;

class KojimifyTest extends TestCase
{
    /** @var \Kojimify\Kojimify */
    protected $app;

    public function setUp()
    {
        $this->app = new Kojimify(' ', ' ', "\n", "\n\n", '!');
    }

    /**
     * @dataProvider dpIsGenius
     */
    public function testIsGenius(string $text, string $expected)
    {
        $actual = $this->app->isGenius($text);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dpBuildText
     */
    public function testBuildText(array $words, string $expected)
    {
        $actual = $this->app->buildText($words);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dpGetCleanText
     */
    public function testGetCleanText(string $text, string $expected)
    {
        $actual = $this->app->getCleanText($text);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dpProcessWord
     */
    public function testProcessWord(string $word, bool $isGenius, string $expected)
    {
        $actual = $this->app->processWord($word, $isGenius);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dpSplitText
     */
    public function testSplitText(string $text, array $expected)
    {
        $actual = $this->app->splitText($text);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dpProcessText
     */
    public function testProcessText(string $text, string $expected)
    {
        $actual = $this->app->processText($text);

        $this->assertEquals($expected, $actual);
    }

    public function dpIsGenius()
    {
        return [
            ['', false],
            ['!', true],
            ['kojima genius', false],
            ['kojima genius!', true],
        ];
    }

    public function dpGetCleanText()
    {
        return [
            ['', ''],
            ['!', ''],
            ['kojima genius', 'kojima genius'],
            ['kojima genius!', 'kojima genius'],
            ['kojima genius!!!', 'kojima genius'],
        ];
    }

    public function dpSplitText()
    {
        return [
            ['', []],
            ['   ', []],
            ['kojima genius', ['kojima', 'genius']],
            ['kojima     genius', ['kojima', 'genius']],
        ];
    }

    public function dpProcessWord()
    {
        return [
            [
                'kojima',
                false,
                <<<EOK
k o j i m a
o
j
i
m
a
EOK
                ,
            ],
            [
                'kojima',
                true,
                <<<EOK
k o j i m a
o o
j   j
i     i
m       m
a         a
EOK
                ,
            ],
            [
                'гений',
                false,
                <<<EOK
г е н и й
е
н
и
й
EOK
                ,
            ],
            [
                'гений',
                true,
                <<<EOK
г е н и й
е е
н   н
и     и
й       й
EOK
                ,
            ],
        ];
    }

    public function dpBuildText()
    {
        return [
            [[], ''],
            [['kojima', 'genius'], "kojima\n\ngenius"],
            [['кодзима', 'гений'], "кодзима\n\nгений"],
        ];
    }

    public function dpProcessText()
    {
        return [
            [
                'kojima genius',
                <<<EOK
k o j i m a
o
j
i
m
a

g e n i u s
e
n
i
u
s
EOK
                ,
            ],
            [
                'kojima genius!',
                <<<EOK
k o j i m a
o o
j   j
i     i
m       m
a         a

g e n i u s
e e
n   n
i     i
u       u
s         s
EOK
                ,

            ],
            [
                'кодзима гений',
                <<<EOK
к о д з и м а
о
д
з
и
м
а

г е н и й
е
н
и
й
EOK
                ,
            ],
        ];
    }
}
