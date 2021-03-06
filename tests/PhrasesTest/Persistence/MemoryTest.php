<?php

namespace Phrases\Persistence;

use Phrases\Entity\Phrase;
use PhrasesTestAsset\ConsumedData;

/**
 * @small
 */
class MemoryTest extends \PHPUnit_Framework_TestCase
{
    protected $phraseList = [];

    protected function setUp()
    {
        foreach(ConsumedData::asRelationalArray() as $value){
            $phrase = new Phrase($value['title'], $value['text']);
            array_push($this->phraseList, $phrase);
        }
    }

    public function testFindOneRandomWithAnEmptyListReturnAnEmptyArray()
    {
        $list = [];
        $phrases = new Memory($list);

        $this->assertInstanceOf(
            RepositoryInterface::class,
            $phrases
        );
        $this->assertEquals(
            $expected = [],
            $phrases->findOneRandom()
        );
    }

    public function testFindOneRandomWithOnlyOnePhraseInTheListAlwaysReturnTheSamePhrase()
    {
        $list = [
            $expectedPhrase = $this->phraseList[0]
        ];
        $phrases = new Memory($list);

        $this->assertEquals(
            $expectedPhrase,
            $phrases->findOneRandom()
        );
        $this->assertEquals(
            $expectedPhrase,
            $phrases->findOneRandom(),
            'Only one phrase is present on the list, so it should be the only "random" option.'
        );
    }

    public function testFindOneRandomWithPhrasesInTheListReturnsFromTheList()
    {
        $list = $this->phraseList;
        $phrases = new Memory($list);

        $this->assertContains(
            $phrases->findOneRandom(),
            $list
        );

        $this->assertContains(
            $phrases->findOneRandom(),
            $list
        );
    }

    /**
     * @depends testFindOneRandomWithOnlyOnePhraseInTheListAlwaysReturnTheSamePhrase
     */
    public function testSaveAppendsPhraseIntoExistingPhraseList()
    {
        $phrases = new Memory([$this->phraseList[0]]);
        $newPhrase = $this->phraseList[1];

        $return = $phrases->save($newPhrase);
        $this->assertEquals(
            $newPhrase,
            $return
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Phrase list should contain only Phrase entities.
     */
    public function testMemoryInstantiatedWithArrayOfArraysThrowsAnException()
    {
        $phraseList = [
            ['title' => 'Its a phrase.']
        ];
        $phrases = new Memory($phraseList);
    }
}

