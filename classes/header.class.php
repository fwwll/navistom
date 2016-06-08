<?php

class Header {
    private static $_meta 	= array();
    private static $_title	= null;
    private static $_h1		= null;
    private static $_social = array();

    public static function Location($url = null) {
        if ($url == null) {
            $url = $_SERVER['HTTP_REFERER'];
        }

        header("Location: $url");
        die();
    }

    public static function ContentType($type) {
        header("Content-Type: $type");
    }

    public static function SetTitle($title) {
        if ($title != null) {
            self::$_title = $title;

            if (Registry::get('route')->controller != 'articles' and Registry::get('route')->controller != 'main' and Registry::get('route')->controller != 'cabinet') {
                $prefix = ' - ' . Registry::get('country_name');
				$prefix ='';
            }

            Registry::get('twig')->addGlobal('meta_title', $title . $prefix);

            return true;
        }

        return false;
    }

    public static function SetMetaTag($name, $content) {
        if ($name != null and $content != null) {

            if ($name == 'description' and Registry::get('route')->controller != 'articles' and Registry::get('route')->controller != 'main' and Registry::get('route')->controller != 'cabinet') {
                $prefix = ' - ' . Registry::get('country_name');
				$prefix ='';
            }

            self::$_meta[$name] = $content . $prefix;

            Registry::get('twig')->addGlobal('meta_tags', self::$_meta);

            return true;
        }

        return false;
    }

    public static function SetH1Tag($name) {
        self::$_h1 = $name;

        if (Registry::get('route')->controller != 'articles' and Registry::get('route')->controller != 'main' and Registry::get('route')->controller != 'cabinet' and Registry::get('route')->controller != 'users') {
             $prefix = ' - ' . Registry::get('country_name'); 
			$prefix ='';
        }

        if (Registry::get('route')->action != 'full' and Registry::get('route')->action != 'resumeFull' and Registry::get('route')->action != 'vacancyFull') {
            Registry::get('twig')->addGlobal('title', $name . $prefix);
        }
    }

    public static function GetMetaTag($key) {
        return self::$_meta[$key];
    }

    public static function GetTitle() {
        return self::$_title;
    }

    public static function GetMetaTagsList() {
        return self::$_meta;
    }

    public static function GetH1Tag() {
        return self::$_h1;
    }

    public static function SetSocialTag($key, $value) {
        self::$_social[$key] = $value;

        Registry::get('twig')->addGlobal('social_meta_tags', self::$_social);

        return true;
    }

    public static function GetSocialTags() {
        return self::$_social;
    }
}