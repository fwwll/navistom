<?php

namespace Mailer;

use \DB, \ArrayObject, \User, \ModelCabinet;

class Storage {

    const table = 'subscribe_storage';

    public static function get() {
        $storage =& DB::select(array('section_id', 'content_id'))->from(self::table)->model('\Mailer\StorageModel')->fetch();
        return new StorageCollection($storage);
    }

    public static function set($sectionId, $contentId) {
        DB::insert(self::table, array(
            'section_id' => $sectionId,
            'content_id' => $contentId
        ), 1);

        return DB::lastInsertId();
    }

    public static function clear() {
        DB::truncate(self::table);
        return false;
    }
}


class StorageCollection extends ArrayObject {

    public function __construct(array $items) {
        $this->setFlags(ArrayObject::ARRAY_AS_PROPS);
        $this->exchangeArray($items);

        return $this;
    }

    public function get($sectionId) {
        return StorageModel::$sorted[$sectionId];
    }

    public function has($sectionId) {
        return isset(StorageModel::$sorted[$sectionId]);
    }

    public function join($sectionId) {
        if ($this->has($sectionId)) {
            return implode(',', $this->get($sectionId));
        } else {
            return '';
        }
    }
}


class StorageModel {

    public $section_id;
    public $content_id;

    public static $sorted = array();

    public function __construct() {
        self::$sorted[$this->section_id][ ] = $this->content_id;
    }

    private function table() {
        $sections = array(
            1 => 'articles', 16 => 'articles', 3 => 'products_new', 4 => 'ads',
            5 => 'activity', 6 => 'work', 7 => 'labs', 8 => 'realty',
            9 => 'services', 10 => 'diagnostic', 11 => 'demand', 15 => 'vacancies'
        );

        return $sections[$this->section_id];
    }

    private function primary() {
        $keys = array(
            1 => 'article_id', 16 => 'article_id', 3 => 'product_new_id', 4 => 'ads_id',
            5 => 'activity_id', 6 => 'work_id', 7 => 'lab_id', 8 => 'realty_id',
            9 => 'service_id', 10 => 'diagnostic_id', 11 => 'demand_id', 15 => 'vacancy_id'
        );

        return $keys[$this->section_id];
    }

    public function __get($name) {
        if (method_exists($this, $name)) {
            return $this->{$name}();
        }
    }
}