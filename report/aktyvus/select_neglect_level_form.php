<?php
/**
 * Created by Vaidas Giedrimas
 * Date: 6/10/13
 * Time: 2:20 PM
 * To change this template use File | Settings | File Templates.
 */

require_once("$CFG->libdir/formslib.php");

class select_neglect_level_form extends moodleform{

    function definition() {
//global $CFG;
 
        $mform = $this->_form;  
        $mform->addElement('date_selector', 'neglecttime', //'Apleistu kursas laikomas jeigu nebuvo naudotas nuo..');
        get_string('nuokada', 'report_aktyvus'));
        
        $this->add_action_buttons($cancel=false, $submitlabel="Vykdyti");
   
    }
}
