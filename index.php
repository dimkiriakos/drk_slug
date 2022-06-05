<?php

/*
Plugin Name: drk_slug
Description: A simple WordPress plugin that controlls the slug generation
Version: 1.0
Author: Dimitrios Kyriakos
Author URI: https://dimkiriakos.com
Text Domain: drk_slug
*/

if (! defined('ABSPATH')){
    die("I'm just a plugin not much I can do when called directly.");
    exit;
}

class DRK_Slug{
    public function __construct()
    {
        $this->Init();
    }
    
    
    private function Init(){
        
        add_filter("wp_unique_post_slug", [$this, 'result'], 10, 6);
    }

    public function greeklish($str) {
        $greek = array('α','ά','Ά','Α','β','Β','γ','Γ','δ','Δ','ε','έ','Ε','Έ','ζ','Ζ','η','ή','Η','θ','Θ',
        'ι','ί','ϊ','ΐ','Ι','Ί','κ','Κ','λ','Λ','μ','Μ','ν','Ν','ξ','Ξ','ο','ό','Ο','Ό','π','Π','ρ','Ρ','σ',
        'ς','Σ','τ','Τ','υ','ύ','Υ','Ύ','φ','Φ','χ','Χ','ψ','Ψ','ω','ώ','Ω','Ώ');
        $english = array('a', 'a','A','A','b','B','g','G','d','D','e','e','E','E','z','Z','i','i','I','th','Th',
        'i','i','i','i','I','I','k','K','l','L','m','M','n','N','x','X','o','o','O','O','p','P' ,'r','R','s',
        's','S','t','T','u','u','Y','Y','f','F','x','X','ps','Ps','o','o','O','O');
        $string = str_replace($greek, $english, $str);
        return $string;
    }
    
    public function slugify($string, string $divider = '-')
    {
      $string = preg_replace('~[^\pL\d]+~u', $divider, $string);
      $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
      $string = preg_replace('~[^-\w]+~', '', $string);
      $string = trim($string, $divider);
      $string = preg_replace('~-+~', $divider, $string);
      $string = strtolower($string);
    
      if (empty($string)) {
        return 'n-a';
      }
    
      return $string;
    }

    public function result($slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug){
        $slug = $this->greeklish($slug);
        $slug = $this->slugify($slug);
        $char_count = strlen($slug);
        if ($char_count > 20){
            $slug = $post_ID . '-' . substr($slug,0,10);
        }
        return $slug;
    }
}

new DRK_Slug();