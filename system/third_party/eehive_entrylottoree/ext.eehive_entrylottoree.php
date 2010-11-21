<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eehive_entrylottoree_ext { 
	
	var $name		= 'EE Hive - Entry Lottoree';
	var $version 		= '1.0';
	var $description	= 'Allows you to pick X random entries from your exp:channel:entries loop';
	var $settings_exist	= 'n';
	var $docs_url		= 'http://www.ee-hive.com/entry-lottoree';

	var $settings        = array();

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	function Eehive_entrylottoree_ext($settings='')
	{
		$this->EE =& get_instance();
		
		$this->settings = $settings;
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
		$this->settings = array();
		
		
		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'lottoree',
			'hook'		=> 'channel_entries_query_result',
			'settings'	=> serialize($this->settings),
			'priority'	=> 10,
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);
		
		$this->EE->db->insert('extensions', $data);
	}
	
	
	
	/**
	 * Pick the Entry Lottoree Winners
	 * 
	 * @param 	array 	array from the preg_match
	 * @return 	string	Newly truncated Link.
	 */
	function lottoree($obj, $query_result) {
		
			$this->EE =& get_instance();
			
			// Fetch the lottoree parameter from the exp:channel:entries tag
			$lottoree = $this->EE->TMPL->fetch_param('lottoree', '');
		
			// if Lottoree is in use
			if ($lottoree != '') {
				// Randomize the entries
				$keys = array_keys($query_result); 
				shuffle($keys); 
				$random = array(); 
				foreach ($keys as $key)  {
					$random[$key] = $query_result[$key]; 
				}
				// Return the requested number of entries
			  	return array_slice($random,0,$lottoree);
			} 
			// Otherwise just return the entries
			else {
				return $query_result;
			}
	}

	
	
	
	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}

		// init data array
		$data = array();

		// Add version to data array
		$data['version'] = $this->version;

		// Update records using data array
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update('exp_extensions', $data);
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
		$this->EE->db->delete('extensions');
	}





}
// END CLASS

/* End of file ext.eehive_entrylottorree.php */