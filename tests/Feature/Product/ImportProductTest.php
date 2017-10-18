<?php

namespace Tests\Feature;

use App\ProductManager\Models\File;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ImportProductTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function import_products_from_stylesheet()
    {
        $this->json('POST', '/api/products', [
            'spreadsheet' => $this->getFile('products_teste_webdev_leroy.xlsx')
        ])->assertStatus(201)
          ->assertExactJson([
            'data' => [
                'url_processing_status' => 'http://localhost/api/processing_status/1'
            ]
        ]);

        $this->json('GET', '/api/processing_status/1')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'processing_status' => File::STATUS_PROCESSED
                ]
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => '123123',
            'name' => 'Ferramentas'
        ]);

        $this->assertDatabaseHas('products', [
            'id' => 1001,
            'category_id' => 123123,
            'name' => 'Furadeira X',
            'free_shipping' => 0,
            'description' => 'Furadeira eficiente X',
            'price' => 100
        ]);

        $this->assertDatabaseHas('products', [
            'id' => 1002,
            'category_id' => 123123,
            'name' => 'Furadeira Y',
            'free_shipping' => 1,
            'description' => 'Furadeira super eficiente Y',
            'price' => 140
        ]);

        $this->assertDatabaseHas('products', [
            'id' => 1003,
            'category_id' => 123123,
            'name' => 'Chave de Fenda X',
            'free_shipping' => 0,
            'description' => 'Chave de fenda simples',
            'price' => 20
        ]);

        $this->assertDatabaseHas('products', [
            'id' => 1008,
            'category_id' => 123123,
            'name' => 'Serra de Marmore',
            'free_shipping' => 1,
            'description' => 'Serra com 1400W modelo 4100NH2Z-127V-L',
            'price' => 399
        ]);

        $this->assertDatabaseHas('products', [
            'id' => 1009,
            'category_id' => 123123,
            'name' => 'Broca Z',
            'free_shipping' => 0,
            'description' => 'Broca simples',
            'price' => 3.9
        ]);

        $this->assertDatabaseHas('products', [
            'id' => 1010,
            'category_id' => 123123,
            'name' => 'Luvas de Proteção',
            'free_shipping' => 0,
            'description' => 'Luva de proteção básica',
            'price' => 5.6
        ]);
    }

    /** @test */
    public function cannot_import_products_with_invalid_product_id()
    {
        $this->json('POST', '/api/products', [
            'spreadsheet' => $this->getFile('invalid_id.xlsx')
        ])->assertExactJson([
            'messages' => ['Invalid product.']
        ]);
    }

    /** @test */
    public function cannot_import_products_with_invalid_product_and_verify_if_processed_status_is_failed()
    {
        $this->json('POST', '/api/products', [
            'spreadsheet' => $this->getFile('invalid_category.xlsx')
        ])->assertExactJson([
            'messages' => ['Invalid product.']
        ]);

        $this->json('GET', '/api/processing_status/1')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'processing_status' => File::STATUS_FAILED
                ]
            ]);
    }

    /** @test */
    public function cannot_import_products_with_invalid_category()
    {
        $this->json('POST', '/api/products', [
            'spreadsheet' => $this->getFile('invalid_category.xlsx')
        ])->assertExactJson([
            'messages' => ['Invalid product.']
        ]);
    }

    /** @test */
    public function cannot_import_products_with_invalid_product_name()
    {
        $this->json('POST', '/api/products', [
            'spreadsheet' => $this->getFile('invalid_name.xlsx')
        ])->assertExactJson([
            'messages' => ['Invalid product.']
        ]);
    }

    /** @test */
    public function cannot_import_products_with_invalid_product_description()
    {
        $this->json('POST', '/api/products', [
            'spreadsheet' => $this->getFile('invalid_description.xlsx')
        ])->assertExactJson([
            'messages' => ['Invalid product.']
        ]);
    }

    /** @test */
    public function cannot_import_products_with_invalid_product_free_shipping()
    {
        $this->json('POST', '/api/products', [
            'spreadsheet' => $this->getFile('invalid_free_shipping.xlsx')
        ])->assertExactJson([
            'messages' => ['Invalid product.']
        ]);
    }

    /** @test */
    public function cannot_import_products_with_invalid_product_price()
    {
        $this->json('POST', '/api/products', [
            'spreadsheet' => $this->getFile('invalid_price.xlsx')
        ])->assertExactJson([
            'messages' => ['Invalid product.']
        ]);
    }

    private function getFile($fileName)
    {
        $fileDetails = $this->getFileDetails($fileName);

        return new UploadedFile(
            $fileDetails['filePath'],
            $fileDetails['fileName'],
            $fileDetails['mimeType'],
            $fileDetails['fileSize'],
            $error = null,
            $test = true
        );
    }

    private function getFileDetails($fileName)
    {
        $filePath = __DIR__ . '/Stub/' . $fileName;

        return [
            'fileName' => $fileName,
            'filePath' => $filePath,
            'fileSize' => filesize($filePath),
            'mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
    }
}
