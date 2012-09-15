<?php
/**
 * The MIT License
 *
 * Copyright (c) 2012 Ricardo Casares
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Jira SOAP Class
 *
 * This class allows interaction with
 * JIRA bug tracker through SOAP API v4.4
 *
 * For more information on this class usage visit:
 *
 * http://docs.atlassian.com/rpc-jira-plugin/4.4/index.html
 *
 * Drop me a line at ricardo@betamonster.com.ar if you find this useful! :)
 *
 * @author      Ricardo Casares
 * @link        http://betamonster.com.ar
 */
class Jirasoap {
	
    protected $username;
    protected $password;
    protected $endpoint;
	protected $client;
	protected $token;

    /**
     * Constructor
     *
     * Initializes SOAP client and gets a token
     *
     */
	public function __construct($username, $password, $endpoint)
    {
        $this->username = $username;
        $this->password = $password;
        $this->endpoint = $endpoint;
        try
        {
            $this->client = new SoapClient($endpoint);
        	$this->token = $this->client->login($username, $password);	
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * Get Issue
    *
    * Gets an issue from jira by it's key
    *
    * @param string
    * @return object
    */
    public function get_issue($key)
    {
    	try
    	{
    		$result = $this->client->getIssue($this->token, $key);
    		return $result;
    	}
    	catch(SoapFault $e)
    	{
    		echo $e->getMessage();
    	}
    }

    /**
    * Get Custom Fields
    *
    * Returns defined custom fields in JIRA
    *
    * @return object
    */
    public function get_custom_fields()
    {
       try
        {
            $result = $this->client->getCustomFields($this->token);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        } 
    }

    /**
    * Get Projects
    *
    * Returns all projects defined in JIRA
    *
    * @return object
    */
    public function get_projects()
    {
        try
        {
            $result = $this->client->getProjectsNoSchemes($this->token);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * Get Issue Types
    *
    * Returns all issue types defined in JIRA
    *
    * @return object
    */
    public function get_issue_types()
    {
        try
        {
            $result = $this->client->getIssueTypes($this->token);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * Create Project
    *
    * Creates a project in JIRA
    * 
    * @param array
    * @return object
    */
    public function create_project($project)
    {
        try
        {
            $result = $this->client->createProject(
                $this->token,
                $project['key'], // string
                $project['name'], // string
                $project['description'], // string
                $project['url'], // string
                $project['lead'], // string
                $project['ps'], // array('id' => permission_scheme_id)
                $project['ns'], // array('id' => notification_scheme_id)
                $project['ss'] // array('id' => security_scheme_id)
            );
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * Update Project
     *
     * Updates properties properties for a given project
     *
     * @param array
     * @return object
     **/
    function update_project($project)
    {
        try
        {
            $result = $this->client->updateProject(
                $this->token,
                $project // array('key' => string, 'name' => string, etc..)
            );
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * Create Issue
    *
    * Creates an issue from an array
    *
    * Array structure:
    *
    * $issue = array(
    *   'summary' => 'text',
    *   'description' => 'text',
    *   'customFieldValues' => array(
    *      array(
    *          'customfieldId' => 'customfield_10003',
    *          'values' => array('Custom field value')
    *      )
    *   ),
    *   'reporter' => 'username',
    *   etc...
    * );
    *
    * @param array
    * @return object
    */
    public function create_issue($issue)
    {
    	try
    	{
    		$result = $this->client->createIssue(
                $this->token,
                $issue
            );
    		return $result;
    	}
    	catch(SoapFault $e)
    	{
    		echo $e->getMessage();
    	}
    }

    /**
    * Create attachment
    *
    * Attaches files on a given issue
    *
    * @param string
    * @param array
    * @param array
    * @return object or false if issue not found or exception
    */
    public function create_attachment($key, $filenames, $attachments)
    {
        try
        {
            $result = $this->client->addBase64EncodedAttachmentsToIssue(
                $this->token,
                $key,
                $filenames,
                $attachments
            );
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * Create Version
    *
    * Creates a version for given project
    *
    * @param string
    * @param string
    * @param boolean
    * @param boolean
    * @return object
    */
    public function create_version($key, $name, $archived = false, $released = false)
    {
        try
        {
            $version = array(
                'name' => $name,
                'archived' => $archived,
                'released' => $released
            );

            $result = $this->client->addVersion($this->token,$key,$version);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * Get Versions
     *
     * Retrieves given project versions
     *
     * @param string
     * @return object
     **/
    function get_versions($key)
    {
        try 
        {
            $result = $this->client->getVersions($this->token,$key);
            return $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * Search
    *
    * Returns issues from given project keys that match
    * the string passed
    *
    * @param array
    * @param string
    * @return object
    */
    public function search($projects,$string, $limit)
    {
        try
        {
            $result = $this->client->getIssuesFromTextSearchWithProject($this->token,$projects,$string,$limit);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * JQL Search
    *
    * Returns issues matching string given
    *
    * @param string
    * @return object
    */
    public function jql_search($string,$limit)
    {
        try
        {
            $result = $this->client->getIssuesFromJqlSearch($this->token,$string,$limit);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * Issue Count From Filter
     *
     * Returns the issue count for a given filter id
     *
     * @return integer
     **/
    function issue_count_for_filter($filter_id)
    {
        try
        {
            $result = $this->client->getIssueCountForFilter($this->token,$filter_id);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * Get Permission Schemes
    *
    * Gets defined permission schemes in JIRA
    *
    * @return object
    */
    public function get_permission_schemes()
    {
        try {
            $result = $this->client->getPermissionSchemes($this->token);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * Get Priorities
    *
    * Returns defined priorities for issues
    *
    * @return object
    */
    public function get_priorities()
    {
        try {
            $result = $this->client->getPriorities($this->token);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }

    /**
    * Get Statuses
    *
    * Retrieves defined statues for issues in JIRA
    *
    * @return object
    */
    public function get_statuses()
    {
        try {
            $result = $this->client->getStatuses($this->token);
            return $result;
        }
        catch(SoapFault $e)
        {
            echo $e->getMessage();
        }
    }
}