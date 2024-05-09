<?php
/**
 * SystemChangeLog
 *
 * @version    7.6
 * @package    model
 * @subpackage log
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemChangeLog extends TRecord
{
    const TABLENAME    = 'system_change_log';
    const PRIMARYKEY   = 'id';
    const IDPOLICY     = 'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('logdate');
        parent::addAttribute('log_year');
        parent::addAttribute('log_month');
        parent::addAttribute('log_day');
        parent::addAttribute('login');
        parent::addAttribute('tablename');
        parent::addAttribute('primarykey');
        parent::addAttribute('pkvalue');
        parent::addAttribute('operation');
        parent::addAttribute('columnname');
        parent::addAttribute('oldvalue');
        parent::addAttribute('newvalue');
        parent::addAttribute('access_ip');
        parent::addAttribute('transaction_id');
        parent::addAttribute('log_trace');
        parent::addAttribute('session_id');
        parent::addAttribute('class_name');
        parent::addAttribute('php_sapi');
    }
    
    /**
     * Return formatted log trace
     */
    public function get_log_trace_formatted()
    {
        $log = $this->log_trace;
        
        preg_match_all('/#(.*)app\/control(.*)/', $log, $matches);
        
        if (count($matches[0]) > 0)
        {
            foreach ($matches[0] as $match)
            {
                $log = str_replace($match, "<b class='red'>{$match}</b>", $log);
            }
        }
        
        $log = str_replace('):', '):<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $log);
        
        return $log;
    }
}
