<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Vartotojas
 * Date: 6/10/13
 * Time: 2:20 PM
 * To change this template use File | Settings | File Templates.
 */

require_once("$CFG->libdir/formslib.php");

class select_students_form extends moodleform{

    function definition() {
        global $CFG;
        $mform =& $this->_form;

        $mform->addElement('text', 'email', get_string('email')); // Add elements to your form
        //$mform->setType('email', PARAM_NOTAGS);
        //$mform->addElement('date_selector', 'timestart', 'xxx');
        
    }
}
