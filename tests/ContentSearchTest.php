<?php

use Krumer\Test\Tools\Utils\ContentSearch;

class ContentSearchTest extends PHPUnit_Framework_TestCase {

    public function test_find_search_fields_with_one_level()
    {
        $content = [
            'value1' => 'Test1',
            'value2' => 'Test2'
        ];

        $result = ContentSearch::findSearchFields('value1', $content);

        $this->assertCount(1, $result);
        $this->assertContains('Test1', $result);
    }

    public function test_find_search_fields_with_two_levels()
    {
        $content = [
            'level1' => [
                'value1' => 'Test1',
                'value2' => 'Test2'
            ],
            'level2' => [
                'value1' => 'Test3'
            ],
            'value1' => 'Test4'
        ];

        $result = ContentSearch::findSearchFields('level1.value2', $content);

        $this->assertCount(1, $result);
        $this->assertContains('Test2', $result);
    }

    public function test_find_search_fields_with_wildcard_query()
    {
        $content = [
            'level1' => [
                'value1' => 'Test1',
                'value2' => 'Test2'
            ],
            'level2' => [
                'value1' => 'Test3'
            ],
            'value1' => 'Test4'
        ];

        $result = ContentSearch::findSearchFields('*.value1', $content);

        $this->assertCount(2, $result);
        $this->assertContains('Test1', $result);
        $this->assertContains('Test3', $result);

    }

}