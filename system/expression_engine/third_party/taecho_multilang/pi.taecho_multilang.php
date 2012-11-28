<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Taecho Group's Multi-Language system
 *
 * An EE2 module to handle language translation
 *
 * @package     TG Languages
 * @author      Robert Banh
 * @copyright   Copyright (c) 2012, Taecho Group, LLC.
 * @link        http://taechogroup.com
 * @since       Version 1.0
 * @filesource
 */

$plugin_info = array(  'pi_name' => 'Taecho_Multilang',
    'pi_version' => '1.0',
    'pi_author' => 'Robert Banh',
    'pi_author_url' => 'http://taechogroup.com',
    'pi_description' => 'Handles multi-languages text',
    'pi_usage' => taecho_multilang::usage());

class Taecho_Multilang
{
    public $return_data = "";
    public $tg_session = "";

    // ----------------------------------------
    //  
    // ----------------------------------------
    public function __construct()
    {
        $this->EE =& get_instance();

        if (!isset($this->EE->tg_session)) 
        {
            $this->EE->load->library("tg_session", array(
                'cookie_prefix'=>'exp_',
                'sess_cookie_name'=>'tg_cookie',
                'cookie_path'=>'/',
                'encryption_key' => 'abc321encrypt'
                ));  
        }

        $tagdata = trim($this->EE->TMPL->tagdata);

        // fetch language
        $languages = $this->EE->tg_session->userdata('taecho_multilang');
        
        if ($languages == 'en' || $languages == false) return $this->return_data = $tagdata;


        $lang = array();
        if (file_exists(PATH_THIRD.'taecho_multilang/languages/'.$languages.'.php'))
            require PATH_THIRD.'taecho_multilang/languages/'.$languages.'.php';
        
        // all lowercase
        $l_word = strtolower($tagdata);
        
        if (isset($lang[$l_word]) && !empty($lang[$l_word]))
            $this->return_data = $lang[$l_word];
        else
            $this->return_data = $tagdata;
    }

    // ----------------------------------------
    //  {exp:taecho_multilang:set_language language="es"}
    // ----------------------------------------
    public function set_language()
    {
        $this->EE =& get_instance();
        
        $language = $this->EE->TMPL->fetch_param('language','en');

        // set it
        $this->EE->tg_session->set_userdata('taecho_multilang', $language);

        if (isset($_SERVER['HTTP_REFERER']))
        {
            $this->EE->functions->redirect($_SERVER['HTTP_REFERER']);
        }

        $this->EE->functions->redirect('/');
    }

    // ----------------------------------------
    /*  {exp:taecho_multilang:get_language}
            {if curr_language == 'es'}
                Do something
            {/if}
        {exp:taecho_multilang:get_language}
    */
    // ----------------------------------------
    public function get_language()
    {
        $this->EE =& get_instance();
        $tagdata = trim($this->EE->TMPL->tagdata);

        $languages = $this->EE->tg_session->userdata('taecho_multilang');
        if ($languages == false)
            $languages = 'en';


        $tagdata = str_replace('{curr_language}', $languages, $tagdata);

        return $this->return_data = $tagdata;
    }

    // ----------------------------------------
    //  
    // ----------------------------------------
    public function dates()
    {
        $this->EE =& get_instance();
        
        $tagdata = trim($this->EE->TMPL->tagdata);

        // fetch language
        $languages = $this->EE->tg_session->userdata('taecho_multilang');
        if ($languages == 'en' || $languages == false) return $this->return_data = $tagdata;

        // all lowercase
        $l_word = preg_split('/ /', strtolower($tagdata));

        $dates = array(
            "january" => 'enero', 
            "february" => 'febrero',
            "march" => 'marzo',
            "april" => 'abril',
            "may" => 'mayo',  
            "june" => 'junio', 
            "july" => 'julio', 
            "august" => 'agosto', 
            "september" => 'septiembre', 
            "october" => 'octubre', 
            "november" => 'noviembre',
            "december" => 'diciembre',
            );
        
        foreach ($l_word as &$l)
        {
            if (in_array($l, array_keys($dates)))
            {
                $l = ucwords($dates["$l"]);
            }
        }

        return $this->return_data = implode(' ', $l_word);
    }


    // ----------------------------------------
    //  Plugin Usage
    // ----------------------------------------
    // This function describes how the plugin is used.
    //  Make sure and use output buffering
    function usage()
    {
        ob_start();
        ?>
Example:
----------------
For TEXT conversion using the language file:

{exp:taecho_multilang}
    About Page
{/exp:taecho_multilang}


-- OR --

Add this one line in a template file such as /language/es and it will set
the session and redirect the user back to the previous page.

{exp:taecho_multilang:set_language language="es"}{/exp:taecho_multilang:set_language}

-- OR --

{exp:taecho_multilang:get_language}
    {if "{curr_language}" == "en"}
        Hi, I'm English!
    {/if}
{/exp:taecho_multilang:get_language}

Parameters:
----------------

----------------
CHANGELOG:

1.0
* 1st version for EE 2.0



        <?php
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
      /* END */

}