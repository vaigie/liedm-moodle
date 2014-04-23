<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Displays live view of recent logs
 *
 * This file generates live view of recent logs.
 *
 * @package    report_apleisti
 * @copyright  2011 Petr Skoda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 
    require_once('../../config.php');
    require_once($CFG->libdir.'/adminlib.php');
    #admin_externalpage_setup('reporapleisti');
    //require_once('select_neglect_level_form.php');
    
    require_once(dirname(__FILE__).'/select_neglect_level_form.php');
	
	$id      = optional_param('id', $SITE->id, PARAM_INT);
	$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);
	
	

	require_login($course);
	
	
	admin_externalpage_setup('reportapleisti', '', null, '', array('pagelayout'=>'report'));
	
 	
    admin_externalpage_print_header();
    //echo $OUTPUT->header();
	echo $OUTPUT->heading(get_string('pluginname', 'report_apleisti'));
	/*$list=array('1' => 'Value 1', '2' => 'Value 2');
    $OUTPUT->htmllist($list);
    */
	#$select = html_select::make(array('1' => 'Value 1', '2' => 'Value 2'), 'choice1', '2');
	#echo $OUTPUT->select($select);

	//$select = html_select::make(array('1' => 'Value 1', '2' => 'Value 2'), 'choice1', '2');
    //echo $OUTPUT->select($select);
	
	/*$form = new html_form();
    //$form->url = new moodle_url('http://domain.com/index.php', array('id' => 3, 'userid' => 5));
    $checkbox = html_select_option::make_checkbox(1, false, 'Agree to terms');
    //$contents = $OUTPUT->container('Terms and conditions: Be kind and courteous');
    $contents .= $OUTPUT->checkbox($checkbox, 'agree');
    echo $OUTPUT->form($form, $contents);
*/
	//ECHO '11111111';
	
	//print_r(error_get_last());
	
	$nl_form = new select_neglect_level_form();
    
    $nl_form->display();
    /*ECHO '33333';
    print_r(error_get_last());
	*/
	
	if ($frmdata = $nl_form->get_data()) {
	 //   echo $frmdata->neglecttime;
    }
    else {
        echo 'Wrong date!';
    }
    
	$table = new html_table();
	$table->head = array('Padalinys',
	                     'Kurso pavadinimas',
	                     'Dėstytojas',
	                     'Registruota studentų',
	                     'Paskutinis dėstytojo prisijungimas',
	                     'Paskutinis studento prisijungimas');
	
	
    
    
	$eilutes=array(
    #array('Harry Potter', '76%', 'Getting better'),
    #array('Rincewind', '89%', 'Lucky as usual'),
    #array('Elminster Aumar', '100%', 'Easy when you know everything!')	
	)	;
	
	
	#echo "<table border='0'>
    #<tr>
    #<th>ID</th>
    #<th>Cat</th>
    #<th>FFN</th>
    #<th>SN</th>
    #</tr>";
	
	//$rs = $DB->get_recordset('course',(array) $conditions=null, $sort='', $fields='*', $limitfrom=0, $limitnum=0);
	
    $destemail='nera@duomenu.lt';
    $sqlemail='SELECT cat.name as name, c.id as id, c.fullname as fullname, c.shortname as shortname FROM {course} as c JOIN {course_categories} AS cat ON c.category = cat.id';
    
   
    
    $sql='SELECT cat.name as name, c.id as id, c.fullname as fullname FROM {course} as c JOIN {course_categories} AS cat ON c.category = cat.id';
    
    ///$rs = get_record_sql('SELECT * FROM {user} WHERE id = ?', array(1));
	//$rs = get_recordset_sql($sql, (array) $conditions=null, $limitfrom=0, $limitnum=0);
	
	$rs = $DB->get_recordset_sql($sql, (array) $conditions=null, $limitfrom=0, $limitnum=0);
	
   
    
     foreach ($rs as $record) {
         
         $lastStud=0;
         $lastDest=0;
         $destemail='<NĖRA DUOMENŲ>';
         //$sqlemail='SELECT cat.name as name, c.id as id, c.fullname as fullname, c.shortname as shortname FROM {course} as c INNER JOIN {course_categories} AS cat ON c.category = cat.id';
            
         //DESTYTOJU DUOMENYS
            $sqlaccess='SELECT u.email as email, u.firstname as fn, u.lastname as ln, ra.userid as userid, ula.timeaccess as timeaccess FROM {role_assignments} as ra INNER JOIN {user} AS u ON ra.userid = u.id JOIN {user_lastaccess} AS ula ON u.id=ula.userid WHERE ra.roleid <> 5 and ula.courseid = ?';// WHERE ula.courseid=5 AND ra.roleid=2'; 
            //$rsdestaccess = $DB->get_recordset_sql($sqlaccess, (array) $conditions=null, $limitfrom=0, $limitnum=0);
            $rsdestaccess = $DB->get_recordset_sql($sqlaccess, array($record->id), $limitfrom=0, $limitnum=0);
            foreach ($rsdestaccess as $recd) {
                //$destemail=$destemail . "|";
                //echo "|";
                if ($lastDest<$recd->timeaccess) {
                    $lastDest=$recd->timeaccess;
                    //$destemail=$recd->fn . " " . $recd->ln . " (" . $recd->email . ")";
                    $destemail=$recd->fn . " " . $recd->ln . "<br> <a href=mailto:" . $recd->email  . "> " . $recd->email . "</a>";
                };
            };
            $rsdestaccess->close();
            
            //STUDENTU DUOMENYS
            $sqlaccess2='SELECT ra.userid as userid, ula.timeaccess as timeaccess FROM {role_assignments} as ra INNER JOIN {user} AS u ON ra.userid = u.id JOIN {user_lastaccess} AS ula ON u.id=ula.userid WHERE ra.roleid=5 and ula.courseid = ?';// WHERE ula.courseid=5 AND ra.roleid=2'; 
            //$rsdestaccess = $DB->get_recordset_sql($sqlaccess, (array) $conditions=null, $limitfrom=0, $limitnum=0);
            $rsstudaccess = $DB->get_recordset_sql($sqlaccess2, array($record->id), $limitfrom=0, $limitnum=0);
            foreach ($rsstudaccess as $recs) {
                //echo "|";
                if ($lastStud<$recs->timeaccess) {
                    $lastStud=$recs->timeaccess;
                    //$destemail=$recd->fn . " " . $recd->ln . " (" . $recd->email . ")";
                };
            };
            $rsstudaccess->close();
            
            
            $sqlcount= "SELECT COUNT(course.id) AS students FROM {role_assignments} AS ra JOIN {context} AS context ON ra.contextid = context.id AND context.contextlevel = 50 JOIN {user} AS USER ON USER.id = ra.userid JOIN {course} AS course ON context.instanceid = course.id WHERE ra.roleid = 5 AND course.id=?";
            
            //$DB->set_debug(true);
            
            $regrec=$DB->get_record_sql($sqlcount, array($record->id));
            $regstud=$regrec->students;
         
         if (($lastDest<$frmdata->neglecttime) and ($lastStud <$frmdata->neglecttime))  
         {
             array_push($eilutes, array(
             $record->name,
             //$record->id,//
        	 $record->fullname, 
        	 $destemail,
        	 $regstud,
        	 //$record->timeaccess,//
        	 gmdate("Y-m-d H:i:s", $lastDest), //'last Dest',
        	 gmdate("Y-m-d H:i:s",$lastStud)//'last Stud'
             ));
         }
    	 //$record->shortname ));
	 
     }
	
	
    
	#echo "</table>";
	$rs->close();
	$table->data = $eilutes; 
	echo html_writer::table($table);
	
    admin_externalpage_print_footer();
 ?>