Jira SOAP Class
===============
This class allows interaction with
JIRA bug tracker through SOAP API

Example
-------

	require('jirasoap.php');

    $username = 'your';
    $password = 'password';
    $endpoint = 'http://localhost:8080/rpc/soap/jirasoapservice-v2?wsdl';

    $jira  = new Jirasoap($username, $password, $endpoint);
    
	// Get an issue from key
	$issue = $jira->get_issue('REQNEG-306');
    
    // Create a new issue
	$new = array(
        'project'           => 'KEY',
        'type'              => 1,
        'summary'           => 'foo',
        'description'       => 'bar',
        'customFieldValues' => array(
            array(
                'customfieldId' => 'customfield_10003',
                'values'        => array('foo-bar')
            )
        ),
        'reporter' => $username
    );

    $jira->create_issue($new);

For more information on this class usage visit [JiraSoapService docs](http://docs.atlassian.com/rpc-jira-plugin/4.4/index.html)