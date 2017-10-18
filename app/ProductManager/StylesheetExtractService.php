<?php

namespace App\ProductManager;

use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Facades\Excel;

class StylesheetExtractService
{
    /**
     * @var RowCollection
     */
    private $contents;

    /**
     * Extract data from excel spreadsheet.
     *
     * @param $filePath
     * @return array with the data extracted from the file.
     */
    public function extractDataFromStylesheet($filePath)
    {
        $data = [];
        $this->loadDataFromStylesheet($filePath);

        $data['category'] = $this->getCategoryFromStylesheet();
        $data['products'] = $this->getProductsFromStylesheet($data['category']['id']);

        return $data;
    }

    /**
     * Loads the data from the first sheet of file.
     *
     * @param $filePath
     */
    private function loadDataFromStylesheet($filePath)
    {
        $this->contents = Excel::load(storage_path("app/$filePath"), function ($reader) {
            $reader->noHeading()->ignoreEmpty();
        })->get()->first();
    }

    /**
     * Extract the category found in file.
     *
     * @param $categoryId
     * @return array with products
     */
    private function getCategoryFromStylesheet()
    {
        $lineCategory = 0;
        $columnIdCategory = 1;

        $lineDescriptionCategory = 3;
        $columnDescriptionCategory = 2;

        return [
            'id' => $this->getValue($lineCategory, $columnIdCategory),
            'name' => $this->getValue($lineDescriptionCategory, $columnDescriptionCategory)
        ];
    }

    /**
     * Extract the products found in file.
     *
     * @param $categoryId
     * @return array with products
     */
    private function getProductsFromStylesheet($categoryId)
    {
        $columnId = 0;
        $columnName = 1;
        $columnFreeShipping = 3;
        $columnDescription = 4;
        $columnPrice = 5;
        $lineProducts = 3;

        $total = $this->contents->count();

        $products = [];

        for ($line = $lineProducts; $line < $total; $line++) {
            array_push($products, [
                'id' => $this->getValue($line, $columnId),
                'category_id' => $categoryId,
                'name' => $this->getValue($line, $columnName),
                'free_shipping' => $this->getValue($line, $columnFreeShipping),
                'description' => $this->getValue($line, $columnDescription),
                'price' => $this->getValue($line, $columnPrice),
            ]);
        }

        return $products;
    }

    /**
     * A helper to assist in reading data.
     *
     * @param int $line
     * @param int $column
     * @return mixed
     */
    private function getValue($line, $column)
    {
        return isset($this->contents->get($line)[$column])
            ? $this->contents->get($line)[$column]
            : null;
    }
}
