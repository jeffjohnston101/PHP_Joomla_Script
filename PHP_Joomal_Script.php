<?php
        // DATABASE CONNECTION INFORMATION
        $DB_HostName = "db";
        $DB_Pass = "passwordgoeshere";
		$DB_Name = "databasenamehere";
		$DB_User = "usernamehere";
		
		
		// CONNECT TO JOOMLA DATABASE
        @mysql_connect($DB_HostName, $DB_User, $DB_Pass) or die ('Could not connect');
 		@mysql_select_db($DB_Name) or die ('Could not find database');
		
				
		// DATABASE QUERY
		// SELECT USES FIELDS IN THE JOOMLA MySQL DB
        $result = mysql_query ("SELECT id, title, introtext, `fulltext`, created, 
        						created_by, created_by_alias, modified, modified_by, 
        						images, urls, alias, xreference 
        						FROM adl_content 
        						WHERE created  > NOW() - INTERVAL 120 DAY") 
        						or die (mysql_error());
              
        // PLACE QUERY RESULTS INTO ARRAY 
        $records = array();
        while ($row = mysql_fetch_assoc($result)) {
        	$records[] = $row;
        }

		// FUNCTION
		// ENSURES ALL DATA IS ENCODED TO UTF8 
		function utf8_encode_all($dat)
		{ 
  			if (is_string($dat)) return utf8_encode($dat); 
  				if (!is_array($dat)) return $dat; 
  					$ret = array(); 
  			foreach($dat as $i=>$d) $ret[$i] = utf8_encode_all($d); 
  			return $ret; 
		} 
        
        
        // FUNCTION
        // STRIPS OUR ALL HTML TAGS 
        function strip_tags_array($data, $tags = null)
		{
    		$stripped_data = array();
    		foreach ($data as $value)
    			{
        		if (is_array($value))
        			{
            		$stripped_data[] = strip_tags_array($value, $tags);
        			}
        		else
        			{
            		$stripped_data[] = strip_tags($value, $tags);
        			}
    			}
    	return $stripped_data;
		}
        
        // CREATE ARRAY FOR HOLDING STRIPPED RECORDS
        $srecords = array();
        
        
        
        
        // PASS RESULTS ARRAY TO UTF8 ENCODE FUNCTION
        $records2 = utf8_encode_all($records);
        
        // STRIP $RECORDS ARRAY OF ALL TAGS
        // $srecords = strip_tags_array($records2, $tags = null);
        
        // SEND JSON ENCODED RESULTS AS OBJECTS TO APP/BROWSER/CALLER
        echo json_encode($records2, JSON_FORCE_OBJECT);
  		

        // CLOSE DATABASE CONNECTION
        mysql_close();

	
?>