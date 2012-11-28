<?

class Taecho_Multilang_ext {

    var $name       = 'Taechogroup Hook Extension for Multilang';
    var $version        = '1.0';
    var $description    = 'Taechogroup hook for multi language';
    var $settings_exist = 'y';
    var $docs_url       = 'http://taechogroup.com';

    var $settings       = array();

    /**
     * Constructor
     *
     * @param   mixed   Settings array or empty string if none exist.
     */
    function __construct($settings = '')
    {
        $this->EE =& get_instance();

        $this->settings = $settings;

        if (!isset($this->EE->tg_session)) 
        {
            $this->EE->load->library("tg_session", array(
                'cookie_prefix'=>'exp_',
                'sess_cookie_name'=>'tg_cookie',
                'cookie_path'=>'/',
                'encryption_key' => 'abc321encrypt'
                ));  
        }
    }

    /**
     * Activate Extension
     *
     * This function enters the extension into the exp_extensions table
     *
     * @see http://codeigniter.com/user_guide/database/index.html for
     * more information on the db class.
     *
     * @return void
     */
    function activate_extension()
    {
        $this->settings = array(
            'max_link_length'   => 18,
            'truncate_cp_links' => 'no',
            'use_in_forum'      => 'no'
        );


        $data = array(
            'class'     => __CLASS__,
            'method'    => 'tg_query_result',
            'hook'      => 'channel_entries_query_result',
            'settings'  => 'n',
            'priority'  => 10,
            'version'   => $this->version,
            'enabled'   => 'y'
        );

        $this->EE->db->insert('exp_extensions', $data);
    }

    /**
     * Update Extension
     *
     * This function performs any necessary db updates when the extension
     * page is visited
     *
     * @return  mixed   void on update / false if none
     */
    function update_extension($current = '')
    {
        if ($current == '' OR $current == $this->version)
        {
            return FALSE;
        }

        if ($current < '1.0')
        {
            // Update to version 1.0
        }

        $this->EE->db->where('class', __CLASS__);
        $this->EE->db->update(
                    'exp_extensions',
                    array('version' => $this->version)
        );
    }

    /**
     * Disable Extension
     *
     * This method removes information from the exp_extensions table
     *
     * @return void
     */
    function disable_extension()
    {
        $this->EE->db->where('class', __CLASS__);
        $this->EE->db->delete('exp_extensions');
    }




    /**
     * The main function
     *
     * 
     *
     * @return void
     */
    function tg_query_result($obj, $query_result)
    {

        $languages = $this->EE->tg_session->userdata('taecho_multilang', 'en');
        
        if ($languages == 'en' || $languages == false) return $query_result;

        $mappings = $this->get_field_mappings();

        // swap out for spanish content
        $re = array();
        foreach ($query_result as $q)
        {
            foreach ($mappings as $m)
            {
                if (!empty($q[$m[1]]))
                    $q[$m[0]] = $q[$m[1]];
            }

            $re[] = $q;
        }

        //var_export($query_result);
        return $re;
    }

    // ===============
    // helper method
    // ===============
    function get_field_mappings()
    {
        $host = $this->EE->input->server('SERVER_NAME');

        // DEV box
        if ($host == 'dev.xxxx.com')
        {
            $data = array();
            $data[] = array('title', 'field_id_30');
            $data[] = array('field_id_2', 'field_id_31');
            $data[] = array('title', 'field_id_32');
            $data[] = array('field_id_19', 'field_id_33');
            $data[] = array('title', 'field_id_34');
            $data[] = array('field_id_12', 'field_id_35');
            $data[] = array('title', 'field_id_36');
            $data[] = array('field_id_1', 'field_id_37');
            $data[] = array('title', 'field_id_38');
            $data[] = array('field_id_21', 'field_id_39');
            $data[] = array('field_id_14', 'field_id_41');
            $data[] = array('field_id_25', 'field_id_42');
            return $data;
        }
        // PROD box
        else
        {
            $data = array();
            $data[] = array('title', 'field_id_30');
            $data[] = array('field_id_2', 'field_id_31');
            $data[] = array('title', 'field_id_32');
            $data[] = array('field_id_19', 'field_id_33');
            $data[] = array('title', 'field_id_34');
            $data[] = array('field_id_12', 'field_id_35');
            $data[] = array('title', 'field_id_36');
            $data[] = array('field_id_1', 'field_id_37');
            $data[] = array('title', 'field_id_38');
            $data[] = array('field_id_21', 'field_id_39');
            $data[] = array('field_id_14', 'field_id_41');
            $data[] = array('field_id_25', 'field_id_42');
            // Tickets page
            $data[] = array('title', 'field_id_76');
            $data[] = array('field_id_44', 'field_id_77');
            $data[] = array('field_id_52', 'field_id_78');
            $data[] = array('field_id_46', 'field_id_79');
            $data[] = array('field_id_48', 'field_id_80');
            $data[] = array('field_id_50', 'field_id_81');
            $data[] = array('field_id_83', 'field_id_108');
            $data[] = array('field_id_73', 'field_id_111');
            // Media page
            $data[] = array('title', 'field_id_89');
            $data[] = array('field_id_87', 'field_id_91');
            $data[] = array('field_id_88', 'field_id_90');
            
            return $data;
        }

    }


}

?>