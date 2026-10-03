<?php

namespace Tests\Feature;

use Tests\TestCase;

class BookCatalogTest extends TestCase
{
    public function test_book_detail_page_renders(): void
    {
        $response = $this->get('/books/1');

        $response->assertOk();
        $response->assertSeeText('Laravel Essentials');
        $response->assertSeeText('Programming');
    }
}
